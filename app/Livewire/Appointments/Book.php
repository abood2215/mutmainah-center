<?php

namespace App\Livewire\Appointments;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use App\Helpers\ActivityLogger;

class Book extends Component
{
    public string $selectedClinic = '';
    public string $selectedDate   = '';
    public string $selectedTime   = '';
    public string $patientSearch  = '';
    public string $notes          = '';
    public ?int   $patientId      = null;
    public        $patient        = null;
    public array  $patientResults = [];
    public array  $clinics        = [];
    public array  $bookedSlots    = [];
    public array  $bookedDetails  = [];

    #[Title('حجز موعد جديد')]
    public function mount(): void
    {
        $this->clinics = DB::table('clinic')->where('state_id', 1)->orderBy('name')->get(['id', 'name'])->all();
        $this->selectedDate = now()->format('Y-m-d');
    }

    public function updatedSelectedClinic(): void
    {
        $this->loadBookedSlots();
        $this->selectedTime = '';
    }

    public function updatedSelectedDate(): void
    {
        $this->loadBookedSlots();
        $this->selectedTime = '';
    }

    public function updatedPatientSearch(): void
    {
        $this->patientId = null;
        $this->patient   = null;

        if (strlen($this->patientSearch) < 2) {
            $this->patientResults = [];
            return;
        }

        $term               = '%' . $this->patientSearch . '%';
        $this->patientResults = DB::table('kstu')
            ->where(function ($q) use ($term) {
                $q->where('full_name', 'like', $term)
                  ->orWhere('phone',    'like', $term)
                  ->orWhere('ssn',      'like', $term)
                  ->orWhere('file_id',  'like', $term);
            })
            ->limit(8)
            ->get(['id', 'full_name', 'phone', 'ssn', 'file_id'])
            ->all();
    }

    public function selectPatient(int $id): void
    {
        $this->patient = DB::table('kstu as k')
            ->leftJoin('kcom as c', 'c.id', '=', 'k.com_id')
            ->where('k.id', $id)
            ->select('k.id', 'k.full_name', 'k.phone', 'k.file_id', 'k.ssn', 'c.name as insurance')
            ->first();

        $this->patientId      = $id;
        $this->patientSearch  = $this->patient->full_name;
        $this->patientResults = [];
    }

    public function clearPatient(): void
    {
        $this->patientId      = null;
        $this->patient        = null;
        $this->patientSearch  = '';
        $this->patientResults = [];
    }

    public function selectTime(string $time): void
    {
        $this->selectedTime = ($this->selectedTime === $time) ? '' : $time;
    }

    private function loadBookedSlots(): void
    {
        if (!$this->selectedClinic || !$this->selectedDate) {
            $this->bookedSlots   = [];
            $this->bookedDetails = [];
            return;
        }

        $dateFormatted = \Carbon\Carbon::parse($this->selectedDate)->format('j-n-Y');

        $rows = DB::table('rec as r')
            ->leftJoin('kstu as k', 'k.id', '=', 'r.st_id')
            ->where('r.clinic_id', $this->selectedClinic)
            ->where('r.rec_date', $dateFormatted)
            ->where('r.confirm_id', 0)
            ->select('r.rec_time', 'k.full_name')
            ->get();

        $normalize = fn(string $t): string => preg_replace_callback(
            '/^(\d{1,2}):(\d{1})$/', fn($m) => $m[1] . ':0' . $m[2], $t
        );

        $this->bookedSlots = $rows->map(fn($r) => $normalize($r->rec_time))->toArray();
        $this->bookedDetails = $rows->mapWithKeys(
            fn($r) => [$normalize($r->rec_time) => $r->full_name]
        )->all();
    }

    public function getTimeSlots(): array
    {
        $slots = [];
        $start = \Carbon\Carbon::createFromTime(8, 0);
        $end   = \Carbon\Carbon::createFromTime(22, 0);

        while ($start <= $end) {
            $slots[] = $start->format('H:i');
            $start->addMinutes(10);
        }

        return $slots;
    }

    public function save(): void
    {
        $this->validate([
            'selectedClinic' => 'required',
            'selectedDate'   => 'required',
            'selectedTime'   => 'required',
            'patientId'      => 'required|integer',
        ], [
            'selectedClinic.required' => 'يرجى اختيار المكتب',
            'selectedDate.required'   => 'يرجى اختيار التاريخ',
            'selectedTime.required'   => 'يرجى اختيار وقت الموعد',
            'patientId.required'      => 'يرجى اختيار العميل',
        ]);

        $dateFormatted = \Carbon\Carbon::parse($this->selectedDate)->format('j-n-Y');

        // منع الحجز المزدوج: نفس العميل + نفس المكتب + نفس الوقت + نفس التاريخ
        $duplicate = DB::table('rec')
            ->where('st_id',    $this->patientId)
            ->where('clinic_id',$this->selectedClinic)
            ->where('rec_date', $dateFormatted)
            ->where('rec_time', $this->selectedTime)
            ->where('confirm_id', 0)
            ->exists();

        if ($duplicate) {
            $this->addError('selectedTime', 'هذا العميل لديه موعد مسبق في نفس المكتب والوقت والتاريخ');
            return;
        }

        DB::table('rec')->insert([
            'rec_date'          => $dateFormatted,
            'rec_time'          => $this->selectedTime,
            'pdate'             => $dateFormatted,
            'st_id'             => $this->patientId,
            'clinic_id'         => $this->selectedClinic,
            'confirm_id'        => 0,
            'state_id'          => 0,
            'service_id'        => 0,
            'new_service_id'    => 0,
            'c_id'              => 0,
            'doc_id'            => 0,
            'pstate_id'         => 0,
            'type_id'           => 0,
            'per_id'            => 0,
            'order_id'          => 0,
            'pharm_id'          => 0,
            'user_id'           => auth()->id() ?? 0,
            'transfer_doc_id'   => 0,
            'rev_id'            => 0,
            'rev_days'          => 0,
            'date_serial'       => 0,
            'call_id'           => 0,
            'dental_id'         => 0,
            'serv_no'           => 0,
            'sym'               => '',
            'dia'               => '',
            'pres'              => '',
            'notes'             => $this->notes,
            'pressure'          => '',
            'heat'              => '',
            'pulse'             => '',
            'diab'              => '',
        ]);

        // تسجيل النشاط
        $clinicName = collect($this->clinics)->where('id', (int)$this->selectedClinic)->first()?->name ?? 'غير محدد';
        ActivityLogger::log(
            'booked', 'appointment', $this->patientId,
            'حجز موعد — المكتب: ' . $clinicName .
            ' — التاريخ: ' . $dateFormatted .
            ' — الوقت: ' . $this->selectedTime
        );

        session()->flash('success', 'تم حجز الموعد بنجاح!');
        $this->redirect(route('appointments.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.appointments.book', [
            'timeSlots' => $this->getTimeSlots(),
        ])->layout('layouts.app');
    }
}
