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
    public $searchPerformed = false;
    public $suggestions = [];

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->suggestions = [];
            return;
        }

        $searchTerm = '%' . $this->search . '%';

        $this->suggestions = DB::table('kstu')
            ->select('id', 'file_id', 'full_name as name', 'phone')
            ->where(function($q) use ($searchTerm) {
                $q->where('full_name', 'like', $searchTerm)
                  ->orWhere('file_id', 'like', $searchTerm)
                  ->orWhere('phone', 'like', $searchTerm);
            })
            ->limit(8)
            ->get();
    }

    #[Title('إدارة العملاء')]
    public function render()
    {
        $patients = collect();

        if ($this->searchPerformed) {
            $searchTerm = '%' . $this->search . '%';

            $patients = DB::table('kstu')
                ->select(
                    'id',
                    'file_id',
                    'full_name as name',
                    'phone',
                    'ssn as identity_number',
                    'reg_date as created_at'
                )
                ->where(function($q) use ($searchTerm) {
                    $q->where('full_name', 'like', $searchTerm)
                      ->orWhere('file_id', 'like', $searchTerm)
                      ->orWhere('phone', 'like', $searchTerm)
                      ->orWhere('ssn', 'like', $searchTerm);
                })
                ->orderBy('id', 'desc')
                ->paginate(15);
        }

        return view('livewire.patients.index', [
            'patients' => $patients
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

    public function confirmDelete($id)
    {
        // TODO: implement delete logic
    }
}
