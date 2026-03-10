<?php

namespace App\Livewire\Patients;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class NewCheck extends Component
{
    public $patientId;
    public $patient;
    public array $clinics  = [];
    public array $services = [];
    public string $selectedClinic  = '';
    public string $selectedService = '';
    public float  $price       = 0;
    public string $notes       = '';
    public string $paymentMethod = '1';

    #[Title('كشف جديد')]
    public function mount($id): void
    {
        $this->patientId = $id;

        $this->patient = DB::table('kstu as k')
            ->leftJoin('kcom as c', 'c.id', '=', 'k.com_id')
            ->where('k.id', $id)
            ->select('k.id', 'k.full_name', 'k.phone', 'k.file_id', 'k.ssn', 'c.name as insurance')
            ->first();

        abort_if(!$this->patient, 404);

        $this->clinics  = DB::table('clinic')->orderBy('name')->get(['id', 'name'])->all();
        $this->services = DB::table('ana')->limit(100)->get(['id', 'name', 'price', 'ccode'])->all();
    }

    public function updatedSelectedClinic(string $value): void
    {
        $query = DB::table('ana');
        if ($value) {
            $query->where(fn ($q) => $q->where('clinic_id', $value)->orWhereNull('clinic_id'));
        }
        $this->services        = $query->limit(100)->get(['id', 'name', 'price', 'ccode'])->all();
        $this->selectedService = '';
        $this->price           = 0;
    }

    public function updatedSelectedService(string $value): void
    {
        if ($value) {
            $service     = DB::table('ana')->where('id', $value)->first();
            $this->price = $service ? (float) $service->price : 0;
        }
    }

    public function save(): void
    {
        $today = now()->format('j-n-Y');

        $recId = DB::table('rec')->insertGetId([
            'rec_date'   => $today,
            'rec_time'   => now()->format('H:i'),
            'st_id'      => $this->patientId,
            'clinic_id'  => $this->selectedClinic ?: null,
            'confirm_id' => 1,
            'state_id'   => 0,
            'notes'      => $this->notes ?: null,
        ]);

        if ($this->price > 0 && $this->selectedService) {
            DB::table('kpayments')->insert([
                'rec_id'         => $recId,
                'pdate'          => $today,
                'price'          => $this->price,
                'payment_method' => (int) $this->paymentMethod,
                'clinic_id'      => $this->selectedClinic ?: null,
                'pdesc'          => $this->notes ?: null,
                'acc_id'         => 0,
            ]);
        }

        session()->flash('success', 'تم تسجيل الكشف بنجاح!');
        $this->redirect(route('checks.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.patients.new-check')->layout('layouts.app');
    }
}
