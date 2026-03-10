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
        Schema::table('medical_records', function (Blueprint $table) {
            $table->text('current_complaint')->nullable();
            $table->text('psychiatric_treatments')->nullable();
            $table->text('impression')->nullable();
            $table->text('plan')->nullable();
            $table->text('family_history')->nullable();
            $table->text('personal_history')->nullable();
            $table->text('mental_state')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('future_plan')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            //
        });
    }
};
