<?php

namespace App\Livewire\Clinics;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public ?int    $editingId    = null;
    public string  $editingName  = '';
    public string  $newName      = '';
    public ?int    $confirmDelete = null;
    public ?string $successMsg   = null;
    public ?string $errorMsg     = null;

    #[Title('إدارة المكاتب')]
    public function startEdit(int $id, string $name): void
    {
        $this->editingId   = $id;
        $this->editingName = $name;
        $this->successMsg  = null;
        $this->errorMsg    = null;
    }

    public function cancelEdit(): void
    {
        $this->editingId   = null;
        $this->editingName = '';
    }

    public function saveEdit(): void
    {
        $name = trim($this->editingName);

        if ($name === '') {
            $this->errorMsg = 'اسم المكتب لا يمكن أن يكون فارغاً';
            return;
        }

        DB::table('clinic')->where('id', $this->editingId)->update(['name' => $name]);

        $this->successMsg  = 'تم تحديث اسم المكتب بنجاح';
        $this->editingId   = null;
        $this->editingName = '';
        $this->errorMsg    = null;
    }

    public function addClinic(): void
    {
        $name = trim($this->newName);

        if ($name === '') {
            $this->errorMsg = 'يرجى إدخال اسم المكتب';
            return;
        }

        DB::table('clinic')->insert([
            'name'         => $name,
            'state_id'     => 1,
            'clinic_time'  => 0,
            'doc_id1'      => 0,
            'doc_id2'      => 0,
            'doc_id3'      => 0,
            'doc_id4'      => 0,
            'doc_id5'      => 0,
            'appoint_day'  => 0,
            'type_id'      => 0,
        ]);

        $this->newName    = '';
        $this->successMsg = 'تمت إضافة المكتب بنجاح';
        $this->errorMsg   = null;
    }

    public function askDelete(int $id): void
    {
        $this->confirmDelete = $id;
        $this->successMsg    = null;
        $this->errorMsg      = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmDelete = null;
    }

    public function deleteClinic(): void
    {
        $inUse = DB::table('rec')->where('clinic_id', $this->confirmDelete)->exists();

        if ($inUse) {
            $this->errorMsg      = 'لا يمكن حذف هذه المكتب لأنها مرتبطة بكشوفات مسجلة';
            $this->confirmDelete = null;
            return;
        }

        DB::table('clinic')->where('id', $this->confirmDelete)->delete();
        $this->successMsg    = 'تم حذف المكتب بنجاح';
        $this->confirmDelete = null;
    }

    public function render()
    {
        $clinics = DB::table('clinic')->orderBy('id')->get();

        return view('livewire.clinics.index', [
            'clinics' => $clinics,
        ])->layout('layouts.app');
    }
}
