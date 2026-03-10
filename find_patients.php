<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Search for the patients shown in screenshot 1 (new system)
$names = ['نورية', 'نورة أوفيان', 'هيفاء', 'مشاري يوسف', 'مزيده', 'ماريا محمد المطيري', 'يوسف جابر'];

echo "=== Searching for patients from screenshot 1 in acck table ===\n";
foreach ($names as $name) {
    $found = DB::table('acck')->where('name', 'like', "%$name%")->get(['id','name']);
    if ($found->isEmpty()) {
        echo "NOT FOUND: $name\n";
    } else {
        foreach ($found as $p) {
            echo "FOUND: id={$p->id} | {$p->name}\n";
            // Check if they have any rec records
            $recCount = DB::table('rec')->where('st_id', $p->id)->count();
            $recent = DB::table('rec')->where('st_id', $p->id)->orderBy('id', 'desc')->first(['id','rec_date','confirm_id']);
            echo "  => rec count=$recCount, latest: id={$recent?->id} date={$recent?->rec_date} confirm={$recent?->confirm_id}\n";
        }
    }
}

echo "\n=== Current app default view (no date filter, first 15 records) ===\n";
$rows = DB::table('rec as r')
    ->leftJoin('acck as a', 'a.id', '=', 'r.st_id')
    ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
    ->leftJoin('kpayments as k', 'k.rec_id', '=', 'r.id')
    ->where('r.confirm_id', 1)
    ->select('r.id','r.rec_date','a.name as patient_name','c.name as clinic_name',
        DB::raw('COALESCE(SUM(k.price), 0) as amount'))
    ->groupBy('r.id','r.rec_date','r.rec_time','r.state_id','r.st_id','r.clinic_id','a.name','c.name')
    ->orderBy('r.id', 'desc')
    ->limit(15)
    ->get();

foreach ($rows as $r) {
    echo "id={$r->id} | {$r->rec_date} | {$r->patient_name} | {$r->amount}\n";
}
