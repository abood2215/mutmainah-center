<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ══ إضافة الأعمدة الناقصة إلى kpayments ══
        if (Schema::hasTable('kpayments')) {
            Schema::table('kpayments', function (Blueprint $table) {
                if (!Schema::hasColumn('kpayments', 'vno'))            $table->integer('vno')->default(0);
                if (!Schema::hasColumn('kpayments', 'tax_value'))      $table->decimal('tax_value', 10, 3)->default(0);
                if (!Schema::hasColumn('kpayments', 'res_amount'))     $table->decimal('res_amount', 10, 3)->default(0);
                if (!Schema::hasColumn('kpayments', 'cash_discount'))  $table->decimal('cash_discount', 10, 3)->default(0);
                if (!Schema::hasColumn('kpayments', 'ratio_discount')) $table->decimal('ratio_discount', 10, 3)->default(0);
                if (!Schema::hasColumn('kpayments', 'p_amount'))       $table->decimal('p_amount', 10, 3)->default(0);
                if (!Schema::hasColumn('kpayments', 'c_amount'))       $table->decimal('c_amount', 10, 3)->default(0);
                if (!Schema::hasColumn('kpayments', 'insur_amount'))   $table->decimal('insur_amount', 10, 3)->default(0);
                if (!Schema::hasColumn('kpayments', 'p_id'))           $table->integer('p_id')->default(0);
                if (!Schema::hasColumn('kpayments', 'c_id'))           $table->integer('c_id')->default(0);
                if (!Schema::hasColumn('kpayments', 'bank'))           $table->integer('bank')->default(0);
                if (!Schema::hasColumn('kpayments', 'branch'))         $table->integer('branch')->default(0);
                if (!Schema::hasColumn('kpayments', 'check_no'))       $table->integer('check_no')->default(0);
                if (!Schema::hasColumn('kpayments', 'pharm_id'))       $table->integer('pharm_id')->default(0);
                if (!Schema::hasColumn('kpayments', 'pharmacy_id'))    $table->integer('pharmacy_id')->default(0);
                if (!Schema::hasColumn('kpayments', 'com_id'))         $table->integer('com_id')->default(0);
                if (!Schema::hasColumn('kpayments', 'dis_id'))         $table->integer('dis_id')->default(0);
                if (!Schema::hasColumn('kpayments', 'serv_no'))        $table->integer('serv_no')->default(0);
                if (!Schema::hasColumn('kpayments', 'v_id'))           $table->integer('v_id')->default(0);
                if (!Schema::hasColumn('kpayments', 'date_serial'))    $table->integer('date_serial')->default(0);
                if (!Schema::hasColumn('kpayments', 'interface_id'))   $table->integer('interface_id')->default(0);
                if (!Schema::hasColumn('kpayments', 'ns'))             $table->integer('ns')->default(0);
                if (!Schema::hasColumn('kpayments', 'clinic_type_id')) $table->integer('clinic_type_id')->default(0);
                if (!Schema::hasColumn('kpayments', 'ptime'))          $table->string('ptime', 10)->default('');
            });
        }

        // ══ إضافة الأعمدة الناقصة إلى acck ══
        if (Schema::hasTable('acck')) {
            Schema::table('acck', function (Blueprint $table) {
                if (!Schema::hasColumn('acck', 'z_id'))         $table->integer('z_id')->default(0);
                if (!Schema::hasColumn('acck', 'pharm_id'))     $table->integer('pharm_id')->default(0);
                if (!Schema::hasColumn('acck', 'clinic_id'))    $table->integer('clinic_id')->default(0);
                if (!Schema::hasColumn('acck', 'third_id'))     $table->integer('third_id')->default(0);
                if (!Schema::hasColumn('acck', 'fourth_id'))    $table->integer('fourth_id')->default(0);
                if (!Schema::hasColumn('acck', 'five_id'))      $table->integer('five_id')->default(0);
                if (!Schema::hasColumn('acck', 'six_id'))       $table->integer('six_id')->default(0);
                if (!Schema::hasColumn('acck', 'seven_id'))     $table->integer('seven_id')->default(0);
                if (!Schema::hasColumn('acck', 'eight_id'))     $table->integer('eight_id')->default(0);
                if (!Schema::hasColumn('acck', 'account_type')) $table->integer('account_type')->default(0);
            });
        }

        // ══ جدول class (فئات) ══
        if (!Schema::hasTable('class')) {
            Schema::create('class', function (Blueprint $table) {
                $table->id();
                $table->string('name', 200)->default('');
                $table->tinyInteger('state')->default(1);
            });
        }

        // ══ جدول vouchers (سندات القبض والصرف) ══
        if (!Schema::hasTable('vouchers')) {
            Schema::create('vouchers', function (Blueprint $table) {
                $table->id();
                $table->integer('vno')->default(0);
                $table->string('pdate', 20)->default('');
                $table->decimal('credit', 10, 3)->default(0);
                $table->decimal('debit', 10, 3)->default(0);
                $table->text('pdesc')->nullable();
                $table->text('notes')->nullable();
                $table->integer('ptype')->default(0);
                $table->integer('stu_id')->default(0);
                $table->integer('acc_id')->default(0);
                $table->integer('account_id')->default(0);
                $table->integer('rec_id')->default(0);
                $table->integer('user_id')->default(0);
                $table->integer('payment_method')->default(0);
                $table->integer('serial_no')->default(0);
                $table->string('ptime', 10)->default('');
            });
        }

        // ══ جدول service (الخدمات) ══
        if (!Schema::hasTable('service')) {
            Schema::create('service', function (Blueprint $table) {
                $table->id();
                $table->string('name', 200)->default('');
                $table->string('ccode', 50)->default('');
                $table->decimal('price', 10, 3)->default(0);
                $table->decimal('cost', 10, 3)->default(0);
                $table->integer('clinic_id')->default(0);
                $table->tinyInteger('state_id')->default(0);
            });
        }

        // ══ جدول per (الفترات) ══
        if (!Schema::hasTable('per')) {
            Schema::create('per', function (Blueprint $table) {
                $table->id();
                $table->string('name', 200)->default('');
                $table->tinyInteger('state')->default(1);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('per');
        Schema::dropIfExists('service');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('class');
    }
};
