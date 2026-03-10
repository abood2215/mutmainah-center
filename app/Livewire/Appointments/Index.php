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

    public bool  $showTodayModal = false;
    public array $todayList      = [];

    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingFilterDate(): void   { $this->resetPage(); }
    public function updatingSelectedClinic(): void { $this->resetPage(); }

    public function openTodayModal(): void
    {
        $today = now()->format('j-n-Y');

        $this->todayList = DB::table('rec as r')
            ->leftJoin('kstu as k', 'k.id', '=', 'r.st_id')
            ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
            ->where('r.rec_date', $today)
            ->where('r.confirm_id', 0)
            ->select('k.full_name', 'k.phone', 'k.file_id', 'r.rec_time', 'r.state_id', 'c.name as clinic_name')
            ->orderBy('r.rec_time')
            ->get()
            ->toArray();

        $this->showTodayModal = true;
    }

    public function closeTodayModal(): void
    {
        $this->showTodayModal = false;
    }

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

        $appointments = $query->orderBy('r.rec_date', 'desc')->orderBy('r.rec_time', 'asc')->paginate(20);
        $clinics      = DB::table('clinic')->orderBy('name')->get();

        $todayCount = DB::table('rec')
            ->where('rec_date', $today)
            ->where('confirm_id', 0)
            ->count();

        $doneCount = DB::table('rec')
            ->where('rec_date', $today)
            ->where('confirm_id', 0)
            ->where('state_id', 1)
            ->count();

        return view('livewire.appointments.index', [
            'clinics'      => $clinics,
            'appointments' => $appointments,
            'todayCount'   => $todayCount,
            'doneCount'    => $doneCount,
        ])->layout('layouts.app');
    }
}
