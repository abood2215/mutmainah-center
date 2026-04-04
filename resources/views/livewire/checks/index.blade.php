<style>
@media (max-width: 768px) {
    .ch-outer { padding: 0.6rem !important; }
    .ch-header { padding: 0.85rem 1rem !important; flex-direction: column !important; align-items: flex-start !important; }
    .ch-inner { padding: 1rem !important; }
    .ch-filter { flex-direction: column !important; align-items: stretch !important; }
    .ch-filter > div { min-width: 0 !important; max-width: 100% !important; width: 100% !important; }
    .ch-filter input[type="date"],
    .ch-filter select { width: 100% !important; }
    .ch-filter button { width: 100% !important; justify-content: center !important; }
    .ch-patient-actions { flex-wrap: wrap !important; gap: 0.4rem !important; }
    .ch-footer { padding: 0.75rem 1rem !important; flex-direction: column !important; gap: 0.75rem !important; }
    .ch-footer-stats { flex-direction: column !important; gap: 0.5rem !important; }
}
@media (max-width: 480px) {
    .ch-success-banner { flex-direction: column !important; }
}
</style>
<div class="pg-outer ch-outer" style="min-height:80vh; padding: 1.5rem 2rem;">

@if(session('check_success'))
@php $cs = session('check_success'); @endphp
<div style="max-width:1400px; margin:0 auto 1rem; background:#e8f5e9; border:2px solid #4caf50; border-radius:10px; padding:14px 20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; font-family:'Tajawal',sans-serif;">
    <div style="display:flex; align-items:center; gap:12px;">
        <span style="font-size:1.5rem;">✅</span>
        <div>
            <div style="font-weight:900; color:#1b5e20; font-size:.95rem;">تم تحويل العميل بنجاح</div>
            <div style="font-size:.83rem; color:#2e7d32; margin-top:2px;">
                <strong>{{ $cs['patient'] }}</strong>
                &nbsp;|&nbsp; العيادة: <strong>{{ $cs['clinic'] }}</strong>
                &nbsp;|&nbsp; المبلغ: <strong>{{ number_format($cs['total'], 3) }} د.ك</strong>
                &nbsp;|&nbsp; رقم الكشف: <strong>#{{ $cs['rec_id'] }}</strong>
            </div>
        </div>
    </div>
    <a href="{{ $cs['invoice_url'] }}" target="_blank"
       style="background:#1b5e20; color:#fff; text-decoration:none; border-radius:6px; padding:8px 20px; font-family:'Tajawal',sans-serif; font-size:.85rem; font-weight:800; white-space:nowrap;">
        🧾 طباعة الفاتورة
    </a>
