<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Invoices extends Component
{
    public string $filterClinic  = '';
    public string $filterUser    = '';
    public string $filterPayment = '';
    public string $filterBranch  = '';
    public bool   $searched      = false;
    public array  $branches      = [];

    // تاريخ البداية
    public string $fromDay   = '1';
    public string $fromMonth = '';
    public string $fromYear  = '';

    // تاريخ النهاية
    public string $toDay   = '';
    public string $toMonth = '';
    public string $toYear  = '';

    const PAYMENT_LABELS = [
        1  => ['ar' => 'نقد',              'en' => 'Cash'],
        2  => ['ar' => 'شيك',              'en' => 'Cheque'],
        3  => ['ar' => 'شبكة',             'en' => 'Net'],
        4  => ['ar' => 'تحويل بنكي',       'en' => 'Bank'],
        5  => ['ar' => 'سند',              'en' => 'Voucher'],
        6  => ['ar' => 'فيزا',             'en' => 'Visa'],
        7  => ['ar' => 'مجاني',            'en' => 'Free'],
        11 => ['ar' => 'myfatoorah',       'en' => 'myfatoorah'],
        12 => ['ar' => 'stcpay',           'en' => 'stcpay'],
        14 => ['ar' => 'Quick Pay',        'en' => 'Quick Pay'],
        23 => ['ar' => 'مجاني - من الرصيد', 'en' => 'Free - from Balance'],
    ];

    #[Title('تقارير الفواتير')]

    public function mount(): void
    {
        $this->fromDay   = '1';
        $this->fromMonth = now()->format('n');
        $this->fromYear  = now()->format('Y');
        $this->toDay     = now()->format('j');
        $this->toMonth   = now()->format('n');
        $this->toYear    = now()->format('Y');
        $this->branches  = DB::table('branches')->where('is_active', 1)->get(['id', 'name'])->all();
    }

    public function search(): void
    {
        $this->searched = true;
    }

    public function resetForm(): void
    {
        $this->filterClinic  = '';
        $this->filterUser    = '';
        $this->filterPayment = '';
        $this->fromDay   = '1';
        $this->fromMonth = now()->format('n');
        $this->fromYear  = now()->format('Y');
        $this->toDay     = now()->format('j');
        $this->toMonth   = now()->format('n');
        $this->toYear    = now()->format('Y');
        $this->searched  = false;
    }

    private function dateFrom(): ?string
    {
        return ($this->fromYear && $this->fromMonth && $this->fromDay)
            ? "{$this->fromYear}-{$this->fromMonth}-{$this->fromDay}"
            : null;
    }

    private function dateTo(): ?string
    {
        return ($this->toYear && $this->toMonth && $this->toDay)
            ? "{$this->toYear}-{$this->toMonth}-{$this->toDay}"
            : null;
    }

    private function buildInvoicesQuery()
    {
        $q = DB::table('kpayments as k')
            ->join('rec as r',           'r.id', '=', 'k.rec_id')
            ->leftJoin('kstu as s',      's.id', '=', 'r.st_id')
            ->leftJoin('clinic as c',    'c.id', '=', 'k.clinic_id')
            ->leftJoin('employees as e', 'e.id', '=', 'k.user_id')
            ->where('r.confirm_id', 1);

        if ($this->filterBranch)
            $q->where('s.branch_id', $this->filterBranch);
        if ($this->filterClinic)
            $q->where('k.clinic_id', $this->filterClinic);
        if ($this->filterUser)
            $q->where('k.user_id', $this->filterUser);
        if ($this->filterPayment)
            $q->where('k.payment_method', $this->filterPayment);
        if ($df = $this->dateFrom())
            $q->whereRaw("STR_TO_DATE(r.rec_date, '%e-%c-%Y') >= ?", [$df]);
        if ($dt = $this->dateTo())
            $q->whereRaw("STR_TO_DATE(r.rec_date, '%e-%c-%Y') <= ?", [$dt]);

        return $q;
    }

    private function buildVouchersQuery()
    {
        $q = DB::table('vouchers as v')
            ->leftJoin('acck as a', 'a.id', '=', 'v.account_id')
            ->where('v.credit', '>', 0)
            ->where('v.rec_id', 0);

        if ($df = $this->dateFrom())
            $q->whereRaw("STR_TO_DATE(v.pdate, '%e-%c-%Y') >= ?", [$df]);
        if ($dt = $this->dateTo())
            $q->whereRaw("STR_TO_DATE(v.pdate, '%e-%c-%Y') <= ?", [$dt]);

        return $q;
    }

    public function render()
    {
        $clinics = DB::table('clinic')->where('state_id', 1)->orderBy('name')->get(['id', 'name']);
        $employees = DB::table('employees as e')
            ->whereIn('e.id', DB::table('kpayments')->distinct()->pluck('user_id'))
            ->select('e.id', 'e.first_name', 'e.middle_initial')
            ->orderBy('e.first_name')
            ->get();

        $invoices      = collect();
        $totals        = ['amount' => 0, 'discount' => 0, 'tax' => 0, 'net' => 0, 'count' => 0];
        $payBreak      = [];
        $vouchers      = collect();
        $vTotals       = ['credit' => 0, 'debit' => 0];
        $vMethodBreak  = ['bank' => 0, 'myf' => 0, 'deema' => 0];
        $clinicName    = 'مطمئنة الكويت';
        $dateLabel     = '';

        if ($this->searched) {
            $invoices = $this->buildInvoicesQuery()
                ->select(
                    'k.id', 'k.serial_no', 'k.vno', 'r.id as rec_id', 'r.rec_date as pdate', 'k.price',
                    'k.discount', 'k.tax_value', 'k.payment_method',
                    DB::raw('(k.price - COALESCE(k.discount, 0)) as net'),
                    's.full_name as patient_name', 's.file_id',
                    'c.name as clinic_name',
                    DB::raw("CONCAT(COALESCE(e.first_name,''), ' ', COALESCE(e.middle_initial,'')) as rep_name")
                )
                ->orderByRaw("STR_TO_DATE(r.rec_date, '%e-%c-%Y') DESC, k.id DESC")
                ->get()
                ->map(fn($r) => (object) array_merge((array)$r, ['is_voided' => false]));

            // الفواتير الملغاة من activity_logs
            $voidedQ = DB::table('activity_logs as al')
                ->leftJoin('kstu as s', 's.id', '=', 'al.subject_id')
                ->where('al.action', 'voided');
            if ($df = $this->dateFrom())
                $voidedQ->whereRaw("DATE(al.created_at) >= ?", [$df]);
            if ($dt = $this->dateTo())
                $voidedQ->whereRaw("DATE(al.created_at) <= ?", [$dt]);
            $voidedLogs = $voidedQ->select('al.description', 'al.user_name', 'al.created_at', 's.full_name as patient_name', 's.file_id')
                ->orderBy('al.id', 'desc')->get()
                ->map(function($v) {
                    preg_match('/#(\d+)/', $v->description, $m);
                    $recId = $m[1] ?? null;
                    $date  = \Carbon\Carbon::parse($v->created_at)->format('j-n-Y');
                    return (object)[
                        'is_voided'    => true,
                        'rec_id'       => $recId,
                        'patient_name' => $v->patient_name,
                        'file_id'      => $v->file_id,
                        'pdate'        => $date,
                        'price'        => 0, 'discount' => 0, 'tax_value' => 0, 'net' => 0,
                        'payment_method' => null,
                        'rep_name'     => $v->user_name,
                        'description'  => $v->description,
                    ];
                });

            $invoices = $invoices->concat($voidedLogs);

            $totals = [
                'amount'   => $invoices->sum('price'),
                'discount' => $invoices->sum('discount'),
                'tax'      => $invoices->sum('tax_value'),
                'net'      => $invoices->sum('net'),
                'count'    => $invoices->count(),
            ];

            foreach (self::PAYMENT_LABELS as $code => $info) {
                $sum = $invoices->where('payment_method', $code)->sum('net');
                $payBreak[$code] = ['label' => $info['ar'], 'en' => $info['en'], 'total' => $sum];
            }

            $vouchers = $this->buildVouchersQuery()
                ->select(
                    'v.id', 'v.pdate', 'v.credit', 'v.debit',
                    'v.pdesc', 'v.notes', 'v.serial_no', 'v.ptype',
                    'a.name as patient_name'
                )
                ->orderByRaw("STR_TO_DATE(v.pdate, '%e-%c-%Y') DESC, v.id DESC")
                ->get();

            $vTotals = [
                'credit' => $vouchers->sum('credit'),
                'debit'  => $vouchers->sum('debit'),
            ];

            // كسر طرق دفع السندات الخارجية من pdesc
            $vMethodBreak = ['bank' => 0, 'myf' => 0, 'deema' => 0];
            foreach ($vouchers as $v) {
                $pd  = $v->pdesc ?? '';
                $amt = (float) $v->credit;
                if (preg_match('/2026\d{5,7}/', $pd))
                    $vMethodBreak['myf']   += $amt;
                elseif (preg_match('/\b4\d{5}\b/', $pd))
                    $vMethodBreak['deema'] += $amt;
                elseif (mb_strpos($pd, 'تحو') !== false || mb_strpos($pd, 'بنك') !== false)
                    $vMethodBreak['bank']  += $amt;
                else
                    $vMethodBreak['myf']   += $amt;
            }

            $clinicName = $this->filterClinic
                ? ($clinics->firstWhere('id', $this->filterClinic)?->name ?? 'مطمئنة الكويت')
                : 'مطمئنة الكويت';

            $dateLabel = "{$this->fromDay}-{$this->fromMonth}-{$this->fromYear} الى {$this->toDay}-{$this->toMonth}-{$this->toYear}";
        }

        return view('livewire.finance.invoices', [
            'branches'       => $this->branches,
            'clinics'        => $clinics,
            'employees'      => $employees,
            'invoices'       => $invoices,
            'totals'         => $totals,
            'vouchers'       => $vouchers,
            'vTotals'        => $vTotals,
            'vMethodBreak'   => $vMethodBreak,
            'payBreak'       => $payBreak,
            'paymentLabels'  => self::PAYMENT_LABELS,
            'clinicName'     => $clinicName,
            'dateLabel'      => $dateLabel,
            'currentUserName' => auth()->user()?->getName() ?? '',
        ])->layout('layouts.app');
    }
}
