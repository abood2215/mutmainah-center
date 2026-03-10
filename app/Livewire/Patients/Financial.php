<?php

namespace App\Livewire\Patients;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Financial extends Component
{
    public $patientId;
    public $patient;

    #[Title('البيان المالي')]
    public function mount($id): void
    {
        $this->patientId = $id;
        $this->patient   = DB::table('kstu')->where('id', $id)->first();
        abort_if(!$this->patient, 404);
    }

    public function render()
    {
        // حساب المريض في acck (مرتبط بـ stu_id)
        $acck   = DB::table('acck')->where('stu_id', $this->patientId)->first();
        $acckId = $acck?->id;

        // الإيداعات على الحساب (مدفوعات مباشرة للحساب)
        $deposits = $acckId
            ? DB::table('kpayments')
                ->where('acc_id', $acckId)
                ->where('status', 1)
                ->get(['id', 'pdate', 'pdesc', 'amount', 'payment_method'])
            : collect();

        $totalDeposited = $deposits->sum('amount');

        // الخدمات المحتسبة عبر الكشوف
        $services = DB::table('kpayments as k')
            ->join('rec as r', 'r.id', '=', 'k.rec_id')
            ->where('r.st_id', $this->patientId)
            ->select('k.id', 'k.pdate', 'k.pdesc', 'k.price', 'k.payment_method', 'k.rec_id')
            ->orderBy('k.id', 'desc')
            ->get();

        // الخدمات المحتسبة على الحساب فقط (method=5 = آجل)
        $totalCharged = $services->where('payment_method', 5)->sum('price');

        // الرصيد المتبقي = الإيداعات - المحتسب على الحساب
        $balance = $totalDeposited - $totalCharged;

        // إجمالي جميع الخدمات (للعرض)
        $totalServices = $services->sum('price');

        return view('livewire.patients.financial', [
            'deposits'       => $deposits,
            'services'       => $services,
            'totalDeposited' => $totalDeposited,
            'totalCharged'   => $totalCharged,
            'totalServices'  => $totalServices,
            'balance'        => $balance,
        ])->layout('layouts.app');
    }
}
