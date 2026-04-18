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
        // تم تطبيق هذا الإصلاح مباشرة على قاعدة البيانات الإنتاجية
        // البيانات في الـ backup جاهزة — لا حاجة لإعادة تشغيل الـ UPDATE
    }

    public function down(): void
    {
        // لا يمكن التراجع — البيانات الأصلية كانت 0
    }
};
