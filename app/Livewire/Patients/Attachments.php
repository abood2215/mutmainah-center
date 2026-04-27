<?php

namespace App\Livewire\Patients;

use App\Helpers\ActivityLogger;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Attachments extends Component
{
    use WithFileUploads;

    public int $patientId;
    public ?object $patient = null;
    public $files = [];
    public $attachments = [];
    public $deleteId = null;

    #[Title('مرفقات العميل')]
    public function mount(int $id): void
    {
        $this->patientId = $id;
        $this->patient   = DB::table('kstu')->where('id', $id)->select('id', 'file_id', 'full_name')->first();
        abort_if(!$this->patient, 404);
        $this->loadAttachments();
    }

    public function loadAttachments(): void
    {
        $this->attachments = DB::table('uploadedfiles')
            ->where('st_id', $this->patientId)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }

    public function saveFiles(): void
    {
        $this->validate([
            'files.*' => 'file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,gif,webp',
        ], [
            'files.*.max'   => 'الحد الأقصى 10MB لكل ملف',
            'files.*.mimes' => 'الأنواع المسموحة: صور، PDF، Word',
        ]);

        foreach ($this->files as $file) {
            $folder   = 'attachments/' . $this->patientId;
            $path     = $file->store($folder, 'public');
            $fileName = basename($file->getClientOriginalName());

            DB::table('uploadedfiles')->insert([
                'st_id'      => $this->patientId,
                'name'       => $fileName,
                'address'    => $path,
                'st_ray_id'  => 0,
                'st_ana_id'  => 0,
                'rec_id'     => 0,
            ]);
        }

        $this->files = [];
        $this->loadAttachments();

        ActivityLogger::log('uploaded', 'attachment', $this->patientId,
            'رفع مرفق للعميل #' . $this->patientId . ' — ' . $this->patient->full_name
        );

        session()->flash('success', 'تم رفع الملفات بنجاح');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
    }

    public function deleteAttachment(): void
    {
        $att = DB::table('uploadedfiles')->where('id', $this->deleteId)->where('st_id', $this->patientId)->first();
        if ($att) {
            Storage::disk('public')->delete($att->address);
            DB::table('uploadedfiles')->where('id', $this->deleteId)->delete();
        }
        $this->deleteId = null;
        $this->loadAttachments();
    }

    public function render()
    {
        return view('livewire.patients.attachments')->layout('layouts.app');
    }
}
