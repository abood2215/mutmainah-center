<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Checks: rec JOIN kstu for 8-3-2026 (should match original website) ===\n";
$rows = DB::table('rec as r')
    ->leftJoin('kstu as a', 'a.id', '=', 'r.st_id')
    ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
    ->leftJoin('kpayments as k', 'k.rec_id', '=', 'r.id')
    ->where('r.confirm_id', 1)
    ->where('r.rec_date', '8-3-2026')
    ->select('r.id', 'a.full_name as patient_name', 'c.name as clinic_name',
        DB::raw('COALESCE(SUM(k.price), 0) as amount'))
    ->groupBy('r.id','r.rec_date','r.rec_time','r.state_id','r.st_id','r.clinic_id','a.full_name','c.name')
    ->orderBy('r.id', 'desc')
    ->get();

foreach ($rows as $r) {
    echo "{$r->patient_name} | {$r->clinic_name} | {$r->amount}\n";
}
