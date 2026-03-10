<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Livewire\Dashboard;
use App\Livewire\Patients\Index as PatientsIndex;
use App\Livewire\Appointments\Index as AppointmentsIndex;

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/patients', PatientsIndex::class)->name('patients.index');
Route::get('/patients/create', \App\Livewire\Patients\Create::class)->name('patients.create');
Route::get('/appointments', AppointmentsIndex::class)->name('appointments.index');

// New Patient-Related Routes
Route::get('/patients/{id}', \App\Livewire\Patients\Show::class)->name('patients.show');
Route::get('/patients/{id}/medical-history', \App\Livewire\Patients\History::class)->name('patients.medical-history');
Route::get('/patients/{id}/financial-statement', \App\Livewire\Patients\Financial::class)->name('patients.financial-statement');
Route::get('/patients/{id}/new-check', \App\Livewire\Patients\NewCheck::class)->name('patients.new-check');

// Checks
Route::get('/checks', \App\Livewire\Checks\Index::class)->name('checks.index');

// Finance
Route::get('/finance/movements', \App\Livewire\Finance\Movements::class)->name('finance.movements');
Route::get('/finance/statement', \App\Livewire\Finance\Statement::class)->name('finance.statement');
Route::get('/finance/vouchers',  \App\Livewire\Finance\Vouchers::class)->name('finance.vouchers');
Route::get('/finance/invoices',  \App\Livewire\Finance\Invoices::class)->name('finance.invoices');
Route::get('/finance/invoice/{recId}', function ($recId) {
    $rec = DB::table('rec')->where('id', $recId)->firstOrFail();

    $patient = DB::table('kstu as k')
        ->leftJoin('kcom as c', 'c.id', '=', 'k.com_id')
        ->where('k.id', $rec->st_id)
        ->select('k.full_name', 'k.file_id', 'c.name as insurance')
        ->first();

    $clinic = DB::table('clinic')->where('id', $rec->clinic_id)->first();
    $clinicName = $clinic?->name ?? '—';

    $items = DB::table('kpayments')
        ->where('rec_id', $recId)
        ->where('price', '>', 0)
        ->get();

    $invoice       = $items->first();
    $total         = $items->sum('price');
    $totalDiscount = $items->sum('discount');
    $clientAmount  = $total - $totalDiscount;
    $insuranceAmount = 0;

    $paymentLabels = [
        1=>'نقدا', 3=>'شبكة', 4=>'تحويل بنكي', 5=>'سند',
        6=>'فيزا', 11=>'Myfatoorah', 12=>'stcpay', 14=>'دفع سريع',
    ];
    $paymentLabel = $paymentLabels[$invoice?->payment_method ?? 1] ?? 'نقدا';
    $cashierName  = 'محمود طه /ح 4';

    return view('finance.invoice-print', compact(
        'rec', 'patient', 'clinicName', 'items',
        'invoice', 'total', 'totalDiscount', 'clientAmount',
        'insuranceAmount', 'paymentLabel', 'cashierName'
    ));
})->name('finance.invoice-print');
Route::get('/finance/reports',   \App\Livewire\Finance\Reports::class)->name('finance.reports');

// Employees
Route::get('/employees', \App\Livewire\Employees\Index::class)->name('employees.index');

// System
Route::get('/system/users', \App\Livewire\System\Users::class)->name('system.users');
Route::get('/system/settings', \App\Livewire\System\Settings::class)->name('system.settings');

// TEMP DEBUG - remove after use
Route::get('/debug-db', function () {
    $db = DB::select('SELECT DATABASE() as db')[0]->db;
    $rows = DB::select("SELECT r.id, a.name, r.rec_date FROM rec r LEFT JOIN acck a ON a.id=r.st_id WHERE r.confirm_id=1 ORDER BY r.id DESC LIMIT 5");
    return response()->json(['connected_db' => $db, 'latest_records' => $rows]);
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

