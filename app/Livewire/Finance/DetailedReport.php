<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class DetailedReport extends Component
{
    public string $fromDay    = '';
    public string $fromMonth  = '';
    public string $fromYear   = '';
    public string $toDay      = '';
    public string $toMonth    = '';
    public string $toYear     = '';
    public string $filterClinic = '';
    public bool   $searched   = false;
    public $rows = null;
    public float $totalNet    = 0;
    public float $totalDisc   = 0;
    public float $totalGross  = 0;

    #[Title('التقرير التفصيلي')]

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

        $q = DB::table('kpayments as k')
            ->join('rec as r', 'r.id', '=', 'k.rec_id')
            ->leftJoin('kstu as s', 's.id', '=', 'r.st_id')
            ->leftJoin('clinic as c', 'c.id', '=', 'k.clinic_id')
            ->leftJoin('service as sv', 'sv.id', '=', 'r.service_id')
            ->leftJoin('employees as e', 'e.id', '=', 'r.user_id')
            ->where('r.confirm_id', 1)
            ->whereRaw("STR_TO_DATE(r.rec_date, '%e-%c-%Y') >= ?", [$from])
            ->whereRaw("STR_TO_DATE(r.rec_date, '%e-%c-%Y') <= ?", [$to]);

        if ($this->filterClinic) {
            $q->where('k.clinic_id', $this->filterClinic);
        }

        $results = $q->select(
            'k.id', 'r.id as rec_id', 'r.rec_date as pdate',
            's.full_name as patient_name', 's.file_id',
            'c.name as clinic_name',
            'k.pdesc as service_name',
            'k.price', 'k.discount',
            DB::raw('(k.price - COALESCE(k.discount, 0)) as net'),
            'k.payment_method',
            DB::raw("CONCAT(COALESCE(e.first_name,''), ' ', COALESCE(e.middle_initial,'')) as cashier")
        )
        ->orderByRaw("STR_TO_DATE(r.rec_date, '%e-%c-%Y') DESC, r.id DESC, k.id ASC")
        ->get();

        $this->rows       = $results;
        $this->totalGross = round($results->sum('price'), 3);
        $this->totalDisc  = round($results->sum('discount'), 3);
        $this->totalNet   = round($results->sum('net'), 3);
        $this->searched   = true;
    }

    public function resetForm(): void
    {
        $this->fromDay     = '1';
        $this->fromMonth   = now()->format('n');
        $this->fromYear    = now()->format('Y');
        $this->toDay       = now()->format('j');
        $this->toMonth     = now()->format('n');
        $this->toYear      = now()->format('Y');
        $this->filterClinic = '';
        $this->rows        = null;
        $this->totalNet    = 0;
        $this->totalDisc   = 0;
        $this->totalGross  = 0;
        $this->searched    = false;
    }

    public function render()
    {
        $clinics = DB::table('clinic')->where('state_id', 1)->orderBy('name')->get(['id', 'name']);
        return view('livewire.finance.detailed-report', compact('clinics'))->layout('layouts.app');
    }
}
