<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'clinic_ids')) {
                $table->text('clinic_ids')->nullable()->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('clinic_ids');
        });
    }
};
