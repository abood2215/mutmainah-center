<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * إصلاح جلسات الحزم المدفوعة مسبقاً (payment_method=5)
 *
 * النظام القديم خزّن هذه الجلسات بـ amount=0 و price=0.
 * السعر الحقيقي موجود في جدول service يمكن إيجاده عبر:
 *   - اسم الخدمة المستخرج من pdesc (صيغة: *SERVICE* أو &nbsp;*&nbsp;SERVICE&nbsp;*&nbsp;)
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
               AND sv.name = TRIM(REPLACE(REPLACE(
                       SUBSTRING_INDEX(SUBSTRING_INDEX(k.pdesc, '*', 2), '*', -1),
                       '&nbsp;', ''
                   ), CHAR(160), ''))
            SET k.amount = sv.price
            WHERE k.payment_method = 5
              AND (k.amount = 0 OR k.amount IS NULL)
              AND (k.price  = 0 OR k.price  IS NULL)
              AND k.clinic_id > 0
              AND k.pdesc LIKE '%*%'
        ");

        // خطوة 2: fallback — بحث بدون clinic_id للجلسات التي clinic_id=0
        DB::statement("
            UPDATE kpayments k
            JOIN service sv
                ON sv.name = TRIM(REPLACE(REPLACE(
                       SUBSTRING_INDEX(SUBSTRING_INDEX(k.pdesc, '*', 2), '*', -1),
                       '&nbsp;', ''
                   ), CHAR(160), ''))
            SET k.amount = sv.price
            WHERE k.payment_method = 5
              AND (k.amount = 0 OR k.amount IS NULL)
              AND (k.price  = 0 OR k.price  IS NULL)
              AND (k.clinic_id = 0 OR k.clinic_id IS NULL)
              AND k.pdesc LIKE '%*%'
        ");
    }

    public function down(): void
    {
        // لا يمكن التراجع — البيانات الأصلية كانت 0
    }
};
