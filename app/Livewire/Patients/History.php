<?php

namespace App\Livewire\Patients;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class History extends Component
{
    public int $patientId;
    public ?object $patient = null;

    // حقول السجل الاستشاري → من جدول kstu مباشرة
    public $current_complaint;      // kstu.chronic
    public $psychiatric_treatments; // kstu.heir
    public $impression;             // kstu.surg
    public $plan;                   // kstu.allerg
    public $family_history;         // kstu.f_history
    public $personal_history;       // kstu.notes
    public $mental_state;           // kstu.evalu
    public $recommendations;        // kstu.recomn
    public $future_plan;            // kstu.fut_plan

    public $birth_day;
    public $birth_month;
    public $birth_year;

    #[Title('السجل الاستشاري')]
    public function mount(int $id): void
    {
        $this->patientId = $id;

        $this->patient = DB::table('kstu as k')
            ->leftJoin('kcom as c',   'c.id', '=', 'k.com_id')
            ->leftJoin('class as cl', 'cl.id', '=', 'k.class_id')
            ->where('k.id', $id)
            ->select('k.*', 'c.name as insurance', 'cl.name as class_name')
            ->first();

        abort_if(!$this->patient, 404);

        // تحميل الحقول من kstu مع تنظيف HTML tags
        $this->current_complaint      = $this->cleanHtml($this->patient->chronic);
        $this->psychiatric_treatments = $this->cleanHtml($this->patient->heir);
        $this->impression             = $this->cleanHtml($this->patient->surg);
        $this->plan                   = $this->cleanHtml($this->patient->allerg);
        $this->family_history         = $this->cleanHtml($this->patient->f_history);
        $this->personal_history       = $this->cleanHtml($this->patient->notes);
        $this->mental_state           = $this->cleanHtml($this->patient->evalu);
        $this->recommendations        = $this->cleanHtml($this->patient->recomn);
        $this->future_plan            = $this->cleanHtml($this->patient->fut_plan);

        // تحميل تاريخ الميلاد - محاولة استخراج يوم/شهر/سنة من date_of_birth
        if ($this->patient->date_of_birth) {
            $parts = preg_split('/[-\/]/', $this->patient->date_of_birth);
            if (count($parts) === 3) {
                $this->birth_day   = (int) $parts[0];
                $this->birth_month = (int) $parts[1];
                $this->birth_year  = (int) $parts[2];
            }
        }
    }

    private function cleanHtml(?string $value): string
    {
        if (!$value) return '';
        // إزالة HTML tags وتحويل <br> إلى سطر جديد قبل الإزالة
        $clean = preg_replace('/<br\s*\/?>/i', "\n", $value);
        $clean = strip_tags($clean);
        return trim($clean);
    }

    public function saveRecord(): void
    {
        $data = [
            'chronic'    => $this->current_complaint,
            'heir'       => $this->psychiatric_treatments,
            'surg'       => $this->impression,
            'allerg'     => $this->plan,
            'f_history'  => $this->family_history,
            'notes'      => $this->personal_history,
            'evalu'      => $this->mental_state,
            'recomn'     => $this->recommendations,
            'fut_plan'   => $this->future_plan,
        ];

        // حفظ تاريخ الميلاد إذا تم تعديله
        if ($this->birth_day && $this->birth_month && $this->birth_year) {
            $data['date_of_birth'] = $this->birth_day . '-' . $this->birth_month . '-' . $this->birth_year;
        }

        DB::table('kstu')->where('id', $this->patientId)->update($data);

        session()->flash('success', 'تم حفظ السجل الاستشاري بنجاح');
    }

    public function render()
    {
        // سجل الزيارات من rec مع العيادة والطبيب
        $visits = DB::table('rec as r')
            ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
            ->leftJoin('employees as e', 'e.id', '=', 'r.doc_id')
            ->where('r.st_id', $this->patientId)
            ->where('r.confirm_id', 1)
            ->select(
                'r.id',
                'r.rec_date',
                'r.rec_time',
                'r.sym',
                'r.dia',
                'r.pres',
                'r.notes as visit_notes',
                'c.name as clinic_name',
                DB::raw("CONCAT(COALESCE(e.first_name,''), ' ', COALESCE(e.middle_initial,'')) as doctor_name")
            )
            ->orderBy('r.id', 'desc')
            ->get()
            ->map(function ($v) {
                $v->visit_notes = $this->cleanHtml($v->visit_notes);
                $v->sym         = $this->cleanHtml($v->sym);
                $v->dia         = $this->cleanHtml($v->dia);
                $v->pres        = $this->cleanHtml($v->pres);
                return $v;
            });

        // حساب العمر من تاريخ الميلاد
        $age = null;
        if ($this->patient->date_of_birth) {
            try {
                $dob = \Carbon\Carbon::createFromFormat('j-n-Y', $this->patient->date_of_birth);
                $age = $dob->age;
            } catch (\Throwable) {}
        }

        return view('livewire.patients.history', ['visits' => $visits, 'age' => $age])
            ->layout('layouts.app');
    }
}
