<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ALL records for 8-3-2026 with confirm_id=1 ===\n";
$rows = DB::select("
    SELECT r.id, r.st_id, a.name, c.name as clinic, COALESCE(SUM(k.price),0) as amount
    FROM rec r
    LEFT JOIN acck a ON a.id=r.st_id
    LEFT JOIN clinic c ON c.id=r.clinic_id
    LEFT JOIN kpayments k ON k.rec_id=r.id
    WHERE r.rec_date='8-3-2026' AND r.confirm_id=1
    GROUP BY r.id, r.st_id, a.name, c.name
    ORDER BY r.id ASC
");
foreach ($rows as $r) {
    echo "rec.id={$r->id} | acck.id={$r->st_id} | {$r->name} | {$r->clinic} | {$r->amount}\n";
}
echo "Total: " . count($rows) . " records\n\n";

echo "=== confirm_id=0 records for 8-3-2026 ===\n";
$rows0 = DB::select("
    SELECT r.id, r.st_id, a.name, c.name as clinic
    FROM rec r
    LEFT JOIN acck a ON a.id=r.st_id
    LEFT JOIN clinic c ON c.id=r.clinic_id
    WHERE r.rec_date='8-3-2026' AND r.confirm_id=0
    ORDER BY r.id ASC
");
foreach ($rows0 as $r) {
    echo "rec.id={$r->id} | {$r->name} | {$r->clinic}\n";
}
