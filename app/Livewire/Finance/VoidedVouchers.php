<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class VoidedVouchers extends Component
{
    public string $fromDay    = '';
    public string $fromMonth  = '';
    public string $fromYear   = '';
    public string $toDay      = '';
    public string $toMonth    = '';
    public string $toYear     = '';
    public bool   $searched   = false;
    public $rows = null;

    #[Title('السندات الملغاة')]

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

        $this->rows = DB::table('activity_logs as al')
            ->leftJoin('kstu as s', 's.id', '=', 'al.subject_id')
            ->where('al.action', 'deleted')
            ->where('al.subject', 'payment')
            ->whereRaw("DATE(al.created_at) >= ?", [$from])
            ->whereRaw("DATE(al.created_at) <= ?", [$to])
            ->select('al.id', 'al.description', 'al.user_name', 'al.created_at', 'al.subject_id', 's.full_name', 's.file_id')
            ->orderBy('al.id', 'desc')
            ->get();

        $this->searched = true;
    }

    public function resetForm(): void
    {
        $this->fromDay   = '1';
        $this->fromMonth = now()->format('n');
        $this->fromYear  = now()->format('Y');
        $this->toDay     = now()->format('j');
        $this->toMonth   = now()->format('n');
        $this->toYear    = now()->format('Y');
        $this->rows      = null;
        $this->searched  = false;
    }

    public function render()
    {
        return view('livewire.finance.voided-vouchers')->layout('layouts.app');
    }
}
