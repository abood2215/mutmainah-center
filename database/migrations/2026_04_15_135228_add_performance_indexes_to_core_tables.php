<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * إضافة indexes للجداول الأساسية لتسريع الاستعلامات المتكررة.
     * كل index مسبوق بفحص إذا كان موجوداً لتجنب الخطأ عند إعادة التشغيل.
     */
    public function up(): void
    {
        // ══ جدول rec (الكشوفات) ══
        $this->addIndexIfMissing('rec', 'rec_st_id_idx',        ['st_id']);
        $this->addIndexIfMissing('rec', 'rec_clinic_id_idx',    ['clinic_id']);
        $this->addIndexIfMissing('rec', 'rec_confirm_date_idx', ['confirm_id', 'rec_date']);
        $this->addIndexIfMissing('rec', 'rec_date_idx',         ['rec_date']);

        // ══ جدول kpayments (المدفوعات) ══
        $this->addIndexIfMissing('kpayments', 'kp_rec_id_idx', ['rec_id']);
        $this->addIndexIfMissing('kpayments', 'kp_acc_id_idx', ['acc_id']);
        $this->addIndexIfMissing('kpayments', 'kp_pdate_idx',  ['pdate']);
        $this->addIndexIfMissing('kpayments', 'kp_status_idx', ['status']);
        $this->addIndexIfMissing('kpayments', 'kp_price_idx',  ['price']);

        // ══ جدول kstu (المرضى) ══
        $this->addIndexIfMissing('kstu', 'kstu_branch_id_idx', ['branch_id']);
        $this->addIndexIfMissing('kstu', 'kstu_file_id_idx',   ['file_id']);
        $this->addIndexIfMissing('kstu', 'kstu_phone_idx',     ['phone']);
        $this->addIndexIfMissing('kstu', 'kstu_ssn_idx',       ['ssn']);

        // ══ جدول acck (الحسابات) ══
        $this->addIndexIfMissing('acck', 'acck_stu_id_idx', ['stu_id']);

        // ══ جدول activity_logs ══
        $this->addIndexIfMissing('activity_logs', 'al_subject_idx',    ['subject', 'subject_id']);
        $this->addIndexIfMissing('activity_logs', 'al_created_at_idx', ['created_at']);
    }

    public function down(): void
    {
        $indexes = [
            'rec'           => ['rec_st_id_idx', 'rec_clinic_id_idx', 'rec_confirm_date_idx', 'rec_date_idx'],
            'kpayments'     => ['kp_rec_id_idx', 'kp_acc_id_idx', 'kp_pdate_idx', 'kp_status_idx', 'kp_price_idx'],
            'kstu'          => ['kstu_branch_id_idx', 'kstu_file_id_idx', 'kstu_phone_idx', 'kstu_ssn_idx'],
            'acck'          => ['acck_stu_id_idx'],
            'activity_logs' => ['al_subject_idx', 'al_created_at_idx'],
        ];

        foreach ($indexes as $table => $names) {
            foreach ($names as $name) {
                $this->dropIndexIfExists($table, $name);
            }
        }
    }

    private function addIndexIfMissing(string $table, string $indexName, array $columns): void
    {
        try {
            if (!Schema::hasTable($table)) return;
            $exists = collect(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]))->isNotEmpty();
            if ($exists) return;
            Schema::table($table, function (Blueprint $t) use ($columns, $indexName) {
                $t->index($columns, $indexName);
            });
        } catch (\Throwable) {}
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        try {
            if (!Schema::hasTable($table)) return;
            $exists = collect(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]))->isNotEmpty();
            if (!$exists) return;
            Schema::table($table, function (Blueprint $t) use ($indexName) {
                $t->dropIndex($indexName);
            });
        } catch (\Throwable) {}
    }
};
