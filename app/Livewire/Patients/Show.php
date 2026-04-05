<?php

namespace App\Livewire\Patients;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\DB;

class Show extends Component
{
    public $patient;
    public int $editBranch = 0;
    public array $branches  = [];

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
                ->whereIn('subject', ['patient', 'check', 'attachment'])
                ->orderBy('id', 'desc')
                ->limit(20)
                ->get();
        } catch (\Throwable) {}

        return view('livewire.patients.show', compact('activityLogs'))->layout('layouts.app');
    }
}
