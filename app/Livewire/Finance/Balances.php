<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Balances extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void { $this->resetPage(); }

    #[Title('أرصدة العملاء')]
    public function render()
    {
        $query = DB::table(DB::raw("(
            SELECT s.id, s.file_id, s.full_name, s.phone,
                COALESCE((
                    SELECT SUM(COALESCE(NULLIF(p2.amount,0), NULLIF(p2.price,0), 0))
                    FROM kpayments p2
                    WHERE p2.acc_id = ac.id AND p2.status = 1
                ), 0) AS deposited,
                COALESCE((
                    SELECT SUM(GREATEST(COALESCE(p3.price,0) - COALESCE(p3.discount,0), 0))
                    FROM kpayments p3
                    INNER JOIN rec r3 ON r3.id = p3.rec_id
                    WHERE r3.st_id = s.id AND p3.payment_method = 5
                ), 0) AS charged
            FROM kstu s
            INNER JOIN acck ac ON ac.stu_id = s.id
        ) as pb"))
            ->selectRaw('*, ROUND(deposited - charged, 3) as balance')
            ->havingRaw('ROUND(deposited - charged, 3) > 0')
            ->orderByRaw('ROUND(deposited - charged, 3) DESC');

        if ($this->search) {
            $t = '%' . $this->search . '%';
            $query->where(fn($q) => $q->where('full_name', 'like', $t)
                ->orWhere('file_id', 'like', $t)
                ->orWhere('phone', 'like', $t));
        }

        $rows         = $query->paginate(25);
        $totalBalance = DB::table(DB::raw("(
            SELECT s.id,
                COALESCE((SELECT SUM(COALESCE(NULLIF(p2.amount,0),NULLIF(p2.price,0),0)) FROM kpayments p2 WHERE p2.acc_id=ac.id AND p2.status=1),0)
                - COALESCE((SELECT SUM(GREATEST(COALESCE(p3.price,0)-COALESCE(p3.discount,0),0)) FROM kpayments p3 INNER JOIN rec r3 ON r3.id=p3.rec_id WHERE r3.st_id=s.id AND p3.payment_method=5),0) as balance
            FROM kstu s INNER JOIN acck ac ON ac.stu_id=s.id
        ) as t"))->where('balance', '>', 0)->sum('balance');

        return view('livewire.finance.balances', compact('rows', 'totalBalance'))
            ->layout('layouts.app');
    }
}
