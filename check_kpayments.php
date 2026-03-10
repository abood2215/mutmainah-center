<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== kpayments columns ===\n";
$cols = DB::select('DESCRIBE kpayments');
foreach ($cols as $c) echo $c->Field . ' | ' . $c->Type . PHP_EOL;

echo "\n=== sample kpayments row ===\n";
$row = DB::table('kpayments')->orderBy('id','desc')->first();
print_r((array)$row);

echo "\n=== Does acc_id link to kstu.id? ===\n";
$k = DB::table('kpayments')->whereNotNull('acc_id')->orderBy('id','desc')->first();
if ($k) {
    $fromKstu = DB::table('kstu')->where('id', $k->acc_id)->first(['id','full_name']);
    $fromAcck = DB::table('acck')->where('id', $k->acc_id)->first(['id','name']);
    echo "kpayments.acc_id = {$k->acc_id}\n";
    echo "kstu.id={$fromKstu?->id} name={$fromKstu?->full_name}\n";
    echo "acck.id={$fromAcck?->id} name={$fromAcck?->name}\n";
    // Also check via rec_id
    $rec = DB::table('rec')->where('id', $k->rec_id)->first(['id','st_id']);
    $fromKstuViaRec = $rec ? DB::table('kstu')->where('id', $rec->st_id)->first(['id','full_name']) : null;
    echo "via rec.st_id={$rec?->st_id} → kstu: {$fromKstuViaRec?->full_name}\n";
}
