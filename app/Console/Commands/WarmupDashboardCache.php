<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class WarmupDashboardCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:warmup-dashboard-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm up dashboard cache to prevent first-load bottlenecks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔥 Starting dashboard cache warmup...');

        $currentMonth = now()->format('n');
        $currentYear  = now()->format('Y');
        $cacheKey = "dash_month_v2_{$currentYear}_{$currentMonth}";

        // تجنب إعادة الحساب إذا كان الـ cache موجوداً بالفعل
        if (Cache::has($cacheKey)) {
            $this->info("✓ Cache already exists for {$currentYear}-{$currentMonth}");
            return;
        }

        $this->info("⏳ Computing dashboard metrics...");

        try {
            Cache::remember($cacheKey, 3600, function () use ($currentMonth, $currentYear) {
                $this->info("  - Monthly Revenue...");
                $monthlyRevenue = DB::table('kpayments')
                    ->where('price', '>', 0)
                    ->whereRaw("MONTH(STR_TO_DATE(pdate, '%e-%c-%Y')) = ?", [$currentMonth])
                    ->whereRaw("YEAR(STR_TO_DATE(pdate, '%e-%c-%Y')) = ?",  [$currentYear])
                    ->sum('price');

                $this->info("  - Daily Revenue Chart...");
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

                $this->info("  - Monthly Comparison Chart...");
                $chartMonthLabels = [];
                $chartMonthData   = [];
                $chartMonthKeys   = [];
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

                $this->info("  - Clinic Distribution...");
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

                $this->info("  - Branch Statistics...");
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

                $this->info("  - All Months Interactive Data...");
                $allMonthsData = [];
                for ($i = 5; $i >= 0; $i--) {
                    $dt  = now()->subMonths($i);
                    $m   = (int) $dt->month;
                    $y   = (int) $dt->year;
                    $dim = $dt->daysInMonth;

                    $dr = DB::table('kpayments')
                        ->where('price', '>', 0)
                        ->whereRaw("MONTH(STR_TO_DATE(pdate, '%e-%c-%Y')) = ?", [$m])
                        ->whereRaw("YEAR(STR_TO_DATE(pdate, '%e-%c-%Y')) = ?",  [$y])
                        ->select(DB::raw("DAY(STR_TO_DATE(pdate, '%e-%c-%Y')) as day"), DB::raw('SUM(price) as total'))
                        ->groupBy('day')
                        ->pluck('total', 'day');

                    $dLabels = []; $dData = [];
                    for ($d = 1; $d <= $dim; $d++) {
                        $dLabels[] = $d;
                        $dData[]   = round($dr[$d] ?? 0, 3);
                    }

                    $cl = DB::table('rec as r')
                        ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
                        ->where('r.confirm_id', 1)
                        ->whereRaw("MONTH(STR_TO_DATE(r.rec_date, '%e-%c-%Y')) = ?", [$m])
                        ->whereRaw("YEAR(STR_TO_DATE(r.rec_date, '%e-%c-%Y')) = ?",  [$y])
                        ->select('c.name as clinic_name', DB::raw('COUNT(r.id) as count'))
                        ->groupBy('r.clinic_id', 'c.name')
                        ->orderBy('count', 'desc')
                        ->limit(8)
                        ->get();

                    $allMonthsData[] = [
                        'label'        => $dt->locale('ar')->isoFormat('MMMM YYYY'),
                        'dailyLabels'  => $dLabels,
                        'dailyData'    => $dData,
                        'clinicLabels' => $cl->pluck('clinic_name')->map(fn($n) => $n ?: 'غير محدد')->values()->toArray(),
                        'clinicCounts' => $cl->pluck('count')->values()->toArray(),
                    ];
                }

                return [$monthlyRevenue, $chartDailyLabels, $chartDailyData, $chartMonthLabels, $chartMonthData, $chartMonthKeys, $clinicChartData, $branchStats, $allMonthsData];
            });

            $this->info("✅ Dashboard cache warmed up successfully!");
        } catch (\Exception $e) {
            $this->error("❌ Error warming up cache: " . $e->getMessage());
            return 1;
        }
    }
}
