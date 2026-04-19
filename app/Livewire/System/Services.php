<?php

namespace App\Livewire\System;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use App\Helpers\ActivityLogger;

class Services extends Component
{
    use WithPagination;

    public string $search        = '';
    public string $filterClinic  = '';

    // Modal
    public bool   $showModal  = false;
    public int    $editId     = 0;
    public string $formName   = '';
    public string $formPrice  = '';
    public string $formCost   = '';
    public string $formClinic = '';

    // تأكيد الحذف
    public int $deleteId = 0;

    #[Title('إدارة الخدمات')]

    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingFilterClinic(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->editId     = 0;
        $this->formName   = '';
        $this->formPrice  = '';
        $this->formCost   = '';
        $this->formClinic = '';
        $this->showModal  = true;
    }

    public function openEdit(int $id): void
    {
        $svc = DB::table('service')->where('id', $id)->first();
        if (!$svc) return;

        $this->editId     = $id;
        $this->formName   = $svc->name ?? '';
        $this->formPrice  = (string)($svc->price ?? '');
        $this->formCost   = (string)($svc->cost ?? '');
        $this->formClinic = (string)($svc->clinic_id ?? '');
        $this->showModal  = true;
    }

    public function save(): void
    {
        $this->validate([
            'formName'   => 'required|string|max:200',
            'formPrice'  => 'required|numeric|min:0',
            'formClinic' => 'required|integer',
        ]);

        $data = [
            'name'      => trim($this->formName),
            'price'     => (float) $this->formPrice,
            'cost'      => $this->formCost !== '' ? (float) $this->formCost : 0,
            'clinic_id' => (int) $this->formClinic,
        ];

        $user   = auth()->user();
        $byName = $user ? ($user->getName() ?? $user->user_name ?? '') : '';

        if ($this->editId) {
            DB::table('service')->where('id', $this->editId)->update($data);
            ActivityLogger::log('updated', 'service', $this->editId,
                'تعديل خدمة: ' . $data['name'] . ' — السعر: ' . $data['price']);
        } else {
            $newId = DB::table('service')->insertGetId(array_merge($data, [
                'state_id' => 0,
                'ccode'    => '',
            ]));
            ActivityLogger::log('created', 'service', $newId,
                'إضافة خدمة جديدة: ' . $data['name'] . ' — السعر: ' . $data['price']);
        }

        $this->showModal = false;
        session()->flash('svc_saved', $this->editId ? 'تم تعديل الخدمة' : 'تمت إضافة الخدمة');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
    }

    public function delete(): void
    {
        if (!$this->deleteId) return;
        $svc = DB::table('service')->where('id', $this->deleteId)->first();
        if ($svc) {
            DB::table('service')->where('id', $this->deleteId)->delete();
            ActivityLogger::log('deleted', 'service', $this->deleteId,
                'حذف خدمة: ' . ($svc->name ?? ''));
        }
        $this->deleteId = 0;
        session()->flash('svc_saved', 'تم حذف الخدمة');
    }

    public function render()
    {
        $clinics = DB::table('clinic')->where('state_id', 1)->orderBy('name')->get(['id', 'name']);

        $q = DB::table('service as sv')
            ->leftJoin('clinic as c', 'c.id', '=', 'sv.clinic_id')
            ->select('sv.id', 'sv.name', 'sv.price', 'sv.cost', 'sv.state_id', 'c.name as clinic_name', 'sv.clinic_id');

        if ($this->search) {
            $q->where('sv.name', 'like', '%' . $this->search . '%');
        }
        if ($this->filterClinic) {
            $q->where('sv.clinic_id', $this->filterClinic);
        }

        $services = $q->orderBy('c.name')->orderBy('sv.name')->paginate(25);

        return view('livewire.system.services', compact('clinics', 'services'))->layout('layouts.app');
    }
}
