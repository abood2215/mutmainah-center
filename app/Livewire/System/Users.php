<?php

namespace App\Livewire\System;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

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
    public int     $editBranchId    = 1;

    // ── إضافة مستخدم جديد ──
    public bool    $showAddForm     = false;
    public string  $newFirstName    = '';
    public string  $newMiddleName   = '';
    public string  $newUserName     = '';
    public string  $newPassword     = '';
    public string  $newRole         = 'reception';
    public int     $newBranchId     = 1;

    public ?string $successMsg = null;
    public ?string $errorMsg   = null;

    public function updatingSearch()      { $this->resetPage(); }
    public function updatingFilterState() { $this->resetPage(); }

    /* ═══════════ إضافة مستخدم ═══════════ */

    public function toggleAddForm(): void
    {
        $this->showAddForm = !$this->showAddForm;
        $this->newFirstName = $this->newMiddleName = $this->newUserName = $this->newPassword = '';
        $this->newRole = 'reception';
        $this->newBranchId = 1;
        $this->successMsg = $this->errorMsg = null;
    }

    public function createUser(): void
    {
        abort_if((auth()->user()?->role ?? '') !== 'admin', 403);
        $firstName = trim($this->newFirstName);
        $userName  = trim($this->newUserName);
        $password  = trim($this->newPassword);

        if ($firstName === '') { $this->errorMsg = 'الاسم مطلوب'; return; }
        if ($userName  === '') { $this->errorMsg = 'اسم المستخدم مطلوب'; return; }
        if ($password  === '') { $this->errorMsg = 'كلمة المرور مطلوبة'; return; }

        $exists = DB::table('employees')->where('user_name', $userName)->exists();
        if ($exists) { $this->errorMsg = 'اسم المستخدم مستخدم مسبقاً'; return; }

        $data = [
            'first_name'    => $firstName,
            'middle_initial'=> trim($this->newMiddleName),
            'third_name'    => '',
            'user_name'     => $userName,
            'arway'         => password_hash($password, PASSWORD_BCRYPT),

            'state'         => 1,
            // أعمدة إجبارية بدون قيمة افتراضية
            'jop'           => 0,
            'depart'        => 0,
            'rank'          => 0,
            'ssn'           => '',
            'work_date'     => '',
            'sponsor_name'  => '',
            'contract_no'   => '',
            'emp_no'        => '',
            'doc_rate'      => 0,
            'per'           => 0,
            'inbox_id'      => 0,
            'rate_amount'   => 0,
            'acc_no'        => '',
            'notes'         => '',
            'bank'          => '',
            'max_discount'  => 0,
            'mr_id'         => 0,
        ];

        // أضف role فقط إذا كان العمود موجوداً
        if (Cache::remember('emp_col_role', 86400, fn() => Schema::hasColumn('employees', 'role'))) {
            $data['role'] = in_array($this->newRole, ['admin', 'reception1', 'reception']) ? $this->newRole : 'reception';
        }
        if (Cache::remember('emp_col_branch', 86400, fn() => Schema::hasColumn('employees', 'branch_id'))) {
            $data['branch_id'] = in_array($this->newBranchId, [1, 2]) ? $this->newBranchId : 1;
        }

        DB::table('employees')->insert($data);

        $this->showAddForm  = false;
        $this->newFirstName = $this->newMiddleName = $this->newUserName = $this->newPassword = '';
        $this->newRole      = 'reception';
        $this->newBranchId  = 1;
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
        $this->editBranchId   = (int)($emp->branch_id ?? 1);
        $this->successMsg     = null;
        $this->errorMsg       = null;
    }

    public function cancelEdit(): void { $this->editingId = null; }

    public function saveEdit(): void
    {
        abort_if((auth()->user()?->role ?? '') !== 'admin', 403);
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

        if (Cache::remember('emp_col_role', 86400, fn() => Schema::hasColumn('employees', 'role'))) {
            $data['role'] = in_array($this->editRole, ['admin', 'reception1', 'reception']) ? $this->editRole : 'reception';
        }
        if (Cache::remember('emp_col_branch', 86400, fn() => Schema::hasColumn('employees', 'branch_id'))) {
            $data['branch_id'] = in_array($this->editBranchId, [1, 2]) ? $this->editBranchId : 1;
        }

        if ($this->editPassword !== '') {
            $data['arway'] = password_hash($this->editPassword, PASSWORD_BCRYPT);
        }

        DB::table('employees')->where('id', $this->editingId)->update($data);
        $this->successMsg = 'تم تحديث بيانات المستخدم بنجاح';
        $this->editingId  = null;
        $this->errorMsg   = null;
    }

    /* ═══════════ تفعيل / تعطيل ═══════════ */

    public function toggleState(int $id, int $currentState): void
    {
        abort_if((auth()->user()?->role ?? '') !== 'admin', 403);
        $newState = ($currentState === 1) ? 8 : 1;
        DB::table('employees')->where('id', $id)->update(['state' => $newState]);
        $this->successMsg = $newState === 1 ? 'تم تفعيل المستخدم' : 'تم تعطيل المستخدم';
    }

    /* ═══════════ Render ═══════════ */

    #[Title('إدارة المستخدمين')]
    public function render()
    {
        $hasRole     = Cache::remember('emp_col_role',   86400, fn() => Schema::hasColumn('employees', 'role'));
        $hasBranchId = Cache::remember('emp_col_branch', 86400, fn() => Schema::hasColumn('employees', 'branch_id'));

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
            'users'       => $users,
            'totalActive' => $totalActive,
            'totalAll'    => $totalAll,
            'hasRole'     => $hasRole,
            'hasBranchId' => $hasBranchId,
        ])->layout('layouts.app');
    }
}
