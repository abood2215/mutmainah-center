<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class BranchReport extends Component
{
    public string $fromDate = '';
    public string $toDate   = '';

    public function mount(): void
    {
        $this->fromDate = now()->startOfMonth()->format('Y-m-d');
        $this->toDate   = now()->format('Y-m-d');
    }

    private function buildDateRange(): array
    {
        $dates   = [];
        $current = \Carbon\Carbon::parse($this->fromDate);
        $end     = \Carbon\Carbon::parse($this->toDate);
        while ($current->lte($end)) {
            $dates[] = $current->format('j-n-Y');
            $current->addDay();
        }
        return $dates;
    }

    #[Title('تقرير الفروع')]
    public function render()
    {
        $branches  = DB::table('branches')->where('is_active', 1)->orderBy('id')->get();
        $dateRange = $this->buildDateRange();

        $stats = $branches->map(function ($branch) use ($dateRange) {

            // إجمالي العملاء
            $patientsCount = DB::table('kstu')->where('branch_id', $branch->id)->count();

            // الكشوفات في الفترة
            $checks = DB::table('rec as r')
                ->join('kstu as k', 'k.id', '=', 'r.st_id')
                ->where('k.branch_id', $branch->id)
                ->where('r.confirm_id', 1)
                ->whereIn('r.rec_date', $dateRange)
                ->count();

            // الإيرادات في الفترة
            $revenue = DB::table('kpayments as p')
                ->join('rec as r', 'r.id', '=', 'p.rec_id')
                ->join('kstu as k', 'k.id', '=', 'r.st_id')
                ->where('k.branch_id', $branch->id)
                ->where('p.price', '>', 0)
                ->whereIn('p.pdate', $dateRange)
                ->sum('p.price');

            // المواعيد في الفترة
            $appointments = DB::table('rec as r')
                ->join('kstu as k', 'k.id', '=', 'r.st_id')
                ->where('k.branch_id', $branch->id)
                ->where('r.confirm_id', 0)
                ->whereIn('r.rec_date', $dateRange)
                ->count();

            // أعلى 5 عيادات إيراداً
            $topClinics = DB::table('kpayments as p')
                ->join('rec as r', 'r.id', '=', 'p.rec_id')
                ->join('kstu as k', 'k.id', '=', 'r.st_id')
                ->join('clinic as c', 'c.id', '=', 'r.clinic_id')
                ->where('k.branch_id', $branch->id)
                ->where('p.price', '>', 0)
                ->whereIn('p.pdate', $dateRange)
                ->select('c.name', DB::raw('SUM(p.price) as total'), DB::raw('COUNT(DISTINCT r.id) as cnt'))
                ->groupBy('r.clinic_id', 'c.name')
                ->orderByDesc('total')
                ->limit(5)
                ->get();

            // إيرادات يومية
            $dailyMap = DB::table('kpayments as p')
                ->join('rec as r', 'r.id', '=', 'p.rec_id')
                ->join('kstu as k', 'k.id', '=', 'r.st_id')
                ->where('k.branch_id', $branch->id)
                ->where('p.price', '>', 0)
                ->whereIn('p.pdate', $dateRange)
                ->select('p.pdate', DB::raw('SUM(p.price) as total'))
                ->groupBy('p.pdate')
                ->get()->pluck('total', 'pdate');

            $chartData = array_map(fn($d) => round($dailyMap[$d] ?? 0, 3), $dateRange);

            return (object)[
                'id'           => $branch->id,
                'name'         => $branch->name,
                'patients'     => $patientsCount,
                'checks'       => $checks,
                'revenue'      => $revenue,
                'appointments' => $appointments,
                'topClinics'   => $topClinics,
                'chartData'    => $chartData,
            ];
        });

        // مقارنة الإجماليات
        $totalRevenue      = $stats->sum('revenue');
        $totalChecks       = $stats->sum('checks');
        $totalAppointments = $stats->sum('appointments');

        return view('livewire.finance.branch-report', compact(
            'stats', 'totalRevenue', 'totalChecks', 'totalAppointments', 'dateRange'
        ))->layout('layouts.app');
    }
}
