<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // تعطيل المكاتب غير المصنّفة لأي فرع
        DB::table('clinic')
            ->where('branch_id', 0)
            ->update(['state_id' => 2]);
    }

    public function down(): void
    {
        // إعادة تفعيلها
        DB::table('clinic')
            ->where('branch_id', 0)
            ->update(['state_id' => 1]);
    }
};
