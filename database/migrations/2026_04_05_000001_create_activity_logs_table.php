<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('user_name', 100)->default('');
            $table->string('action', 50);          // created | updated | deleted | uploaded
            $table->string('subject', 50);          // patient | check | payment | attachment
            $table->unsignedBigInteger('subject_id')->default(0);
            $table->string('description', 500)->default('');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
