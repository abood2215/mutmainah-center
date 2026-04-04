<style>
@media (max-width: 768px) {
    .ps-outer { padding: 0.75rem !important; }
    .ps-wrap { max-width: 100% !important; }
    .ps-card-header { padding: 0.85rem 1rem !important; flex-direction: column !important; align-items: flex-start !important; gap: 0.5rem !important; }
    .ps-table-scroll { overflow-x: auto; }
    .ps-action-bar { flex-wrap: wrap !important; gap: 0.4rem !important; }
    .ps-action-bar a,
    .ps-action-bar button { font-size: 0.8rem !important; padding: 0.4rem 0.75rem !important; }
}
</style>
<div class="pg-outer ps-outer" style="min-height:80vh; padding:2rem; display:flex; align-items:flex-start; justify-content:center;">
<div class="ps-wrap" style="width:100%; max-width:750px; animation:fadeIn 0.4s ease;">

<div id="print-area">

    {{-- الترويسة + زر الطباعة --}}
    <x-print-header title="ملف العميل" />

    {{-- بطاقة العميل --}}
    <div style="border-radius:14px; overflow:hidden; border:2px solid var(--primary); box-shadow:0 10px 30px rgba(139,28,43,0.12);">

        {{-- رأس البطاقة --}}
        <div class="ps-card-header" style="background:var(--primary); padding:1.1rem 1.5rem; display:flex; align-items:center; justify-content:space-between;">
            <span style="color:#fff; font-weight:900; font-size:1.15rem; display:flex; align-items:center; gap:0.6rem;">
                👤 ملف العميل:
                <span style="font-weight:400; opacity:0.9;">{{ $patient->full_name }}</span>
            </span>
            <span style="background:rgba(255,255,255,0.2); color:#fff; padding:0.25rem 0.9rem; border-radius:20px; font-weight:800; font-size:0.9rem; font-family:'Inter';">
                #{{ $patient->file_id }}
            </span>
        </div>

        {{-- جدول البيانات --}}
        <div class="ps-table-scroll" style="background:#fff; overflow-x:auto;">
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

        {{-- شريط الأزرار --}}
        <div class="no-print ps-action-bar" style="background:#f1f5f9; padding:1rem 1.25rem; display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap; border-top:1px solid #e2e8f0;">
            <a href="{{ route('patients.new-check', $patient->id) }}" wire:navigate class="btn btn-primary" style="padding:0.5rem 1.25rem; font-size:0.9rem;">
                ➕ كشف جديد
            </a>
            <div style="width:1px; height:20px; background:#cbd5e1;"></div>
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
            <button style="color:var(--text-dim); font-size:0.85rem; font-weight:800; padding:0.5rem 1rem; border-radius:8px; background:#fff; border:1px solid #e2e8f0; cursor:pointer;">
                📎 المرفقات
            </button>
        </div>

    </div>

    {{-- تذييل الطباعة --}}
    <div class="print-footer" style="display:none; margin-top:1.5rem; text-align:center; font-size:0.72rem; color:#9ca3af; font-family:'Tajawal',sans-serif; border-top:1px solid #e2e8f0; padding-top:0.5rem;">
        تاريخ الطباعة: {{ now()->format('d/m/Y H:i') }} &nbsp;|&nbsp; مركز مطمئنة الاستشاري
    </div>

</div>{{-- end #print-area --}}

</div>
</div>
