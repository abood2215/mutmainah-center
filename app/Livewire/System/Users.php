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

    // ── تعديل ──
    public ?int    $editingId       = null;
    public string  $editFirstName   = '';
    public string  $editMiddleName  = '';
    public string  $editUserName    = '';
    public string  $editPassword    = '';
    public string  $editRole        = '';

    // ── إضافة مستخدم جديد ──
    public bool    $showAddForm     = false;
    public string  $newFirstName    = '';
    public string  $newMiddleName   = '';
    public string  $newUserName     = '';
    public string  $newPassword     = '';
    public string  $newRole         = 'reception';

    public ?string $successMsg    = null;
    public ?string $errorMsg      = null;
    public bool    $confirmDisableAll = false;

    public function updatingSearch()      { $this->resetPage(); }
    public function updatingFilterState() { $this->resetPage(); }

    /* ═══════════ إضافة مستخدم ═══════════ */

    public function toggleAddForm(): void
    {
        $this->showAddForm = !$this->showAddForm;
        $this->newFirstName = $this->newMiddleName = $this->newUserName = $this->newPassword = '';
        $this->newRole = 'reception';
        $this->successMsg = $this->errorMsg = null;
    }

    public function createUser(): void
    {
        $firstName = trim($this->newFirstName);
        $userName  = trim($this->newUserName);
        $password  = trim($this->newPassword);

        if ($firstName === '') { $this->errorMsg = 'الاسم مطلوب'; return; }
        if ($userName  === '') { $this->errorMsg = 'اسم المستخدم مطلوب'; return; }
        if ($password  === '') { $this->errorMsg = 'كلمة المرور مطلوبة'; return; }
        if (!in_array($this->newRole, ['admin', 'reception'])) { $this->errorMsg = 'الصلاحية غير صحيحة'; return; }

        // تحقق من عدم تكرار اسم المستخدم
        $exists = DB::table('employees')->where('user_name', $userName)->exists();
        if ($exists) { $this->errorMsg = 'اسم المستخدم مستخدم مسبقاً'; return; }

        DB::table('employees')->insert([
            'first_name'     => $firstName,
            'middle_initial' => trim($this->newMiddleName),
            'user_name'      => $userName,
            'arway'          => md5($password),
            'state'          => 1,
            'role'           => $this->newRole,
        ]);

        $this->showAddForm  = false;
        $this->newFirstName = $this->newMiddleName = $this->newUserName = $this->newPassword = '';
        $this->newRole = 'reception';
        $this->errorMsg  = null;
        $this->successMsg = 'تم إنشاء المستخدم بنجاح ✓';
    }

    /* ═══════════ تعديل ═══════════ */

    public function startEdit(int $id): void
    {
        $emp = DB::table('employees')->where('id', $id)->first();
        $this->editingId      = $id;
        $this->editFirstName  = $emp->first_name;
        $this->editMiddleName = $emp->middle_initial;
        $this->editUserName   = $emp->user_name;
        $this->editPassword   = '';
        $this->editRole       = $emp->role ?? '';
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
            'role'           => $this->editRole ?: null,
        ];

        if ($this->editPassword !== '') {
            $data['arway'] = md5($this->editPassword);
        }

        DB::table('employees')->where('id', $this->editingId)->update($data);

        $this->successMsg = 'تم تحديث بيانات المستخدم بنجاح';
        $this->editingId  = null;
        $this->errorMsg   = null;
    }

    /* ═══════════ تفعيل / تعطيل ═══════════ */

    public function toggleState(int $id, int $currentState): void
    {
        $newState = ($currentState === 1) ? 8 : 1;
        DB::table('employees')->where('id', $id)->update(['state' => $newState]);
        $this->successMsg = $newState === 1 ? 'تم تفعيل المستخدم' : 'تم تعطيل المستخدم';
    }

    public function confirmDisableAll(): void  { $this->confirmDisableAll = true; }
    public function cancelDisableAll(): void   { $this->confirmDisableAll = false; }

    public function disableAllExceptMe(): void
    {
        $myId = auth()->user()->getAuthIdentifier();
        DB::table('employees')->where('id', '!=', $myId)->update(['state' => 8]);
        $this->confirmDisableAll = false;
        $this->successMsg = 'تم تعطيل جميع المستخدمين ما عدا حسابك';
    }

    /* ═══════════ Render ═══════════ */

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

        $users       = $query->orderByRaw('state = 1 DESC')->orderBy('id')->paginate(20);
        $totalActive = DB::table('employees')->where('state', 1)->count();
        $totalAll    = DB::table('employees')->count();

        return view('livewire.system.users', [
            'users'        => $users,
            'totalActive'  => $totalActive,
            'totalAll'     => $totalAll,
        ])->layout('layouts.app');
    }
}
