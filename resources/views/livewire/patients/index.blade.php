<style>
@media (max-width: 768px) {
    .pt-outer { padding: 1rem 0.75rem !important; }
    .pt-search-card { width: 100% !important; max-width: 100% !important; }
    .pt-search-head { padding: 1.5rem 1.25rem 1.25rem !important; }
    .pt-results { max-width: 100% !important; margin-top: 1.25rem !important; }
    .pt-table-wrap { overflow-x: auto; }
    .pt-actions { flex-wrap: wrap !important; gap: 0.3rem !important; }
    .pt-action-btn { padding: 0.35rem 0.5rem !important; font-size: 0.72rem !important; }
}
@media (max-width: 480px) {
    .pt-search-head h2 { font-size: 1.1rem !important; }
    .pt-results-header { padding: 0.6rem 1rem !important; }
}
</style>
<div class="pg-outer pt-outer" style="min-height:80vh; padding:2.5rem 2rem; display:flex; flex-direction:column; align-items:center; background:var(--bg);">

{{-- ═══ بطاقة البحث ═══ --}}
<div class="pt-search-card" style="width:100%; max-width:580px; animation:fadeIn 0.45s ease;">

    {{-- الكارد الرئيسية --}}
    <div style="border-radius:18px; overflow:hidden; box-shadow:0 12px 40px rgba(26,26,46,0.18); border:1px solid rgba(200,148,26,0.25);">

        {{-- رأس: شعار + عنوان --}}
        <div style="background:linear-gradient(135deg, var(--navy) 0%, #252550 100%); padding:2rem 2rem 1.5rem; text-align:center; position:relative; overflow:hidden;">
            {{-- خط زخرفي خلفي --}}
            <div style="position:absolute; inset:0; background:repeating-linear-gradient(45deg, transparent, transparent 30px, rgba(200,148,26,0.03) 30px, rgba(200,148,26,0.03) 60px);"></div>

            {{-- أيقونة دائرية --}}
            <div style="width:64px; height:64px; background:linear-gradient(135deg,var(--primary),#b5243a); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; box-shadow:0 6px 20px rgba(139,28,43,0.4); position:relative; z-index:1;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>

            <h2 style="margin:0; color:#fff; font-size:1.3rem; font-weight:900; font-family:'Tajawal',sans-serif; position:relative; z-index:1;">بحث العملاء</h2>
            <p style="margin:0.3rem 0 0; color:rgba(200,148,26,0.9); font-size:0.82rem; font-weight:700; letter-spacing:2px; font-family:'Inter'; position:relative; z-index:1;">PATIENTS SEARCH</p>

            {{-- خط ذهبي فاصل --}}
            <div style="height:2px; background:linear-gradient(to right, transparent, var(--gold), transparent); margin:1.2rem auto 0; width:60%; position:relative; z-index:1;"></div>
        </div>

        {{-- جسم الكارد: حقل البحث --}}
        <div style="background:#fff; padding:1.75rem 2rem;">

            <label style="display:block; font-size:0.78rem; font-weight:800; color:#9ca3af; letter-spacing:1px; margin-bottom:0.5rem; font-family:'Tajawal',sans-serif;">ابحث بـ: الاسم | رقم الملف | الجوال | الهوية</label>

            <div style="position:relative;">
                {{-- أيقونة البحث --}}
                <span style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:#9ca3af; pointer-events:none;">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                </span>

                <input type="text"
                    wire:model.live.debounce.300ms="search"
                    wire:keydown.enter="performSearch"
                    placeholder="اكتب للبحث..."
                    style="width:100%; padding:0.82rem 2.8rem 0.82rem 1rem; border:2px solid #e5e7eb; border-radius:10px; font-family:'Tajawal',sans-serif; font-size:0.95rem; outline:none; color:#1a1a2e; box-sizing:border-box; transition:border-color 0.2s, box-shadow 0.2s; background:#f9fafb;"
                    onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(139,28,43,0.1)'; this.style.background='#fff';"
                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'; this.style.background='#f9fafb';">

                {{-- Autocomplete dropdown --}}
                @if(!empty($suggestions))
                <div style="position:absolute; top:calc(100% + 6px); left:0; right:0; background:#fff; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 16px 40px rgba(0,0,0,0.12); z-index:300; overflow:hidden; animation:dropIn 0.15s ease;">
                    @foreach($suggestions as $s)
                    <div wire:click="selectPatient({{ $s->id }}, '{{ addslashes($s->name) }}')"
                        style="padding:0.7rem 1.1rem; cursor:pointer; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center; transition:background 0.15s;"
                        onmouseover="this.style.background='#fef5f5'" onmouseout="this.style.background='#fff'">
                        <div>
                            <div style="font-weight:800; color:#1a1a2e; font-size:0.88rem; font-family:'Tajawal',sans-serif;">{{ $s->name }}</div>
                            <div style="font-size:0.73rem; color:#9ca3af; margin-top:0.1rem;">{{ $s->phone }}</div>
                        </div>
                        <span style="background:#fef5f5; color:var(--primary); font-weight:900; font-size:0.78rem; padding:0.18rem 0.55rem; border-radius:6px; border:1px solid #fdd5da; direction:ltr;">#{{ $s->file_id ?? $s->id }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- أزرار --}}
            <div style="display:flex; gap:0.75rem; margin-top:1.1rem;">
                <button wire:click="performSearch"
                    style="flex:2; padding:0.72rem 1rem; background:var(--primary); color:#fff; border:none; border-radius:9px; font-weight:900; font-size:0.9rem; font-family:'Tajawal',sans-serif; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:0.4rem; transition:background 0.2s; box-shadow:0 4px 12px rgba(139,28,43,0.3);"
                    onmouseover="this.style.background='#6e1522'" onmouseout="this.style.background='var(--primary)'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    بحث
                </button>
                <button wire:click="resetSearch"
                    style="flex:1; padding:0.72rem 1rem; background:#f3f4f6; color:#6b7280; border:1px solid #e5e7eb; border-radius:9px; font-weight:700; font-size:0.9rem; font-family:'Tajawal',sans-serif; cursor:pointer; transition:all 0.2s;"
                    onmouseover="this.style.background='#e9ecef'; this.style.color='#374151'" onmouseout="this.style.background='#f3f4f6'; this.style.color='#6b7280'">
                    مسح
                </button>
            </div>

        </div>

        {{-- روابط سريعة --}}
        <div style="background:linear-gradient(135deg, var(--navy) 0%, #252550 100%); padding:0.85rem 2rem; display:flex; justify-content:center; gap:0; border-top:1px solid rgba(200,148,26,0.2);">

            <a href="{{ route('patients.create') }}" wire:navigate
                style="flex:1; text-align:center; color:rgba(255,255,255,0.65); font-weight:700; font-size:0.8rem; text-decoration:none; font-family:'Tajawal',sans-serif; padding:0.3rem 0.5rem; border-left:1px solid rgba(255,255,255,0.1); transition:color 0.2s; display:flex; flex-direction:column; align-items:center; gap:0.2rem;"
                onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='rgba(255,255,255,0.65)'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                ملف جديد
            </a>

            <a href="{{ route('checks.index') }}" wire:navigate
                style="flex:1; text-align:center; color:rgba(255,255,255,0.65); font-weight:700; font-size:0.8rem; text-decoration:none; font-family:'Tajawal',sans-serif; padding:0.3rem 0.5rem; border-left:1px solid rgba(255,255,255,0.1); transition:color 0.2s; display:flex; flex-direction:column; align-items:center; gap:0.2rem;"
                onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='rgba(255,255,255,0.65)'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                الكشوف
            </a>

            <a href="{{ route('appointments.index') }}" wire:navigate
                style="flex:1; text-align:center; color:rgba(255,255,255,0.65); font-weight:700; font-size:0.8rem; text-decoration:none; font-family:'Tajawal',sans-serif; padding:0.3rem 0.5rem; transition:color 0.2s; display:flex; flex-direction:column; align-items:center; gap:0.2rem;"
                onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='rgba(255,255,255,0.65)'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                المواعيد
            </a>

        </div>
    </div>

    {{-- إحصائية صغيرة تحت الكارد --}}
    @if(!$searchPerformed)
    <p style="text-align:center; color:#9ca3af; font-size:0.78rem; margin-top:1rem; font-family:'Tajawal',sans-serif;">
        ابحث باسم العميل أو رقم الملف أو الجوال للعثور على سجلاته
    </p>
    @endif

