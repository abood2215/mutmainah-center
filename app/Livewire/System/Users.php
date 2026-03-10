<?php

namespace App\Livewire\System;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Users extends Component
{
    use WithPagination;

    public string  $search      = '';
    public string  $filterState = '';

    public ?int    $editingId       = null;
    public string  $editFirstName   = '';
    public string  $editMiddleName  = '';
    public string  $editUserName    = '';
    public string  $editPassword    = '';

    public ?string $successMsg    = null;
    public ?string $errorMsg      = null;
    public bool    $confirmDisableAll = false;

    public function updatingSearch()      { $this->resetPage(); }
    public function updatingFilterState() { $this->resetPage(); }

    public function startEdit(int $id): void
    {
        $emp = DB::table('employees')->where('id', $id)->first();
        $this->editingId      = $id;
        $this->editFirstName  = $emp->first_name;
        $this->editMiddleName = $emp->middle_initial;
        $this->editUserName   = $emp->user_name;
        $this->editPassword   = '';
        $this->successMsg     = null;
        $this->errorMsg       = null;
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
    }

    public function saveEdit(): void
    {
        $firstName = trim($this->editFirstName);
        $userName  = trim($this->editUserName);

        if ($firstName === '' || $userName === '') {
            $this->errorMsg = 'الاسم واسم المستخدم مطلوبان';
            return;
        }

        $data = [
            'first_name'     => $firstName,
            'middle_initial' => trim($this->editMiddleName),
            'user_name'      => $userName,
        ];

        if ($this->editPassword !== '') {
            $data['arway'] = md5($this->editPassword);
        }

        DB::table('employees')->where('id', $this->editingId)->update($data);

        $this->successMsg = 'تم تحديث بيانات المستخدم بنجاح';
        $this->editingId  = null;
        $this->errorMsg   = null;
    }

    public function toggleState(int $id, int $currentState): void
    {
        $newState = ($currentState === 1) ? 8 : 1;
        DB::table('employees')->where('id', $id)->update(['state' => $newState]);
        $this->successMsg = $newState === 1 ? 'تم تفعيل المستخدم' : 'تم تعطيل المستخدم';
    }

    public function confirmDisableAll(): void
    {
        $this->confirmDisableAll = true;
    }

    public function cancelDisableAll(): void
    {
        $this->confirmDisableAll = false;
    }

    public function disableAllExceptMe(): void
    {
        $myId = auth()->user()->getAuthIdentifier();

        DB::table('employees')
            ->where('id', '!=', $myId)
            ->update(['state' => 8]);

        $this->confirmDisableAll = false;
        $this->successMsg = 'تم تعطيل جميع المستخدمين ما عدا حسابك';
    }

    #[Title('إدارة المستخدمين')]
    public function render()
    {
        $query = DB::table('employees');

        if ($this->search) {
            $term = '%' . $this->search . '%';
            $query->where(function ($q) use ($term) {
                $q->where('first_name',     'like', $term)
                  ->orWhere('middle_initial','like', $term)
                  ->orWhere('user_name',     'like', $term);
            });
        }

        if ($this->filterState === 'active') {
            $query->where('state', 1);
        } elseif ($this->filterState === 'inactive') {
            $query->where('state', '!=', 1);
        }

        $users      = $query->orderByRaw('state = 1 DESC')->orderBy('id')->paginate(20);
        $totalActive = DB::table('employees')->where('state', 1)->count();
        $totalAll    = DB::table('employees')->count();

        return view('livewire.system.users', [
            'users'        => $users,
            'totalActive'  => $totalActive,
            'totalAll'     => $totalAll,
        ])->layout('layouts.app');
    }
}
