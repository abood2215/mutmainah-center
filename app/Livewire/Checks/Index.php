<?php

namespace App\Livewire\Checks;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $filterDate   = '';
    public string $filterClinic = '';

    public ?int    $selectedStId      = null;
    public ?object $selectedPatient   = null;

    public bool    $showWaitingModal  = false;
    public array   $waitingList       = [];

    #[Title('الكشوف')]
    public function render()
    {
        $today = now()->format('j-n-Y');

        $paymentsAgg = DB::table('kpayments')
            ->select(
                'rec_id',
                DB::raw('COALESCE(SUM(price), 0) as amount'),
                DB::raw('MAX(vno) as vno'),
                DB::raw('MAX(serial_no) as serial_no')
            )
            ->groupBy('rec_id');

        $query = DB::table('rec as r')
            ->leftJoin('kstu as a', 'a.id', '=', 'r.st_id')
            ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
            ->leftJoinSub($paymentsAgg, 'k', 'k.rec_id', '=', 'r.id')
            ->where('r.confirm_id', 1)
            ->select(
                'r.id',
                'r.rec_date',
                'r.rec_time',
                'r.state_id as status',
                'r.st_id',
                'r.clinic_id',
                'a.full_name as patient_name',
                'c.name as clinic_name',
                DB::raw('COALESCE(k.amount, 0) as amount'),
                'k.vno',
                'k.serial_no'
            );

        if ($this->search) {
            $term = '%' . $this->search . '%';
            $query->where(function($q) use ($term) {
                $q->where('a.full_name', 'like', $term)
                  ->orWhere('r.id', 'like', $term);
            });
        }

        if ($this->filterDate) {
            $d = \Carbon\Carbon::parse($this->filterDate);
            $query->where('r.rec_date', $d->format('j-n-Y'));
        }

        if ($this->filterClinic) {
            $query->where('r.clinic_id', $this->filterClinic);
        }

        $checks  = $query->orderBy('r.id', 'desc')->paginate(15);
        $clinics = DB::table('clinic')->orderBy('name')->get(['id', 'name']);

        // تم الكشف = دفع ونزل بقائمة الكشوف
        $todayDone    = DB::table('rec')->where('rec_date', $today)->where('confirm_id', 1)->count();
        // في الانتظار = حاجز موعد اليوم ولسا ما دفع
        $todayWaiting = DB::table('rec')->where('rec_date', $today)->where('confirm_id', 0)->count();

        return view('livewire.checks.index', compact(
            'checks', 'clinics', 'todayDone', 'todayWaiting'
        ))->layout('layouts.app');
    }

    public function selectPatient(int $stId): void
    {
        if ($this->selectedStId === $stId) {
            // نفس المريض → إخفاء البانيل
            $this->selectedStId    = null;
            $this->selectedPatient = null;
            return;
        }

        $this->selectedStId = $stId;

        $this->selectedPatient = DB::table('kstu as k')
            ->leftJoin('kcom as c',  'c.id', '=', 'k.com_id')
            ->leftJoin('class as cl', 'cl.id', '=', 'k.class_id')
            ->where('k.id', $stId)
            ->select(
                'k.id',
                'k.full_name',
                'k.file_id',
                'k.ssn',
                'k.phone',
                'k.gender',
                'k.date_of_birth',
                'k.reg_date',
                'c.name as insurance',
                'cl.name as class_name'
            )
            ->first();
    }

    public function openWaitingModal(): void
    {
        $today = now()->format('j-n-Y');

        $this->waitingList = DB::table('rec as r')
            ->leftJoin('kstu as k', 'k.id', '=', 'r.st_id')
            ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
            ->where('r.rec_date', $today)
            ->where('r.confirm_id', 0)
            ->select('k.full_name', 'k.phone', 'k.file_id', 'r.rec_time', 'c.name as clinic_name')
            ->orderBy('r.rec_time')
            ->get()
            ->toArray();

        $this->showWaitingModal = true;
    }

    public function closeWaitingModal(): void
    {
        $this->showWaitingModal = false;
    }

    public function resetFilters(): void
    {
        $this->search       = '';
        $this->filterDate   = '';
        $this->filterClinic = '';
        $this->resetPage();
    }

    public function updatedSearch()       { $this->resetPage(); }
    public function updatedFilterDate()   { $this->resetPage(); }
    public function updatedFilterClinic() { $this->resetPage(); }
}
