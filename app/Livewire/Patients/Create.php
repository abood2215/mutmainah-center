<?php

namespace App\Livewire\Patients;

use App\Data\Countries;
use App\Helpers\ActivityLogger;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $name           = '';
    public $ssn            = '';
    public $phone          = '';
    public $phone_code     = '+965';
    public $email          = '';
    public $gender         = 1;
    public $nationality    = 'كويتي/ة';
    public $social         = 1;
    public $birth_day      = '';
    public $birth_month    = '';
    public $birth_year     = '';
    public $address        = '';
    public $com_id         = 28;
    public $assur_no       = '';
    public $class_id       = 0;
    public $notes          = '';
    public int $branch_id  = 0;

    public array $companies = [];
    public array $branches  = [];

    protected $rules = [
        'name'      => 'required|min:2',
        'phone'     => 'required',
        'ssn'       => 'nullable',
        'branch_id' => 'required|integer|min:1',
    ];

    protected $messages = [
        'name.required'      => 'الاسم مطلوب',
        'name.min'           => 'الاسم يجب أن يكون حرفين على الأقل',
        'phone.required'     => 'رقم الجوال مطلوب',
        'branch_id.min'      => 'يجب اختيار الفرع',
        'branch_id.required' => 'يجب اختيار الفرع',
    ];

    #[Title('فتح ملف جديد')]
    public function mount(): void
    {
        $this->companies = DB::table('kcom')->orderBy('id')->get(['id', 'name'])->all();
        if (empty($this->companies)) {
            $this->companies = [(object)['id' => 28, 'name' => 'على نفقته']];
        }
        $this->branches = DB::table('branches')->where('is_active', 1)->get(['id', 'name'])->all();
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
            'branch_id'     => (int) $this->branch_id,
            'full_name'     => trim($this->name),
            'ssn'           => trim($this->ssn),
            'phone'         => trim($this->phone_code) . trim($this->phone),
            'email'         => trim($this->email),
            'gender'        => (int) $this->gender,
            'nationality'   => trim($this->nationality),
            'social'        => (int) $this->social,
            'date_of_birth' => $dob,
            'address1'      => trim($this->address),
            'com_id'        => (int) $this->com_id,
            'assur_no'      => trim($this->assur_no),
            'class_id'      => (int) $this->class_id,
            'notes'         => trim($this->notes),
            'reg_date'      => $today,
            'assur_date'    => '',
            'state'         => 0,
            'com_id1'       => 0,
            'file_id'       => 0,
            'bg_id'         => '',
            'weight'        => 0,
            'height'        => 0,
            'RH'            => '',
            'smoke'         => '',
            'drug'          => '',
            'chronic'       => '',
            'heir'          => '',
            'pno'           => '',
            'surg'          => '',
            'allerg'        => '',
            'f_history'     => '',
            'pnotes'        => '',
            'evalu'         => '',
            'recomn'        => '',
            'fut_plan'      => '',
        ]);

        // Set file_id = id (as legacy system does)
        DB::table('kstu')->where('id', $newId)->update(['file_id' => $newId]);

        $branchName = collect($this->branches)->firstWhere('id', $this->branch_id)->name ?? '';
        ActivityLogger::log('created', 'patient', $newId, 'فتح ملف جديد: ' . trim($this->name) . ' — رقم الملف #' . $newId . ' — ' . $branchName);

        session()->flash('success', 'تم فتح الملف بنجاح! رقم الملف: ' . $newId);

        $this->redirect(route('patients.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.patients.create', [
            'countryCodes'  => Countries::forDropdown(),
            'nationalities' => Countries::nationalities(),
        ])->layout('layouts.app');
    }
}
