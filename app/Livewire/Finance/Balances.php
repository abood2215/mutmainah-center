<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Balances extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void { $this->resetPage(); }

    #[Title('أرصدة العملاء')]
    public function render()
    {
        $sql = "
            SELECT s.id, s.file_id, s.full_name, s.phone,
                COALESCE(dep.deposited, 0) AS deposited,
                COALESCE(chg.charged, 0)   AS charged_svc,
                COALESCE(deb.debited, 0)   AS charged_old,
                ROUND(COALESCE(dep.deposited,0) - COALESCE(chg.charged,0) - COALESCE(deb.debited,0), 3) AS balance
            FROM kstu s
            INNER JOIN acck ac ON ac.stu_id = s.id
            LEFT JOIN (
                SELECT acc_id, SUM(COALESCE(NULLIF(amount,0), NULLIF(price,0), 0)) AS deposited
                FROM kpayments WHERE status = 1 AND type_id != 2
                GROUP BY acc_id
            ) dep ON dep.acc_id = ac.id
            LEFT JOIN (
                SELECT r.st_id, SUM(GREATEST(COALESCE(p.price,0) - COALESCE(p.discount,0), 0)) AS charged
                FROM kpayments p INNER JOIN rec r ON r.id = p.rec_id
                WHERE p.payment_method = 5
                GROUP BY r.st_id
            ) chg ON chg.st_id = s.id
            LEFT JOIN (
                SELECT acc_id, SUM(COALESCE(NULLIF(amount,0), NULLIF(price,0), 0)) AS debited
                FROM kpayments WHERE (status = 2 AND payment_method != 5) OR (status = 1 AND type_id = 2)
                GROUP BY acc_id
            ) deb ON deb.acc_id = ac.id
        ";

        $query = DB::table(DB::raw("({$sql}) AS pb"))
            ->where('balance', '>', 0)
            ->orderBy('balance', 'desc');

        if ($this->search) {
            $t = '%' . $this->search . '%';
            $query->where(fn($q) => $q->where('full_name', 'like', $t)
                ->orWhere('file_id', 'like', $t)
                ->orWhere('phone', 'like', $t));
        }

        // كاش 5 دقائق للإجمالي عند البحث الفارغ
        $totalBalance = $this->search === ''
            ? Cache::remember('balances_total_v2', 300, fn() => (float)(clone $query)->sum('balance'))
            : (float)(clone $query)->sum('balance');

        $rows = $query->paginate(25);

        return view('livewire.finance.balances', compact('rows', 'totalBalance'))
            ->layout('layouts.app');
    }
}
