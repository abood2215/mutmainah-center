<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== employees columns ===\n";
$cols = DB::select('DESCRIBE employees');
foreach ($cols as $c) echo $c->Field . ' | ' . $c->Type . PHP_EOL;

echo "\n=== employees sample (doc_id=124) ===\n";
$e = DB::table('employees')->where('id', 124)->first();
print_r($e ? (array)$e : ['not found']);

echo "\n=== rec history for patient (st_id=16978) with joins ===\n";
$recs = DB::table('rec as r')
    ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
    ->leftJoin('employees as e', 'e.id', '=', 'r.doc_id')
    ->where('r.st_id', 16978)
    ->where('r.confirm_id', 1)
    ->select('r.id','r.rec_date','r.sym','r.dia','r.pres','r.notes','c.name as clinic','e.name as doctor')
    ->orderBy('r.id','desc')->limit(5)->get();
foreach ($recs as $r) print_r((array)$r);
