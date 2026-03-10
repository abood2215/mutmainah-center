<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$data = \Illuminate\Support\Facades\DB::table('rec')->paginate(15);
echo $data->links()->toHtml();
