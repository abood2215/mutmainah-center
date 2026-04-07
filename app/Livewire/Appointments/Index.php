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

        // تحديث تلقائي: إذا كان العميل أخذ كشفه اليوم → state_id=1
        DB::statement("
            UPDATE rec r1
            INNER JOIN rec r2 ON r2.st_id = r1.st_id
                AND r2.rec_date = ? AND r2.confirm_id = 1 AND r2.state_id = 1
            SET r1.state_id = 1
            WHERE r1.rec_date = ? AND r1.confirm_id = 0 AND r1.state_id = 0
        ", [$today, $today]);

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

    public function markChecked(int $recId): void
    {
        DB::table('rec')->where('id', $recId)->where('confirm_id', 0)->update(['confirm_id' => 1]);
    }

    #[Title('جدول المواعيد : Appointments')]
    public function render()
    {
        $today = now()->format('j-n-Y');

        // تحديث تلقائي: كل موعد محجوز (confirm_id=0) قبل اليوم → منتهي (confirm_id=1)
        $pastDates = collect();
        for ($i = 1; $i <= 30; $i++) {
            $pastDates->push(now()->subDays($i)->format('j-n-Y'));
        }
        DB::table('rec')
            ->where('confirm_id', 0)
            ->whereIn('rec_date', $pastDates)
            ->update(['confirm_id' => 1]);

        $query = DB::table('rec as r')
            ->leftJoin('kstu as a', 'a.id', '=', 'r.st_id')
            ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
            ->leftJoin('employees as e', 'e.id', '=', 'r.user_id')
            ->where('r.confirm_id', 0)
            ->select(
                'r.id',
                'r.rec_date',
                'r.rec_time',
                'r.state_id as status',
                'r.st_id',
                'a.full_name as patient_name',
                'a.phone as patient_phone',
                'c.name as clinic_name',
                DB::raw("TRIM(CONCAT(COALESCE(e.first_name,''), ' ', COALESCE(e.middle_initial,''))) as booked_by")
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

        $appointments = $query
            ->orderByRaw("STR_TO_DATE(r.rec_date, '%e-%c-%Y') ASC")
            ->orderBy('r.rec_time', 'asc')
            ->paginate(20);
        $clinics = DB::table('clinic')->where('state_id', 1)->orderBy('name')->get();

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
