<?php

namespace App\Livewire\Patients;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\DB;

class Show extends Component
{
    public $patient;
    public int   $editBranch = 0;
    public array $branches   = [];
    public float $balance    = 0.0;
    public bool  $hasAccount = false;

    #[Title('ملف العميل')]
    public function mount($id): void
    {
        $this->patient = DB::table('kstu as k')
            ->leftJoin('kcom as c',  'c.id', '=', 'k.com_id')
            ->leftJoin('class as cl', 'cl.id', '=', 'k.class_id')
            ->leftJoin('branches as b', 'b.id', '=', 'k.branch_id')
            ->where('k.id', $id)
            ->select(
                'k.id', 'k.full_name', 'k.file_id', 'k.ssn', 'k.phone',
                'k.gender', 'k.date_of_birth', 'k.reg_date', 'k.branch_id',
                'c.name as insurance', 'cl.name as class_name',
                'b.name as branch_name'
            )
            ->first();

        abort_if(!$this->patient, 404);
        $this->editBranch = (int) ($this->patient->branch_id ?? 0);
        $this->branches   = DB::table('branches')->where('is_active', 1)->get(['id', 'name'])->all();

        // حساب رصيد العميل (يدعم النظامَين القديم والجديد)
        $acck   = DB::table('acck')->where('stu_id', $id)->first();
        $acckId = $acck?->id;
        $this->hasAccount = (bool) $acckId;
        if ($acckId) {
            $totalDeposited = (float) DB::table('kpayments')
                ->where('acc_id', $acckId)->where('status', 1)
                ->selectRaw('COALESCE(SUM(COALESCE(NULLIF(amount,0), NULLIF(price,0), 0)),0) as total')
                ->value('total');

            // خدمات آجلة (النظام الجديد)
            $recIds = DB::table('rec')->where('st_id', $id)->pluck('id');
            $charged_svc = 0.0;
            if ($recIds->isNotEmpty()) {
                $svc = DB::table('kpayments')
                    ->whereIn('rec_id', $recIds)->where('payment_method', 5)
                    ->selectRaw('COALESCE(SUM(price),0) as tp, COALESCE(SUM(discount),0) as td')
                    ->first();
                $charged_svc = max(0.0, (float)($svc->tp ?? 0) - (float)($svc->td ?? 0));
            }

            // قيود مديونية صريحة (النظام القديم: acc_id=acckId, status=2, rec_id=0)
            $charged_old = (float) DB::table('kpayments')
                ->where('acc_id', $acckId)
                ->where('status', 2)
                ->where('rec_id', 0)
                ->selectRaw('COALESCE(SUM(COALESCE(NULLIF(amount,0), NULLIF(price,0), 0)),0) as total')
                ->value('total');

            $this->balance = round($totalDeposited - $charged_svc - $charged_old, 3);
        }
    }

    public function saveBranch(): void
    {
        DB::table('kstu')->where('id', $this->patient->id)->update(['branch_id' => $this->editBranch]);
        $branchName = collect($this->branches)->firstWhere('id', $this->editBranch)->name ?? '';
        ActivityLogger::log('updated', 'patient', $this->patient->id, 'تحديث الفرع إلى: ' . $branchName);
        $this->patient = DB::table('kstu as k')
            ->leftJoin('kcom as c',  'c.id', '=', 'k.com_id')
            ->leftJoin('class as cl', 'cl.id', '=', 'k.class_id')
            ->leftJoin('branches as b', 'b.id', '=', 'k.branch_id')
            ->where('k.id', $this->patient->id)
            ->select('k.id','k.full_name','k.file_id','k.ssn','k.phone','k.gender','k.date_of_birth','k.reg_date','k.branch_id','c.name as insurance','cl.name as class_name','b.name as branch_name')
            ->first();
        session()->flash('branch_saved', 'تم حفظ الفرع');
    }

    public function render()
    {
        $activityLogs = [];
        try {
            $activityLogs = DB::table('activity_logs')
                ->where('subject_id', $this->patient->id)
                ->whereIn('subject', ['patient', 'check', 'attachment', 'appointment', 'payment'])
                ->orderBy('id', 'desc')
                ->limit(30)
                ->get();
        } catch (\Throwable) {}

        return view('livewire.patients.show', compact('activityLogs'))->layout('layouts.app');
    }
}
