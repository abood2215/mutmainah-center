<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Force UTF-8 for mutmainah_new
DB::statement("SET NAMES utf8");

echo "=== mutmainah_new records for 8-3-2026 (confirm_id=1) ===\n";
$rows = DB::select("
    SELECT r.id, a.name as patient, c.name as clinic, COALESCE(SUM(k.price),0) as amount
    FROM mutmainah_new.rec r
    LEFT JOIN mutmainah_new.acck a ON a.id=r.st_id
    LEFT JOIN mutmainah_new.clinic c ON c.id=r.clinic_id
    LEFT JOIN mutmainah_new.kpayments k ON k.rec_id=r.id
    WHERE r.rec_date='8-3-2026' AND r.confirm_id=1
    GROUP BY r.id, a.name, c.name
    ORDER BY r.id DESC LIMIT 15
");
foreach ($rows as $r) {
    echo "id={$r->id} | {$r->patient} | {$r->clinic} | {$r->amount}\n";
}
echo "Total: " . count($rows) . "\n\n";

echo "=== Compare acck table - first 5 patients ===\n";
$n1 = DB::select("SELECT id, name FROM mutmainah_new.acck ORDER BY id DESC LIMIT 5");
$n2 = DB::select("SELECT id, name FROM mutmainah_new2.acck ORDER BY id DESC LIMIT 5");
echo "mutmainah_new:\n";
foreach ($n1 as $r) echo "  id={$r->id} | {$r->name}\n";
echo "mutmainah_new2:\n";
foreach ($n2 as $r) echo "  id={$r->id} | {$r->name}\n";
