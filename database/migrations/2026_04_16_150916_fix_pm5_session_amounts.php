<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * إصلاح جلسات الحزم المدفوعة مسبقاً (payment_method=5)
 *
 * النظام القديم خزّن هذه الجلسات بـ amount=0 و price=0.
 * السعر الحقيقي موجود في جدول service يمكن إيجاده عبر:
 *   - اسم الخدمة المستخرج من pdesc (صيغة: &nbsp;*&nbsp;SERVICE&nbsp;*&nbsp;)
 *   - clinic_id الموجود في kpayments
 *
 * هذه الـ migration تحدّث kpayments.amount بالسعر الصحيح مرة واحدة.
 */
return new class extends Migration
{
    public function up(): void
    {
        // خطوة 1: تحديث amount عبر clinic_id + اسم الخدمة من pdesc
        DB::statement("
            UPDATE kpayments k
            JOIN service sv
                ON sv.clinic_id = k.clinic_id
               AND sv.name = TRIM(REPLACE(
                       SUBSTRING_INDEX(SUBSTRING_INDEX(k.pdesc, '*', 2), '*', -1),
                       '&nbsp;', ''
                   ))
            SET k.amount = sv.price
            WHERE k.payment_method = 5
              AND (k.amount = 0 OR k.amount IS NULL)
              AND (k.price  = 0 OR k.price  IS NULL)
              AND k.clinic_id > 0
              AND k.pdesc LIKE '%*%'
              AND sv.price > 0
        ");

        // خطوة 2: fallback — بحث بدون clinic_id للجلسات التي clinic_id=0
        DB::statement("
            UPDATE kpayments k
            JOIN service sv
                ON sv.name = TRIM(REPLACE(
                       SUBSTRING_INDEX(SUBSTRING_INDEX(k.pdesc, '*', 2), '*', -1),
                       '&nbsp;', ''
                   ))
            SET k.amount = sv.price
            WHERE k.payment_method = 5
              AND (k.amount = 0 OR k.amount IS NULL)
              AND (k.price  = 0 OR k.price  IS NULL)
              AND (k.clinic_id = 0 OR k.clinic_id IS NULL)
              AND k.pdesc LIKE '%*%'
              AND sv.price > 0
        ");

        // خطوة 3: حالات خاصة — أسماء لا تطابق جدول service بالضبط
        // "جلسة استشارة د .خلف 2" — clinic 64 — السعر 40 KD
        DB::statement("
            UPDATE kpayments k
            SET k.amount = 40
            WHERE k.payment_method = 5
              AND (k.amount = 0 OR k.amount IS NULL)
              AND (k.price  = 0 OR k.price  IS NULL)
              AND k.clinic_id = 64
              AND k.pdesc LIKE '%جلسة استشارة د .خلف 2%'
        ");

        // "جلسة استشارة 20 ر.س" — clinic 123 — السعر 20
        DB::statement("
            UPDATE kpayments k
            SET k.amount = 20
            WHERE k.payment_method = 5
              AND (k.amount = 0 OR k.amount IS NULL)
              AND (k.price  = 0 OR k.price  IS NULL)
              AND k.clinic_id = 123
              AND k.pdesc LIKE '%جلسة استشارة 20 ر.س%'
        ");

        // خطوة 5: مزامنة — amount=0 لكن price>0 (النظام الجديد خزّن السعر في price فقط)
        DB::statement("
            UPDATE kpayments
            SET amount = price
            WHERE payment_method = 5
              AND (amount = 0 OR amount IS NULL)
              AND price > 0
        ");
    }

    public function down(): void
    {
        // لا يمكن التراجع — البيانات الأصلية كانت 0
    }
};
