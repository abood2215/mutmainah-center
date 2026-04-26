<?php

namespace App\Livewire\System;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class ActivityLog extends Component
{
    use WithPagination;

    public string $tab        = 'all';
    public string $search     = '';
    public string $filterDate = '';
    public string $filterUser = '';

    // تعريف الفئات وما يقابلها من subjects
    protected array $tabSubjects = [
        'all'          => [],
        'patients'     => ['patient'],
        'checks'       => ['check'],
        'appointments' => ['appointment'],
        'payments'     => ['payment'],
        'auth'         => ['auth'],
        'attachments'  => ['attachment'],
    ];

    public function switchTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function updatingSearch()     { $this->resetPage(); }
    public function updatingFilterDate() { $this->resetPage(); }
    public function updatingFilterUser() { $this->resetPage(); }

    public function resetFilters(): void
    {
        $this->search     = '';
        $this->filterDate = '';
        $this->filterUser = '';
        $this->resetPage();
    }

    #[Title('سجل النشاط')]
    public function render()
    {
        $query = DB::table('activity_logs as a')
            ->leftJoin('kstu as k', function($join) {
                $join->on('k.id', '=', 'a.subject_id')
                     ->whereNotIn('a.subject', ['auth']);
            })
            ->select('a.*', 'k.full_name as patient_name', 'k.file_id as patient_file')
            ->orderBy('a.id', 'desc');

        $subjects = $this->tabSubjects[$this->tab] ?? [];
        if (!empty($subjects)) {
            $query->whereIn('a.subject', $subjects);
        }

        if ($this->search) {
            $term = '%' . $this->search . '%';
            $query->where(function($q) use ($term) {
                $q->where('a.description', 'like', $term)
                  ->orWhere('a.user_name', 'like', $term)
                  ->orWhere('k.full_name', 'like', $term);
            });
        }

        if ($this->filterDate) {
            $query->whereDate('a.created_at', $this->filterDate);
        }

        if ($this->filterUser) {
            $query->where('a.user_name', 'like', '%' . $this->filterUser . '%');
        }

        $logs = $query->paginate(30);

        // عدد كل تبويب (بدون فلاتر لتجنب الثقل)
        $counts = DB::table('activity_logs')
            ->selectRaw("
                COUNT(*) as total,
                SUM(subject='patient')     as patients,
                SUM(subject='check')       as checks,
                SUM(subject='appointment') as appointments,
                SUM(subject='payment')     as payments,
                SUM(subject='auth')        as auth,
                SUM(subject='attachment')  as attachments
            ")
            ->first();

        $users = DB::table('activity_logs')
            ->whereNotNull('user_name')->where('user_name', '!=', '')
            ->distinct()->orderBy('user_name')->pluck('user_name');

        return view('livewire.system.activity-log', compact('logs', 'users', 'counts'))
            ->layout('layouts.app');
    }
}
