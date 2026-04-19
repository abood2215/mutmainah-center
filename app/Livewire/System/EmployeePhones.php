<?php

namespace App\Livewire\System;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class EmployeePhones extends Component
{
    use WithPagination;

    public $search = '';
    public $filterMissing = false;
    public ?string $successMsg = null;

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterMissing(): void { $this->resetPage(); }

    /**
     * تحرير رقم الموظف
     */
    public function editPhone(int $empId, string $newPhone): void
    {
        $newPhone = trim($newPhone);
        
        if (empty($newPhone)) {
            return;
        }

        // تنظيف الرقم - إزالة الأحرف غير الرقمية
        $cleanPhone = preg_replace('/[^0-9]/', '', $newPhone);
        
        if (empty($cleanPhone)) {
            return;
        }

        DB::table('employees')->where('id', $empId)->update([
            'phone' => $cleanPhone,
            'updated_at' => now(),
        ]);

        $this->successMsg = 'تم تحديث رقم الهاتف بنجاح ✓';
        session()->flash('phone_updated', true);
    }

    /**
     * حذف رقم الموظف
     */
    public function deletePhone(int $empId): void
    {
        DB::table('employees')->where('id', $empId)->update([
            'phone' => '',
            'updated_at' => now(),
        ]);

        $this->successMsg = 'تم حذف رقم الهاتف';
    }

    public function render()
    {
        $query = DB::table('employees')
            ->select('id', 'first_name', 'middle_initial', 'last_name', 'phone', 'emp_no', 'state', 'role');

        // البحث
        if (!empty($this->search)) {
            $term = '%' . $this->search . '%';
            $query->where(DB::raw("CONCAT(first_name, ' ', middle_initial, ' ', last_name)"), 'like', $term)
                  ->orWhere('phone', 'like', $term)
                  ->orWhere('emp_no', 'like', $term);
        }

        // تصفية الموظفين بدون أرقام
        if ($this->filterMissing) {
            $query->where(function ($q) {
                $q->whereNull('phone')
                  ->orWhere('phone', '')
                  ->orWhere('phone', '—');
            });
        }

        // فقط الموظفين النشطين
        $query->where('state', 1);

        $employees = $query->orderBy('first_name')->paginate(20);

        return view('livewire.system.employee-phones', [
            'employees' => $employees,
        ]);
    }
}
