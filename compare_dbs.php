<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$dbs = ['mutmainah_new', 'mutmainah_new2'];
foreach ($dbs as $db) {
    echo "=== $db ===" . PHP_EOL;
    $rows = DB::select("SELECT r.id, a.name, c.name as clinic, COALESCE(SUM(k.price),0) as amount
        FROM $db.rec r
        LEFT JOIN $db.acck a ON a.id=r.st_id
        LEFT JOIN $db.clinic c ON c.id=r.clinic_id
        LEFT JOIN $db.kpayments k ON k.rec_id=r.id
        WHERE r.rec_date='8-3-2026' AND r.confirm_id=1
        GROUP BY r.id, a.name, c.name
        ORDER BY r.id DESC LIMIT 10");
    foreach ($rows as $r) {
        echo "id={$r->id} | {$r->name} | {$r->clinic} | {$r->amount}" . PHP_EOL;
    }
    echo PHP_EOL;
}
