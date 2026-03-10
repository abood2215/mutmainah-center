<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    #[Title('لوحة التحكم')]
    public function render()
    {
        $totalPatients = DB::table('kstu')->count();

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
        $monthlyRevenue = DB::table('kpayments')
            ->where('price', '>', 0)
            ->whereRaw("MONTH(STR_TO_DATE(pdate, '%e-%c-%Y')) = ?", [$currentMonth])
            ->whereRaw("YEAR(STR_TO_DATE(pdate, '%e-%c-%Y')) = ?",  [$currentYear])
            ->sum('price');

        // إيرادات اليوم
        $todayRevenue = DB::table('kpayments')
            ->where('price', '>', 0)
            ->where('pdate', $today)
            ->sum('price');

        // أحدث الكشوف اليوم
        $recentChecks = DB::table('rec as r')
            ->leftJoin('kstu as s', 's.id', '=', 'r.st_id')
            ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
            ->where('r.rec_date', $today)
            ->where('r.confirm_id', 1)
            ->select('r.id', 'r.rec_time', 'r.state_id', 's.full_name as patient_name', 'c.name as clinic_name')
            ->orderBy('r.id', 'desc')
            ->limit(8)
            ->get();

        // إحصائيات العيادات اليوم
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
            'totalPatients'  => $totalPatients,
            'todayChecks'    => $todayChecks,
            'todayDone'      => $todayDone,
            'todayWaiting'   => $todayWaiting,
            'monthlyRevenue' => $monthlyRevenue,
            'todayRevenue'   => $todayRevenue,
            'recentChecks'   => $recentChecks,
            'clinicStats'    => $clinicStats,
        ])->layout('layouts.app');
    }
}
