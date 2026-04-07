<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Reprint extends Component
{
    public string  $voucherNo = '';
    public ?object $voucher   = null;
    public ?string $error     = null;
    public ?string $refNo     = null;
    public ?string $descClean = null;

    public array $payMethods = [
        1  => 'Cash',         3  => 'K-Net',
        6  => 'Visa',         4  => 'Bank Transfer',
        11 => 'MyFatoorah',   12 => 'STC Pay',
        14 => 'Quick Pay',    20 => 'Deema',
        21 => 'Zakaa',        22 => 'Balance Transfer',
    ];

    public function search(): void
    {
        $this->error   = null;
        $this->voucher = null;
        $no = trim($this->voucherNo);

        if ($no === '') {
            $this->error = 'أدخل رقم السند';
            return;
        }

        $id = ltrim($no, '#');

        if (!is_numeric($id)) {
            $this->error = 'رقم السند غير صحيح';
            return;
        }

        $mov = DB::table('kpayments as k')
            ->join('acck as a', 'a.id', '=', 'k.acc_id')
            ->leftJoin('kstu as s', 's.id', '=', 'a.stu_id')
            ->leftJoin('employees as e', 'e.id', '=', 'k.user_id')
            ->where('k.id', (int)$id)
            ->where('k.acc_id', '>', 0)
            ->whereIn('k.status', [1, 2])
            ->select(
                'k.id', 'k.pdate', 'k.ptime', 'k.pdesc', 'k.status', 'k.payment_method',
                DB::raw('COALESCE(NULLIF(k.amount,0), NULLIF(k.price,0), 0) as mov_amount'),
                's.full_name as patient_name',
                's.file_id as patient_file',
                's.phone as patient_phone',
                'a.name as acck_name',
                DB::raw("TRIM(CONCAT(IFNULL(e.first_name,''), ' ', IFNULL(e.middle_initial,''))) as emp_name")
            )
            ->first();

        if (!$mov) {
            $this->error = "لم يُوجد سند بالرقم #{$id}";
            return;
        }

        $desc = $mov->pdesc ?? '';
        $hasRef = str_contains($desc, '| Ref:');
        $this->refNo     = $hasRef ? trim(explode('| Ref:', $desc, 2)[1]) : null;
        $this->descClean = $hasRef ? trim(explode('| Ref:', $desc, 2)[0]) : trim($desc);
        $this->voucher   = $mov;
    }

    #[Title('إعادة طباعة سند')]
    public function render()
    {
        return view('livewire.finance.reprint')->layout('layouts.app');
    }
}
