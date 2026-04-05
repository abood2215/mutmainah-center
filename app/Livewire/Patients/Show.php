<?php

namespace App\Livewire\Patients;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Show extends Component
{
    public $patient;

    #[Title('ملف العميل')]
    public function mount($id): void
    {
        $this->patient = DB::table('kstu as k')
            ->leftJoin('kcom as c',  'c.id', '=', 'k.com_id')
            ->leftJoin('class as cl', 'cl.id', '=', 'k.class_id')
            ->where('k.id', $id)
            ->select(
                'k.id', 'k.full_name', 'k.file_id', 'k.ssn', 'k.phone',
                'k.gender', 'k.date_of_birth', 'k.reg_date',
                'c.name as insurance', 'cl.name as class_name'
            )
            ->first();

        abort_if(!$this->patient, 404);
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
