<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->enum('type', ['percent', 'fixed'])->default('percent');
            $table->decimal('value', 10, 3);
            $table->date('expires_at')->nullable();
            $table->unsignedInteger('max_uses')->default(0);   // 0 = غير محدود
            $table->unsignedInteger('used_count')->default(0);
            $table->unsignedInteger('clinic_id')->default(0);  // 0 = كل المكاتب
            $table->decimal('min_amount', 10, 3)->default(0);
            $table->string('notes', 255)->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_codes');
    }
};
