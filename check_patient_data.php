<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// kcom = insurance companies
echo "=== kcom sample ===\n";
$rows = DB::table('kcom')->limit(5)->get();
foreach ($rows as $r) print_r((array)$r);

echo "\n=== kstu for مشاري يوسف (rec st_id=16978) ===\n";
$p = DB::table('kstu')->where('id', 16978)->first();
if ($p) {
    print_r((array)$p);
    echo "\nkcom for com_id={$p->com_id}:\n";
    $com = DB::table('kcom')->where('id', $p->com_id)->first();
    print_r($com ? (array)$com : ['not found']);
}
