<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== kcom rows ===\n";
$rows = DB::table('kcom')->limit(20)->get(['id','name']);
foreach ($rows as $r) echo $r->id . ' | ' . $r->name . PHP_EOL;

echo "\nmax file_id: " . DB::table('kstu')->max('file_id') . PHP_EOL;
echo "\nsample kstu row:\n";
print_r((array)DB::table('kstu')->first());