</div>
@endif

    <!-- الإطار الرئيسي (المحاط ببوردر والمحصور العرض) -->
    <div style="max-width: 1400px; margin: 0 auto; background: #fff; border: 1px solid var(--border); border-radius: 16px; box-shadow: var(--shadow-sm); overflow: hidden; display: flex; flex-direction: column; animation: fadeIn 0.5s ease;">
        
        <!-- رأس الإطار: العنوان والتاريخ -->
        <div class="ch-header" style="padding: 1.25rem 1.75rem; border-bottom: 1px solid var(--border); background: #fafbfc; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 42px; height: 42px; background: var(--primary-glow); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">📋</div>
                <h1 style="font-size:1.4rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">سجل الكشوفات</h1>
            </div>
            <div style="background: #fff; padding: 0.4rem 1rem; border: 1px solid var(--border); border-radius: 50px; font-size:0.82rem; color:var(--text-dim); font-weight:800; box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);">
                <span style="opacity: 0.6;">📅</span> {{ now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}
            </div>
        </div>

        <!-- محتوى الإطار -->
        <div class="pg-inner ch-inner" style="padding: 1.75rem;">

            <!-- فلاتر البحث -->
            <div style="margin-bottom: 1.5rem; background: #f8fafc; border: 1px solid var(--border); border-radius: 12px; padding: 1rem 1.25rem;">
                <div class="pg-filter ch-filter" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center;">
                    <div style="position: relative; flex: 1; min-width: 220px; max-width: 360px;">
                        <input type="text" wire:model.live.debounce.400ms="search"
                            placeholder="بحث باسم العميل أو رقم الكشف..."
                            class="form-input" style="padding-right: 2.8rem;">
                        <span style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); opacity: 0.4; font-size: 1.1rem;">🔍</span>
                    </div>

                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <span style="font-size: 0.82rem; font-weight: 800; color: var(--text-dim); white-space: nowrap;">التاريخ:</span>
                        <input type="date" wire:model="filterDate" class="form-input" style="width:160px; font-weight: 700;">
                    </div>

                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <span style="font-size: 0.82rem; font-weight: 800; color: var(--text-dim); white-space: nowrap;">العيادة:</span>
                        <select wire:model="filterClinic" class="form-input" style="width:200px; font-weight: 700;">
                            <option value="">جميع العيادات</option>
                            @foreach($clinics as $clinic)
                                <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button wire:click="$refresh"
                        style="padding:0.6rem 1.2rem; background:var(--primary); border:none; border-radius:8px; cursor:pointer; display:flex; align-items:center; gap:0.4rem; font-size:0.85rem; color:#fff; font-weight:800;">
                        🔍 <span style="font-size:0.8rem;">بحث</span>
                    </button>
                    <button wire:click="resetFilters" title="إعادة تعيين"
                        style="padding:0.6rem 1rem; background:#fff; border:1px solid var(--border); border-radius:8px; cursor:pointer; display: flex; align-items: center; gap: 0.4rem; font-size:0.85rem; color:var(--text-dim); transition: all 0.2s; font-weight: 800;"
                        onmouseover="this.style.background='#f0f2f5'" onmouseout="this.style.background='#fff'">
                        🔄 <span style="font-size: 0.8rem;">مسح</span>
                    </button>
                </div>
            </div>

            <!-- بانيل بيانات العميل (يظهر عند الاختيار) -->
            @if($selectedPatient)
            <div style="margin-bottom:1.5rem; border-radius:12px; overflow:hidden; border:1.5px solid var(--primary); box-shadow:0 10px 25px rgba(139,28,43,0.12); animation: dropIn 0.3s ease;">
                <!-- رأس البانيل -->
                <div style="background:var(--primary); padding:0.65rem 1.25rem; display:flex; align-items:center; justify-content:space-between;">
                    <span style="color:#fff; font-weight:900; font-size:1rem; display: flex; align-items: center; gap: 0.5rem;">👤 ملف العميل: <span style="font-weight: 400; opacity: 0.9;">{{ $selectedPatient->full_name }}</span></span>
                    <button wire:click="selectPatient({{ $selectedStId }})"
                        style="background:rgba(255,255,255,0.2); border:none; color:#fff; width:26px; height:26px; border-radius:6px; cursor:pointer; font-size:0.8rem; display:flex; align-items:center; justify-content:center; transition: background 0.2s;"
                        onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">✕</button>
                </div>

                <!-- جدول البيانات -->
                <div style="background:#fff; overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#fff9f9; border-bottom:1px solid #fcecee;">
                                <th style="padding:0.8rem 1rem; font-size:0.8rem; font-weight:900; color:var(--primary); text-align:right;">الاسم</th>
                                <th style="padding:0.8rem 1rem; font-size:0.8rem; font-weight:900; color:var(--primary); text-align:center;">الملف</th>
                                <th style="padding:0.8rem 1rem; font-size:0.8rem; font-weight:900; color:var(--primary); text-align:center;">الهوية</th>
                                <th style="padding:0.8rem 1rem; font-size:0.8rem; font-weight:900; color:var(--primary); text-align:center;">الجوال</th>
                                <th style="padding:0.8rem 1rem; font-size:0.8rem; font-weight:900; color:var(--primary); text-align:center;">التأمين</th>
                                <th style="padding:0.8rem 1rem; font-size:0.8rem; font-weight:900; color:var(--primary); text-align:center;">الأدوات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding:1rem; font-weight:900; color:var(--navy); font-size:1rem;">{{ $selectedPatient->full_name }}</td>
                                <td style="padding:1rem; text-align:center;"><span style="background: rgba(21,101,192,0.1); color: #1565c0; padding: 0.25rem 0.75rem; border-radius: 6px; font-weight: 800; font-family: 'Inter';">#{{ $selectedPatient->file_id }}</span></td>
                                <td style="padding:1rem; text-align:center; font-weight:700; color:var(--text-dim);">{{ $selectedPatient->ssn ?: '—' }}</td>
                                <td style="padding:1rem; text-align:center; font-weight:700; color:var(--text-dim);">{{ $selectedPatient->phone ?: '—' }}</td>
                                <td style="padding:1rem; text-align:center; color:var(--text-muted); font-size:0.85rem;">{{ $selectedPatient->insurance ?: 'بدون تأمين' }}</td>
                                <td style="padding:1rem; text-align:center;">
                                    <div style="display: flex; gap: 0.4rem; justify-content: center;">
                                        <button title="تعديل" style="width:32px; height:32px; border:1px solid var(--border); background:#fff; border-radius:8px; cursor:pointer;">✏️</button>
                                        <button title="حذف" style="width:32px; height:32px; border:1px solid #ffdde1; background:#fff5f6; border-radius:8px; cursor:pointer; color:var(--danger);">✕</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- شريط الإجراءات السريعة -->
                <div class="ch-patient-actions" style="background:#f1f5f9; padding:0.6rem 1rem; display:flex; align-items:center; gap:0.6rem; flex-wrap:wrap; border-top: 1px solid #e2e8f0;">
                    <a href="{{ route('patients.new-check', $selectedPatient->id) }}" wire:navigate class="btn btn-primary" style="padding: 0.4rem 1rem; border-radius: 6px; font-size: 0.8rem;">
                        ➕ كشف جديد
                    </a>
                    <div style="width: 1px; height: 18px; background: #cbd5e1; margin: 0 0.25rem;"></div>
                    <a href="{{ route('patients.medical-history', $selectedPatient->id) }}" wire:navigate style="color:var(--text-dim); text-decoration:none; font-size:0.82rem; font-weight:800; padding:0.4rem 0.75rem; border-radius:6px; background:#fff; border:1px solid #e2e8f0;">السجل الاستشاري</a>
                    <a href="{{ route('patients.financial-statement', $selectedPatient->id) }}" wire:navigate style="color:var(--text-dim); text-decoration:none; font-size:0.82rem; font-weight:800; padding:0.4rem 0.75rem; border-radius:6px; background:#fff; border:1px solid #e2e8f0;">البيان المالي</a>
                    <button style="color:var(--text-dim); font-size:0.82rem; font-weight:800; padding:0.4rem 0.75rem; border-radius:6px; background:#fff; border:1px solid #e2e8f0; cursor:pointer;">المرفقات</button>
                </div>
            </div>
            @endif

            <!-- جدول الكشوفات الرئيسي -->
            <div style="border: 1px solid var(--border); border-radius: 12px; overflow: hidden; background: #fff;">
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f8fafc; border-bottom:2px solid var(--border);">
                                <th style="padding:1rem; text-align:center; font-size:0.8rem; font-weight:900; color:var(--text-dim); width:50px;">#</th>
                                <th style="padding:1rem; font-size:0.8rem; font-weight:900; color:var(--text-dim); min-width:180px; text-align: right;">العميل</th>
                                <th style="padding:1rem; text-align:center; font-size:0.8rem; font-weight:900; color:var(--text-dim);">تاريخ الكشف</th>
                                <th style="padding:1rem; font-size:0.8rem; font-weight:900; color:var(--text-dim); min-width:160px; text-align: right;">العيادة / المستشار</th>
                                <th style="padding:1rem; text-align:center; font-size:0.8rem; font-weight:900; color:var(--text-dim);">المبلغ</th>
                                <th style="padding:1rem; text-align:center; font-size:0.8rem; font-weight:900; color:var(--text-dim);">الفاتورة</th>
                                <th style="padding:1rem; text-align:center; font-size:0.8rem; font-weight:900; color:var(--text-dim);">الحالة</th>
                                <th style="padding:1rem; text-align:center; font-size:0.8rem; font-weight:900; color:var(--text-dim); width:80px;">إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checks as $check)
                                @php
                                    $rowBg     = $selectedStId == $check->st_id ? '#fff9f9' : 'transparent';
                                    $hoverBg   = '#fcfdfe';
                                @endphp
                                <tr wire:key="check-{{ $check->id }}"
                                    style="border-bottom:1px solid #f1f5f9; transition:all 0.2s; background:{{ $rowBg }};"
                                    onmouseover="this.style.background='{{ $hoverBg }}'" onmouseout="this.style.background='{{ $rowBg }}'">

                                    <td style="padding:0.85rem 1rem; text-align:center; font-weight:800; color:var(--text-muted); font-size:0.85rem;">
                                        {{ ($checks->currentPage() - 1) * $checks->perPage() + $loop->iteration }}
                                    </td>

                                    <td style="padding:0.85rem 1rem;">
                                        <a href="{{ route('patients.show', $check->st_id) }}" target="_blank"
                                            style="font-weight:900; color:var(--primary); font-size:1rem; font-family:'Tajawal',sans-serif; text-decoration:none; transition:all 0.15s;"
                                            onmouseover="this.style.color='var(--navy)'; this.style.textDecoration='underline';" onmouseout="this.style.color='var(--primary)'; this.style.textDecoration='none';">
                                            {{ $check->patient_name ?? '—' }}
                                        </a>
                                    </td>

                                    <td style="padding:0.85rem 1rem; text-align:center;">
                                        <div style="font-weight:800; color:#1e40af; font-size:0.88rem; direction:ltr; unicode-bidi:isolate;">{{ fmt_date($check->rec_date) }}</div>
                                        @if($check->rec_time)
                                            <div style="font-size:0.72rem; color:var(--text-muted); margin-top:0.1rem; direction:ltr; unicode-bidi:isolate;">{{ $check->rec_time }}</div>
                                        @endif
                                    </td>

                                    <td style="padding:0.85rem 1rem; font-size:0.85rem; color:var(--text-dim); font-weight: 600;">
                                        {{ $check->clinic_name ?? '—' }}
                                    </td>

                                    <td style="padding:0.85rem 1rem; text-align:center; font-weight:900; font-size:1.1rem; color:var(--navy);">
                                        {{ number_format($check->amount, 0) }} <span style="font-size: 0.65rem; font-weight: 400; opacity: 0.7;">د.ك</span>
                                    </td>

                                    <td style="padding:0.85rem 1rem; text-align:center;">
                                        <a href="{{ route('finance.invoice-print', $check->id) }}" target="_blank"
                                           style="color:#2563eb; font-weight:800; font-size:0.8rem; text-decoration:none; border:1px solid #dbeafe; background:#eff6ff; padding: 0.25rem 0.6rem; border-radius: 6px;">📄 فاتورة</a>
                                    </td>

                                    <td style="padding:0.85rem 1rem; text-align:center;">
                                        <span style="background:#ecfdf5; color:#059669; font-weight:900; font-size:0.75rem; padding:0.25rem 0.75rem; border-radius:50px; border:1px solid #d1fae5;">اكتمل ✓</span>
                                    </td>

                                    <td style="padding:0.85rem 1rem; text-align:center;">
                                        <div style="display:flex; gap:6px; justify-content:center;">
                                            <button title="تعديل" style="width:30px; height:30px; border:1px solid var(--border); background:#fff; border-radius:8px; cursor:pointer; font-size:0.8rem; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'">✏️</button>
                                            <button title="إلغاء" style="width:30px; height:30px; border:1px solid #fee2e2; background:#fff; border-radius:8px; cursor:pointer; font-size:0.8rem; color:var(--danger); transition: all 0.2s;" onmouseover="this.style.background='#fef2f2'">✕</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="padding:5rem 2rem; text-align:center; color:var(--text-muted);">
                                        <div style="font-size:3rem; margin-bottom:1rem; filter: grayscale(1); opacity: 0.2;">📁</div>
                                        <div style="font-weight:900; font-size: 1.25rem; color: #94a3b8;">لا توجد سجلات كشوفات حالياً</div>
                                        <div style="font-size: 0.9rem; margin-top: 0.5rem; opacity: 0.7;">يمكنك البحث بفلتر آخر أو إضافة عميل جديد</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- الترقيم -->
                @if($checks->hasPages())
                    <div style="padding:0.85rem 1.5rem; border-top:1px solid var(--border); background:#fcfdfe;">
                        {{ $checks->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- شريط الإحصائيات (تذييل الإطار) -->
        <div class="ch-footer" style="background:var(--navy); padding:1rem 2rem; display:flex; align-items:center; justify-content:space-between; color:#fff; flex-wrap:wrap; gap:1.5rem; border-top: 4px solid var(--gold);">
            <div class="ch-footer-stats" style="display:flex; align-items:center; gap:1.5rem;">
                <div wire:click="openWaitingModal"
                    style="display:flex; align-items:center; gap:0.6rem; cursor:pointer; padding:0.4rem 0.8rem; border-radius:8px; transition:background 0.2s;"
                    onmouseover="this.style.background='rgba(251,191,36,0.15)'"
                    onmouseout="this.style.background='transparent'"
                    title="اضغط لعرض قائمة المنتظرين">
                    <div style="width: 10px; height: 10px; background: #fbbf24; border-radius: 50%;"></div>
                    <span style="font-size:0.85rem; font-weight: 600; opacity:0.8;">في الانتظار اليوم:</span>
                    <span style="font-size:1.4rem; font-weight:900; color:#fbbf24;">{{ $todayWaiting }}</span>
                    <span style="font-size:0.7rem; opacity:0.5; margin-right:0.2rem;">▲</span>
                </div>
                <div style="display:flex; align-items:center; gap:0.6rem;">
                    <div style="width: 10px; height: 10px; background: #34d399; border-radius: 50%;"></div>
                    <span style="font-size:0.85rem; font-weight: 600; opacity:0.8;">تم الكشف اليوم:</span>
                    <span style="font-size:1.4rem; font-weight:900; color:#34d399;">{{ $todayDone }}</span>
                </div>
            </div>
            <div style="font-size:0.85rem; font-weight:800; background: rgba(255,255,255,0.1); padding: 0.4rem 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.15);">
                📅 {{ now()->locale('ar')->isoFormat('dddd YYYY/MM/DD') }}
            </div>
        </div>
    </div>

</div>

<!-- ══════ MODAL: قائمة الانتظار ══════ -->
@if($showWaitingModal)
<div wire:click.self="closeWaitingModal"
    style="position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.55); display:flex; align-items:center; justify-content:center; padding:1rem; animation:fadeIn 0.2s ease;">

    <div style="background:#fff; border-radius:16px; width:100%; max-width:620px; box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow:hidden; animation:slideUp 0.25s cubic-bezier(0.16,1,0.3,1);">

        <!-- رأس الـ modal -->
        <div style="background:var(--navy); padding:1.1rem 1.5rem; display:flex; align-items:center; justify-content:space-between; border-bottom:3px solid #fbbf24;">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <div style="width:36px; height:36px; background:rgba(251,191,36,0.2); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1.2rem;">⏳</div>
                <div>
                    <div style="color:#fff; font-weight:900; font-size:1rem;">قائمة الانتظار اليوم</div>
                    <div style="color:rgba(255,255,255,0.5); font-size:0.75rem;">{{ now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}</div>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <span style="background:rgba(251,191,36,0.2); color:#fbbf24; font-size:0.78rem; font-weight:900; padding:0.25rem 0.75rem; border-radius:20px; border:1px solid rgba(251,191,36,0.3);">
                    {{ count($waitingList) }} موعد
                </span>
                <button wire:click="closeWaitingModal"
                    style="width:32px; height:32px; border-radius:8px; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15); color:rgba(255,255,255,0.7); font-size:1rem; cursor:pointer; display:flex; align-items:center; justify-content:center; line-height:1;">
                    ✕
                </button>
            </div>
        </div>

        <!-- القائمة -->
        <div style="max-height:420px; overflow-y:auto;">
            @forelse($waitingList as $i => $w)
            <div style="display:flex; align-items:center; gap:1rem; padding:0.85rem 1.5rem; border-bottom:1px solid #f1f5f9; transition:background 0.15s;"
                onmouseover="this.style.background='#fffbeb'"
                onmouseout="this.style.background=''">

                <!-- رقم -->
                <div style="width:28px; height:28px; background:#f1f5f9; border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:900; color:var(--text-muted); flex-shrink:0;">
                    {{ $i + 1 }}
                </div>

                <!-- الوقت -->
                <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:0.3rem 0.65rem; font-size:0.82rem; font-weight:900; color:#92400e; white-space:nowrap; flex-shrink:0; direction:ltr;">
                    {{ $w->rec_time ? preg_replace('/^(\d+):(\d)$/', '$1:0$2', $w->rec_time) : '--:--' }}
                </div>

                <!-- الاسم -->
                <div style="flex:1; min-width:0;">
                    <div style="font-weight:800; color:var(--navy); font-size:0.92rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $w->full_name ?: 'غير محدد' }}
                    </div>
                    <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.1rem; display:flex; gap:0.75rem;">
                        @if($w->clinic_name)<span>🏥 {{ $w->clinic_name }}</span>@endif
                        @if($w->phone)<span>📞 {{ $w->phone }}</span>@endif
                    </div>
                </div>

                <!-- رقم الملف -->
                @if($w->file_id)
                <div style="font-size:0.75rem; color:#1565c0; font-weight:800; background:#e3f2fd; padding:0.2rem 0.6rem; border-radius:6px; white-space:nowrap; flex-shrink:0;">
                    #{{ $w->file_id }}
                </div>
                @endif

            </div>
            @empty
            <div style="padding:3rem; text-align:center; color:var(--text-muted);">
                <div style="font-size:2.5rem; opacity:0.2; margin-bottom:0.5rem;">✅</div>
                <div style="font-weight:800;">لا يوجد أحد في الانتظار</div>
            </div>
            @endforelse
        </div>

        <!-- ذيل -->
        <div style="padding:0.85rem 1.5rem; background:#f8fafc; border-top:1px solid var(--border); text-align:center;">
            <button wire:click="closeWaitingModal"
                style="background:var(--navy); color:#fff; border:none; border-radius:8px; padding:0.55rem 2rem; font-family:'Tajawal',sans-serif; font-weight:800; font-size:0.88rem; cursor:pointer;">
                إغلاق
            </button>
        </div>

    </div>
</div>

<style>
@keyframes slideUp {
    from { opacity:0; transform:translateY(20px) scale(0.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
</style>
@endif

<style>
    .form-input {
        width: 100%;
        padding: 0.65rem 1rem;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-family: 'Tajawal', sans-serif;
        font-size: 0.9rem;
        outline: none;
        transition: all 0.2s;
        background: #fff;
    }
    .form-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary-glow);
    }
</style>
