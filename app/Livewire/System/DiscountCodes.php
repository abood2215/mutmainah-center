<?php

namespace App\Livewire\System;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class DiscountCodes extends Component
{
    #[Title('كودات الخصم')]

    // قائمة
    public string $search   = '';
    public string $filter   = 'all'; // all | active | expired

    // فورم
    public bool   $showForm  = false;
    public ?int   $editId    = null;
    public string $code      = '';
    public string $type      = 'percent';
    public string $value     = '';
    public string $expiresAt = '';
    public string $maxUses   = '0';
    public string $clinicId  = '0';
    public string $minAmount = '0';
    public string $notes     = '';
    public int    $isActive  = 1;

    // تأكيد الحذف
    public ?int $confirmDelete = null;

    // رسائل
    public string $successMsg = '';
    public string $errorMsg   = '';

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editId   = null;
    }

    public function openEdit(int $id): void
    {
        $row = DB::table('discount_codes')->find($id);
        if (!$row) return;

        $this->editId    = $id;
        $this->code      = $row->code;
        $this->type      = $row->type;
        $this->value     = (string) $row->value;
        $this->expiresAt = $row->expires_at ?? '';
        $this->maxUses   = (string) $row->max_uses;
        $this->clinicId  = (string) $row->clinic_id;
        $this->minAmount = (string) $row->min_amount;
        $this->notes     = $row->notes ?? '';
        $this->isActive  = (int) $row->is_active;
        $this->showForm  = true;
    }

    public function save(): void
    {
        $this->errorMsg = '';

        // التحقق
        $this->code  = strtoupper(trim($this->code));
        if (!$this->code)  { $this->errorMsg = 'الكود مطلوب'; return; }
        if (!$this->value || (float)$this->value <= 0) { $this->errorMsg = 'القيمة يجب أن تكون أكبر من صفر'; return; }
        if ($this->type === 'percent' && (float)$this->value > 100) { $this->errorMsg = 'النسبة لا تتجاوز 100%'; return; }

        // تحقق من تكرار الكود
        $existing = DB::table('discount_codes')->where('code', $this->code)
            ->when($this->editId, fn($q) => $q->where('id', '!=', $this->editId))
            ->exists();
        if ($existing) { $this->errorMsg = 'الكود موجود مسبقاً'; return; }

        $data = [
            'code'       => $this->code,
            'type'       => $this->type,
            'value'      => (float)$this->value,
            'expires_at' => $this->expiresAt ?: null,
            'max_uses'   => (int)$this->maxUses,
            'clinic_id'  => (int)$this->clinicId,
            'min_amount' => (float)$this->minAmount,
            'notes'      => trim($this->notes) ?: null,
            'is_active'  => $this->isActive,
        ];

        if ($this->editId) {
            DB::table('discount_codes')->where('id', $this->editId)->update($data);
            $this->successMsg = 'تم تحديث الكود بنجاح';
        } else {
            $data['used_count'] = 0;
            DB::table('discount_codes')->insert($data);
            $this->successMsg = 'تم إضافة الكود بنجاح';
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function toggleActive(int $id): void
    {
        $row = DB::table('discount_codes')->find($id);
        if (!$row) return;
        DB::table('discount_codes')->where('id', $id)->update(['is_active' => $row->is_active ? 0 : 1]);
    }

    public function deleteConfirm(int $id): void
    {
        $this->confirmDelete = $id;
    }

    public function deleteCancel(): void
    {
        $this->confirmDelete = null;
    }

    public function delete(): void
    {
        if (!$this->confirmDelete) return;
        DB::table('discount_codes')->where('id', $this->confirmDelete)->delete();
        $this->confirmDelete = null;
        $this->successMsg = 'تم حذف الكود';
    }

    public function closeForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->code      = '';
        $this->type      = 'percent';
        $this->value     = '';
        $this->expiresAt = '';
        $this->maxUses   = '0';
        $this->clinicId  = '0';
        $this->minAmount = '0';
        $this->notes     = '';
        $this->isActive  = 1;
        $this->editId    = null;
        $this->errorMsg  = '';
    }

    public function render()
    {
        $today = now()->toDateString();

        $query = DB::table('discount_codes')->orderBy('id', 'desc');

        if ($this->search) {
            $query->where(fn($q) => $q->where('code', 'like', '%'.$this->search.'%')
                ->orWhere('notes', 'like', '%'.$this->search.'%'));
        }

        if ($this->filter === 'active') {
            $query->where('is_active', 1)
                ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', $today));
        } elseif ($this->filter === 'expired') {
            $query->where(fn($q) => $q->where('is_active', 0)
                ->orWhere(fn($q2) => $q2->whereNotNull('expires_at')->where('expires_at', '<', $today)));
        }

        $codes   = $query->get();
        $clinics = DB::table('clinic')->where('state_id', 1)->orderBy('name')->get(['id', 'name']);

        return view('livewire.system.discount-codes', compact('codes', 'clinics', 'today'))
            ->layout('layouts.app');
    }
}
