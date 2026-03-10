<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Movements extends Component
{
    // حقول البحث عن العميل
    public string $clientSearch   = '';
    public ?int   $clientId       = null;
    public string $clientName     = '';

    // حقول النموذج
    public string $moveType       = 'receipt'; // receipt = سند قبض, payment = سند صرف
    public string $amount         = '';
    public string $paymentMethod  = '1';
    public string $day            = '';
    public string $month          = '';
    public string $year           = '';
    public string $desc           = '';
    public string $classId        = '';

    // نتائج البحث
    public array  $searchResults  = [];
    public bool   $saved          = false;
    public string $savedMessage   = '';

    const PAYMENT_METHODS = [
        '1'  => ['ar' => 'نقدا',        'en' => 'Cash'],
        '6'  => ['ar' => 'فيزا',        'en' => 'Visa'],
        '3'  => ['ar' => 'شبكة',        'en' => 'Net'],
        '4'  => ['ar' => 'تحويل بنكي',  'en' => 'Bank Transfer'],
        '14' => ['ar' => 'دفع سريع',    'en' => 'Quick pay'],
        '12' => ['ar' => 'stcpay',      'en' => 'stcpay'],
        '11' => ['ar' => 'Myfatoorah',  'en' => 'Myfatoorah'],
        '20' => ['ar' => 'Deema',       'en' => 'Deema'],
        '21' => ['ar' => 'زكاء',        'en' => 'Zakaa'],
        '22' => ['ar' => 'نقل رصيد',    'en' => 'Transfer'],
        '23' => ['ar' => 'Kidding',     'en' => 'Kidding'],
    ];

    #[Title('الحركات المالية')]
    public function mount(): void
    {
        $this->day   = now()->format('j');
        $this->month = now()->format('n');
        $this->year  = now()->format('Y');
    }

    public function updatedClientSearch(): void
    {
        $this->clientId   = null;
        $this->clientName = '';

        if (strlen($this->clientSearch) < 2) {
            $this->searchResults = [];
            return;
        }

        $q = $this->clientSearch;
        $this->searchResults = DB::table('kstu')
            ->where('full_name', 'like', '%' . $q . '%')
            ->orWhere('phone', 'like', '%' . $q . '%')
            ->orWhere('file_id', 'like', '%' . $q . '%')
            ->select('id', 'full_name', 'file_id', 'phone')
            ->limit(8)
            ->get()
            ->toArray();
    }

    public function selectClient(int $id, string $name): void
    {
        $this->clientId      = $id;
        $this->clientName    = $name;
        $this->clientSearch  = $name;
        $this->searchResults = [];
    }

    public function save(): void
    {
        if (!$this->clientId || !$this->amount) return;

        $pdate = "{$this->day}-{$this->month}-{$this->year}";

        $data = [
            'stu_id'  => $this->clientId,
            'pdate'   => $pdate,
            'pdesc'   => $this->desc,
            'ptype'   => $this->moveType === 'receipt' ? 'سند قبض' : 'سند صرف',
            'notes'   => self::PAYMENT_METHODS[$this->paymentMethod]['ar'] ?? '',
            'cost_id' => $this->classId ?: null,
        ];

        if ($this->moveType === 'receipt') {
            $data['credit'] = (float) $this->amount;
            $data['debit']  = 0;
        } else {
            $data['debit']  = (float) $this->amount;
            $data['credit'] = 0;
        }

        DB::table('vouchers')->insert($data);

        $this->savedMessage = 'تم حفظ الحركة بنجاح';
        $this->saved        = true;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->clientSearch  = '';
        $this->clientId      = null;
        $this->clientName    = '';
        $this->moveType      = 'receipt';
        $this->amount        = '';
        $this->paymentMethod = '1';
        $this->day           = now()->format('j');
        $this->month         = now()->format('n');
        $this->year          = now()->format('Y');
        $this->desc          = '';
        $this->classId       = '';
        $this->searchResults = [];
    }

    public function render()
    {
        $classes = DB::table('class')->orderBy('name')->get(['id', 'name']);

        return view('livewire.finance.movements', [
            'classes'        => $classes,
            'paymentMethods' => self::PAYMENT_METHODS,
        ])->layout('layouts.app');
    }
}
