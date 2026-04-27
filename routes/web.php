<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Livewire\Dashboard;
use App\Livewire\Patients\Index as PatientsIndex;
use App\Livewire\Appointments\Index as AppointmentsIndex;

/* ══════════════════════════════════════
   Guest Routes — لا تحتاج تسجيل دخول
══════════════════════════════════════ */
Route::middleware('guest')->group(function () {
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
});

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

/* ══════════════════════════════════════
   Authenticated Routes
══════════════════════════════════════ */
Route::middleware(['auth.employee'])->group(function () {

    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/patients', PatientsIndex::class)->name('patients.index');
    Route::get('/patients/create', \App\Livewire\Patients\Create::class)->name('patients.create');
    Route::get('/appointments', AppointmentsIndex::class)->name('appointments.index');
    Route::get('/appointments/book', \App\Livewire\Appointments\Book::class)->name('appointments.book');

    // Patient
    Route::get('/patients/{id}', \App\Livewire\Patients\Show::class)->name('patients.show');
    Route::get('/patients/{id}/medical-history', \App\Livewire\Patients\History::class)->name('patients.medical-history');
    Route::get('/patients/{id}/financial-statement', \App\Livewire\Patients\Financial::class)->name('patients.financial-statement');
    Route::get('/patients/{id}/new-check', \App\Livewire\Patients\NewCheck::class)->name('patients.new-check');
    Route::get('/patients/{id}/attachments', \App\Livewire\Patients\Attachments::class)->name('patients.attachments');

    // Checks
    Route::get('/checks', \App\Livewire\Checks\Index::class)->name('checks.index');

    // Finance
    Route::get('/finance/movements', \App\Livewire\Finance\Movements::class)->name('finance.movements');
    Route::get('/finance/reprint',   \App\Livewire\Finance\Reprint::class)->name('finance.reprint');
    Route::get('/finance/statement', \App\Livewire\Finance\Statement::class)->name('finance.statement');
    Route::get('/finance/balances',  \App\Livewire\Finance\Balances::class)->name('finance.balances');
    Route::get('/finance/vouchers',         \App\Livewire\Finance\Vouchers::class)->name('finance.vouchers');
    Route::get('/finance/invoices',         \App\Livewire\Finance\Invoices::class)->name('finance.invoices');
    Route::get('/finance/voided-invoices',  \App\Livewire\Finance\VoidedInvoices::class)->name('finance.voided-invoices');
    Route::get('/finance/voided-vouchers',  \App\Livewire\Finance\VoidedVouchers::class)->name('finance.voided-vouchers');
    Route::get('/finance/detailed-report',  \App\Livewire\Finance\DetailedReport::class)->name('finance.detailed-report');
    Route::get('/finance/invoice/{recId}', function ($recId) {
        $rec = DB::table('rec')->where('id', $recId)->firstOrFail();

        $patient = DB::table('kstu as k')
            ->leftJoin('kcom as c', 'c.id', '=', 'k.com_id')
            ->where('k.id', $rec->st_id)
            ->select('k.full_name', 'k.file_id', 'k.pno as policy_no', 'c.name as insurance', 'k.branch_id')
            ->first();

        $clinic      = DB::table('clinic')->where('id', $rec->clinic_id)->first();
        $clinicName  = $clinic?->name ?? '—';
        $branchName  = $patient?->branch_id
            ? DB::table('branches')->where('id', $patient->branch_id)->value('name')
            : null;

        // صف header لجلب ptime و user_id (بدون فلتر السعر لدعم الخدمات المجانية)
        $invoiceHeader = DB::table('kpayments')->where('rec_id', $recId)->first();
        $rawNotes = $rec->notes ?: ($invoiceHeader->notes ?? '');
        $recNotes = strip_tags(html_entity_decode(str_replace("\xc2\xa0", ' ', $rawNotes), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        $items = DB::table('kpayments')
            ->where('rec_id', $recId)
            ->where('price', '>=', 0)
            ->get()
            ->flatMap(function ($item) {
                $desc = html_entity_decode($item->pdesc ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $desc = strip_tags($desc);
                $desc = str_replace("\xc2\xa0", ' ', $desc);
                $parts = array_values(array_filter(array_map('trim', preg_split('/\s*\*\s*/', $desc))));

                // استخرج الأجزاء التي تحتوي أرقام (= خدمات) وتجاهل اسم المريض
                $svcParts = array_values(array_filter($parts, fn($p) => preg_match('/\d/', $p)));

                if (count($svcParts) <= 1) {
                    // خدمة واحدة — السلوك الأصلي
                    $item->pdesc_clean = $parts[0] ?? trim($desc) ?: '—';
                    return [$item];
                }

                // عدة خدمات مدمجة — نفكها لصفوف منفصلة
                // المرور الأول: استخرج الأسعار المعروفة من النص
                $knownPrices  = [];
                $totalKnown   = 0;
                foreach ($svcParts as $i => $part) {
                    if (preg_match('/([\d.]+)\s*(?:د\.ك|د\.ك\.|KD|D\.K)/ui', $part, $m)) {
                        $knownPrices[$i] = (float) $m[1];
                        $totalKnown     += (float) $m[1];
                    }
                }
                // وزّع الباقي على الخدمات بدون سعر
                $unknownCount    = count($svcParts) - count($knownPrices);
                $remainingPrice  = round($item->price - $totalKnown, 3);
                $pricePerUnknown = $unknownCount > 0 ? round($remainingPrice / $unknownCount, 3) : 0;

                $expanded = [];
                foreach ($svcParts as $i => $part) {
                    $unitPrice = $knownPrices[$i] ?? $pricePerUnknown;
                    $name      = isset($knownPrices[$i])
                        ? trim(preg_replace('/\s*[-–]\s*[\d.]+\s*(?:د\.ك|د\.ك\.|KD|D\.K).*/ui', '', $part))
                        : $part;

                    $row = clone $item;
                    $row->pdesc_clean = $name ?: $part;
                    $row->price       = $unitPrice;
                    // الخصم الأصلي على الصف الأول فقط
                    $row->discount    = $i === 0 ? (float)($item->discount ?? 0) : 0;
                    $expanded[]       = $row;
                }
                return $expanded;
            });

        $invoice         = $invoiceHeader;
        $total           = $items->sum('price');
        $totalDiscount   = $items->sum('discount');
        $insuranceAmount = $items->sum('insur_amount');
        $clientAmount    = $total - $totalDiscount - $insuranceAmount;

        $paymentLabels = [
            1=>'نقدا', 2=>'شيك', 3=>'شبكة', 4=>'تحويل بنكي', 5=>'سند',
            6=>'فيزا', 7=>'مجاني', 8=>'آجل', 11=>'Myfatoorah',
            12=>'stcpay', 14=>'دفع سريع', 23=>'مجاني - من الرصيد',
        ];
        $paymentLabel = $paymentLabels[$invoice?->payment_method ?? 1] ?? 'نقدا';

        // helper: ابحث عن موظف بـ id أو emp_no (للفواتير القديمة)
        $findEmp = function (int $uid) {
            if (!$uid) return null;
            $e = DB::table('employees')->where('id', $uid)->first(['first_name','middle_initial','user_name']);
            if (!$e) {
                $e = DB::table('employees')->where('emp_no', (string)$uid)->first(['first_name','middle_initial','user_name']);
            }
            return $e;
        };
        $empToName = function ($e): string {
            if (!$e) return '';
            $n = trim(($e->first_name ?? '') . ' ' . ($e->middle_initial ?? ''));
            if ($n !== '' && $n !== ' ') return $n;
            return $e->user_name ?? '';
        };

        // الأولوية: kpayments.user_id → rec.user_id
        $cashierUserId = (int)($invoice?->user_id ?? 0);
        $cashierName   = $empToName($findEmp($cashierUserId));
        if (!$cashierName) {
            $recUserId   = (int)($rec->user_id ?? 0);
            $cashierName = $empToName($findEmp($recUserId));
        }
        $cashierName = $cashierName ?: '—';

        return view('finance.invoice-print', compact(
            'rec', 'patient', 'clinicName', 'branchName', 'items',
            'invoice', 'total', 'totalDiscount', 'clientAmount',
            'insuranceAmount', 'paymentLabel', 'cashierName', 'recNotes'
        ));
    })->name('finance.invoice-print');

    Route::post('/finance/invoice/{recId}/void', function ($recId) {
        abort_if((auth()->user()?->role ?? '') !== 'admin', 403);

        $rec = DB::table('rec')->where('id', $recId)->first();
        abort_if(!$rec, 404);

        $reason    = trim(request('reason', ''));
        $patientId = $rec->st_id ?? 0;

        // رفع الصورة إن وُجدت
        $attachPath = null;
        if (request()->hasFile('attachment') && request()->file('attachment')->isValid()) {
            $file = request()->file('attachment');
            abort_if($file->getSize() > 5 * 1024 * 1024, 422, 'حجم الملف يتجاوز 5MB');
            request()->validate(['attachment' => 'file|mimes:jpg,jpeg,png,gif,pdf|max:5120']);
            $attachPath = $file->store('void-invoices', 'public');
        }

        // حذف خدمات الكشف
        DB::table('kpayments')->where('rec_id', $recId)->delete();

        // حذف أي سند قبض مرتبط بنفس العميل في نفس تاريخ الفاتورة
        $acck = DB::table('acck')->where('stu_id', $patientId)->first();
        if ($acck) {
            DB::table('kpayments')
                ->where('acc_id', $acck->id)
                ->where('status', 1)
                ->where('pdate', $rec->pdate ?: $rec->rec_date)
                ->delete();
        }

        DB::table('rec')->where('id', $recId)->delete();

        $desc = 'إلغاء فاتورة #' . $recId;
        if ($reason)     $desc .= ' — السبب: ' . $reason;
        if ($attachPath) $desc .= ' — مرفق: ' . $attachPath;

        \App\Helpers\ActivityLogger::log('voided', 'check', $patientId, $desc);

        return redirect()->route('checks.index')
            ->with('success', 'تم إلغاء الفاتورة #' . $recId . ' بنجاح');
    })->name('finance.invoice-void');

    Route::get('/finance/movement/{id}/print', function ($id) {
        $mov = DB::table('kpayments as k')
            ->join('acck as a', 'a.id', '=', 'k.acc_id')
            ->leftJoin('kstu as s', 's.id', '=', 'a.stu_id')
            ->leftJoin('employees as e', 'e.id', '=', 'k.user_id')
            ->where('k.id', $id)
            ->select(
                'k.id', 'k.pdate', 'k.ptime', 'k.pdesc', 'k.status', 'k.payment_method',
                DB::raw('COALESCE(NULLIF(k.amount,0), NULLIF(k.price,0), 0) as mov_amount'),
                's.full_name as patient_name',
                's.file_id as patient_file',
                's.phone as patient_phone',
                'a.name as acck_name',
                DB::raw('COALESCE(e.branch_id, 1) as branch_id'),
                DB::raw("TRIM(CONCAT(IFNULL(e.first_name,''), ' ', IFNULL(e.middle_initial,''))) as emp_name")
            )
            ->firstOrFail();

        $payMethods = [
            1=>'نقدا', 3=>'شبكة', 6=>'فيزا', 4=>'تحويل بنكي',
            11=>'MyFatoorah', 12=>'STC Pay', 14=>'دفع سريع', 20=>'Deema', 21=>'زكاء', 22=>'نقل رصيد',
            23=>'مجاني - من الرصيد',
        ];

        $desc = $mov->pdesc ?? '';
        $hasRef = str_contains($desc, '| Ref:');
        $refNo = $hasRef ? trim(explode('| Ref:', $desc, 2)[1]) : null;
        $descClean = $hasRef ? trim(explode('| Ref:', $desc, 2)[0]) : trim($desc);

        return view('finance.movement-print', [
            'mov'        => $mov,
            'payMethods' => $payMethods,
            'refNo'      => $refNo,
            'descClean'  => $descClean,
        ]);
    })->name('finance.movement-print');

    // مستقبل أول + مدير
    Route::middleware('require.reception1')->group(function () {
        Route::get('/finance/reports',      \App\Livewire\Finance\Reports::class)->name('finance.reports');
        Route::get('/finance/branch-report',\App\Livewire\Finance\BranchReport::class)->name('finance.branch-report');
    });

    // Admin only
    Route::middleware('require.admin')->group(function () {
        Route::get('/system/activity-log',    \App\Livewire\System\ActivityLog::class)->name('system.activity-log');
        Route::get('/system/users',           \App\Livewire\System\Users::class)->name('system.users');
        Route::get('/system/settings',        \App\Livewire\System\Settings::class)->name('system.settings');
        Route::get('/system/discount-codes',  \App\Livewire\System\DiscountCodes::class)->name('system.discount-codes');
        Route::get('/system/services',        \App\Livewire\System\Services::class)->name('system.services');
        Route::get('/system/employee-phones', \App\Livewire\System\EmployeePhones::class)->name('system.employee-phones');
        Route::get('/clinics',              \App\Livewire\Clinics\Index::class)->name('clinics.index');
        Route::get('/employees',            \App\Livewire\Employees\Index::class)->name('employees.index');
    });

    // Backup — المدير فقط
    Route::get('/system/backup', function () {
        abort_if((auth()->user()?->role ?? '') !== 'admin', 403);

        $dbName = config('database.connections.mysql.database');
        $now    = now()->format('Y-m-d_H-i');

        // الجداول المهمة فقط — بدون الجداول الثقيلة من النظام القديم
        $keepTables = [
            // بيانات العملاء والكشوف
            'kstu', 'rec', 'kpayments', 'kpayments_cancel', 'kpayments_cancel2',
            // الخدمات والمكاتب
            'service', 'clinic', 'stop_clinic',
            // الحسابات
            'acck',
            // الإعدادات
            'branches', 'branchk', 'class', 'kcom', 'kcom_discount',
            'discount', 'jops', 'qualifications', 'instit', 'f_setting',
            'words', 'perax', 'expens', 'p_amount',
            // الموظفين
            'employees',
            // المرفقات والمواعيد
            'uploadedfiles', 'appoint_cancel',
            // جداول Laravel
            'activity_logs', 'migrations', 'sessions', 'cache', 'cache_locks',
            'failed_jobs', 'jobs', 'job_batches', 'password_reset_tokens',
            'users', 'appointments', 'invoices', 'medical_records',
        ];

        // تصفية الجداول الموجودة فعلاً في قاعدة البيانات
        $existing = array_map(fn($r) => array_values((array)$r)[0], DB::select('SHOW TABLES'));
        $tableNames = array_filter($keepTables, fn($t) => in_array($t, $existing));
        $tables = array_map(fn($t) => (object)[array_keys((array)DB::select('SHOW TABLES')[0])[0] => $t], $tableNames);

        $callback = function () use ($dbName, $tables) {
            set_time_limit(0);
            $out = fopen('php://output', 'w');

            fwrite($out, "-- ====================================================\n");
            fwrite($out, "--  مركز مطمئنة الاستشاري — نسخة احتياطية\n");
            fwrite($out, "--  قاعدة البيانات : {$dbName}\n");
            fwrite($out, "--  التاريخ        : " . now()->format('Y-m-d H:i:s') . "\n");
            fwrite($out, "-- ====================================================\n\n");
            fwrite($out, "SET FOREIGN_KEY_CHECKS=0;\n");
            fwrite($out, "SET NAMES utf8mb4;\n\n");

            foreach ($tables as $tableRow) {
                $table = array_values((array)$tableRow)[0];

                $create    = DB::select("SHOW CREATE TABLE `{$table}`");
                $createSql = $create[0]->{'Create Table'} ?? '';

                fwrite($out, "-- ----------------------------------------------------\n");
                fwrite($out, "-- جدول: {$table}\n");
                fwrite($out, "-- ----------------------------------------------------\n");
                fwrite($out, "DROP TABLE IF EXISTS `{$table}`;\n");
                fwrite($out, $createSql . ";\n\n");

                $total = DB::table($table)->count();
                if ($total > 0) {
                    $columns = DB::getSchemaBuilder()->getColumnListing($table);
                    $colList = implode(', ', array_map(fn($c) => "`{$c}`", $columns));

                    DB::table($table)->orderByRaw('1')->chunk(500, function ($rows) use ($out, $table, $colList) {
                        $values = [];
                        foreach ($rows as $row) {
                            $escaped = array_map(function ($val) {
                                if ($val === null) return 'NULL';
                                return "'" . addslashes((string)$val) . "'";
                            }, (array)$row);
                            $values[] = '(' . implode(', ', $escaped) . ')';
                        }
                        fwrite($out, "INSERT INTO `{$table}` ({$colList}) VALUES\n");
                        fwrite($out, implode(",\n", $values) . ";\n");
                        ob_flush();
                        flush();
                    });
                    fwrite($out, "\n");
                }
            }

            fwrite($out, "SET FOREIGN_KEY_CHECKS=1;\n");
            fclose($out);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => "attachment; filename=\"backup_mutmainah_{$now}.sql\"",
            'X-Accel-Buffering'   => 'no',
        ]);
    })->name('system.backup');

});

/* ══════════════════════════════════════
   Misc
══════════════════════════════════════ */
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');
