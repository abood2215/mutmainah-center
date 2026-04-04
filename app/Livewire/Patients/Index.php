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

        $q = trim($this->search);
        $like = '%' . $q . '%';

        $this->suggestions = DB::table('kstu')
            ->select('id', 'file_id', 'full_name as name', 'phone', 'ssn')
            ->where(function($query) use ($like, $q) {
                $query->where('full_name', 'like', $like)
                      ->orWhere('phone', 'like', $like)
                      ->orWhere('ssn', 'like', $like)
                      ->orWhereRaw('CAST(file_id AS CHAR) LIKE ?', [$like])
                      ->orWhereRaw('CAST(id AS CHAR) LIKE ?', [$like]);
            })
            ->limit(10)
            ->get();
    }

    #[Title('إدارة العملاء')]
    public function render()
    {
        $patients = collect();

        if ($this->searchPerformed && strlen(trim($this->search)) >= 1) {
            $q    = trim($this->search);
            $like = '%' . $q . '%';

            $patients = DB::table('kstu')
                ->select(
                    'id',
                    'file_id',
                    'full_name as name',
                    'phone',
                    'ssn as identity_number',
                    'reg_date as created_at'
                )
                ->where(function($query) use ($like, $q) {
                    $query->where('full_name', 'like', $like)
                          ->orWhere('phone', 'like', $like)
                          ->orWhere('ssn', 'like', $like)
                          ->orWhereRaw('CAST(file_id AS CHAR) LIKE ?', [$like])
                          ->orWhereRaw('CAST(id AS CHAR) LIKE ?', [$like]);
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
