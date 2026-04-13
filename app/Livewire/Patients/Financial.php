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
            ->get()
            ->map(function ($svc) {
                // إذا price=0 نحاول استخراج السعر من نص البيان (مثل "جلسة 40 د.ك")
                $effective = (float) $svc->price;
                if ($effective == 0 && !empty($svc->pdesc)) {
                    $desc = strip_tags(html_entity_decode(str_replace("\xc2\xa0", ' ', $svc->pdesc ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                    if (preg_match_all('/([\d.]+)\s*(?:د\.ك|KD|D\.K)/ui', $desc, $m)) {
                        $effective = array_sum(array_map('floatval', $m[1]));
                    }
                }
                $svc->effective_price = $effective;
                return $svc;
            });

        // إجمالي الخدمات المُقدَّمة الفعلية
        $totalServices = $services->sum('effective_price');

        // الخدمات المدفوعة من رصيد الحساب فقط (payment_method=5 = آجل/من الرصيد)
        $deferredServices = $services->where('payment_method', 5);
        $totalDiscount    = $deferredServices->sum('discount');
        $totalCharged     = max(0, $deferredServices->sum('effective_price') - $totalDiscount);

        // الرصيد المتبقي = الإيداعات − الخدمات المدفوعة من الرصيد فقط
        $balance = round($totalDeposited - $totalCharged, 3);

        // ═══ كشف الحساب: إيداعات + مسحوبات مرتبة حسب التاريخ مع رصيد متراكم ═══
        $statementItems = collect();

        foreach ($deposits as $dep) {
            $statementItems->push([
                'date'   => $dep->pdate,
                'type'   => 'deposit',
                'credit' => (float) $dep->dep_amount,
                'debit'  => 0.0,
            ]);
        }

        foreach ($deferredServices as $svc) {
            $net = max(0.0, (float)$svc->effective_price - (float)($svc->discount ?? 0));
            if ($net > 0) {
                $statementItems->push([
                    'date'   => $svc->pdate,
                    'type'   => 'debit',
                    'credit' => 0.0,
                    'debit'  => $net,
                ]);
            }
        }

        // ترتيب بالتاريخ (صيغة j-n-Y)
        $statementItems = $statementItems->sortBy(function ($item) {
            $p = explode('-', $item['date']);
            return count($p) === 3 ? mktime(0, 0, 0, (int)$p[1], (int)$p[0], (int)$p[2]) : 0;
        })->values();

        // رصيد متراكم لكل سطر
        $running = 0.0;
        $statement = $statementItems->map(function ($item) use (&$running) {
            $running += $item['credit'] - $item['debit'];
            return array_merge($item, ['balance' => round($running, 3)]);
        });

        return view('livewire.patients.financial', [
            'deposits'        => $deposits,
            'services'        => $services,
            'statement'       => $statement,
            'totalDeposited'  => $totalDeposited,
            'totalDiscount'   => $totalDiscount,
            'totalCharged'    => $totalCharged,
            'totalServices'   => $totalServices,
            'balance'         => $balance,
        ])->layout('layouts.app');
    }
}
