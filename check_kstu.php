<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== kstu columns ===\n";
$cols = DB::select('DESCRIBE kstu');
foreach ($cols as $c) echo $c->Field . ' | ' . $c->Type . PHP_EOL;

echo "\n=== kstu sample rows ===\n";
$rows = DB::table('kstu')->limit(3)->get();
foreach ($rows as $r) print_r((array)$r);

echo "\n=== rec JOIN kstu for 8-3-2026 ===\n";
$recs = DB::select("
    SELECT r.id, k.full_name, k.id as kstu_id, r.rec_date
    FROM rec r
    LEFT JOIN kstu k ON k.id = r.st_id
    WHERE r.rec_date = '8-3-2026' AND r.confirm_id = 1
    ORDER BY r.id DESC LIMIT 5
");
foreach ($recs as $r) {
    echo "rec.id={$r->id} | kstu.id={$r->kstu_id} | {$r->full_name}\n";
}
