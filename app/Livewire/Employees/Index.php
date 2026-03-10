<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    #[Title('الموظفين')]
    public function render()
    {
        $query = DB::table('employees as e')
            ->leftJoin('jops as j', 'j.id', '=', 'e.jop')
            ->leftJoin('qualifications as q', 'q.id', '=', 'e.qualification')
            ->select(
                'e.id',
                'e.emp_no',
                'e.first_name',
                'e.last_name',
                'e.phone',
                'e.email',
                'j.name as job_name',
                'q.name as qual_name'
            );

        if ($this->search) {
            $term = '%' . $this->search . '%';
            $query->where(function ($q) use ($term) {
                $q->where('e.first_name', 'like', $term)
                  ->orWhere('e.last_name', 'like', $term)
                  ->orWhere('e.emp_no', 'like', $term)
                  ->orWhere('e.phone', 'like', $term);
            });
        }

        $employees = $query->orderBy('e.id', 'desc')->paginate(10);

        return view('livewire.employees.index', compact('employees'))
            ->layout('layouts.app');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
}
