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
        Schema::table('kpayments', function (Blueprint $table) {
            if (!Schema::hasIndex('kpayments', 'idx_pdate')) {
                $table->index('pdate', 'idx_pdate');
            }
        });

        Schema::table('rec', function (Blueprint $table) {
            if (!Schema::hasIndex('rec', 'idx_rec_date_confirm')) {
                $table->index(['rec_date', 'confirm_id'], 'idx_rec_date_confirm');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kpayments', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_pdate');
        });

        Schema::table('rec', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_rec_date_confirm');
        });
    }
};
