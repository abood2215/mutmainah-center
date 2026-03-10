<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$names = ['نورية', 'يوسف جابر', 'مشاري يوسف', 'احمد جديد', 'هيفاء كريم', 'نورة أوقيان', 'شيخة'];
foreach ($names as $name) {
    $r = DB::table('acck')->where('name', 'like', "%$name%")->get(['id', 'name']);
    foreach ($r as $row) {
        echo "MATCH [$name] -> ID: $row->id | Name: $row->name\n";
    }
}
