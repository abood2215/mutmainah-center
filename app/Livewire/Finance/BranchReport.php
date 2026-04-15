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
        $from = \Carbon\Carbon::parse($this->fromDate);
        $to   = \Carbon\Carbon::parse($this->toDate);

        // تحديد بحد أقصى 90 يوماً لتجنب IN() ضخم يبطئ قاعدة البيانات
        if ($from->diffInDays($to) > 90) {
            $to = $from->copy()->addDays(90);
        }

        $dates   = [];
        $current = $from->copy();
        while ($current->lte($to)) {
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
        $branchIds = $branches->pluck('id')->toArray();

        if (empty($branchIds) || empty($dateRange)) {
            $stats = collect();
            return view('livewire.finance.branch-report', [
                'stats'             => $stats,
                'totalRevenue'      => 0,
                'totalChecks'       => 0,
                'totalAppointments' => 0,
                'dateRange'         => $dateRange,
            ])->layout('layouts.app');
        }

        // ══ 1. عدد العملاء لكل فرع (استعلام واحد) ══
        $patientCounts = DB::table('kstu')
            ->whereIn('branch_id', $branchIds)
            ->select('branch_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('branch_id')
            ->get()->pluck('cnt', 'branch_id');

        // ══ 2. عدد الكشوفات لكل فرع في الفترة (استعلام واحد) ══
        $checkCounts = DB::table('rec as r')
            ->join('kstu as k', 'k.id', '=', 'r.st_id')
            ->whereIn('k.branch_id', $branchIds)
            ->where('r.confirm_id', 1)
            ->whereIn('r.rec_date', $dateRange)
            ->select('k.branch_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('k.branch_id')
            ->get()->pluck('cnt', 'branch_id');

        // ══ 3. الإيرادات لكل فرع في الفترة (استعلام واحد) ══
        $revenues = DB::table('kpayments as p')
            ->join('rec as r', 'r.id', '=', 'p.rec_id')
            ->join('kstu as k', 'k.id', '=', 'r.st_id')
            ->whereIn('k.branch_id', $branchIds)
            ->where('p.price', '>', 0)
            ->whereIn('p.pdate', $dateRange)
            ->select('k.branch_id', DB::raw('SUM(p.price) as total'))
            ->groupBy('k.branch_id')
            ->get()->pluck('total', 'branch_id');

        // ══ 4. عدد المواعيد لكل فرع في الفترة (استعلام واحد) ══
        $appointmentCounts = DB::table('rec as r')
            ->join('kstu as k', 'k.id', '=', 'r.st_id')
            ->whereIn('k.branch_id', $branchIds)
            ->where('r.confirm_id', 0)
            ->whereIn('r.rec_date', $dateRange)
            ->select('k.branch_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('k.branch_id')
            ->get()->pluck('cnt', 'branch_id');

        // ══ 5. إيرادات العيادات لكل فرع (استعلام واحد، نأخذ أعلى 5 لاحقاً في PHP) ══
        $clinicRevenues = DB::table('kpayments as p')
            ->join('rec as r', 'r.id', '=', 'p.rec_id')
            ->join('kstu as k', 'k.id', '=', 'r.st_id')
            ->join('clinic as c', 'c.id', '=', 'r.clinic_id')
            ->whereIn('k.branch_id', $branchIds)
            ->where('p.price', '>', 0)
            ->whereIn('p.pdate', $dateRange)
            ->select(
                'k.branch_id', 'r.clinic_id', 'c.name',
                DB::raw('SUM(p.price) as total'),
                DB::raw('COUNT(DISTINCT r.id) as cnt')
            )
            ->groupBy('k.branch_id', 'r.clinic_id', 'c.name')
            ->orderByDesc('total')
            ->get()->groupBy('branch_id');

        // ══ 6. الإيرادات اليومية لكل فرع (استعلام واحد) ══
        $dailyRevenues = DB::table('kpayments as p')
            ->join('rec as r', 'r.id', '=', 'p.rec_id')
            ->join('kstu as k', 'k.id', '=', 'r.st_id')
            ->whereIn('k.branch_id', $branchIds)
            ->where('p.price', '>', 0)
            ->whereIn('p.pdate', $dateRange)
            ->select('k.branch_id', 'p.pdate', DB::raw('SUM(p.price) as total'))
            ->groupBy('k.branch_id', 'p.pdate')
            ->get()->groupBy('branch_id');

        // ══ تجميع النتائج لكل فرع (بدون أي استعلام إضافي) ══
        $stats = $branches->map(function ($branch) use (
            $patientCounts, $checkCounts, $revenues, $appointmentCounts,
            $clinicRevenues, $dailyRevenues, $dateRange
        ) {
            $dailyMap  = ($dailyRevenues[$branch->id] ?? collect())->pluck('total', 'pdate');
            $chartData = array_map(fn($d) => round((float)($dailyMap[$d] ?? 0), 3), $dateRange);

            return (object)[
                'id'           => $branch->id,
                'name'         => $branch->name,
                'patients'     => $patientCounts[$branch->id] ?? 0,
                'checks'       => $checkCounts[$branch->id] ?? 0,
                'revenue'      => $revenues[$branch->id] ?? 0,
                'appointments' => $appointmentCounts[$branch->id] ?? 0,
                'topClinics'   => ($clinicRevenues[$branch->id] ?? collect())->take(5),
                'chartData'    => $chartData,
            ];
        });

        $totalRevenue      = $stats->sum('revenue');
        $totalChecks       = $stats->sum('checks');
        $totalAppointments = $stats->sum('appointments');

        return view('livewire.finance.branch-report', compact(
            'stats', 'totalRevenue', 'totalChecks', 'totalAppointments', 'dateRange'
        ))->layout('layouts.app');
    }
}
