<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function findName($id) {
    if (!$id) return 'NULL';
    $n = DB::table('acck')->where('id', $id)->value('name');
    return $n ?: "ID($id)";
}

$rows = DB::table('rec as r')
    ->where('r.rec_date', '8-3-2026')
    ->orderBy('r.id')
    ->get();

foreach ($rows as $row) {
    echo "ID: $row->id | CID: $row->confirm_id | st_id: $row->st_id (" . findName($row->st_id) . ") | c_id: $row->c_id (" . findName($row->c_id) . ")\n";
}
