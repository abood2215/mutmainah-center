<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    // ── عيادات الموظف ──
    public ?int   $selectedEmpId   = null;
    public string $selectedEmpName = '';
    public array  $empClinics      = [];

    // ── مودال التعديل ──
    public bool   $showEditModal  = false;
    public ?int   $editId         = null;
    public string $editFirstName  = '';
    public string $editMiddle     = '';
    public string $editEmpNo      = '';
    public string $editPhone      = '';
    public string $editEmail      = '';
    public string $editUserName   = '';
    public string $editState      = '1';
    public string $editPassword   = '';

    // ── مودال الحذف ──
    public bool   $showDeleteModal = false;
    public ?int   $deleteId        = null;
    public string $deleteEmpName   = '';

    #[Title('الموظفين')]

    /* ═══════════ عيادات ═══════════ */

    public function openClinics(int $empId, string $empName): void
    {
        if ($this->selectedEmpId === $empId) {
            $this->selectedEmpId   = null;
            $this->selectedEmpName = '';
            $this->empClinics      = [];
            return;
        }

        $this->selectedEmpId   = $empId;
        $this->selectedEmpName = $empName;

        // العيادات المرتبطة بالموظف مع حالتها الحقيقية من stop_clinic
        $stoppedIds = DB::table('stop_clinic')
            ->where('state_id', 1)
            ->pluck('clinic_id')
            ->toArray();

        $this->empClinics = DB::table('clinic')
            ->where(function ($q) use ($empId) {
                $q->where('doc_id1', $empId)
                  ->orWhere('doc_id2', $empId)
                  ->orWhere('doc_id3', $empId)
                  ->orWhere('doc_id4', $empId)
                  ->orWhere('doc_id5', $empId);
            })
            ->select('id', 'name')
            ->orderBy('id')
            ->get()
            ->map(fn($c) => [
                'id'      => $c->id,
                'name'    => $c->name,
                'active'  => in_array($c->id, $stoppedIds) ? 0 : 1, // 1=مفعلة, 0=موقوفة
            ])
            ->toArray();
    }

    public function toggleClinic(int $clinicId): void
    {
        $stopRow = DB::table('stop_clinic')->where('clinic_id', $clinicId)->first();

        if ($stopRow && $stopRow->state_id == 1) {
            // موقوفة → تفعيل: state_id=0
            DB::table('stop_clinic')->where('clinic_id', $clinicId)->update(['state_id' => 0]);
            $newActive = true;
        } else {
            // مفعلة → إيقاف: state_id=1
            if ($stopRow) {
                DB::table('stop_clinic')->where('clinic_id', $clinicId)->update(['state_id' => 1]);
            } else {
                DB::table('stop_clinic')->insert([
                    'clinic_id' => $clinicId,
                    'user_id'   => auth()->id() ?? 0,
                    'state_id'  => 1,
                ]);
            }
            $newActive = false;
        }

        $this->empClinics = array_map(function ($c) use ($clinicId, $newActive) {
            if ($c['id'] === $clinicId) $c['active'] = $newActive ? 1 : 0;
            return $c;
        }, $this->empClinics);
    }

    /* ═══════════ تعديل ═══════════ */

    public function openEdit(int $empId): void
    {
        $emp = DB::table('employees')->where('id', $empId)->first();
        if (!$emp) return;

        $this->editId        = $empId;
        $this->editFirstName = $emp->first_name ?? '';
        $this->editMiddle    = $emp->middle_initial ?? '';
        $this->editEmpNo     = $emp->emp_no ?? '';
        $this->editPhone     = $emp->phone ?? '';
        $this->editEmail     = $emp->email ?? '';
        $this->editUserName  = $emp->user_name ?? '';
        $this->editState     = (string)($emp->state ?? '1');
        $this->editPassword  = '';
        $this->showEditModal = true;
    }

    public function saveEdit(): void
    {
        if (!$this->editId) return;

        $data = [
            'first_name'     => trim($this->editFirstName),
            'middle_initial' => trim($this->editMiddle),
            'emp_no'         => trim($this->editEmpNo),
            'phone'          => trim($this->editPhone),
            'email'          => trim($this->editEmail),
            'user_name'      => trim($this->editUserName),
            'state'          => (int)$this->editState,
        ];

        if (trim($this->editPassword) !== '') {
            $data['arway'] = md5(trim($this->editPassword));
        }

        DB::table('employees')->where('id', $this->editId)->update($data);
        $this->showEditModal = false;
        $this->editId = null;
    }

    public function closeEdit(): void
    {
        $this->showEditModal = false;
        $this->editId = null;
    }

    /* ═══════════ حذف ═══════════ */

    public function confirmDelete(int $empId, string $empName): void
    {
        $this->deleteId        = $empId;
        $this->deleteEmpName   = $empName;
        $this->showDeleteModal = true;
    }

    public function doDelete(): void
    {
        if (!$this->deleteId) return;
        DB::table('employees')->where('id', $this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->deleteId        = null;
        $this->deleteEmpName   = '';
        $this->resetPage();
    }

    public function closeDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deleteId        = null;
    }

    /* ═══════════ Render ═══════════ */

    public function render()
    {
        $query = DB::table('employees as e')
            ->leftJoin('jops as j', 'j.id', '=', 'e.jop')
            ->leftJoin('qualifications as q', 'q.id', '=', 'e.qualification')
            ->select(
                'e.id',
                'e.emp_no',
                'e.first_name',
                'e.middle_initial',
                'e.phone',
                'e.email',
                'e.state',
                'j.name as job_name',
                'q.name as qual_name'
            );

        if ($this->search) {
            $term = '%' . $this->search . '%';
            $query->where(function ($q) use ($term) {
                $q->where('e.first_name', 'like', $term)
                  ->orWhere('e.middle_initial', 'like', $term)
                  ->orWhere('e.emp_no', 'like', $term)
                  ->orWhere('e.phone', 'like', $term);
            });
        }

        $employees = $query->orderBy('e.id', 'desc')->paginate(10);

        return view('livewire.employees.index', compact('employees'))
            ->layout('layouts.app');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
}
