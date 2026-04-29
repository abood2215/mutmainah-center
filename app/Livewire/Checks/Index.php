<?php

namespace App\Livewire\Checks;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public string $search        = '';
    public string $filterDate    = '';
    public string $filterClinic  = '';
    public string $filterBranch  = '';
    public array  $branches      = [];

    public ?int    $selectedStId      = null;
    public ?object $selectedPatient   = null;

    public bool    $showWaitingModal  = false;
    public array   $waitingList       = [];

    #[Title('الكشوف')]
    public function mount(): void
    {
        $this->branches = DB::table('branches')->where('is_active', 1)->get(['id', 'name'])->all();
        // تعيين اليوم كـ filter افتراضي (Y-m-d لتوافق input type="date")
        $this->filterDate = now()->format('Y-m-d');
    }

    public function render()
    {
        $today = now()->format('j-n-Y');

        $query = DB::table('rec as r')
            ->leftJoin('kstu as a', 'a.id', '=', 'r.st_id')
            ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
            ->leftJoin('branches as b', 'b.id', '=', 'c.branch_id')
            ->where('r.confirm_id', 1)
            ->select(
                'r.id',
                'r.rec_date',
                'r.rec_time',
                'r.confirm_id',
                'r.st_id',
                'r.clinic_id',
                'a.full_name as patient_name',
                'c.name as clinic_name',
                'b.name as branch_name'
            );

        // دور عيادة: فلتر إجباري بعيادات المستخدم
        $userRole = auth()->user()?->role ?? '';
        if ($userRole === 'clinic') {
            $clinicIds = json_decode(auth()->user()?->clinic_ids ?? '[]', true);
            if (!empty($clinicIds)) {
                $query->whereIn('r.clinic_id', $clinicIds);
            }
        } elseif ($this->filterBranch) {
            $query->where('c.branch_id', $this->filterBranch);
        }

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

        $checks = $query->orderBy('r.id', 'desc')->paginate(15);

        // جيب مدفوعات الـ 15 سجل الحالية بس (بدل full scan على كل الجدول)
        $pageIds = $checks->pluck('id')->toArray();
        $payments = DB::table('kpayments')
            ->whereIn('rec_id', $pageIds)
            ->select('rec_id', DB::raw('COALESCE(SUM(price - COALESCE(discount, 0)), 0) as amount'), DB::raw('MAX(vno) as vno'), DB::raw('MAX(serial_no) as serial_no'))
            ->groupBy('rec_id')
            ->get()
            ->keyBy('rec_id');

        $checks->getCollection()->transform(function ($check) use ($payments) {
            $p = $payments[$check->id] ?? null;
            $check->amount    = $p ? (float) $p->amount    : 0;
            $check->vno       = $p ? $p->vno       : null;
            $check->serial_no = $p ? $p->serial_no : null;
            return $check;
        });
        $clinics = DB::table('clinic')->where('state_id', 1)->orderBy('name')->get(['id', 'name']);
        $branches = $this->branches;

        // تم الكشف = دفع ونزل بقائمة الكشوف
        $todayDone    = DB::table('rec')->where('rec_date', $today)->where('confirm_id', 1)->count();
        // في الانتظار = حاجز موعد اليوم ولسا ما دفع
        $todayWaiting = DB::table('rec')->where('rec_date', $today)->where('confirm_id', 0)->count();

        return view('livewire.checks.index', compact(
            'checks', 'clinics', 'branches', 'todayDone', 'todayWaiting'
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

    public function updatedSearch()        { $this->filterDate = ''; $this->resetPage(); }
    public function updatedFilterDate()    { $this->resetPage(); }
    public function updatedFilterClinic()  { $this->resetPage(); }
    public function updatedFilterBranch()  { $this->resetPage(); }
}
