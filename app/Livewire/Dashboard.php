<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Dashboard extends Component
{
    #[Title('لوحة التحكم')]

    public string $monthModalLabel   = '';
    public array  $monthModalClinics = [];
    public bool   $showMonthModal    = false;

    public function loadMonthClinics(int $year, int $month, string $label): void
    {
        $clinics = DB::table('rec as r')
            ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
            ->where('r.confirm_id', 1)
            ->whereRaw("MONTH(STR_TO_DATE(r.rec_date, '%e-%c-%Y')) = ?", [$month])
            ->whereRaw("YEAR(STR_TO_DATE(r.rec_date, '%e-%c-%Y')) = ?",  [$year])
            ->select('c.name as clinic_name', DB::raw('COUNT(r.id) as count'))
            ->groupBy('r.clinic_id', 'c.name')
            ->orderBy('count', 'desc')
            ->limit(12)
            ->get();

        $this->monthModalLabel   = $label;
        $this->monthModalClinics = $clinics->toArray();
        $this->showMonthModal    = true;
    }

    public function closeMonthModal(): void
    {
        $this->showMonthModal = false;
    }

    public function render()
    {
        $totalPatients = DB::table('kstu')->count();

        $lastPatient = DB::table('kstu')
            ->select('id', 'file_id', 'full_name')
            ->orderBy('id', 'desc')
            ->first();

        $today = now()->format('j-n-Y');

        // تم الكشف = نزل بقائمة الكشوف (دفع) confirm_id=1
        $todayDone = DB::table('rec')
            ->where('rec_date', $today)
            ->where('confirm_id', 1)
            ->count();

        // في الانتظار = حاجز موعد اليوم ولسا ما دفع confirm_id=0
        $todayWaiting = DB::table('rec')
            ->where('rec_date', $today)
            ->where('confirm_id', 0)
            ->count();

        $todayChecks = $todayDone + $todayWaiting;

        // إيرادات هذا الشهر — pdate بصيغة j-n-Y
        $currentMonth = now()->format('n');
        $currentYear  = now()->format('Y');
        $cacheKey = "dash_month_{$currentYear}_{$currentMonth}";

        // الإيرادات والرسوم البيانية: cache 10 دقائق (لا تتغير كثيراً)
        [$monthlyRevenue, $chartDailyLabels, $chartDailyData, $chartMonthLabels, $chartMonthData, $chartMonthKeys, $clinicChartData, $branchStats] =
            Cache::remember($cacheKey, 600, function () use ($currentMonth, $currentYear) {

            $monthlyRevenue = DB::table('kpayments')
                ->where('price', '>', 0)
                ->whereRaw("MONTH(STR_TO_DATE(pdate, '%e-%c-%Y')) = ?", [$currentMonth])
                ->whereRaw("YEAR(STR_TO_DATE(pdate, '%e-%c-%Y')) = ?",  [$currentYear])
                ->sum('price');

            // إيرادات يومية للشهر الحالي
            $daysInMonth  = now()->daysInMonth;
            $dailyRevenue = DB::table('kpayments')
                ->where('price', '>', 0)
                ->whereRaw("MONTH(STR_TO_DATE(pdate, '%e-%c-%Y')) = ?", [$currentMonth])
                ->whereRaw("YEAR(STR_TO_DATE(pdate, '%e-%c-%Y')) = ?",  [$currentYear])
                ->select(DB::raw("DAY(STR_TO_DATE(pdate, '%e-%c-%Y')) as day"), DB::raw('SUM(price) as total'))
                ->groupBy('day')
                ->pluck('total', 'day');

            $chartDailyLabels = [];
            $chartDailyData   = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $chartDailyLabels[] = $d;
                $chartDailyData[]   = round($dailyRevenue[$d] ?? 0, 3);
            }

            // مقارنة آخر 6 أشهر
            $chartMonthLabels = [];
            $chartMonthData   = [];
            $chartMonthKeys   = []; // year-month للـ JavaScript
            for ($i = 5; $i >= 0; $i--) {
                $dt  = now()->subMonths($i);
                $m   = $dt->month;
                $y   = $dt->year;
                $rev = DB::table('kpayments')
                    ->where('price', '>', 0)
                    ->whereRaw("MONTH(STR_TO_DATE(pdate, '%e-%c-%Y')) = ?", [$m])
                    ->whereRaw("YEAR(STR_TO_DATE(pdate, '%e-%c-%Y')) = ?",  [$y])
                    ->sum('price');
                $chartMonthLabels[] = $dt->locale('ar')->isoFormat('MMM YY');
                $chartMonthData[]   = round($rev, 3);
                $chartMonthKeys[]   = ['year' => $y, 'month' => $m, 'label' => $dt->locale('ar')->isoFormat('MMMM YYYY')];
            }

            // توزيع العيادات هذا الشهر
            $clinicChartData = DB::table('rec as r')
                ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
                ->where('r.confirm_id', 1)
                ->whereRaw("MONTH(STR_TO_DATE(r.rec_date, '%e-%c-%Y')) = ?", [$currentMonth])
                ->whereRaw("YEAR(STR_TO_DATE(r.rec_date, '%e-%c-%Y')) = ?",  [$currentYear])
                ->select('c.name as clinic_name', DB::raw('COUNT(r.id) as count'))
                ->groupBy('r.clinic_id', 'c.name')
                ->orderBy('count', 'desc')
                ->limit(8)
                ->get();

            // إحصائيات الفروع
            $allBranches = DB::table('branches')->where('is_active', 1)->get(['id', 'name']);
            $branchPatients = DB::table('kstu')
                ->whereIn('branch_id', $allBranches->pluck('id'))
                ->select('branch_id', DB::raw('COUNT(*) as cnt'))
                ->groupBy('branch_id')
                ->pluck('cnt', 'branch_id');

            $branchMonthRevenue = DB::table('kpayments as p')
                ->join('rec as r', 'r.id', '=', 'p.rec_id')
                ->join('kstu as k', 'k.id', '=', 'r.st_id')
                ->where('p.price', '>', 0)
                ->whereRaw("MONTH(STR_TO_DATE(p.pdate, '%e-%c-%Y')) = ?", [$currentMonth])
                ->whereRaw("YEAR(STR_TO_DATE(p.pdate, '%e-%c-%Y')) = ?",  [$currentYear])
                ->whereIn('k.branch_id', $allBranches->pluck('id'))
                ->select('k.branch_id', DB::raw('SUM(p.price) as revenue'))
                ->groupBy('k.branch_id')
                ->pluck('revenue', 'branch_id');

            $branchStats = $allBranches->map(function ($b) use ($branchPatients, $branchMonthRevenue) {
                $b->patients_count  = $branchPatients[$b->id]     ?? 0;
                $b->monthly_revenue = $branchMonthRevenue[$b->id] ?? 0;
                return $b;
            });

            return [$monthlyRevenue, $chartDailyLabels, $chartDailyData, $chartMonthLabels, $chartMonthData, $chartMonthKeys, $clinicChartData, $branchStats];
        });

        // إيرادات اليوم (لا cache — تتغير لحظياً)
        $todayRevenue = DB::table('kpayments')
            ->where('price', '>', 0)
            ->where('pdate', $today)
            ->sum('price');

        // أحدث الكشوف اليوم (لا cache)
        $recentChecks = DB::table('rec as r')
            ->leftJoin('kstu as s', 's.id', '=', 'r.st_id')
            ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
            ->where('r.rec_date', $today)
            ->where('r.confirm_id', 1)
            ->select('r.id', 'r.rec_time', 'r.state_id', 's.full_name as patient_name', 'c.name as clinic_name')
            ->orderBy('r.id', 'desc')
            ->limit(8)
            ->get();

        // إحصائيات العيادات اليوم (لا cache)
        $clinicStats = DB::table('rec as r')
            ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
            ->where('r.rec_date', $today)
            ->where('r.confirm_id', 1)
            ->select('c.name as clinic_name', DB::raw('COUNT(r.id) as count'))
            ->groupBy('r.clinic_id', 'c.name')
            ->orderBy('count', 'desc')
            ->limit(6)
            ->get();


        return view('livewire.dashboard', [
            'isAdmin'          => (auth()->user()?->role ?? '') === 'admin',
            'branchStats'      => $branchStats,
            'totalPatients'    => $totalPatients,
            'lastPatient'      => $lastPatient,
            'todayChecks'      => $todayChecks,
            'todayDone'        => $todayDone,
            'todayWaiting'     => $todayWaiting,
            'monthlyRevenue'   => $monthlyRevenue,
            'todayRevenue'     => $todayRevenue,
            'recentChecks'     => $recentChecks,
            'clinicStats'      => $clinicStats,
            // charts
            'chartDailyLabels' => $chartDailyLabels,
            'chartDailyData'   => $chartDailyData,
            'chartMonthLabels' => $chartMonthLabels,
            'chartMonthData'   => $chartMonthData,
            'chartMonthKeys'   => $chartMonthKeys,
            'clinicChartData'  => $clinicChartData,
        ])->layout('layouts.app');
    }
}
