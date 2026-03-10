<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$rows = DB::table('rec as r')
    ->leftJoin('acck as a', 'a.id', '=', 'r.st_id')
    ->where('r.rec_date', '8-3-2026')
    ->select('r.id', 'r.confirm_id', 'a.name', 'r.rec_time')
    ->orderBy('r.id')
    ->get();

foreach ($rows as $row) {
    echo "ID: $row->id | CID: $row->confirm_id | Time: $row->rec_time | Name: $row->name\n";
}
