<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // الدور السادس (branch_id = 2 في جدول branches)
    private array $floor6 = [
        59, 62, 63, 74, 75, 87, 111, 136, 137, 142,
        157, 163, 164, 166, 170, 178, 179, 180, 184,
        186, 187, 188, 189, 195,
    ];

    // الدور الثالث (branch_id = 1 في جدول branches)
    private array $floor3 = [
        78, 91, 114, 150, 152, 165, 174, 175, 185, 192, 193,
    ];

    public function up(): void
    {
        // 1. إضافة branch_id لجدول clinic إذا لم يوجد
        if (!Schema::hasColumn('clinic', 'branch_id')) {
            Schema::table('clinic', function (Blueprint $table) {
                $table->unsignedBigInteger('branch_id')->default(0)->after('id');
            });
        }

        // 2. تحديث المكاتب
        DB::table('clinic')->whereIn('id', $this->floor3)->update(['branch_id' => 1]);
        DB::table('clinic')->whereIn('id', $this->floor6)->update(['branch_id' => 2]);

        // 3. تحديث branch_id للعملاء من آخر زيارة لعيادة محددة الفرع
        DB::statement("
            UPDATE kstu k
            INNER JOIN (
                SELECT r.st_id, c.branch_id
                FROM rec r
                INNER JOIN clinic c ON c.id = r.clinic_id
                WHERE c.branch_id > 0
                ORDER BY r.id DESC
            ) last_visit ON last_visit.st_id = k.id
            SET k.branch_id = last_visit.branch_id
            WHERE k.branch_id = 0
        ");
    }

    public function down(): void
    {
        Schema::table('clinic', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        DB::table('kstu')->update(['branch_id' => 0]);
    }
};
