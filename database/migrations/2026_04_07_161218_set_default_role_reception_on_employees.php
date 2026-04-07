<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تعيين role = 'reception' لكل المستخدمين الذين ليس لديهم role محدد
        if (Schema::hasColumn('employees', 'role')) {
            \Illuminate\Support\Facades\DB::table('employees')
                ->whereNull('role')
                ->orWhere('role', '')
                ->update(['role' => 'reception']);
        }
    }

    public function down(): void
    {
        //
    }
};
