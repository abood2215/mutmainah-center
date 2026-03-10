<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Statement extends Component
{
    public string $searchQuery   = '';
    public array  $suggestions  = [];
    public ?int   $patientId    = null;
    public string $day          = '';
    public string $month        = '';
    public string $year         = '';
    public string $toDay        = '';
    public string $toMonth      = '';
    public string $toYear       = '';
    public bool   $searched     = false;
    public bool   $notFound     = false;

    public ?object $patient     = null;
    public array   $rows        = [];
    public float   $totalCredit = 0;
    public float   $totalDebit  = 0;

    #[Title('بيان الحساب')]
    public function mount(): void
    {
        $this->day     = '1';
        $this->month   = now()->format('n');
        $this->year    = now()->format('Y');
        $this->toDay   = now()->format('j');
        $this->toMonth = now()->format('n');
        $this->toYear  = now()->format('Y');
    }

    public function updatedSearchQuery(): void
    {
        $this->patientId = null;
        $this->searched  = false;
        $q = trim($this->searchQuery);
        if (strlen($q) < 2) {
            $this->suggestions = [];
            return;
        }
        $this->suggestions = DB::table('kstu')
            ->where(function ($w) use ($q) {
                $w->where('ssn', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%")
                  ->orWhere('file_id', 'like', "%{$q}%")
                  ->orWhere('id', $q);
            })
            ->select('id', 'full_name', 'file_id', 'ssn', 'phone')
            ->limit(8)
            ->get()
            ->toArray();
    }

    public function selectPatient(int $id): void
    {
        $this->patientId   = $id;
        $this->patient     = DB::table('kstu')->where('id', $id)->first();
        $this->searchQuery = $this->patient->full_name ?? '';
        $this->suggestions = [];
    }

    public function search(): void
    {
        if (!$this->patientId) return;

        $this->patient = DB::table('kstu')->where('id', $this->patientId)->first();

        if (!$this->patient) {
            $this->notFound = true;
            $this->searched = true;
            $this->rows     = [];
            return;
        }
        $this->notFound = false;

        $from = sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->day);
        $to   = sprintf('%04d-%02d-%02d', $this->toYear, $this->toMonth, $this->toDay);

        // kpayments مرتبطة بالمريض عبر rec.st_id → kstu.id
        $results = DB::table('kpayments as k')
            ->join('rec as r', 'r.id', '=', 'k.rec_id')
            ->where('r.st_id', $this->patient->id)
            ->where('k.rec_id', '>', 0)
            ->whereRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') >= ?", [$from])
            ->whereRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') <= ?", [$to])
            ->orderByRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') ASC, k.id ASC")
            ->select('k.id', 'k.pdate', 'k.amount', 'k.payment_method', 'k.pdesc', 'k.serial_no', 'r.id as rec_id')
            ->get();

        $this->rows = $results->toArray();

        // دائن = ما دُفع فعلاً (نقدي/كنت/فيزا)
        $this->totalCredit = $results->whereIn('payment_method', [1, 2, 3])->sum('amount');
        // مدين = ما حُمّل على الحساب (آجل)
        $this->totalDebit  = $results->where('payment_method', 5)->sum('amount');
        $this->searched    = true;
    }

    public function resetForm(): void
    {
        $this->searchQuery  = '';
        $this->suggestions  = [];
        $this->patientId    = null;
        $this->patient      = null;
        $this->notFound     = false;
        $this->day          = '1';
        $this->month        = now()->format('n');
        $this->year         = now()->format('Y');
        $this->toDay        = now()->format('j');
        $this->toMonth      = now()->format('n');
        $this->toYear       = now()->format('Y');
        $this->rows         = [];
        $this->searched     = false;
    }

    public function render()
    {
        return view('livewire.finance.statement')->layout('layouts.app');
    }
}