</div>

{{-- ═══ النتائج ═══ --}}
@if($searchPerformed)
<div class="pt-results" style="width:100%; max-width:1150px; margin-top:1.75rem; animation:fadeIn 0.3s ease;">

    @if(count($patients) > 0)
    <div style="background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08); border:1px solid #e9ecef;">

        {{-- رأس الجدول --}}
        <div style="background:linear-gradient(135deg,var(--navy) 0%,#252550 100%); padding:0.8rem 1.5rem; display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:0.6rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                <span style="color:#fff; font-weight:900; font-size:0.92rem; font-family:'Tajawal',sans-serif;">نتائج البحث</span>
            </div>
            <span style="background:rgba(200,148,26,0.2); color:#fbbf24; font-size:0.78rem; font-weight:800; padding:0.2rem 0.75rem; border-radius:20px; border:1px solid rgba(200,148,26,0.3); font-family:'Tajawal',sans-serif;">
                {{ $patients->total() }} عميل
            </span>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif;">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:2px solid #e9ecef;">
                        <th style="padding:0.7rem 1.1rem; text-align:center; font-size:0.75rem; font-weight:900; color:#9ca3af; width:42px;">#</th>
                        <th style="padding:0.7rem 1.1rem; text-align:right; font-size:0.75rem; font-weight:900; color:#9ca3af;">الاسم الكامل</th>
                        <th style="padding:0.7rem 1.1rem; text-align:center; font-size:0.75rem; font-weight:900; color:#9ca3af;">رقم الملف</th>
                        <th style="padding:0.7rem 1.1rem; text-align:center; font-size:0.75rem; font-weight:900; color:#9ca3af;">الهوية</th>
                        <th style="padding:0.7rem 1.1rem; text-align:center; font-size:0.75rem; font-weight:900; color:#9ca3af;">الجوال</th>
                        <th style="padding:0.7rem 1.3rem; text-align:center; font-size:0.75rem; font-weight:900; color:#9ca3af;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $i => $patient)
                    <tr style="border-bottom:1px solid #f3f4f6; transition:background 0.15s; {{ $i % 2 == 0 ? '' : 'background:#fafbfc;' }}"
                        onmouseover="this.style.background='#fef9f0'" onmouseout="this.style.background='{{ $i % 2 == 0 ? '#fff' : '#fafbfc' }}'">

                        <td style="padding:0.8rem 1.1rem; text-align:center; color:#d1d5db; font-size:0.78rem; font-weight:600;">
                            {{ $patients->firstItem() + $i }}
                        </td>

                        <td style="padding:0.8rem 1.1rem;">
                            <div style="font-weight:800; color:#1a1a2e; font-size:0.92rem;">{{ $patient->name }}</div>
                        </td>

                        <td style="padding:0.8rem 1.1rem; text-align:center;">
                            <span style="display:inline-block; background:#fef5f5; color:var(--primary); font-weight:900; font-size:0.8rem; padding:0.22rem 0.7rem; border-radius:7px; border:1px solid #fdd5da; direction:ltr;">
                                {{ $patient->file_id ?? $patient->id }}
                            </span>
                        </td>

                        <td style="padding:0.8rem 1.1rem; text-align:center; color:#6b7280; font-size:0.83rem; direction:ltr;">
                            {{ $patient->identity_number ?: '—' }}
                        </td>

                        <td style="padding:0.8rem 1.1rem; text-align:center; color:#6b7280; font-size:0.83rem; direction:ltr;">
                            {{ $patient->phone ?: '—' }}
                        </td>

                        {{-- ═ الإجراءات ═ --}}
                        <td style="padding:0.65rem 1rem;">
                            <div class="pt-actions" style="display:flex; gap:0.4rem; justify-content:center; align-items:center;">

                                {{-- كشف جديد --}}
                                <a href="{{ route('patients.new-check', $patient->id) }}" wire:navigate
                                    title="كشف جديد"
                                    style="display:inline-flex; align-items:center; gap:0.28rem; padding:0.4rem 0.9rem; background:var(--primary); color:#fff; border-radius:8px; text-decoration:none; font-weight:800; font-size:0.78rem; font-family:'Tajawal',sans-serif; box-shadow:0 2px 8px rgba(139,28,43,0.25); transition:all 0.2s; white-space:nowrap;"
                                    onmouseover="this.style.background='#6e1522'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(139,28,43,0.35)'"
                                    onmouseout="this.style.background='var(--primary)'; this.style.transform=''; this.style.boxShadow='0 2px 8px rgba(139,28,43,0.25)'">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    كشف جديد
                                </a>

                                {{-- السجل الاستشاري --}}
                                <a href="{{ route('patients.medical-history', $patient->id) }}" wire:navigate
                                    title="السجل الاستشاري"
                                    style="display:inline-flex; align-items:center; gap:0.28rem; padding:0.4rem 0.75rem; background:#fff; color:#374151; border-radius:8px; text-decoration:none; font-weight:700; font-size:0.78rem; font-family:'Tajawal',sans-serif; border:1.5px solid #e5e7eb; transition:all 0.2s; white-space:nowrap;"
                                    onmouseover="this.style.borderColor='var(--navy)'; this.style.color='var(--navy)'; this.style.background='#f0f4ff'" onmouseout="this.style.borderColor='#e5e7eb'; this.style.color='#374151'; this.style.background='#fff'">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    السجل
                                </a>

                                {{-- البيان المالي --}}
                                <a href="{{ route('patients.financial-statement', $patient->id) }}" wire:navigate
                                    title="البيان المالي"
                                    style="display:inline-flex; align-items:center; gap:0.28rem; padding:0.4rem 0.75rem; background:#fff; color:#374151; border-radius:8px; text-decoration:none; font-weight:700; font-size:0.78rem; font-family:'Tajawal',sans-serif; border:1.5px solid #e5e7eb; transition:all 0.2s; white-space:nowrap;"
                                    onmouseover="this.style.borderColor='#16a34a'; this.style.color='#16a34a'; this.style.background='#f0fdf4'" onmouseout="this.style.borderColor='#e5e7eb'; this.style.color='#374151'; this.style.background='#fff'">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                    المالي
                                </a>

                                {{-- المرفقات --}}
                                <span title="قريباً"
                                    style="display:inline-flex; align-items:center; gap:0.28rem; padding:0.4rem 0.65rem; background:#f9fafb; color:#d1d5db; border-radius:8px; font-weight:700; font-size:0.78rem; font-family:'Tajawal',sans-serif; border:1.5px solid #f3f4f6; cursor:not-allowed; white-space:nowrap;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                                    مرفقات
                                </span>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($patients->hasPages())
        <div style="padding:0.9rem 1.5rem; border-top:1px solid #f3f4f6; background:#fcfdfe; display:flex; justify-content:flex-end;">
            <div class="custom-pagination">{{ $patients->links() }}</div>
        </div>
        @endif

    </div>

    @else
    <div style="text-align:center; padding:4rem 2rem; background:#fff; border:2px dashed #e5e7eb; border-radius:16px;">
        <div style="width:64px; height:64px; background:#f9fafb; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </div>
        <div style="font-size:1rem; font-weight:800; color:#9ca3af; font-family:'Tajawal',sans-serif;">لا توجد نتائج مطابقة</div>
        <div style="font-size:0.82rem; color:#d1d5db; margin-top:0.4rem; font-family:'Tajawal',sans-serif;">حاول البحث بكلمة مختلفة</div>
        <button wire:click="resetSearch"
            style="margin-top:1.25rem; padding:0.55rem 1.75rem; background:var(--primary); color:#fff; border:none; border-radius:8px; font-family:'Tajawal',sans-serif; font-weight:700; font-size:0.88rem; cursor:pointer;">
            بحث جديد
        </button>
    </div>
    @endif

</div>
@endif

</div>
