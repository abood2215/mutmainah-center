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

        // ══ الإيداعات (status=1, type_id!=2, acc_id=acckId) ══
        // type_id=2 يعني استرداد/سحب من الحساب — يُعامَل كخصم لا كإيداع
        $deposits = $acckId
            ? DB::table('kpayments')
                ->where('acc_id', $acckId)
                ->where('status', 1)
                ->where('type_id', '!=', 2)
                ->select(
                    'id', 'pdate', 'pdesc', 'payment_method',
                    DB::raw('COALESCE(NULLIF(amount,0), NULLIF(price,0), 0) as dep_amount')
                )
                ->orderByRaw("STR_TO_DATE(pdate, '%e-%c-%Y')")
                ->orderBy('id')
                ->get()
            : collect();

        $totalDeposited = $deposits->sum('dep_amount');

        // ══ قيود الخصم من الرصيد (النظام القديم) ══
        // حالتان: status=2 (مديونية صريحة) أو status=1+type_id=2 (استرداد/سحب)
        // نستثني payment_method=5 من status=2 لأنه يُحسب في deferredServices
        $accountDebits = $acckId
            ? DB::table('kpayments')
                ->where('acc_id', $acckId)
                ->where(function($q) {
                    $q->where(fn($q2) => $q2->where('status', 2)->where('payment_method', '!=', 5))
                      ->orWhere(fn($q2) => $q2->where('status', 1)->where('type_id', 2));
                })
                ->select(
                    'id', 'pdate', 'pdesc',
                    DB::raw('COALESCE(NULLIF(amount,0), NULLIF(price,0), 0) as debit_amount')
                )
                ->orderByRaw("STR_TO_DATE(pdate, '%e-%c-%Y')")
                ->orderBy('id')
                ->get()
            : collect();

        $totalAccountDebits = (float) $accountDebits->sum('debit_amount');

        // ══ الخدمات (مرتبطة بكشوف العميل عبر rec) ══
        $services = DB::table('kpayments as k')
            ->join('rec as r', 'r.id', '=', 'k.rec_id')
            ->where('r.st_id', $this->patientId)
            ->select(
                'k.id', 'k.pdate', 'k.pdesc', 'k.price', 'k.net',
                'k.discount', 'k.payment_method', 'k.rec_id'
            )
            ->orderByRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') DESC")
            ->orderBy('k.id', 'desc')
            ->get()
            ->map(function ($svc) {
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

        $totalServices = $services->sum('effective_price');

        // ══ الخدمات الآجلة: payment_method=5 (النظام الجديد) ══
        $deferredServices = $services->where('payment_method', 5);
        $totalDiscount    = $deferredServices->sum('discount');
        $totalCharged_svc = max(0, $deferredServices->sum('effective_price') - $totalDiscount);

        // ══ إجمالي المحسوم من الرصيد = طريقة النظام الجديد + طريقة النظام القديم ══
        // لا يوجد تكرار: الجديد payment_method=5 على rec, القديم status=2 على acc_id (مستثنى payment_method=5)
        $totalCharged = $totalCharged_svc + $totalAccountDebits;

        // الرصيد المتبقي
        $balance = round($totalDeposited - $totalCharged, 3);

        // ══ كشف الحساب: إيداعات + مسحوبات مرتبة بالتاريخ مع رصيد متراكم ══
        $statementItems = collect();

        foreach ($deposits as $dep) {
            $statementItems->push([
                'date'   => $dep->pdate,
                'type'   => 'deposit',
                'label'  => $dep->pdesc ?: 'إيداع',
                'credit' => (float) $dep->dep_amount,
                'debit'  => 0.0,
            ]);
        }

        // قيود مديونية صريحة (النظام القديم)
        foreach ($accountDebits as $deb) {
            if ((float)$deb->debit_amount > 0) {
                $statementItems->push([
                    'date'   => $deb->pdate,
                    'type'   => 'debit',
                    'label'  => $deb->pdesc ?: 'خصم من الرصيد',
                    'credit' => 0.0,
                    'debit'  => (float) $deb->debit_amount,
                ]);
            }
        }

        // خدمات آجلة (النظام الجديد)
        foreach ($deferredServices as $svc) {
            $net = max(0.0, (float)$svc->effective_price - (float)($svc->discount ?? 0));
            if ($net > 0) {
                $statementItems->push([
                    'date'   => $svc->pdate,
                    'type'   => 'debit',
                    'label'  => '',
                    'credit' => 0.0,
                    'debit'  => $net,
                ]);
            }
        }

        $statementItems = $statementItems->sortBy(function ($item) {
            $p = explode('-', $item['date']);
            if (count($p) !== 3) return 0;
            return sprintf('%04d%02d%02d', (int)$p[2], (int)$p[1], (int)$p[0]);
        })->values();

        $running = 0.0;
        $statement = $statementItems->map(function ($item) use (&$running) {
            $running += $item['credit'] - $item['debit'];
            return array_merge($item, ['balance' => round($running, 3)]);
        });

        return view('livewire.patients.financial', [
            'deposits'        => $deposits,
            'services'        => $services,
            'accountDebits'   => $accountDebits,
            'statement'       => $statement,
            'totalDeposited'  => $totalDeposited,
            'totalDiscount'   => $totalDiscount,
            'totalCharged'    => $totalCharged,
            'totalServices'   => $totalServices,
            'balance'         => $balance,
        ])->layout('layouts.app');
    }
}
