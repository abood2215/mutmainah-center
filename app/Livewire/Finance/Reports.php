<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;

class Reports extends Component
{
    use WithPagination;

    #[Url(as: 'type')]
    public string $reportType = 'income';
    public string $dateFrom   = '';
    public string $dateTo     = '';
    public string $filterClinic = '';
    public string $search     = '';
    public bool   $searched   = false;

    #[Title('التقارير')]

    public function updatingReportType()  { $this->resetPage(); $this->searched = false; }
    public function updatingSearch($value) { if ($value !== '') $this->searched = true; $this->resetPage(); }

    public function runSearch(): void
    {
        $this->searched = true;
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->dateFrom     = '';
        $this->dateTo       = '';
        $this->filterClinic = '';
        $this->search       = '';
        $this->searched     = false;
        $this->resetPage();
    }

    /* ─────── date helper ─────── */
    private function applyDateFilter($query, string $column): void
    {
        if ($this->dateFrom) {
            $query->whereRaw("STR_TO_DATE({$column}, '%e-%c-%Y') >= ?", [$this->dateFrom]);
        }
        if ($this->dateTo) {
            $query->whereRaw("STR_TO_DATE({$column}, '%e-%c-%Y') <= ?", [$this->dateTo]);
        }
    }

    /* ══════════════════════════════════════════════
       1. الدخل – Income
    ══════════════════════════════════════════════ */
    private function getIncomeData()
    {
        $query = DB::table('kpayments as k')
            ->leftJoin('clinic as c', 'c.id', '=', 'k.clinic_id')
            ->where('k.price', '>', 0)
            ->select(
                'k.pdate',
                'c.name as clinic_name',
                DB::raw('SUM(k.price) as total'),
                DB::raw('COUNT(k.id) as count')
            )
            ->groupBy('k.pdate', 'k.clinic_id', 'c.name')
            ->orderByRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') DESC");

        $this->applyDateFilter($query, 'k.pdate');
        if ($this->filterClinic) $query->where('k.clinic_id', $this->filterClinic);

        $rows   = $query->paginate(20);
        $total  = DB::table('kpayments as k')->where('k.price', '>', 0)
            ->when($this->filterClinic, fn($q) => $q->where('k.clinic_id', $this->filterClinic))
            ->when($this->dateFrom, fn($q) => $q->whereRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') >= ?", [$this->dateFrom]))
            ->when($this->dateTo,   fn($q) => $q->whereRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') <= ?", [$this->dateTo]))
            ->sum('price');

        return ['rows' => $rows, 'summary' => ['total' => $total]];
    }

    /* ══════════════════════════════════════════════
       2. الفواتير – Invoices
    ══════════════════════════════════════════════ */
    private function getInvoicesData()
    {
        $query = DB::table('kpayments as k')
            ->leftJoin('rec as r', 'r.id', '=', 'k.rec_id')
            ->leftJoin('kstu as s', 's.id', '=', 'r.st_id')
            ->leftJoin('clinic as c', 'c.id', '=', 'k.clinic_id')
            ->where('k.price', '>', 0)
            ->select(
                'k.id', 'k.serial_no', 'k.pdate', 'k.price',
                'k.payment_method', 'k.pdesc', 'k.vno',
                's.full_name as patient_name', 's.file_id',
                'c.name as clinic_name'
            )
            ->orderBy('k.id', 'desc');

        $this->applyDateFilter($query, 'k.pdate');

        // دور عيادة: فلتر إجباري
        $userClinicIds = [];
        if ((auth()->user()?->role ?? '') === 'clinic') {
            $userClinicIds = json_decode(auth()->user()?->clinic_ids ?? '[]', true);
            if (!empty($userClinicIds)) $query->whereIn('k.clinic_id', $userClinicIds);
        } elseif ($this->filterClinic) {
            $query->where('k.clinic_id', $this->filterClinic);
        }

        if ($this->search) {
            $t = '%' . $this->search . '%';
            $query->where(fn($q) => $q->where('s.full_name', 'like', $t)
                ->orWhere('k.serial_no', 'like', $t)
                ->orWhere('s.file_id', 'like', $t));
        }

        $rows  = $query->paginate(20);
        $total = DB::table('kpayments as k')
            ->leftJoin('rec as r', 'r.id', '=', 'k.rec_id')
            ->leftJoin('kstu as s', 's.id', '=', 'r.st_id')
            ->where('k.price', '>', 0)
            ->when(!empty($userClinicIds), fn($q) => $q->whereIn('k.clinic_id', $userClinicIds))
            ->when(empty($userClinicIds) && $this->filterClinic, fn($q) => $q->where('k.clinic_id', $this->filterClinic))
            ->when($this->dateFrom, fn($q) => $q->whereRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') >= ?", [$this->dateFrom]))
            ->when($this->dateTo,   fn($q) => $q->whereRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') <= ?", [$this->dateTo]))
            ->when($this->search,   fn($q) => $q->where('s.full_name', 'like', '%'.$this->search.'%'))
            ->sum('k.price');

        return ['rows' => $rows, 'summary' => ['total' => $total]];
    }

    /* ══════════════════════════════════════════════
       3. السندات – Vouchers
    ══════════════════════════════════════════════ */
    private function getVouchersData()
    {
        $query = DB::table('vouchers as v')
            ->leftJoin('kstu as s', 's.id', '=', 'v.stu_id')
            ->select(
                'v.id', 'v.vno', 'v.pdate', 'v.credit', 'v.debit',
                'v.pdesc', 'v.ptype', 's.full_name as patient_name'
            )
            ->orderBy('v.id', 'desc');

        $this->applyDateFilter($query, 'v.pdate');
        if ($this->search) {
            $t = '%' . $this->search . '%';
            $query->where(fn($q) => $q->where('v.pdesc', 'like', $t)
                ->orWhere('s.full_name', 'like', $t));
        }

        $rows = $query->paginate(20);
        $totalCredit = DB::table('vouchers')
            ->when($this->dateFrom, fn($q) => $q->whereRaw("STR_TO_DATE(pdate, '%e-%c-%Y') >= ?", [$this->dateFrom]))
            ->when($this->dateTo,   fn($q) => $q->whereRaw("STR_TO_DATE(pdate, '%e-%c-%Y') <= ?", [$this->dateTo]))
            ->sum('credit');
        $totalDebit = DB::table('vouchers')
            ->when($this->dateFrom, fn($q) => $q->whereRaw("STR_TO_DATE(pdate, '%e-%c-%Y') >= ?", [$this->dateFrom]))
            ->when($this->dateTo,   fn($q) => $q->whereRaw("STR_TO_DATE(pdate, '%e-%c-%Y') <= ?", [$this->dateTo]))
            ->sum('debit');

        return ['rows' => $rows, 'summary' => ['credit' => $totalCredit, 'debit' => $totalDebit]];
    }

    /* ══════════════════════════════════════════════
       4. المواعيد – Appointments
    ══════════════════════════════════════════════ */
    private function getAppointmentsData()
    {
        $query = DB::table('rec as r')
            ->leftJoin('kstu as s', 's.id', '=', 'r.st_id')
            ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
            ->where('r.confirm_id', 0)
            ->select(
                'r.id', 'r.rec_date', 'r.rec_time', 'r.state_id',
                's.full_name as patient_name', 's.file_id', 's.phone',
                'c.name as clinic_name'
            )
            ->orderByRaw("STR_TO_DATE(r.rec_date, '%e-%c-%Y') DESC")
            ->orderBy('r.rec_time');

        $this->applyDateFilter($query, 'r.rec_date');
        if ($this->filterClinic) $query->where('r.clinic_id', $this->filterClinic);
        if ($this->search) {
            $t = '%' . $this->search . '%';
            $query->where(fn($q) => $q->where('s.full_name', 'like', $t)
                ->orWhere('s.phone', 'like', $t)
                ->orWhere('s.file_id', 'like', $t));
        }

        $rows  = $query->paginate(25);
        $total = DB::table('rec')->where('confirm_id', 0)
            ->when($this->filterClinic, fn($q) => $q->where('clinic_id', $this->filterClinic))
            ->when($this->dateFrom, fn($q) => $q->whereRaw("STR_TO_DATE(rec_date, '%e-%c-%Y') >= ?", [$this->dateFrom]))
            ->when($this->dateTo,   fn($q) => $q->whereRaw("STR_TO_DATE(rec_date, '%e-%c-%Y') <= ?", [$this->dateTo]))
            ->count();

        return ['rows' => $rows, 'summary' => ['count' => $total]];
    }

    /* ══════════════════════════════════════════════
       5. المكاتب – Clinics
    ══════════════════════════════════════════════ */
    private function getClinicsData()
    {
        $query = DB::table('clinic as c')
            ->leftJoin('rec as r', fn($j) => $j->on('r.clinic_id', '=', 'c.id')->where('r.confirm_id', 1))
            ->leftJoin('kpayments as k', 'k.rec_id', '=', 'r.id')
            ->select(
                'c.id', 'c.name',
                DB::raw('COUNT(DISTINCT r.id) as visits'),
                DB::raw('COALESCE(SUM(k.price), 0) as revenue')
            )
            ->groupBy('c.id', 'c.name')
            ->orderBy('revenue', 'desc');

        if ($this->filterClinic) {
            $query->where('c.id', $this->filterClinic);
        } elseif ((auth()->user()?->role ?? '') === 'clinic') {
            $ids = json_decode(auth()->user()?->clinic_ids ?? '[]', true);
            if (!empty($ids)) $query->whereIn('c.id', $ids);
        }

        $rows = $query->paginate(20);
        return ['rows' => $rows, 'summary' => []];
    }

    /* ══════════════════════════════════════════════
       6. العملاء – Clients
    ══════════════════════════════════════════════ */
    private function getPatientsData()
    {
        $query = DB::table('kstu as s')
            ->leftJoin('kcom as co', 'co.id', '=', 's.com_id')
            ->select(
                's.id', 's.file_id', 's.full_name', 's.phone',
                's.gender', 's.reg_date', 'co.name as insurance'
            )
            ->orderBy('s.id', 'desc');

        $this->applyDateFilter($query, 's.reg_date');
        if ($this->search) {
            $t = '%' . $this->search . '%';
            $query->where(fn($q) => $q->where('s.full_name', 'like', $t)
                ->orWhere('s.phone', 'like', $t)
                ->orWhere('s.file_id', 'like', $t));
        }

        $rows  = $query->paginate(20);
        $total = DB::table('kstu')
            ->when($this->search, fn($q) => $q->where('full_name', 'like', '%'.$this->search.'%'))
            ->count();

        return ['rows' => $rows, 'summary' => ['count' => $total]];
    }

    /* ══════════════════════════════════════════════
       7. البيان المالي – PFS
    ══════════════════════════════════════════════ */
    private function getPfsData()
    {
        $query = DB::table('kpayments as k')
            ->leftJoin('rec as r', 'r.id', '=', 'k.rec_id')
            ->leftJoin('kstu as s', 's.id', '=', 'r.st_id')
            ->where('k.price', '>', 0)
            ->whereNotNull('r.st_id')
            ->select(
                's.id as patient_id', 's.full_name as patient_name', 's.file_id',
                DB::raw('SUM(k.price) as total_paid'),
                DB::raw('COUNT(DISTINCT r.id) as visits'),
                DB::raw('MAX(k.pdate) as last_payment')
            )
            ->groupBy('s.id', 's.full_name', 's.file_id')
            ->orderBy('total_paid', 'desc');

        $this->applyDateFilter($query, 'k.pdate');
        if ($this->search) {
            $t = '%' . $this->search . '%';
            $query->where(fn($q) => $q->where('s.full_name', 'like', $t)
                ->orWhere('s.file_id', 'like', $t));
        }

        $rows  = $query->paginate(20);
        $total = DB::table('kpayments')->where('price', '>', 0)
            ->when($this->dateFrom, fn($q) => $q->whereRaw("STR_TO_DATE(pdate, '%e-%c-%Y') >= ?", [$this->dateFrom]))
            ->when($this->dateTo,   fn($q) => $q->whereRaw("STR_TO_DATE(pdate, '%e-%c-%Y') <= ?", [$this->dateTo]))
            ->sum('price');

        return ['rows' => $rows, 'summary' => ['total' => $total]];
    }

    /* ══════════════════════════════════════════════
       8. الدرج – Till
    ══════════════════════════════════════════════ */
    private function getTillData()
    {
        $query = DB::table('kpayments as k')
            ->leftJoin('clinic as c', 'c.id', '=', 'k.clinic_id')
            ->where('k.price', '>', 0)
            ->select(
                'k.pdate',
                DB::raw('SUM(k.price) as total'),
                DB::raw('COUNT(k.id) as transactions'),
                DB::raw('SUM(CASE WHEN k.payment_method = 1 THEN k.price ELSE 0 END) as cash'),
                DB::raw('SUM(CASE WHEN k.payment_method = 2 THEN k.price ELSE 0 END) as card'),
                DB::raw('SUM(CASE WHEN k.payment_method NOT IN (1,2) THEN k.price ELSE 0 END) as other')
            )
            ->groupBy('k.pdate')
            ->orderByRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') DESC");

        $this->applyDateFilter($query, 'k.pdate');
        if ($this->filterClinic) $query->where('k.clinic_id', $this->filterClinic);

        $rows  = $query->paginate(20);
        $total = DB::table('kpayments')->where('price', '>', 0)
            ->when($this->filterClinic, fn($q) => $q->where('clinic_id', $this->filterClinic))
            ->when($this->dateFrom, fn($q) => $q->whereRaw("STR_TO_DATE(pdate, '%e-%c-%Y') >= ?", [$this->dateFrom]))
            ->when($this->dateTo,   fn($q) => $q->whereRaw("STR_TO_DATE(pdate, '%e-%c-%Y') <= ?", [$this->dateTo]))
            ->sum('price');

        return ['rows' => $rows, 'summary' => ['total' => $total]];
    }

    /* ══════════════════════════════════════════════
       9. المطالبات – Claims
    ══════════════════════════════════════════════ */
    private function getClaimsData()
    {
        $query = DB::table('kpayments as k')
            ->leftJoin('rec as r', 'r.id', '=', 'k.rec_id')
            ->leftJoin('kstu as s', 's.id', '=', 'r.st_id')
            ->leftJoin('kcom as co', 'co.id', '=', 'k.com_id')
            ->leftJoin('clinic as c', 'c.id', '=', 'k.clinic_id')
            ->where('k.com_id', '>', 0)
            ->select(
                'k.id', 'k.pdate', 'k.price', 'k.insur_amount',
                's.full_name as patient_name', 's.file_id',
                'co.name as insurance_name', 'c.name as clinic_name'
            )
            ->orderBy('k.id', 'desc');

        $this->applyDateFilter($query, 'k.pdate');
        if ($this->filterClinic) $query->where('k.clinic_id', $this->filterClinic);
        if ($this->search) {
            $t = '%' . $this->search . '%';
            $query->where(fn($q) => $q->where('s.full_name', 'like', $t)
                ->orWhere('co.name', 'like', $t));
        }

        $rows  = $query->paginate(20);
        $total = DB::table('kpayments')->where('com_id', '>', 0)
            ->when($this->filterClinic, fn($q) => $q->where('clinic_id', $this->filterClinic))
            ->when($this->dateFrom, fn($q) => $q->whereRaw("STR_TO_DATE(pdate, '%e-%c-%Y') >= ?", [$this->dateFrom]))
            ->when($this->dateTo,   fn($q) => $q->whereRaw("STR_TO_DATE(pdate, '%e-%c-%Y') <= ?", [$this->dateTo]))
            ->sum('price');

        return ['rows' => $rows, 'summary' => ['total' => $total]];
    }

    /* ══════════════════════════════════════════════
       10. أرصدة العملاء – Patient Balances
    ══════════════════════════════════════════════ */
    private function getPbData()
    {
        $query = DB::table(DB::raw("(
            SELECT s.id, s.file_id, s.full_name, s.phone,
                COALESCE(dep.deposited, 0) AS deposited,
                COALESCE(chg.charged,   0) AS charged_svc,
                COALESCE(deb.debited,   0) AS charged_old,
                ROUND(
                    COALESCE(dep.deposited,0)
                    - COALESCE(chg.charged,0)
                    - COALESCE(deb.debited,0),
                3) AS balance
            FROM kstu s
            INNER JOIN acck ac ON ac.stu_id = s.id
            LEFT JOIN (
                SELECT acc_id,
                    SUM(COALESCE(NULLIF(amount,0), NULLIF(price,0), 0)) AS deposited
                FROM kpayments WHERE status = 1 AND type_id != 2
                GROUP BY acc_id
            ) dep ON dep.acc_id = ac.id
            LEFT JOIN (
                SELECT r.st_id,
                    SUM(GREATEST(
                        COALESCE(NULLIF(p.amount,0), NULLIF(p.price,0), NULLIF(sv.price,0), 0)
                        - COALESCE(p.discount,0),
                    0)) AS charged
                FROM kpayments p
                INNER JOIN rec r ON r.id = p.rec_id
                LEFT JOIN service sv ON sv.id = r.service_id
                WHERE p.payment_method = 5
                GROUP BY r.st_id
            ) chg ON chg.st_id = s.id
            LEFT JOIN (
                SELECT acc_id,
                    SUM(COALESCE(NULLIF(amount,0), NULLIF(price,0), 0)) AS debited
                FROM kpayments WHERE (status = 2 AND payment_method != 5) OR (status = 1 AND type_id = 2)
                GROUP BY acc_id
            ) deb ON deb.acc_id = ac.id
        ) AS pb"))
            ->where('balance', '>', 0)
            ->orderBy('balance', 'desc');

        if ($this->search) {
            $t = '%' . $this->search . '%';
            $query->where(fn($q) => $q->where('full_name', 'like', $t)
                ->orWhere('file_id', 'like', $t)
                ->orWhere('phone', 'like', $t));
        }

        $totalBalance = round((float) (clone $query)->sum('balance'), 3);
        $rows         = $query->paginate(20);

        return ['rows' => $rows, 'summary' => ['total_balance' => $totalBalance]];
    }

    /* ══════════════════════════════════════════════
       11. الخدمات – Services
    ══════════════════════════════════════════════ */
    private function getServicesData()
    {
        $query = DB::table('service as sv')
            ->leftJoin('clinic as c', 'c.id', '=', 'sv.clinic_id')
            ->select(
                'sv.id', 'sv.name', 'sv.price', 'sv.cost',
                'c.name as clinic_name'
            )
            ->orderBy('sv.name');

        if ($this->filterClinic) $query->where('sv.clinic_id', $this->filterClinic);
        if ($this->search) {
            $query->where('sv.name', 'like', '%' . $this->search . '%');
        }

        $rows = $query->paginate(20);
        return ['rows' => $rows, 'summary' => []];
    }

    /* ══════════════════════════════════════════════
       Render
    ══════════════════════════════════════════════ */
    public function mount(): void
    {
        // دور عيادة: اجبر نوع التقرير على invoices وابحث مباشرة
        if ((auth()->user()?->role ?? '') === 'clinic') {
            $this->reportType = 'invoices';
            $this->searched   = true;
        }
    }

    public function render()
    {
        $userRole  = auth()->user()?->role ?? '';
        $isClinic  = $userRole === 'clinic';
        $userClinicIds = $isClinic ? json_decode(auth()->user()?->clinic_ids ?? '[]', true) : [];

        $clinics = DB::table('clinic')->where('state_id', 1)->orderBy('name')->get(['id', 'name']);

        // دور عيادة: يرى عيادته فقط
        if ($isClinic && !empty($userClinicIds)) {
            $clinics = $clinics->whereIn('id', $userClinicIds)->values();
        }

        $rows    = collect();
        $summary = [];

        if ($this->searched) {
            $result = match($this->reportType) {
                'invoices'     => $this->getInvoicesData(),
                'vouchers'     => $this->getVouchersData(),
                'pb'           => $this->getPbData(),
                'services'     => $this->getServicesData(),
                'appointments' => $this->getAppointmentsData(),
                'clinics'      => $this->getClinicsData(),
                'claims'       => $this->getClaimsData(),
                'pfs'          => $this->getPfsData(),
                'patients'     => $this->getPatientsData(),
                'till'         => $this->getTillData(),
                default        => $this->getIncomeData(),
            };
            $rows    = $result['rows'];
            $summary = $result['summary'];
        }

        return view('livewire.finance.reports', [
            'rows'    => $rows,
            'summary' => $summary,
            'clinics' => $clinics,
        ])->layout('layouts.app');
    }
}
