<?php

namespace App\Livewire\Patients;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterBranch = '';
    public $searchPerformed = false;
    public $suggestions = [];
    public array $branches = [];

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->suggestions = [];
            return;
        }

        $raw = trim($this->search);
        $isFileId = str_starts_with($raw, '#');
        $term = $isFileId ? ltrim($raw, '#') : $raw;
        $searchTerm = '%' . $term . '%';

        $this->suggestions = DB::table('kstu')
            ->select('id', 'file_id', 'full_name as name', 'phone')
            ->where(function($q) use ($searchTerm, $isFileId) {
                if ($isFileId) {
                    $q->where('file_id', 'like', $searchTerm);
                } else {
                    $q->where('full_name', 'like', $searchTerm)
                      ->orWhere('file_id', 'like', $searchTerm)
                      ->orWhere('phone', 'like', $searchTerm);
                }
            })
            ->limit(8)
            ->get();
    }

    #[Title('إدارة العملاء')]
    public function mount(): void
    {
        $this->branches = DB::table('branches')->where('is_active', 1)->get(['id', 'name'])->all();
    }

    public function render()
    {
        $patients = collect();

        if ($this->searchPerformed) {
            $raw = trim($this->search);
            $isFileId = str_starts_with($raw, '#');
            $term = $isFileId ? ltrim($raw, '#') : $raw;
            $searchTerm = '%' . $term . '%';

            $q = DB::table('kstu as k')
                ->leftJoin('branches as b', 'b.id', '=', 'k.branch_id')
                ->select(
                    'k.id', 'k.file_id',
                    'k.full_name as name',
                    'k.phone',
                    'k.ssn as identity_number',
                    'k.reg_date as created_at',
                    'b.name as branch_name'
                )
                ->where(function($q) use ($searchTerm, $isFileId) {
                    if ($isFileId) {
                        $q->where('k.file_id', 'like', $searchTerm);
                    } else {
                        $q->where('k.full_name', 'like', $searchTerm)
                          ->orWhere('k.file_id', 'like', $searchTerm)
                          ->orWhere('k.phone', 'like', $searchTerm)
                          ->orWhere('k.ssn', 'like', $searchTerm);
                    }
                });

            if ($this->filterBranch) {
                $q->where('k.branch_id', $this->filterBranch);
            }

            $patients = $q->orderBy('k.id', 'desc')->paginate(15);
        }

        return view('livewire.patients.index', [
            'patients' => $patients,
            'branches' => $this->branches,
        ])->layout('layouts.app');
    }

    public function selectPatient($id, $name)
    {
        $this->search = $name;
        $this->suggestions = [];
        $this->performSearch();
    }

    public function performSearch()
    {
        $this->searchPerformed = true;
        $this->suggestions = [];
        $this->resetPage();
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->suggestions = [];
        $this->searchPerformed = false;
        $this->resetPage();
    }

}
