<?php

namespace App\Livewire\Appointments;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterDate = '';
    public $selectedClinic = '';

    public bool  $showTodayModal = false;
    public array $todayList      = [];

    // تعديل الموعد
    public bool   $showEditModal = false;
    public int    $editId        = 0;
    public int    $editPatientId = 0;
    public string $editPatientName = '';
    public string $editDate      = '';
    public string $editTime      = '';
    public string $editClinic    = '';

    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingFilterDate(): void   { $this->resetPage(); }
    public function updatingSelectedClinic(): void { $this->resetPage(); }

    public function openTodayModal(): void
    {
        $today = now()->format('j-n-Y');

        // تحديث تلقائي: إذا كان العميل أخذ كشفه اليوم → state_id=1
        DB::statement("
            UPDATE rec r1
            INNER JOIN rec r2 ON r2.st_id = r1.st_id
                AND r2.rec_date = ? AND r2.confirm_id = 1 AND r2.state_id = 1
            SET r1.state_id = 1
            WHERE r1.rec_date = ? AND r1.confirm_id = 0 AND r1.state_id = 0
        ", [$today, $today]);

        $this->todayList = DB::table('rec as r')
            ->leftJoin('kstu as k', 'k.id', '=', 'r.st_id')
            ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
            ->where('r.rec_date', $today)
            ->where('r.confirm_id', 0)
            ->select('r.id', 'r.rec_time', 'r.state_id', 'r.st_id',
                     'k.full_name', 'k.phone', 'k.file_id',
                     'c.name as clinic_name')
            ->orderBy('r.rec_time')
            ->get()
            ->toArray();

        $this->showTodayModal = true;
    }

    public function closeTodayModal(): void
    {
        $this->showTodayModal = false;
    }

    public function markChecked(int $recId): void
    {
        DB::table('rec')->where('id', $recId)->where('confirm_id', 0)->update(['confirm_id' => 1]);
    }

    public function openEdit(int $recId): void
    {
        $rec = DB::table('rec as r')
            ->leftJoin('kstu as k', 'k.id', '=', 'r.st_id')
            ->where('r.id', $recId)
            ->select('r.*', 'k.full_name')
            ->first();
        if (!$rec) return;

        $this->editId          = $recId;
        $this->editPatientId   = $rec->st_id;
        $this->editPatientName = $rec->full_name ?? '';
        $this->editDate        = $rec->rec_date
            ? \Carbon\Carbon::createFromFormat('j-n-Y', $rec->rec_date)->format('Y-m-d')
            : '';
        $this->editTime        = $rec->rec_time ?? '';
        $this->editClinic      = $rec->clinic_id ?? '';
        $this->showEditModal   = true;
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editDate'   => 'required',
            'editTime'   => 'required',
            'editClinic' => 'required',
        ]);

        $oldRec = DB::table('rec')->where('id', $this->editId)->first();
        if (!$oldRec) { $this->showEditModal = false; return; }

        $newDate = \Carbon\Carbon::parse($this->editDate)->format('j-n-Y');

        DB::table('rec')->where('id', $this->editId)->update([
            'rec_date'  => $newDate,
            'rec_time'  => $this->editTime,
            'clinic_id' => $this->editClinic,
        ]);

        $user    = auth()->user();
        $byName  = $user ? ($user->getName() ?? $user->user_name ?? 'غير معروف') : 'غير معروف';
        $clinic  = DB::table('clinic')->where('id', $this->editClinic)->value('name') ?? '';

        try {
            DB::table('activity_logs')->insert([
                'action'      => 'updated',
                'subject'     => 'appointment',
                'subject_id'  => $this->editPatientId,
                'description' => 'تعديل موعد: ' . ($oldRec->rec_date ?? '') . ' → ' . $newDate
                               . ' | ' . ($oldRec->rec_time ?? '') . ' → ' . $this->editTime
                               . ' | عيادة: ' . $clinic,
                'user_id'     => $user?->id ?? 0,
                'user_name'   => $byName,
                'created_at'  => now(),
            ]);
        } catch (\Throwable) {}

        $this->showEditModal = false;
        session()->flash('appt_saved', 'تم تعديل الموعد بنجاح');
    }

    public function deleteAppointment(int $recId): void
    {
        $rec = DB::table('rec')->where('id', $recId)->where('confirm_id', 0)->first();
        if (!$rec) return;

        $user    = auth()->user();
        $byName  = $user ? ($user->getName() ?? $user->user_name ?? 'غير معروف') : 'غير معروف';

        DB::table('rec')->where('id', $recId)->delete();

        try {
            DB::table('activity_logs')->insert([
                'action'      => 'cancelled',
                'subject'     => 'appointment',
                'subject_id'  => $rec->st_id,
                'description' => 'حذف موعد بتاريخ ' . $rec->rec_date . ' الساعة ' . ($rec->rec_time ?: '--') . ' — بواسطة: ' . $byName,
                'user_id'     => $user?->id ?? 0,
                'user_name'   => $byName,
                'created_at'  => now(),
            ]);
        } catch (\Throwable) {}
    }

    public function cancelAppointment(int $recId): void
    {
        $rec = DB::table('rec')->where('id', $recId)->where('confirm_id', 0)->first();
        if (!$rec) return;

        $patient   = DB::table('kstu')->where('id', $rec->st_id)->first();
        $user      = auth()->user();
        $cancelledBy = $user ? ($user->getName() ?? $user->user_name ?? 'غير معروف') : 'غير معروف';

        // حذف الموعد
        DB::table('rec')->where('id', $recId)->delete();

        // تسجيل الإلغاء في activity_logs
        try {
            DB::table('activity_logs')->insert([
                'action'     => 'cancelled',
                'subject'    => 'appointment',
                'subject_id' => $rec->st_id,
                'description'=> 'إلغاء موعد بتاريخ ' . $rec->rec_date . ' الساعة ' . ($rec->rec_time ?: '--') . ' — بواسطة: ' . $cancelledBy,
                'user_id'    => $user?->id ?? 0,
                'user_name'  => $cancelledBy,
                'created_at' => now(),
            ]);
        } catch (\Throwable) {}

        // تحديث القائمة
        $this->openTodayModal();
    }

    public function sendSpecialistNotif(int $clinicId, string $recDate): void
    {
        $spec = DB::table('clinic as c')
            ->leftJoin('employees as e', 'e.id', '=', 'c.doc_id1')
            ->where('c.id', $clinicId)
            ->select('c.name as clinic_name', 'e.id as spec_id', 'e.phone as spec_phone',
                     DB::raw("TRIM(CONCAT(COALESCE(e.first_name,''), ' ', COALESCE(e.middle_initial,''))) as spec_name"))
            ->first();

        if (!$spec) {
            $this->dispatch('spec-notif-error', msg: 'لا يوجد أخصائي مرتبط بهذه العيادة');
            return;
        }

        if (!trim($spec->spec_phone ?? '')) {
            $this->dispatch('spec-notif-error', 
                msg: 'لا يوجد رقم هاتف مسجل للأخصائي: ' . trim($spec->spec_name));
            return;
        }

        $appts = DB::table('rec as r')
            ->leftJoin('kstu as k', 'k.id', '=', 'r.st_id')
            ->where('r.clinic_id', $clinicId)
            ->where('r.rec_date', $recDate)
            ->where('r.confirm_id', 0)
            ->orderBy('r.rec_time')
            ->get(['r.rec_time', 'k.full_name']);

        $phone = preg_replace('/[^0-9]/', '', $spec->spec_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '965' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '965')) {
            $phone = '965' . $phone;
        }

        // تسجيل إرسال التنبيه
        try {
            DB::table('activity_logs')->insert([
                'action'      => 'sent',
                'subject'     => 'specialist_notification',
                'subject_id'  => $spec->spec_id,
                'description' => 'إرسال تنبيه جدول مواعيد إلى ' . trim($spec->spec_name) 
                               . ' (' . $phone . ') بتاريخ: ' . $recDate,
                'user_id'     => auth()->user()?->id ?? 0,
                'user_name'   => auth()->user()?->getName() ?? auth()->user()?->user_name ?? 'نظام',
                'created_at'  => now(),
            ]);
        } catch (\Throwable) {}

        $this->dispatch('open-specialist-wa', [
            'phone'      => $phone,
            'specName'   => trim($spec->spec_name),
            'specPhone'  => $phone,
            'clinicName' => $spec->clinic_name,
            'date'       => $recDate,
            'appts'      => $appts->map(fn($a) => [
                'time' => $a->rec_time ?: '--:--',
                'name' => $a->full_name ?: '—',
            ])->toArray(),
        ]);
    }

    #[Title('جدول المواعيد : Appointments')]
    public function render()
    {
        $today = now()->format('j-n-Y');

        // تحديث تلقائي: مرة واحدة فقط في اليوم (بدل كل render)
        Cache::remember('appt_past_close_' . now()->format('Y-m-d'), 3600 * 6, function () {
            $pastDates = [];
            for ($i = 1; $i <= 30; $i++) {
                $pastDates[] = now()->subDays($i)->format('j-n-Y');
            }
            return DB::table('rec')
                ->where('confirm_id', 0)
                ->whereIn('rec_date', $pastDates)
                ->update(['confirm_id' => 1]);
        });

        $query = DB::table('rec as r')
            ->leftJoin('kstu as a', 'a.id', '=', 'r.st_id')
            ->leftJoin('clinic as c', 'c.id', '=', 'r.clinic_id')
            ->leftJoin('employees as e', 'e.id', '=', 'r.user_id')
            ->leftJoin('employees as spec', 'spec.id', '=', 'c.doc_id1')
            ->where('r.confirm_id', 0)
            ->select(
                'r.id',
                'r.rec_date',
                'r.rec_time',
                'r.clinic_id',
                'r.state_id as status',
                'r.st_id',
                'a.full_name as patient_name',
                'a.phone as patient_phone',
                'c.name as clinic_name',
                'spec.phone as spec_phone',
                DB::raw("TRIM(CONCAT(COALESCE(spec.first_name,''), ' ', COALESCE(spec.middle_initial,''))) as spec_name"),
                DB::raw("TRIM(CONCAT(COALESCE(e.first_name,''), ' ', COALESCE(e.middle_initial,''))) as booked_by")
            );

        if ($this->search) {
            $term = '%' . $this->search . '%';
            $query->where('a.full_name', 'like', $term);
        }

        if ($this->filterDate) {
            $d = \Carbon\Carbon::parse($this->filterDate);
            $query->where('r.rec_date', $d->format('j-n-Y'));
        }

        if ($this->selectedClinic) {
            $query->where('r.clinic_id', $this->selectedClinic);
        }

        $appointments = $query
            ->orderByRaw("STR_TO_DATE(r.rec_date, '%e-%c-%Y') ASC")
            ->orderBy('r.rec_time', 'asc')
            ->paginate(20);
        $clinics = DB::table('clinic')->where('state_id', 1)->orderBy('name')->get();

        $todayCount = DB::table('rec')
            ->where('rec_date', $today)
            ->where('confirm_id', 0)
            ->count();

        $doneCount = DB::table('rec')
            ->where('rec_date', $today)
            ->where('confirm_id', 0)
            ->where('state_id', 1)
            ->count();

        return view('livewire.appointments.index', [
            'clinics'      => $clinics,
            'appointments' => $appointments,
            'todayCount'   => $todayCount,
            'doneCount'    => $doneCount,
        ])->layout('layouts.app');
    }
}
