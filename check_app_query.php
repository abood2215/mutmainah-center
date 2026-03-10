<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Exact query used by Checks/Index.php
$rows = DB::table('rec as r')
    ->leftJoin('acck as a', 'a.id', '=', 'r.st_id')
    ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
    ->leftJoin('kpayments as k', 'k.rec_id', '=', 'r.id')
    ->where('r.confirm_id', 1)
    ->where('r.rec_date', '8-3-2026')
    ->select(
        'r.id', 'r.rec_date', 'r.st_id',
        'a.name as patient_name',
        'c.name as clinic_name',
        DB::raw('COALESCE(SUM(k.price), 0) as amount')
    )
    ->groupBy('r.id','r.rec_date','r.rec_time','r.state_id','r.st_id','r.clinic_id','a.name','c.name')
    ->orderBy('r.id', 'desc')
    ->limit(15)
    ->get();

echo "App query results for 8-3-2026 (confirm_id=1):\n";
foreach ($rows as $r) {
    echo "rec_id={$r->id} | st_id={$r->st_id} | {$r->patient_name} | {$r->clinic_name} | {$r->amount}\n";
}

echo "\n--- Check acck table for a few IDs ---\n";
$stIds = $rows->pluck('st_id')->take(5)->toArray();
$patients = DB::table('acck')->whereIn('id', $stIds)->get(['id','name']);
foreach ($patients as $p) {
    echo "acck.id={$p->id} | name={$p->name}\n";
}

echo "\n--- Check confirm_id values in rec for 8-3-2026 ---\n";
$confirms = DB::table('rec')->where('rec_date', '8-3-2026')
    ->selectRaw('confirm_id, COUNT(*) as cnt')
    ->groupBy('confirm_id')
    ->get();
foreach ($confirms as $c) {
    echo "confirm_id={$c->confirm_id} => count={$c->cnt}\n";
}
