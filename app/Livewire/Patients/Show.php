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

    // وضع تعديل ملف العميل (المدير فقط)
    public bool   $editMode     = false;
    public string $editName     = '';
    public string $editSsn      = '';
    public string $editPhone    = '';
    public int    $editGender   = 0;
    public int    $editComId    = 0;
    public array  $insurances   = [];

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
        $this->insurances = DB::table('kcom')->orderBy('name')->get(['id', 'name'])->all();

        // حساب رصيد العميل (يدعم النظامَين القديم والجديد)
        $acck   = DB::table('acck')->where('stu_id', $id)->first();
        $acckId = $acck?->id;
        $this->hasAccount = (bool) $acckId;
        if ($acckId) {
            $totalDeposited = (float) DB::table('kpayments')
                ->where('acc_id', $acckId)->where('status', 1)
                ->selectRaw('COALESCE(SUM(COALESCE(NULLIF(amount,0), NULLIF(price,0), 0)),0) as total')
                ->value('total');

            // خدمات مدفوعة من الرصيد (pm=5) — يستخدم سعر الخدمة كـ fallback
            $charged_svc = 0.0;
            $recIds = DB::table('rec')->where('st_id', $id)->pluck('id');
            if ($recIds->isNotEmpty()) {
                $svc = DB::table('kpayments as p')
                    ->join('rec as r', 'r.id', '=', 'p.rec_id')
                    ->leftJoin('service as sv', 'sv.id', '=', 'r.service_id')
                    ->whereIn('p.rec_id', $recIds)->where('p.payment_method', 5)
                    ->selectRaw('COALESCE(SUM(GREATEST(COALESCE(NULLIF(p.amount,0),NULLIF(p.price,0),NULLIF(sv.price,0),0)-COALESCE(p.discount,0),0)),0) as tp')
                    ->first();
                $charged_svc = (float)($svc->tp ?? 0);
            }

            // قيود الخصم (status=2 payment!=5) أو استرداد (status=1 type_id=2)
            $charged_old = (float) DB::table('kpayments')
                ->where('acc_id', $acckId)
                ->where(function($q) {
                    $q->where(fn($q2) => $q2->where('status', 2)->where('payment_method', '!=', 5))
                      ->orWhere(fn($q2) => $q2->where('status', 1)->where('type_id', 2));
                })
                ->selectRaw('COALESCE(SUM(COALESCE(NULLIF(amount,0), NULLIF(price,0), 0)),0) as total')
                ->value('total');

            $this->balance = round($totalDeposited - $charged_svc - $charged_old, 3);
        }
    }

    public function openEdit(): void
    {
        $this->editName   = $this->patient->full_name ?? '';
        $this->editSsn    = $this->patient->ssn   ?? '';
        $this->editPhone  = $this->patient->phone  ?? '';
        $this->editGender = (int) ($this->patient->gender ?? 0);
        $this->editComId  = (int) DB::table('kstu')->where('id', $this->patient->id)->value('com_id');
        $this->editMode   = true;
    }

    public function cancelEdit(): void
    {
        $this->editMode = false;
    }

    public function saveEdit(): void
    {

        $this->validate([
            'editName'  => 'required|string|max:200',
            'editPhone' => 'nullable|string|max:20',
            'editSsn'   => 'nullable|string|max:30',
        ]);

        $old = $this->patient;
        DB::table('kstu')->where('id', $old->id)->update([
            'full_name' => trim($this->editName),
            'ssn'       => trim($this->editSsn),
            'phone'     => trim($this->editPhone),
            'gender'    => $this->editGender,
            'com_id'    => $this->editComId ?: 28,
        ]);

        $changes = [];
        if (trim($this->editName)  !== ($old->full_name ?? '')) $changes[] = 'الاسم';
        if (trim($this->editSsn)   !== ($old->ssn   ?? ''))     $changes[] = 'الهوية';
        if (trim($this->editPhone) !== ($old->phone  ?? ''))     $changes[] = 'الجوال';
        if ($this->editGender      !== (int)($old->gender ?? 0)) $changes[] = 'الجنس';

        ActivityLogger::log('updated', 'patient', $old->id,
            'تعديل بيانات العميل' . (count($changes) ? ': ' . implode('، ', $changes) : '')
        );

        $this->patient = DB::table('kstu as k')
            ->leftJoin('kcom as c',   'c.id', '=', 'k.com_id')
            ->leftJoin('class as cl', 'cl.id', '=', 'k.class_id')
            ->leftJoin('branches as b', 'b.id', '=', 'k.branch_id')
            ->where('k.id', $old->id)
            ->select('k.id','k.full_name','k.file_id','k.ssn','k.phone','k.gender','k.date_of_birth','k.reg_date','k.branch_id','c.name as insurance','cl.name as class_name','b.name as branch_name')
            ->first();

        $this->editMode = false;
        session()->flash('edit_saved', 'تم حفظ بيانات العميل');
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
        if ((auth()->user()?->role ?? '') === 'admin') {
            try {
                $activityLogs = DB::table('activity_logs')
                    ->where('subject_id', $this->patient->id)
                    ->whereIn('subject', ['patient', 'check', 'attachment', 'appointment', 'payment', 'discount', 'voided'])
                    ->orderBy('id', 'desc')
                    ->limit(30)
                    ->get();
            } catch (\Throwable) {}
        }

        return view('livewire.patients.show', compact('activityLogs'))->layout('layouts.app');
    }
}
