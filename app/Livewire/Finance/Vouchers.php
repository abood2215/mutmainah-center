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

        $query = DB::table('vouchers as v')
            ->leftJoin('kstu as s', 's.id', '=', 'v.stu_id')
            ->whereRaw("STR_TO_DATE(v.pdate, '%e-%c-%Y') >= ?", [$from])
            ->whereRaw("STR_TO_DATE(v.pdate, '%e-%c-%Y') <= ?", [$to])
            ->select('v.id', 'v.pdate', 'v.credit', 'v.debit', 'v.pdesc', 'v.ptype', 'v.notes', 'v.serial_no', 's.full_name as client_name', 's.file_id');

        if ($this->voucherType === 'receipt') {
            $query->where('v.credit', '>', 0);
        } elseif ($this->voucherType === 'payment') {
            $query->where('v.debit', '>', 0);
        }

        $query->orderByRaw("STR_TO_DATE(v.pdate, '%e-%c-%Y') DESC, v.id DESC");

        $results          = $query->get();
        $this->rows       = $results;
        $this->totalCredit = $results->sum('credit');
        $this->totalDebit  = $results->sum('debit');
        $this->searched   = true;
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
