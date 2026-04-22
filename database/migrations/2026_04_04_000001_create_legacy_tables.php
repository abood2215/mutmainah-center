<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ══════════════════════════════════════
        // employees — جدول الموظفين والمستخدمين
        // ══════════════════════════════════════
        if (!Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->string('first_name', 100)->default('');
                $table->string('middle_initial', 100)->default('');
                $table->string('last_name', 100)->default('');
                $table->string('emp_no', 50)->default('');
                $table->string('phone', 50)->default('');
                $table->string('email', 100)->default('');
                $table->string('user_name', 100)->default('');
                $table->string('arway', 100)->default('');   // MD5 password
                $table->tinyInteger('state')->default(1);    // 1=active
                $table->timestamps();
            });

            // مستخدم افتراضي admin/admin
            DB::table('employees')->insert([
                'first_name'     => 'مدير',
                'middle_initial' => 'النظام',
                'last_name'      => '',
                'emp_no'         => '1',
                'phone'          => '',
                'email'          => '',
                'user_name'      => 'admin',
                'arway'          => md5('admin123'),
                'state'          => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        // ══════════════════════════════════════
        // kcom — شركات التأمين
        // ══════════════════════════════════════
        if (!Schema::hasTable('kcom')) {
            Schema::create('kcom', function (Blueprint $table) {
                $table->id();
                $table->string('name', 200)->default('');
                $table->tinyInteger('state')->default(1);
            });

            DB::table('kcom')->insert([
                ['id' => 28, 'name' => 'على نفقته', 'state' => 1],
            ]);
        }

        // ══════════════════════════════════════
        // kstu — العملاء / المرضى
        // ══════════════════════════════════════
        if (!Schema::hasTable('kstu')) {
            Schema::create('kstu', function (Blueprint $table) {
                $table->id();
                $table->string('full_name', 200)->default('');
                $table->string('ssn', 50)->default('');
                $table->string('phone', 50)->default('');
                $table->string('email', 100)->default('');
                $table->tinyInteger('gender')->default(0);
                $table->tinyInteger('nationality')->default(0);
                $table->tinyInteger('social')->default(0);
                $table->string('date_of_birth', 20)->default('');
                $table->string('address1', 255)->default('');
                $table->integer('com_id')->default(28);
                $table->string('assur_no', 100)->default('');
                $table->integer('class_id')->default(0);
                $table->text('notes')->nullable();
                $table->string('reg_date', 20)->default('');
                $table->string('assur_date', 20)->default('');
                $table->tinyInteger('state')->default(0);
                $table->integer('com_id1')->default(0);
                $table->integer('file_id')->default(0);
                $table->string('bg_id', 10)->default('');
                $table->decimal('weight', 8, 2)->default(0);
            });
        }

        // ══════════════════════════════════════
        // clinic — المكاتب
        // ══════════════════════════════════════
        if (!Schema::hasTable('clinic')) {
            Schema::create('clinic', function (Blueprint $table) {
                $table->id();
                $table->string('name', 200)->default('');
                $table->tinyInteger('state_id')->default(1);
                $table->integer('clinic_time')->default(0);
                $table->integer('doc_id1')->default(0);
                $table->integer('doc_id2')->default(0);
                $table->integer('doc_id3')->default(0);
                $table->integer('doc_id4')->default(0);
                $table->integer('doc_id5')->default(0);
                $table->tinyInteger('appoint_day')->default(0);
                $table->integer('type_id')->default(0);
            });
        }

        // ══════════════════════════════════════
        // stop_clinic — عيادات موقوفة
        // ══════════════════════════════════════
        if (!Schema::hasTable('stop_clinic')) {
            Schema::create('stop_clinic', function (Blueprint $table) {
                $table->id();
                $table->integer('clinic_id')->default(0);
                $table->integer('user_id')->default(0);
                $table->tinyInteger('state_id')->default(0);
            });
        }

        // ══════════════════════════════════════
        // rec — الكشوفات / المواعيد
        // ══════════════════════════════════════
        if (!Schema::hasTable('rec')) {
            Schema::create('rec', function (Blueprint $table) {
                $table->id();
                $table->string('rec_date', 20)->default('');
                $table->string('rec_time', 10)->default('');
                $table->string('pdate', 20)->default('');
                $table->integer('st_id')->default(0);
                $table->integer('clinic_id')->default(0);
                $table->tinyInteger('confirm_id')->default(0);
                $table->tinyInteger('state_id')->default(0);
                $table->integer('service_id')->default(0);
                $table->integer('new_service_id')->default(0);
                $table->integer('c_id')->default(0);
                $table->integer('doc_id')->default(0);
                $table->integer('pstate_id')->default(0);
                $table->integer('type_id')->default(0);
                $table->integer('per_id')->default(0);
                $table->integer('order_id')->default(0);
            });
        }

        // ══════════════════════════════════════
        // acck — الحسابات المالية
        // ══════════════════════════════════════
        if (!Schema::hasTable('acck')) {
            Schema::create('acck', function (Blueprint $table) {
                $table->id();
                $table->string('name', 200)->default('');
                $table->text('pdesc')->nullable();
                $table->integer('parent_id')->default(0);
                $table->integer('first_id')->default(0);
                $table->integer('credit_debit')->default(0);
                $table->integer('level_id')->default(0);
                $table->integer('cat_id')->default(0);
                $table->integer('stu_id')->default(0);
                $table->integer('branch_id')->default(0);
                $table->integer('comp_id')->default(0);
                $table->integer('second_id')->default(0);
                $table->integer('coms_id')->default(0);
                $table->integer('client_id')->default(0);
                $table->integer('importer_id')->default(0);
                $table->integer('close_id')->default(0);
            });
        }

        // ══════════════════════════════════════
        // kpayments — المدفوعات والخدمات
        // ══════════════════════════════════════
        if (!Schema::hasTable('kpayments')) {
            Schema::create('kpayments', function (Blueprint $table) {
                $table->id();
                $table->integer('rec_id')->default(0);
                $table->string('pdate', 20)->default('');
                $table->decimal('price', 10, 3)->default(0);
                $table->decimal('amount', 10, 3)->default(0);
                $table->decimal('net', 10, 3)->default(0);
                $table->decimal('credit', 10, 3)->default(0);
                $table->decimal('discount', 10, 3)->default(0);
                $table->integer('payment_method')->default(0);
                $table->integer('clinic_id')->default(0);
                $table->text('pdesc')->nullable();
                $table->integer('acc_id')->default(0);
                $table->integer('serial_no')->default(0);
                $table->integer('user_id')->default(0);
                $table->integer('type_id')->default(0);
                $table->integer('client_id')->default(0);
                $table->tinyInteger('status')->default(0);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kpayments');
        Schema::dropIfExists('acck');
        Schema::dropIfExists('rec');
        Schema::dropIfExists('stop_clinic');
        Schema::dropIfExists('clinic');
        Schema::dropIfExists('kstu');
        Schema::dropIfExists('kcom');
        Schema::dropIfExists('employees');
    }
};
