<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportOpeningBalances extends Command
{
    protected $signature   = 'balances:import {--dry-run : عرض فقط بدون حفظ} {--file-id= : تجربة على ملف واحد فقط} {--path= : مسار الملف إذا كان في مكان آخر}';
    protected $description = 'استيراد الأرصدة الافتتاحية من ملف ارصده العملاء2.xls';

    private const PDATE = '17-4-2026';
    private const DESC  = 'رصيد افتتاحي 17-4-2026';
    private const FILE  = 'ارصده العملاء2.xls';
    private const JSON  = 'balances_data.json';

    public function handle(): int
    {
        $dryRun   = $this->option('dry-run');
        $singleId = $this->option('file-id') ? (int) $this->option('file-id') : null;

        // يحاول JSON أولاً ثم XLS
        $jsonPath = base_path(self::JSON);
        $xlsPath  = $this->option('path') ?: base_path(self::FILE);

        if (file_exists($jsonPath)) {
            $rows = $this->parseJson($jsonPath);
        } elseif (file_exists($xlsPath)) {
            $rows = $this->parseFile($xlsPath);
        } else {
            $this->error("لا يوجد ملف بيانات: " . self::JSON . " أو " . self::FILE);
            return 1;
        }

        if (empty($rows)) {
            $this->error('لم يتم قراءة أي صفوف من الملف');
            return 1;
        }

        // تصفية على ملف واحد إذا طُلب
        if ($singleId !== null) {
            $rows = array_filter($rows, fn($r) => (int)$r['file_id'] === $singleId);
            $rows = array_values($rows);
            if (empty($rows)) {
                $this->error("رقم الملف {$singleId} غير موجود في الملف");
                return 1;
            }
        }

        $this->info('الصفوف المراد معالجتها: ' . count($rows));

        $inserted = 0;
        $skipped  = 0;
        $notFound = 0;
        $errors   = [];

        if (!$dryRun) {
            if ($singleId !== null) {
                // عند التجربة على ملف واحد: احذف سجلاته الافتتاحية فقط
                $patient = DB::table('kstu')->where('file_id', $singleId)->first();
                if ($patient) {
                    $acck = DB::table('acck')->where('stu_id', $patient->id)->first();
                    if ($acck) {
                        $deleted = DB::table('kpayments')
                            ->where('acc_id', $acck->id)
                            ->where('pdesc', self::DESC)
                            ->delete();
                        if ($deleted > 0) $this->warn("تم حذف {$deleted} سجل افتتاحي سابق لهذا العميل");
                    }
                }
            } else {
                // حذف كل الأرصدة الافتتاحية السابقة
                $deleted = DB::table('kpayments')->where('pdesc', self::DESC)->delete();
                if ($deleted > 0) $this->warn("تم حذف {$deleted} سجل افتتاحي سابق");
            }
        }

        $bar = $this->output->createProgressBar(count($rows));
        $bar->start();

        foreach ($rows as $row) {
            $fileId = (int) $row['file_id'];
            $credit = (float) $row['credit']; // له
            $debit  = (float) $row['debit'];  // عليه

            // تجاهل من لا رقم ملف له
            if ($fileId === 0) {
                $skipped++;
                $bar->advance();
                continue;
            }

            // تجاهل من لا رصيد له
            if ($credit == 0 && $debit == 0) {
                $skipped++;
                $bar->advance();
                continue;
            }

            // البحث عن العميل في kstu
            $patient = DB::table('kstu')->where('file_id', $fileId)->first();
            if (!$patient) {
                $notFound++;
                $errors[] = "لم يوجد ملف رقم: {$fileId} | {$row['name']}";
                $bar->advance();
                continue;
            }

            if ($dryRun) {
                $this->newLine();
                $this->line("[معاينة] {$row['name']} | ملف:{$fileId} | له:{$credit} | عليه:{$debit}");
                $inserted++;
                $bar->advance();
                continue;
            }

            // إيجاد أو إنشاء سجل acck للعميل
            $acck = DB::table('acck')->where('stu_id', $patient->id)->first();
            if (!$acck) {
                $acckId = DB::table('acck')->insertGetId([
                    'name'   => $patient->full_name ?? '',
                    'stu_id' => $patient->id,
                ]);
            } else {
                $acckId = $acck->id;
            }

            // ── رصيد دائن (له) → إيداع ──
            $base = [
                'acc_id'         => $acckId,
                'pdate'          => self::PDATE,
                'price'          => 0,
                'net'            => 0,
                'payment_method' => 0,
                'pdesc'          => self::DESC,
                'rec_id'         => 0,
                'clinic_id'      => 0,
                'serial_no'      => 0,
                'user_id'        => 0,
                'client_id'      => 0,
                'discount'       => 0,
                'credit'         => 0,
            ];

            if ($credit > 0) {
                DB::table('kpayments')->insert($base + [
                    'amount'  => $credit,
                    'status'  => 1,
                    'type_id' => 1,
                ]);
            }

            if ($debit > 0) {
                DB::table('kpayments')->insert($base + [
                    'amount'  => $debit,
                    'status'  => 2,
                    'type_id' => 0,
                ]);
            }

            $inserted++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("تم المعالجة: {$inserted}");
        $this->warn("تم التجاهل (بدون ملف أو بدون رصيد): {$skipped}");
        if ($notFound > 0) {
            $this->error("لم يوجد في قاعدة البيانات: {$notFound}");
            foreach (array_slice($errors, 0, 20) as $e) {
                $this->line("  - {$e}");
            }
        }

        if ($dryRun) {
            $this->newLine();
            $this->warn('وضع المعاينة — لم يُحفظ شيء. شغّل بدون --dry-run للحفظ الفعلي.');
        }

        return 0;
    }

    private function parseJson(string $path): array
    {
        $data = json_decode(file_get_contents($path), true) ?? [];
        return array_map(fn($r) => [
            'name'    => $r['n'] ?? '',
            'file_id' => (string) ($r['f'] ?? '0'),
            'debit'   => (string) ($r['d'] ?? '0'),
            'credit'  => (string) ($r['c'] ?? '0'),
        ], $data);
    }

    private function parseFile(string $path): array
    {
        $content = file_get_contents($path);
        $rows    = [];

        preg_match_all('/<tr[^>]*>(.*?)<\/tr>/is', $content, $matches);

        foreach ($matches[1] as $i => $rowHtml) {
            // تجاهل أول 3 صفوف (عنوان + رأس عربي + رأس إنجليزي)
            if ($i < 3) continue;

            preg_match_all('/<t[dh][^>]*>(.*?)<\/t[dh]>/is', $rowHtml, $cells);
            $cols = array_map(
                fn($c) => trim(strip_tags(html_entity_decode($c, ENT_QUOTES | ENT_HTML5, 'UTF-8'))),
                $cells[1]
            );

            if (count($cols) < 8) continue;

            // تجاهل صف الإجمالي
            if (str_contains($cols[0] ?? '', 'Total')) continue;

            $rows[] = [
                'name'    => $cols[1] ?? '',
                'file_id' => $cols[2] ?? '0',
                'ssn'     => $cols[3] ?? '',
                'phone'   => $cols[5] ?? '',
                'debit'   => $cols[6] ?? '0',  // عليه
                'credit'  => $cols[7] ?? '0',  // له
            ];
        }

        return $rows;
    }
}
