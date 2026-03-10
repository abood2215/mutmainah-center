<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$row = DB::table('rec')->where('id', 106370)->first();
foreach ($row as $k => $v) {
    echo "$k: $v\n";
}
echo "--- PAYMENTS ---\n";
$pays = DB::table('kpayments')->where('rec_id', 106370)->get();
foreach ($pays as $p) {
    foreach ($p as $k => $v) {
        echo "$k: $v | ";
    }
    echo "\n";
}
