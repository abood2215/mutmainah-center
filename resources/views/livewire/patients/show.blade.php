<div class="pg-outer" style="min-height:80vh; padding:2rem; display:flex; align-items:flex-start; justify-content:center;">
<div style="width:100%; max-width:750px; animation:fadeIn 0.4s ease;">

<div id="print-area">

    {{-- الترويسة + زر الطباعة --}}
    <x-print-header title="ملف العميل" />

    {{-- بطاقة العميل --}}
    <div style="border-radius:14px; overflow:hidden; border:2px solid var(--primary); box-shadow:0 10px 30px rgba(139,28,43,0.12);">

        {{-- رأس البطاقة --}}
        <div style="background:var(--primary); padding:1.1rem 1.5rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem;">
            <span style="color:#fff; font-weight:900; font-size:1.15rem; display:flex; align-items:center; gap:0.6rem;">
                👤 ملف العميل:
                <span style="font-weight:400; opacity:0.9;">{{ $patient->full_name }}</span>
            </span>
            <div style="display:flex; align-items:center; gap:0.6rem; flex-wrap:wrap;">
                @if($patient->branch_name)
                <span style="background:rgba(255,255,255,0.15); color:#fff; padding:0.2rem 0.8rem; border-radius:20px; font-size:0.78rem; font-weight:700; font-family:'Tajawal',sans-serif;">
                    🏢 {{ $patient->branch_name }}
                </span>
                @endif
                <span style="background:rgba(255,255,255,0.2); color:#fff; padding:0.25rem 0.9rem; border-radius:20px; font-weight:800; font-size:0.9rem; font-family:'Inter';">
                    #{{ $patient->file_id }}
                </span>
                @if((auth()->user()?->role ?? '') === 'admin')
                <button wire:click="{{ $editMode ? 'cancelEdit' : 'openEdit' }}" class="no-print"
                        style="background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.4); border-radius:8px; padding:0.25rem 0.85rem; font-size:0.78rem; font-weight:800; cursor:pointer; font-family:'Tajawal',sans-serif;">
                    {{ $editMode ? '✕ إلغاء' : '✏️ تعديل' }}
                </button>
                @endif
            </div>
        </div>

        {{-- flash --}}
        @if(session()->has('edit_saved'))
        <div class="no-print" style="background:#e8f5e9; color:#2e7d32; padding:0.5rem 1.25rem; font-size:0.82rem; font-weight:700; border-bottom:1px solid #c8e6c9;">✅ {{ session('edit_saved') }}</div>
        @endif

        {{-- جدول البيانات --}}
        @if(!$editMode)
        <div style="background:#fff; overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#fff9f9; border-bottom:1px solid #fcecee;">
                        <th style="padding:0.75rem 1.25rem; font-size:0.8rem; font-weight:900; color:var(--primary); text-align:right;">الاسم</th>
                        <th style="padding:0.75rem 1rem; font-size:0.8rem; font-weight:900; color:var(--primary); text-align:center;">الملف</th>
                        <th style="padding:0.75rem 1rem; font-size:0.8rem; font-weight:900; color:var(--primary); text-align:center;">الهوية</th>
                        <th style="padding:0.75rem 1rem; font-size:0.8rem; font-weight:900; color:var(--primary); text-align:center;">الجوال</th>
                        <th style="padding:0.75rem 1rem; font-size:0.8rem; font-weight:900; color:var(--primary); text-align:center;">الجنس</th>
                        <th style="padding:0.75rem 1rem; font-size:0.8rem; font-weight:900; color:var(--primary); text-align:center;">التأمين</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:1.1rem 1.25rem; font-weight:900; color:var(--navy); font-size:1.05rem;">{{ $patient->full_name }}</td>
                        <td style="padding:1.1rem 1rem; text-align:center;">
                            <span style="background:rgba(21,101,192,0.1); color:#1565c0; padding:0.25rem 0.75rem; border-radius:6px; font-weight:800; font-family:'Inter';">#{{ $patient->file_id }}</span>
                        </td>
                        <td style="padding:1.1rem 1rem; text-align:center; font-weight:700; color:var(--text-dim);">{{ $patient->ssn ?: '—' }}</td>
                        <td style="padding:1.1rem 1rem; text-align:center; font-weight:700; color:var(--text-dim);">{{ $patient->phone ?: '—' }}</td>
                        <td style="padding:1.1rem 1rem; text-align:center;">
                            @if($patient->gender == 1)
                                <span class="badge badge-blue">ذكر</span>
                            @elseif($patient->gender == 2)
                                <span class="badge badge-red">أنثى</span>
                            @else
                                <span style="color:var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td style="padding:1.1rem 1rem; text-align:center; color:var(--text-muted); font-size:0.85rem;">{{ $patient->insurance ?: 'على نفقته' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @else
        {{-- وضع التعديل --}}
        <div style="background:#fffbeb; border-bottom:1px solid #fde68a; padding:1.1rem 1.5rem;" class="no-print">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.9rem;">
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#92400e; margin-bottom:0.3rem;">الاسم الكامل *</label>
                    <input wire:model="editName" type="text" class="form-input" style="width:100%; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#92400e; margin-bottom:0.3rem;">الهوية / الإقامة</label>
                    <input wire:model="editSsn" type="text" class="form-input" style="width:100%; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#92400e; margin-bottom:0.3rem;">الجوال</label>
                    <input wire:model="editPhone" type="text" class="form-input" style="width:100%; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#92400e; margin-bottom:0.3rem;">الجنس</label>
                    <select wire:model="editGender" class="form-input" style="width:100%; box-sizing:border-box;">
                        <option value="0">— غير محدد —</option>
                        <option value="1">ذكر</option>
                        <option value="2">أنثى</option>
                    </select>
                </div>
                <div style="grid-column:1/-1;">
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#92400e; margin-bottom:0.3rem;">جهة التأمين</label>
                    <select wire:model="editComId" class="form-input" style="width:100%; box-sizing:border-box;">
                        <option value="28">على نفقته</option>
                        @foreach($insurances as $ins)
                            @if($ins->id != 28)
                            <option value="{{ $ins->id }}">{{ $ins->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="margin-top:0.9rem; display:flex; gap:0.6rem;">
                <button wire:click="saveEdit" class="btn btn-primary" style="padding:0.45rem 1.5rem; font-size:0.88rem;">
                    💾 حفظ التعديلات
                </button>
                <button wire:click="cancelEdit" class="btn" style="padding:0.45rem 1rem; font-size:0.88rem; background:#f4f6f9; color:#374151; border:1px solid #e5e7eb;">
                    إلغاء
                </button>
            </div>
        </div>
        @endif

        {{-- تعديل الفرع --}}
        @if(session()->has('branch_saved'))
        <div class="no-print" style="background:#e8f5e9; color:#2e7d32; padding:0.5rem 1.25rem; font-size:0.82rem; font-weight:700; border-top:1px solid #c8e6c9;">✅ {{ session('branch_saved') }}</div>
        @endif
        <div class="no-print" style="background:#f8fafc; padding:0.6rem 1.25rem; border-top:1px solid #e2e8f0; display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
            <span style="font-size:0.78rem; font-weight:800; color:var(--text-muted);">🏢 الفرع:</span>
            <select wire:model="editBranch" style="border:1.5px solid var(--border); border-radius:7px; padding:0.3rem 0.7rem; font-family:'Tajawal',sans-serif; font-size:0.82rem; color:var(--text-dim); outline:none; background:#fff;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
                <option value="0">— غير محدد —</option>
                @foreach($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
            <button wire:click="saveBranch" style="padding:0.3rem 1rem; background:var(--primary); color:#fff; border:none; border-radius:7px; font-size:0.8rem; font-weight:800; font-family:'Tajawal',sans-serif; cursor:pointer;">حفظ</button>
        </div>

        {{-- شريط الأزرار --}}
        <div class="no-print" style="background:#f1f5f9; padding:1rem 1.25rem; display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap; border-top:1px solid #e2e8f0;">
            <a href="{{ route('patients.new-check', $patient->id) }}" wire:navigate class="btn btn-primary" style="padding:0.5rem 1.25rem; font-size:0.9rem;">
                ➕ كشف جديد
            </a>
            <div style="width:1px; height:20px; background:#cbd5e1;"></div>
            @if($hasAccount)
            <div style="display:flex; align-items:center; gap:8px; background:{{ $balance > 0 ? '#e8f5e9' : ($balance == 0 ? '#f0f4ff' : '#ffebee') }}; border:1.5px solid {{ $balance > 0 ? '#a5d6a7' : ($balance == 0 ? '#c7d2fe' : '#ffcdd2') }}; border-radius:8px; padding:6px 14px;">
                <span style="font-size:.74rem; font-weight:800; color:#777;">رصيد الحساب</span>
                <span style="font-size:1rem; font-weight:900; color:{{ $balance > 0 ? '#1b5e20' : ($balance == 0 ? '#3730a3' : '#b71c1c') }}; font-family:'Inter',sans-serif;">
                    {{ number_format(abs($balance), 3) }} د.ك
                    @if($balance < 0)<span style="font-size:.72rem;"> (مديونية)</span>@endif
                </span>
            </div>
            <div style="width:1px; height:20px; background:#cbd5e1;"></div>
            @endif
            <a href="{{ route('patients.medical-history', $patient->id) }}" wire:navigate
               style="color:var(--text-dim); text-decoration:none; font-size:0.85rem; font-weight:800; padding:0.5rem 1rem; border-radius:8px; background:#fff; border:1px solid #e2e8f0;"
               onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='var(--text-dim)'">
                📋 السجل الاستشاري
            </a>
            <a href="{{ route('patients.financial-statement', $patient->id) }}" wire:navigate
               style="color:var(--text-dim); text-decoration:none; font-size:0.85rem; font-weight:800; padding:0.5rem 1rem; border-radius:8px; background:#fff; border:1px solid #e2e8f0;"
               onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='var(--text-dim)'">
                💰 البيان المالي
            </a>
            <a href="{{ route('patients.attachments', $patient->id) }}" wire:navigate
               style="color:var(--text-dim); text-decoration:none; font-size:0.85rem; font-weight:800; padding:0.5rem 1rem; border-radius:8px; background:#fff; border:1px solid #e2e8f0;"
               onmouseover="this.style.borderColor='#f59e0b'; this.style.color='#b45309'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='var(--text-dim)'">
                📎 المرفقات
            </a>
        </div>

    </div>

    {{-- سجل النشاط --}}
    @if(count($activityLogs) > 0)
    <div class="no-print" style="margin-top:1.25rem; background:#fff; border-radius:12px; border:1px solid var(--border); overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.05);">
        <div style="background:var(--navy); padding:0.7rem 1.25rem; display:flex; align-items:center; gap:0.5rem;">
            <span style="color:#fbbf24; font-size:0.9rem;">🕓</span>
            <span style="color:#fbbf24; font-weight:900; font-size:0.88rem; font-family:'Tajawal',sans-serif;">سجل النشاط</span>
        </div>
        <div style="padding:0.25rem 0;">
            @foreach($activityLogs as $log)
            @php
                $icon = match($log->action) {
                    'created'   => $log->subject === 'patient' ? '👤' : '📋',
                    'uploaded'  => '📎',
                    'updated'   => '✏️',
                    'deleted'   => '🗑️',
                    'receipt'   => '💰',
                    'payment'   => '💸',
                    'cancelled' => '❌',
                    'booked'    => '📅',
                    'discount'  => '🏷️',
                    'voided'    => '🚫',
                    default     => '•',
                };
                $color = match($log->action) {
                    'created'   => '#2e7d32',
                    'uploaded'  => '#1565c0',
                    'deleted'   => '#dc2626',
                    'receipt'   => '#166534',
                    'payment'   => '#b45309',
                    'cancelled' => '#dc2626',
                    'booked'    => '#1d4ed8',
                    'discount'  => '#7c3aed',
                    'voided'    => '#dc2626',
                    default     => '#546e7a',
                };
            @endphp
            <div style="padding:0.65rem 1.25rem; border-bottom:1px solid #f4f6f9; display:flex; align-items:flex-start; gap:0.75rem;"
                 onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                <span style="font-size:1rem; margin-top:0.05rem;">{{ $icon }}</span>
                <div style="flex:1; min-width:0;">
                    <div style="font-size:0.83rem; font-weight:700; color:var(--navy); font-family:'Tajawal',sans-serif;">{{ $log->description }}</div>
                    <div style="font-size:0.74rem; color:var(--text-muted); margin-top:0.15rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
                        <span style="color:{{ $color }}; font-weight:800;">{{ $log->user_name ?: 'النظام' }}</span>
                        <span>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- تذييل الطباعة --}}
    <div class="print-footer" style="display:none; margin-top:1.5rem; text-align:center; font-size:0.72rem; color:#9ca3af; font-family:'Tajawal',sans-serif; border-top:1px solid #e2e8f0; padding-top:0.5rem;">
        تاريخ الطباعة: {{ now()->format('d/m/Y H:i') }} &nbsp;|&nbsp; شركة مركز مطمئنة الكويتية للاستشارات اللغوية
    </div>

</div>{{-- end #print-area --}}

</div>
</div>
