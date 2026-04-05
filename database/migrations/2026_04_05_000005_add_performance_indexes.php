<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // index على confirm_id في rec — يُسرّع فلتر الكشوف المكتملة
        Schema::table('rec', function (Blueprint $table) {
            if (!$this->indexExists('rec', 'idx_rec_confirm_date')) {
                $table->index(['confirm_id', 'rec_date'], 'idx_rec_confirm_date');
            }
        });

        // index على branch_id في kstu — يُسرّع فلتر الفرع
        Schema::table('kstu', function (Blueprint $table) {
            if (!$this->indexExists('kstu', 'idx_kstu_branch')) {
                $table->index('branch_id', 'idx_kstu_branch');
            }
        });

        // index على pdate في kpayments — يُسرّع فلتر إيرادات اليوم
        Schema::table('kpayments', function (Blueprint $table) {
            if (!$this->indexExists('kpayments', 'idx_kpayments_pdate_price')) {
                $table->index(['pdate', 'price'], 'idx_kpayments_pdate_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rec',       fn($t) => $t->dropIndex('idx_rec_confirm_date'));
        Schema::table('kstu',      fn($t) => $t->dropIndex('idx_kstu_branch'));
        Schema::table('kpayments', fn($t) => $t->dropIndex('idx_kpayments_pdate_price'));
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = \Illuminate\Support\Facades\DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }
};
