<?php

namespace App\Livewire\Patients;

use App\Helpers\ActivityLogger;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class NewCheck extends Component
{
    public $patientId;
    public $patient;
    public float $balance    = 0.0;
    public bool  $hasAccount = false;
    public array $clinics    = [];
    public array $services = [];

    // حقول إضافة الخدمة
    public string $filterClinic    = '';
    public string $serviceSearch   = '';
    public string $selectedService = '';
    public string $qty             = '1';

    // قائمة الخدمات المضافة
    public array $items = [];

    // بيانات الدفع
    public string $totalDiscount = '0';
    public int    $isFree        = 0; // 0=لا، 1=مجاني
    public string $credit        = '';
    public string $paymentMethod = '1';
    public string $notes         = '';

    #[Title('كشف جديد')]
    public function mount($id): void
    {
        $this->patientId = $id;

        $this->patient = DB::table('kstu as k')
            ->leftJoin('kcom as c', 'c.id', '=', 'k.com_id')
            ->where('k.id', $id)
            ->select('k.id', 'k.full_name', 'k.phone', 'k.file_id', 'k.ssn', 'c.name as insurance')
            ->first();

        abort_if(!$this->patient, 404);

        // حساب رصيد العميل الحالي
        $acck   = DB::table('acck')->where('stu_id', $id)->first();
        $acckId = $acck?->id;
        $this->hasAccount = (bool) $acckId;
        if ($acckId) {
            // إيداعات الحساب
            $totalDeposited = (float) DB::table('kpayments')
                ->where('acc_id', $acckId)
                ->where('status', 1)
                ->selectRaw('COALESCE(SUM(COALESCE(NULLIF(amount,0), NULLIF(price,0), 0)),0) as total')
                ->value('total');

            // خدمات مدفوعة من الرصيد (pm=5) — يستخدم سعر الخدمة كـ fallback
            $charged_svc = 0.0;
            $recIds = DB::table('rec')->where('st_id', $id)->pluck('id');
            if ($recIds->isNotEmpty()) {
                $svc = DB::table('kpayments as p')
                    ->join('rec as r', 'r.id', '=', 'p.rec_id')
                    ->leftJoin('service as sv', 'sv.id', '=', 'r.service_id')
                    ->whereIn('p.rec_id', $recIds)->where('p.payment_method', 5)
                    ->selectRaw('COALESCE(SUM(GREATEST(COALESCE(NULLIF(p.amount,0),NULLIF(p.price,0),NULLIF(sv.price,0),0)-COALESCE(p.discount,0),0)),0) as tp')
                    ->first();
                $charged_svc = (float)($svc->tp ?? 0);
            }

            // قيود الخصم (status=2 payment!=5) أو استرداد (status=1 type_id=2)
            $charged_old = (float) DB::table('kpayments')
                ->where('acc_id', $acckId)
                ->where(function($q) {
                    $q->where(fn($q2) => $q2->where('status', 2)->where('payment_method', '!=', 5))
                      ->orWhere(fn($q2) => $q2->where('status', 1)->where('type_id', 2));
                })
                ->selectRaw('COALESCE(SUM(COALESCE(NULLIF(amount,0), NULLIF(price,0), 0)),0) as total')
                ->value('total');

            $this->balance = round($totalDeposited - $charged_svc - $charged_old, 3);
            // إذا كان في رصيد — اجعل طريقة الدفع "من الرصيد" تلقائياً
            if ($this->balance > 0) {
                $this->paymentMethod = '5';
            }
        }

        $this->clinics = DB::table('clinic')->where('state_id', 1)->orderBy('name')->get(['id', 'name'])->all();
        $this->loadServices();
    }

    public function updatedTotalDiscount(): void
    {
        // تأكد أن القيمة رقم موجب
        $val = (float)$this->totalDiscount;
        $max = $this->getTotal();
        if ($val < 0) $val = 0;
        if ($val > $max) $val = $max;
        $this->totalDiscount = (string)$val;
    }

    public function updatedFilterClinic(): void
    {
        $this->selectedService = '';
        $this->loadServices();
    }

    public function updatedServiceSearch(): void
    {
        $this->loadServices();
    }

    private function loadServices(): void
    {
        // إذا لم تُختر عيادة بعد — القائمة فارغة
        if (!$this->filterClinic) {
            $this->services = [];
            return;
        }

        $q = DB::table('service')
            ->where('clinic_id', $this->filterClinic)
            ->where('state_id', 0);

        if ($this->serviceSearch) {
            $q->where(fn($q) => $q->where('name', 'like', '%'.$this->serviceSearch.'%')
                                  ->orWhere('ccode', 'like', '%'.$this->serviceSearch.'%'));
        }

        $this->services = $q->orderBy('name')->limit(150)
            ->get(['id', 'name', 'price', 'ccode', 'clinic_id'])
            ->all();
    }

    public function addItem(): void
    {
        if (!$this->selectedService) return;

        $svc = collect($this->services)->first(fn($s) => (int)$s->id === (int)$this->selectedService);

        if (!$svc) {
            $svc = DB::table('service')->where('id', $this->selectedService)
                ->first(['id', 'name', 'price', 'ccode', 'clinic_id']);
        }

        if (!$svc) return;

        $clinicId   = $this->filterClinic ?: ($svc->clinic_id ?? null);
        $clinicName = '—';

        if ($clinicId) {
            $c = collect($this->clinics)->first(fn($c) => $c->id == $clinicId);
            $clinicName = $c ? $c->name : '—';
        }

        // الكود: لا تعرض إذا كان نفس الاسم أو فارغ
        $code = $svc->ccode ?? '';
        if ($code === $svc->name || trim($code) === '') {
            $code = '';
        }

        // qty جلسات = qty صفوف منفصلة بنفس السعر
        $count = max(1, (int)$this->qty);
        for ($i = 0; $i < $count; $i++) {
            $this->items[] = [
                'service_id'   => (int)$svc->id,
                'service_name' => $svc->name,
                'code'         => $code,
                'clinic_id'    => $clinicId,
                'clinic_name'  => $clinicName,
                'price'        => round((float)$svc->price, 3),
                'notes'        => '',
                'insurance_val'=> 0,
            ];
        }

        $this->selectedService = '';
        $this->qty             = '1';
    }

    public function removeItem(int $index): void
    {
        array_splice($this->items, $index, 1);
        $this->items = array_values($this->items);
    }

    public function getTotal(): float
    {
        return round(array_sum(array_column($this->items, 'price')), 3);
    }

    public function getInsuranceTotal(): float
    {
        return round(array_sum(array_column($this->items, 'insurance_val')), 3);
    }

    public function getPatientAmount(): float
    {
        $disc = $this->isFree ? $this->getTotal() : (float)$this->totalDiscount;
        return max(0, round($this->getTotal() - $disc - $this->getInsuranceTotal(), 3));
    }

    public function save(): void
    {
        if (empty($this->items)) return;

        // منع الحجز إذا كان الرصيد غير كافٍ
        if ($this->hasAccount && !$this->isFree) {
            $due = $this->getPatientAmount();
            if ($due > 0 && $this->balance < $due) {
                session()->flash('balance_error',
                    'الرصيد غير كافٍ — الرصيد المتاح: ' . number_format($this->balance, 3) .
                    ' د.ك، المطلوب: ' . number_format($due, 3) . ' د.ك'
                );
                return;
            }
        }

        $today         = now()->format('j-n-Y');
        $firstClinicId = $this->items[0]['clinic_id'] ?? 0;
        $firstServiceId= $this->items[0]['service_id'] ?? 0;

        $recId = DB::table('rec')->insertGetId([
            'rec_date'        => $today,
            'rec_time'        => now()->format('H:i'),
            'st_id'           => $this->patientId,
            'clinic_id'       => $firstClinicId,
            'service_id'      => $firstServiceId,
            'confirm_id'      => 1,
            'state_id'        => 1,
            'notes'           => $this->notes ?: '',
            // حقول إلزامية بقيم افتراضية
            'c_id'            => 0,
            'doc_id'          => 0,
            'pstate_id'       => 0,
            'type_id'         => 0,
            'per_id'          => 0,
            'order_id'        => 0,
            'pharm_id'        => 0,
            'user_id'         => 0,
            'transfer_doc_id' => 0,
            'new_service_id'  => 0,
            'rev_id'          => 0,
            'rev_days'        => 0,
            'date_serial'     => 0,
            'call_id'         => 0,
            'dental_id'       => 0,
            'serv_no'         => 0,
            'sym'             => '',
            'dia'             => '',
            'pres'            => '',
            'pdate'           => $today,
            'pressure'        => '',
            'heat'            => '',
            'pulse'           => '',
            'diab'            => '',
        ]);

        // تحديث الموعد القديم (إن وُجد) ليصبح مكتملاً فيختفي من قائمة المواعيد
        DB::table('rec')
            ->where('st_id', $this->patientId)
            ->where('rec_date', $today)
            ->where('confirm_id', 0)
            ->update(['confirm_id' => 1]);

        $payMethod = $this->isFree ? 7 : (int)$this->paymentMethod;
        $totalDisc = $this->isFree ? $this->getTotal() : (float)$this->totalDiscount;

        foreach ($this->items as $i => $item) {
            DB::table('kpayments')->insert([
                'rec_id'          => $recId,
                'pdate'           => $today,
                'price'           => $item['price'],
                'discount'        => $i === 0 ? $totalDisc : 0,
                'payment_method'  => $payMethod,
                'clinic_id'       => $item['clinic_id'] ?? 0,
                'pdesc'           => $item['service_name'],
                'acc_id'          => 0,
                // حقول إلزامية بدون default
                'serial_no'       => 0,
                'credit'          => $item['price'],
                'net'             => $item['price'] - ($i === 0 ? $totalDisc : 0),
                'user_id'         => 0,
                'type_id'         => 0,
                'client_id'       => 0,
                'res_amount'      => 0,
                'cash_discount'   => 0,
                'ratio_discount'  => 0,
                'importer_id'     => 0,
                'c_id'            => 0,
                'p_id'            => 0,
                'bank'            => 0,
                'branch'          => 0,
                'check_no'        => 0,
                'amount'          => $item['price'],
                'pharm_id'        => 0,
                'status'          => 0,
                'p_amount'        => 0,
                'c_amount'        => 0,
                'com_id'          => 0,
                'dis_id'          => 0,
                'vno'             => 0,
                'ptime'           => now()->format('H:i'),
                'insur_amount'    => 0,
                'pharmacy_id'     => 0,
                'serv_no'         => 0,
                'v_id'            => 0,
                'date_serial'     => 0,
                'interface_id'    => 0,
                'ns'              => 0,
                'clinic_type_id'  => 0,
            ]);
        }

        $clinicName = $this->items[0]['clinic_name'] ?? '—';
        ActivityLogger::log('created', 'check', $recId,
            'كشف جديد للعميل: ' . $this->patient->full_name . ' — ' . $clinicName . ' — المبلغ: ' . $this->getTotal() . ' د.ك'
        );

        session()->flash('check_success', [
            'rec_id'     => $recId,
            'patient'    => $this->patient->full_name,
            'clinic'     => $this->items[0]['clinic_name'] ?? '—',
            'total'      => $this->getTotal(),
            'invoice_url'=> route('finance.invoice-print', ['recId' => $recId]),
        ]);

        $this->redirect(route('checks.index'), navigate: true);
    }

    public function render()
    {
        $total          = $this->getTotal();
        $insuranceTotal = $this->getInsuranceTotal();
        $discount       = $this->isFree ? $total : (float)$this->totalDiscount;
        $patientAmount  = $this->getPatientAmount();

        return view('livewire.patients.new-check', compact(
            'total', 'insuranceTotal', 'discount', 'patientAmount'
        ) + ['balance' => $this->balance])->layout('layouts.app');
    }
}
