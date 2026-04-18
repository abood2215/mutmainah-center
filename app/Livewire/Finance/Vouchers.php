<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Vouchers extends Component
{
    public string $classId    = 'mutmainah';
    public string $voucherType = '';   // '' = الكل, receipt, payment
    public string $fromDay    = '';
    public string $fromMonth  = '';
    public string $fromYear   = '';
    public string $toDay      = '';
    public string $toMonth    = '';
    public string $toYear     = '';
    public bool   $searched   = false;

    public $rows      = null;
    public float $totalCredit = 0;
    public float $totalDebit  = 0;

    #[Title('تقارير السندات')]
    public function mount(): void
    {
        $this->fromDay   = '1';
        $this->fromMonth = now()->format('n');
        $this->fromYear  = now()->format('Y');
        $this->toDay     = now()->format('j');
        $this->toMonth   = now()->format('n');
        $this->toYear    = now()->format('Y');
    }

    public function search(): void
    {
        $from = "{$this->fromYear}-{$this->fromMonth}-{$this->fromDay}";
        $to   = "{$this->toYear}-{$this->toMonth}-{$this->toDay}";

        $query = DB::table('kpayments as k')
            ->join('acck as a', 'a.id', '=', 'k.acc_id')
            ->leftJoin('kstu as s', 's.id', '=', 'a.stu_id')
            ->where('k.acc_id', '>', 0)
            ->whereIn('k.status', [1, 2])
            ->whereRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') >= ?", [$from])
            ->whereRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') <= ?", [$to])
            ->selectRaw("
                k.id,
                k.pdate,
                CASE WHEN k.status = 1 THEN COALESCE(NULLIF(k.amount,0), NULLIF(k.price,0), 0) ELSE 0 END as credit,
                CASE WHEN k.status = 2 THEN COALESCE(NULLIF(k.amount,0), NULLIF(k.price,0), 0) ELSE 0 END as debit,
                k.pdesc,
                k.status as ptype,
                k.notes,
                k.vno as serial_no,
                s.full_name as client_name,
                s.file_id,
                s.id as stu_id
            ");

        if ($this->voucherType === 'receipt') {
            $query->where('k.status', 1);
        } elseif ($this->voucherType === 'payment') {
            $query->where('k.status', 2);
        }

        $query->orderByRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') DESC, k.id DESC");

        $results           = $query->get();
        $this->rows        = $results;
        $this->totalCredit = $results->sum('credit');
        $this->totalDebit  = $results->sum('debit');
        $this->searched    = true;
    }

    public function resetForm(): void
    {
        $this->classId     = 'mutmainah';
        $this->voucherType = '';
        $this->fromDay     = '1';
        $this->fromMonth   = now()->format('n');
        $this->fromYear    = now()->format('Y');
        $this->toDay       = now()->format('j');
        $this->toMonth     = now()->format('n');
        $this->toYear      = now()->format('Y');
        $this->rows        = null;
        $this->searched    = false;
    }

    public function render()
    {
        return view('livewire.finance.vouchers')->layout('layouts.app');
    }
}
