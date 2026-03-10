<?php

namespace App\Livewire\Appointments;

use App\Models\LegacyClinic;
use App\Models\LegacyEmployee;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterDate = '';
    public $selectedClinic = '';

    #[Title('جدول المواعيد : Appointments')]
    public function render()
    {
        $today = now()->format('j-n-Y');

        $query = DB::table('rec as r')
            ->leftJoin('kstu as a', 'a.id', '=', 'r.st_id')
            ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
            ->where('r.confirm_id', 0)
            ->select(
                'r.id',
                'r.rec_date',
                'r.rec_time',
                'r.state_id as status',
                'a.full_name as patient_name',
                'c.name as clinic_name'
            );

        if ($this->search) {
            $term = '%' . $this->search . '%';
            $query->where('a.full_name', 'like', $term);
        }

        if ($this->filterDate) {
            $d = \Carbon\Carbon::parse($this->filterDate);
            $query->where('r.rec_date', $d->format('j-n-Y'));
        }

        if ($this->selectedClinic) {
            $query->where('r.clinic_id', $this->selectedClinic);
        }

        $appointments = $query->orderBy('r.id', 'desc')->paginate(15);
        $clinics = DB::table('clinic')->orderBy('name')->get();

        return view('livewire.appointments.index', [
            'clinics' => $clinics,
            'appointments' => $appointments
        ])->layout('layouts.app');
    }
}
