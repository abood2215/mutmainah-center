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

    private function baseQuery()
    {
        return DB::table('kstu as s')
            ->join('acck as ac', 'ac.stu_id', '=', 's.id')
            ->leftJoin(DB::raw('(
                SELECT acc_id,
                    SUM(COALESCE(NULLIF(amount,0), NULLIF(price,0), 0)) as deposited
                FROM kpayments
                WHERE status = 1
                GROUP BY acc_id
            ) as dep'), 'dep.acc_id', '=', 'ac.id')
            ->leftJoin(DB::raw('(
                SELECT r.st_id,
                    SUM(GREATEST(COALESCE(p.price,0) - COALESCE(p.discount,0), 0)) as charged
                FROM kpayments p
                INNER JOIN rec r ON r.id = p.rec_id
                WHERE p.payment_method = 5
                GROUP BY r.st_id
            ) as chg'), 'chg.st_id', '=', 's.id')
            ->select(
                's.id', 's.file_id', 's.full_name', 's.phone',
                DB::raw('COALESCE(dep.deposited, 0) as deposited'),
                DB::raw('COALESCE(chg.charged, 0) as charged'),
                DB::raw('ROUND(COALESCE(dep.deposited, 0) - COALESCE(chg.charged, 0), 3) as balance')
            )
            ->havingRaw('balance > 0');
    }

    #[Title('أرصدة العملاء')]
    public function render()
    {
        $query = $this->baseQuery()->orderBy('balance', 'desc');

        if ($this->search) {
            $t = '%' . $this->search . '%';
            $query->where(fn($q) => $q->where('s.full_name', 'like', $t)
                ->orWhere('s.file_id', 'like', $t)
                ->orWhere('s.phone', 'like', $t));
        }

        $rows         = $query->paginate(25);
        $totalBalance = round((float) $this->baseQuery()->sum(DB::raw('COALESCE(dep.deposited,0) - COALESCE(chg.charged,0)')), 3);

        return view('livewire.finance.balances', compact('rows', 'totalBalance'))
            ->layout('layouts.app');
    }
}
