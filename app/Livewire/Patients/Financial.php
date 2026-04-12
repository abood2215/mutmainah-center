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
        // طرق الدفع المجانية (لا تُحتسب في أي مجموع مالي)
        $freeMethods = [4, 7]; // 4=مجاني، 7=مجاني via isFree

        // حساب المريض في acck (مرتبط بـ stu_id)
        $acck   = DB::table('acck')->where('stu_id', $this->patientId)->first();
        $acckId = $acck?->id;

        // الإيداعات على الحساب: كل السجلات المرتبطة بحساب العميل (acc_id)
        // نستخدم COALESCE لأن بعض السجلات تخزّن القيمة في amount وبعضها في price
        $deposits = $acckId
            ? DB::table('kpayments')
                ->where('acc_id', $acckId)
                ->whereIn('status', [1])
                ->select(
                    'id', 'pdate', 'pdesc', 'payment_method',
                    DB::raw('COALESCE(NULLIF(amount,0), NULLIF(price,0), 0) as dep_amount')
                )
                ->orderBy('id')
                ->get()
            : collect();

        $totalDeposited = $deposits->sum('dep_amount');

        // جميع الخدمات المرتبطة بكشوف العميل
        $services = DB::table('kpayments as k')
            ->join('rec as r', 'r.id', '=', 'k.rec_id')
            ->where('r.st_id', $this->patientId)
            ->select(
                'k.id', 'k.pdate', 'k.pdesc', 'k.price', 'k.net',
                'k.discount', 'k.payment_method', 'k.rec_id'
            )
            ->orderBy('k.id', 'desc')
            ->get();

        // إجمالي الخدمات المُقدَّمة الفعلية (تستثني المجانية)
        $totalServices = $services
            ->whereNotIn('payment_method', $freeMethods)
            ->sum('price');

        // الخدمات المدفوعة من رصيد الحساب فقط (payment_method=5 = آجل/من الرصيد)
        $deferredServices = $services->where('payment_method', 5);
        $totalDiscount    = $deferredServices->sum('discount');
        $totalCharged     = max(0, $deferredServices->sum('price') - $totalDiscount);

        // الرصيد المتبقي = الإيداعات − الخدمات المدفوعة من الرصيد فقط
        $balance = round($totalDeposited - $totalCharged, 3);

        return view('livewire.patients.financial', [
            'deposits'        => $deposits,
            'services'        => $services,
            'totalDeposited'  => $totalDeposited,
            'totalDiscount'   => $totalDiscount,
            'totalCharged'    => $totalCharged,
            'totalServices'   => $totalServices,
            'balance'         => $balance,
        ])->layout('layouts.app');
    }
}
