<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportOpeningBalances extends Command
{
    protected $signature   = 'balances:import {--dry-run : عرض فقط بدون حفظ} {--file-id= : تجربة على ملف واحد فقط} {--path= : مسار الملف}';
    protected $description = 'تسوية أرصدة العملاء بناءً على تقرير النظام القديم';

    private const CUTOFF = '2026-04-18'; // ما قبل هذا التاريخ = النظام القديم
    private const PDATE  = '17-4-2026';
    private const DESC   = 'تسوية رصيد 17-4-2026';
    private const JSON   = 'balances_data.json';
    private const FILE   = 'ارصده العملاء2.xls';

    public function handle(): int
    {
        $dryRun   = $this->option('dry-run');
        $singleId = $this->option('file-id') ? (int) $this->option('file-id') : null;

        $jsonPath = base_path(self::JSON);
        $xlsPath  = $this->option('path') ?: base_path(self::FILE);

        if (file_exists($jsonPath)) {
            $rows = $this->parseJson($jsonPath);
        } elseif (file_exists($xlsPath)) {
            $rows = $this->parseFile($xlsPath);
        } else {
            $this->error("لا يوجد ملف بيانات");
            return 1;
        }

        if ($singleId !== null) {
            $rows = array_values(array_filter($rows, fn($r) => (int)$r['file_id'] === $singleId));
            if (empty($rows)) {
                $this->error("رقم الملف {$singleId} غير موجود في الملف");
                return 1;
            }
        }

        $this->info('الصفوف: ' . count($rows));

        // حذف أي تسويات سابقة أدرجها هذا الأمر
        if (!$dryRun) {
            $deleted = DB::table('kpayments')->where('pdesc', self::DESC)->delete();
            // حذف أي "رصيد افتتاحي" قديم أضفناه بالخطأ
            DB::table('kpayments')->where('pdesc', 'رصيد افتتاحي 17-4-2026')->delete();
            if ($deleted > 0) $this->warn("تم حذف {$deleted} تسوية سابقة");
        }

        $processed = $skipped = $notFound = $noChange = 0;
        $errors = [];

        $bar = $this->output->createProgressBar(count($rows));
        $bar->start();

        foreach ($rows as $row) {
            $fileId     = (int)   $row['file_id'];
            $xlsCredit  = (float) $row['credit']; // له  (يملك)
            $xlsDebit   = (float) $row['debit'];  // عليه (يدين)
            $xlsBalance = $xlsCredit - $xlsDebit; // الرصيد الصافي المستهدف

            if ($fileId === 0) { $skipped++; $bar->advance(); continue; }

            $patient = DB::table('kstu')->where('file_id', $fileId)->first();
            if (!$patient) {
                $notFound++;
                $errors[] = "ملف {$fileId}: {$row['name']}";
                $bar->advance();
                continue;
            }

            $acck   = DB::table('acck')->where('stu_id', $patient->id)->first();
            $acckId = $acck?->id;

            // ── حساب رصيد النظام حتى 17 أبريل ──
            $systemBalance = $this->calcSystemBalance($patient->id, $acckId);

            $correction = round($xlsBalance - $systemBalance, 3);

            if (abs($correction) < 0.001) {
                $noChange++;
                $bar->advance();
                continue;
            }

            if ($dryRun) {
                $this->newLine();
                $label = $correction > 0 ? "إيداع تسوية" : "خصم تسوية";
                $this->line("[معاينة] {$row['name']} | ملف:{$fileId} | رصيد النظام:{$systemBalance} | XLS:{$xlsBalance} | تسوية:{$correction} ({$label})");
                $processed++;
                $bar->advance();
                continue;
            }

            // إنشاء acck إذا لم يكن موجوداً
            if (!$acckId) {
                $acckId = DB::table('acck')->insertGetId([
                    'name'   => $patient->full_name ?? '',
                    'stu_id' => $patient->id,
                ]);
            }

            if ($correction > 0) {
                // رصيد النظام أقل من المطلوب → أضف إيداع
                DB::table('kpayments')->insert($this->buildRow($acckId, $correction, 1, 1));
            } else {
                // رصيد النظام أكثر من المطلوب → أضف خصم
                DB::table('kpayments')->insert($this->buildRow($acckId, abs($correction), 2, 0));
            }

            $processed++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("تسويات أُدرجت: {$processed}");
        $this->line("لا تسوية لازمة (رصيد صحيح): {$noChange}");
        $this->warn("تجاهل (بدون ملف/رصيد): {$skipped}");
        if ($notFound > 0) {
            $this->error("لم يوجد في DB: {$notFound}");
            foreach (array_slice($errors, 0, 20) as $e) $this->line("  - {$e}");
        }
        if ($dryRun) $this->warn("\nوضع المعاينة — لم يُحفظ شيء.");

        return 0;
    }

    // يحسب الرصيد كما يراه النظام حتى 17 أبريل فقط
    private function calcSystemBalance(int $patientId, ?int $acckId): float
    {
        $cutoff = self::CUTOFF;

        // إيداعات
        $deposits = $acckId
            ? (float) DB::table('kpayments')
                ->where('acc_id', $acckId)
                ->where('status', 1)
                ->where('type_id', '!=', 2)
                ->whereRaw("STR_TO_DATE(pdate, '%e-%c-%Y') < ?", [$cutoff])
                ->whereNotIn('pdesc', [self::DESC, 'رصيد افتتاحي 17-4-2026'])
                ->sum(DB::raw('COALESCE(NULLIF(amount,0), NULLIF(price,0), 0)'))
            : 0.0;

        // خصومات حساب (status=2 أو type_id=2)
        $debits = $acckId
            ? (float) DB::table('kpayments')
                ->where('acc_id', $acckId)
                ->where(function($q) {
                    $q->where(fn($q2) => $q2->where('status', 2)->where('payment_method', '!=', 5))
                      ->orWhere(fn($q2) => $q2->where('status', 1)->where('type_id', 2));
                })
                ->whereRaw("STR_TO_DATE(pdate, '%e-%c-%Y') < ?", [$cutoff])
                ->sum(DB::raw('COALESCE(NULLIF(amount,0), NULLIF(price,0), 0)'))
            : 0.0;

        // خدمات من الرصيد (payment_method=5) مرتبطة بالعميل
        $services = (float) DB::table('kpayments as k')
            ->join('rec as r', 'r.id', '=', 'k.rec_id')
            ->where('r.st_id', $patientId)
            ->where('k.payment_method', 5)
            ->whereRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') < ?", [$cutoff])
            ->sum(DB::raw('GREATEST(0, k.price - COALESCE(k.discount,0))'));

        return round($deposits - $debits - $services, 3);
    }

    private ?array $rowTemplate = null;

    private function buildRow(int $acckId, float $amount, int $status, int $typeId): array
    {
        if ($this->rowTemplate === null) {
            $sample = DB::table('kpayments')->first();
            if ($sample) {
                $this->rowTemplate = array_map(fn($v) => is_numeric($v) ? 0 : '', (array) $sample);
                unset($this->rowTemplate['id']);
            } else {
                $this->rowTemplate = [];
            }
        }

        return array_merge($this->rowTemplate, [
            'acc_id'         => $acckId,
            'pdate'          => self::PDATE,
            'amount'         => $amount,
            'price'          => 0,
            'net'            => 0,
            'status'         => $status,
            'type_id'        => $typeId,
            'payment_method' => 0,
            'pdesc'          => self::DESC,
            'rec_id'         => 0,
            'clinic_id'      => 0,
        ]);
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
            if ($i < 3) continue;
            preg_match_all('/<t[dh][^>]*>(.*?)<\/t[dh]>/is', $rowHtml, $cells);
            $cols = array_map(
                fn($c) => trim(strip_tags(html_entity_decode($c, ENT_QUOTES | ENT_HTML5, 'UTF-8'))),
                $cells[1]
            );
            if (count($cols) < 8) continue;
            if (str_contains($cols[0] ?? '', 'Total')) continue;
            $rows[] = [
                'name'    => $cols[1] ?? '',
                'file_id' => $cols[2] ?? '0',
                'debit'   => $cols[6] ?? '0',
                'credit'  => $cols[7] ?? '0',
            ];
        }
        return $rows;
    }
}
