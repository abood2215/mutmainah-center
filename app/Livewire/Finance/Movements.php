<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Movements extends Component
{
    use WithPagination;

    /* ══ فلاتر البحث ══ */
    public string $filterType    = 'all'; // all | receipt | payment
    public string $fromDate      = '';   // YYYY-MM-DD
    public string $toDate        = '';   // YYYY-MM-DD
    public string $accountSearch = '';
    public bool   $searched      = false;

    /* ══ نموذج إضافة حركة جديدة ══ */
    public bool   $showAddModal     = false;
    public string $newClientSearch  = '';
    public array  $newClientResults = [];
    public ?int   $newClientId      = null;
    public string $newClientName    = '';
    public string $newMoveType      = 'receipt';
    public string $newAmount        = '';
    public string $newPayMethod     = '11';
    public string $newDate          = '';   // YYYY-MM-DD
    public string $newDay           = '';
    public string $newMonth         = '';
    public string $newYear          = '';
    public string $newDesc          = '';

    const PAYMENT_METHODS = [
        '1'  => 'نقدا',
        '3'  => 'شبكة',
        '6'  => 'فيزا',
        '4'  => 'تحويل بنكي',
        '11' => 'Myfatoorah',
        '12' => 'stcpay',
        '14' => 'دفع سريع',
        '20' => 'Deema',
        '21' => 'زكاء',
        '22' => 'نقل رصيد',
    ];

    #[Title('الحركات المالية')]
    public function mount(): void
    {
        $this->fromDate = now()->startOfMonth()->format('Y-m-d');
        $this->toDate   = now()->format('Y-m-d');
        $this->newDate  = now()->format('Y-m-d');
        $this->newDay   = now()->format('j');
        $this->newMonth = now()->format('n');
        $this->newYear  = now()->format('Y');
    }

    /* ══ بحث ══ */
    public function send(): void
    {
        $this->resetPage();
        $this->searched = true;
    }

    public function resetFilters(): void
    {
        $this->searched      = false;
        $this->filterType    = 'all';
        $this->fromDate      = now()->startOfMonth()->format('Y-m-d');
        $this->toDate        = now()->format('Y-m-d');
        $this->accountSearch = '';
        $this->resetPage();
    }

    /* ══ حذف حركة ══ */
    public function deleteMovement(int $id): void
    {
        DB::table('kpayments')->where('id', $id)->delete();
    }

    /* ══ بحث العميل في نموذج الإضافة ══ */
    public function updatedNewClientSearch(): void
    {
        $this->newClientId   = null;
        $this->newClientName = '';

        if (strlen($this->newClientSearch) < 2) {
            $this->newClientResults = [];
            return;
        }

        $q = $this->newClientSearch;
        $this->newClientResults = DB::table('kstu')
            ->where(fn($w) => $w->where('full_name', 'like', "%{$q}%")
                ->orWhere('phone', 'like', "%{$q}%")
                ->orWhere('file_id', 'like', "%{$q}%"))
            ->select('id', 'full_name', 'file_id', 'phone')
            ->limit(8)
            ->orderBy('full_name')
            ->get()
            ->toArray();
    }

    public function selectNewClient(int $id, string $name): void
    {
        $this->newClientId      = $id;
        $this->newClientName    = $name;
        $this->newClientSearch  = $name;
        $this->newClientResults = [];
    }

    /* ══ حفظ الحركة الجديدة ══ */
    public function saveMovement(): void
    {
        if (!$this->newClientId || !$this->newAmount || (float)$this->newAmount <= 0) return;

        // جلب أو إنشاء حساب acck للعميل
        $acck = DB::table('acck')->where('stu_id', $this->newClientId)->first();

        if (!$acck) {
            $patient = DB::table('kstu')->where('id', $this->newClientId)->first();
            $acckId  = DB::table('acck')->insertGetId([
                'name'         => $patient->full_name,
                'pdesc'        => '',
                'parent_id'    => 37,
                'first_id'     => 2,
                'credit_debit' => 3,
                'level_id'     => 2,
                'cat_id'       => 0,
                'stu_id'       => $this->newClientId,
                'branch_id'    => 0,
                'comp_id'      => 0,
                'second_id'    => 37,
                'coms_id'      => 0,
                'client_id'    => 0,
                'importer_id'  => 0,
                'close_id'     => 3,
                'z_id'         => 1,
                'pharm_id'     => 0,
                'clinic_id'    => 0,
                'third_id'     => 0,
                'fourth_id'    => 0,
                'five_id'      => 0,
                'six_id'       => 0,
                'seven_id'     => 0,
                'eight_id'     => 0,
                'account_type' => 0,
            ]);
        } else {
            $acckId = $acck->id;
        }

        // بناء التاريخ من الحقول المنفصلة أو newDate
        $dateObj = ($this->newDay && $this->newMonth && $this->newYear)
            ? \Carbon\Carbon::createFromDate((int)$this->newYear, (int)$this->newMonth, (int)$this->newDay)
            : \Carbon\Carbon::parse($this->newDate);
        $pdate   = $dateObj->format('j-n-Y');
        $amount = round((float)$this->newAmount, 3);
        $userId = Auth::id() ?? 0;
        $isReceipt = $this->newMoveType === 'receipt';

        $newId = DB::table('kpayments')->insertGetId([
            'rec_id'         => 0,
            'pdate'          => $pdate,
            'price'          => 0,
            'amount'         => $isReceipt ? $amount : 0,
            'net'            => $isReceipt ? $amount : 0,
            'credit'         => $isReceipt ? $amount : 0,
            'discount'       => 0,
            'payment_method' => (int)$this->newPayMethod,
            'clinic_id'      => 0,
            'pdesc'          => $this->newDesc,
            'acc_id'         => $acckId,
            'serial_no'      => 0,
            'user_id'        => $userId,
            'type_id'        => $isReceipt ? 1 : 2,
            'client_id'      => 0,
            'res_amount'     => 0,
            'cash_discount'  => 0,
            'ratio_discount' => 0,
            'importer_id'    => 0,
            'c_id'           => 0,
            'p_id'           => 0,
            'bank'           => 0,
            'branch'         => 0,
            'check_no'       => 0,
            'pharm_id'       => 0,
            'status'         => $isReceipt ? 1 : 2,
            'p_amount'       => 0,
            'c_amount'       => 0,
            'com_id'         => 0,
            'dis_id'         => 0,
            'vno'            => 0,
            'ptime'          => now()->format('H:i'),
            'insur_amount'   => 0,
            'pharmacy_id'    => 0,
            'serv_no'        => 0,
            'v_id'           => 0,
            'date_serial'    => 0,
            'interface_id'   => 0,
            'ns'             => 0,
            'clinic_type_id' => 0,
        ]);

        // تحديث vno = id
        DB::table('kpayments')->where('id', $newId)->update(['vno' => $newId]);

        $this->resetAddModal();
        $this->showAddModal = false;
        session()->flash('movement_saved', "تم حفظ سند " . ($isReceipt ? 'القبض' : 'الصرف') . " بنجاح — رقم السند: #{$newId}");
    }

    public function openAddModal(): void
    {
        $this->showAddModal = true;
    }

    public function closeAddModal(): void
    {
        $this->showAddModal = false;
        $this->resetAddModal();
    }

    private function resetAddModal(): void
    {
        $this->newClientSearch  = '';
        $this->newClientResults = [];
        $this->newClientId      = null;
        $this->newClientName    = '';
        $this->newMoveType      = 'receipt';
        $this->newAmount        = '';
        $this->newPayMethod     = '11';
        $this->newDate          = now()->format('Y-m-d');
        $this->newDay           = now()->format('j');
        $this->newMonth         = now()->format('n');
        $this->newYear          = now()->format('Y');
        $this->newDesc          = '';
    }

    /* ══ Render ══ */
    public function render()
    {
        $movements = null;
        $grandTotal = 0;

        if ($this->searched) {
            $from = $this->fromDate ?: now()->startOfMonth()->format('Y-m-d');
            $to   = $this->toDate   ?: now()->format('Y-m-d');

            $query = DB::table('kpayments as k')
                ->join('acck as a', 'a.id', '=', 'k.acc_id')
                ->leftJoin('kstu as s', 's.id', '=', 'a.stu_id')
                ->leftJoin('employees as e', 'e.id', '=', 'k.user_id')
                ->where('k.acc_id', '>', 0)
                ->whereRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') >= ?", [$from])
                ->whereRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') <= ?", [$to]);

            if ($this->filterType === 'receipt') {
                $query->where('k.status', 1);
            } elseif ($this->filterType === 'payment') {
                $query->where('k.status', 2);
            } else {
                $query->whereIn('k.status', [1, 2]);
            }

            if ($this->accountSearch) {
                $term = '%' . $this->accountSearch . '%';
                $query->where('s.full_name', 'like', $term);
            }

            $grandTotal = (clone $query)->sum(
                DB::raw('COALESCE(NULLIF(k.amount,0), NULLIF(k.price,0), 0)')
            );

            $movements = $query
                ->select(
                    'k.id',
                    'k.pdate',
                    'k.pdesc',
                    'k.status',
                    'k.payment_method',
                    DB::raw('COALESCE(NULLIF(k.amount,0), NULLIF(k.price,0), 0) as mov_amount'),
                    's.full_name as patient_name',
                    's.id as patient_id',
                    DB::raw("CONCAT(IFNULL(e.first_name,''), IF(e.middle_initial IS NOT NULL AND e.middle_initial != '', CONCAT(' ', e.middle_initial), '')) as emp_name")
                )
                ->orderByRaw("STR_TO_DATE(k.pdate, '%e-%c-%Y') DESC, k.id DESC")
                ->paginate(20);
        }

        return view('livewire.finance.movements', [
            'movements'  => $movements,
            'grandTotal' => $grandTotal,
            'payMethods' => self::PAYMENT_METHODS,
        ])->layout('layouts.app');
    }
}
