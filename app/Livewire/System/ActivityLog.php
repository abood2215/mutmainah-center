<?php

namespace App\Livewire\System;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class ActivityLog extends Component
{
    use WithPagination;

    public string $search     = '';
    public string $filterDate = '';
    public string $filterUser = '';
    public string $filterAction = '';

    public function updatingSearch()      { $this->resetPage(); }
    public function updatingFilterDate()  { $this->resetPage(); }
    public function updatingFilterUser()  { $this->resetPage(); }
    public function updatingFilterAction(){ $this->resetPage(); }

    public function resetFilters(): void
    {
        $this->search       = '';
        $this->filterDate   = '';
        $this->filterUser   = '';
        $this->filterAction = '';
        $this->resetPage();
    }

    #[Title('سجل النشاط')]
    public function render()
    {
        $query = DB::table('activity_logs as a')
            ->leftJoin('kstu as k', function($join) {
                $join->on('k.id', '=', 'a.subject_id')
                     ->whereIn('a.subject', ['patient','check','attachment','appointment','payment','discount','voided']);
            })
            ->select('a.*', 'k.full_name as patient_name', 'k.file_id as patient_file')
            ->orderBy('a.id', 'desc');

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

        if ($this->filterAction) {
            $query->where('a.action', $this->filterAction);
        }

        $logs = $query->paginate(30);

        $users = DB::table('activity_logs')
            ->whereNotNull('user_name')
            ->where('user_name', '!=', '')
            ->distinct()
            ->orderBy('user_name')
            ->pluck('user_name');

        return view('livewire.system.activity-log', compact('logs', 'users'))
            ->layout('layouts.app');
    }
}
