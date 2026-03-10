<?php

namespace App\Livewire\Patients;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $name           = '';
    public $ssn            = '';
    public $phone          = '';
    public $email          = '';
    public $gender         = 1;
    public $nationality    = 1;
    public $social         = 1;
    public $birth_day      = '';
    public $birth_month    = '';
    public $birth_year     = '';
    public $address        = '';
    public $com_id         = 28;
    public $assur_no       = '';
    public $class_id       = 0;
    public $notes          = '';

    public array $companies = [];

    protected $rules = [
        'name'  => 'required|min:2',
        'phone' => 'required',
        'ssn'   => 'nullable',
    ];

    protected $messages = [
        'name.required'  => 'الاسم مطلوب',
        'name.min'       => 'الاسم يجب أن يكون حرفين على الأقل',
        'phone.required' => 'رقم الجوال مطلوب',
    ];

    #[Title('فتح ملف جديد')]
    public function mount(): void
    {
        $this->companies = DB::table('kcom')->orderBy('id')->get(['id', 'name'])->all();
        if (empty($this->companies)) {
            $this->companies = [(object)['id' => 28, 'name' => 'على نفقته']];
        }
    }

    public function save(): void
    {
        $this->validate();

        $today = now()->format('j-n-Y');

        $dob = '';
        if ($this->birth_day && $this->birth_month && $this->birth_year) {
            $dob = $this->birth_day . '-' . $this->birth_month . '-' . $this->birth_year;
        }

        $newId = DB::table('kstu')->insertGetId([
            'full_name'     => trim($this->name),
            'ssn'           => trim($this->ssn),
            'phone'         => trim($this->phone),
            'email'         => trim($this->email),
            'gender'        => (int) $this->gender,
            'nationality'   => (int) $this->nationality,
            'social'        => (int) $this->social,
            'date_of_birth' => $dob,
            'address1'      => trim($this->address),
            'com_id'        => (int) $this->com_id,
            'assur_no'      => trim($this->assur_no),
            'class_id'      => (int) $this->class_id,
            'notes'         => trim($this->notes),
            'reg_date'      => $today,
            'state'         => 0,
            'com_id1'       => 0,
            'file_id'       => 0,
        ]);

        // Set file_id = id (as legacy system does)
        DB::table('kstu')->where('id', $newId)->update(['file_id' => $newId]);

        session()->flash('success', 'تم فتح الملف بنجاح! رقم الملف: ' . $newId);

        $this->redirect(route('patients.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.patients.create')->layout('layouts.app');
    }
}
