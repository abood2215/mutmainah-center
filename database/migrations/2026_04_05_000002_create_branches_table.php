<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('license_no', 50)->default('');
            $table->boolean('is_active')->default(true);
        });

        // إدراج الفرعين
        DB::table('branches')->insert([
            ['name' => 'مركز مطمئنة للاستشارات اللغوية — الدور الثالث',              'license_no' => '2202/1753', 'is_active' => 1],
            ['name' => 'مركز مطمئنة للاستشارات التربوية والتدريب — الدور السادس',    'license_no' => '2017/7946', 'is_active' => 1],
        ]);

        // إضافة branch_id لجدول kstu
        Schema::table('kstu', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->default(0)->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('kstu', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::dropIfExists('branches');
    }
};
