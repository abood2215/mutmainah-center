<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$rows = DB::table('rec as r')
    ->leftJoin('acck as a', 'a.id', '=', 'r.st_id')
    ->where('r.rec_date', '8-3-2026')
    ->where('r.confirm_id', 1)
    ->select('r.id', 'a.name', 'r.st_id')
    ->get();

foreach ($rows as $row) {
    echo "ID: $row->id | Name: $row->name\n";
    $pays = DB::table('kpayments')->where('rec_id', $row->id)->get(['amount', 'price', 'vno']);
    foreach ($pays as $p) {
        echo "  - Amt: $p->amount | Prc: $p->price | Vno: $p->vno\n";
    }
}
