<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1400px; margin:0 auto; animation:fadeIn 0.4s ease;">

{{-- رسالة نجاح --}}
@if(session('movement_saved'))
<div style="margin-bottom:1rem; background:#ecfdf5; border:2px solid #4caf50; border-radius:10px; padding:12px 20px; display:flex; align-items:center; gap:10px; font-family:'Tajawal',sans-serif;">
    <span style="font-size:1.3rem;">✅</span>
    <span style="font-weight:800; color:#1b5e20;">{{ session('movement_saved') }}</span>
</div>
@endif

{{-- الإطار الرئيسي --}}
<div style="background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow-sm); overflow:hidden;">

    {{-- رأس الصفحة --}}
    <div style="padding:1.1rem 1.75rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="width:42px; height:42px; background:var(--primary-glow); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">💳</div>
            <div>
                <h1 style="font-size:1.3rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">الحركات المالية</h1>
                <div style="font-size:0.72rem; color:var(--text-muted); font-weight:700; letter-spacing:1px;">FINANCIAL MOVEMENTS</div>
            </div>
        </div>
        <div style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap;">
            <button wire:click="openAddModal"
                style="padding:0.55rem 1.25rem; background:var(--primary); color:#fff; border:none; border-radius:8px; font-family:'Tajawal',sans-serif; font-weight:800; font-size:0.88rem; cursor:pointer; display:flex; align-items:center; gap:0.4rem; box-shadow:0 3px 10px rgba(139,28,43,0.25); transition:all 0.2s;"
                onmouseover="this.style.background='#6e1522'" onmouseout="this.style.background='var(--primary)'">
                ➕ إضافة حركة مالية
            </button>
            <a href="{{ route('dashboard') }}" wire:navigate
                style="padding:0.5rem 1rem; background:#fff; color:var(--text-dim); border:1px solid var(--border); border-radius:8px; font-family:'Tajawal',sans-serif; font-weight:700; font-size:0.82rem; text-decoration:none; display:flex; align-items:center; gap:0.3rem;"
                onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                🏠 الرئيسية
            </a>
        </div>
    </div>

    <div style="padding:1.5rem 1.75rem;">

        {{-- ═══ فلتر البحث ═══ --}}
        <div style="background:linear-gradient(135deg, var(--navy) 0%, #252550 100%); border-radius:12px; padding:1.5rem; margin-bottom:1.5rem; border:2px solid rgba(200,148,26,0.3);">
            <div style="color:rgba(255,255,255,0.7); font-size:0.75rem; font-weight:800; letter-spacing:2px; margin-bottom:1.1rem; text-transform:uppercase;">
                الحركات المدخلة &nbsp;:&nbsp; <span style="color:var(--gold);">Movements Display</span>
            </div>

            <div class="pg-2col" style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem 1.25rem;">

                {{-- التصنيف --}}
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <label style="color:rgba(255,255,255,0.8); font-size:0.82rem; font-weight:800; white-space:nowrap; min-width:115px; text-align:right;">التصنيف : Class</label>
                    <select style="flex:1; padding:0.55rem 0.9rem; border:1.5px solid rgba(200,148,26,0.4); border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.88rem; background:#1e1e3a; color:#fff; outline:none; cursor:pointer;">
                        <option value="1" style="background:#1e1e3a;">مطمئنة الكويت</option>
                    </select>
                </div>

                {{-- نوع الحركة --}}
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <label style="color:rgba(255,255,255,0.8); font-size:0.82rem; font-weight:800; white-space:nowrap; min-width:115px; text-align:right;">نوع الحركة : Type</label>
                    <select wire:model="filterType"
                        style="flex:1; padding:0.55rem 0.9rem; border:1.5px solid rgba(200,148,26,0.4); border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.88rem; background:#1e1e3a; color:#fff; outline:none; cursor:pointer;">
                        <option value="all"     style="background:#1e1e3a;">الكل : All</option>
                        <option value="receipt" style="background:#1e1e3a;">سند قبض : Receipt</option>
                        <option value="payment" style="background:#1e1e3a;">سند صرف : Payment</option>
                    </select>
                </div>

                {{-- من تاريخ --}}
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <label style="color:rgba(255,255,255,0.8); font-size:0.82rem; font-weight:800; white-space:nowrap; min-width:115px; text-align:right;">من تاريخ : From</label>
                    <input type="date" wire:model="fromDate"
                        style="flex:1; padding:0.55rem 0.9rem; border:1.5px solid rgba(200,148,26,0.4); border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.88rem; background:#1e1e3a; color:#fff; outline:none; cursor:pointer; color-scheme:dark;">
                </div>

                {{-- حتى تاريخ --}}
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <label style="color:rgba(255,255,255,0.8); font-size:0.82rem; font-weight:800; white-space:nowrap; min-width:115px; text-align:right;">حتى تاريخ : To</label>
                    <input type="date" wire:model="toDate"
                        style="flex:1; padding:0.55rem 0.9rem; border:1.5px solid rgba(200,148,26,0.4); border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.88rem; background:#1e1e3a; color:#fff; outline:none; cursor:pointer; color-scheme:dark;">
                </div>

            </div>

            {{-- أزرار الفلتر --}}
            <div style="display:flex; gap:0.75rem; justify-content:center; margin-top:1.25rem; padding-top:1rem; border-top:1px solid rgba(200,148,26,0.2);">
                <button wire:click="send"
                    style="padding:0.6rem 2.5rem; background:var(--gold); color:#1a1a2e; border:none; border-radius:8px; font-family:'Tajawal',sans-serif; font-weight:900; font-size:0.92rem; cursor:pointer; box-shadow:0 3px 10px rgba(200,148,26,0.35); transition:all 0.2s;"
                    onmouseover="this.style.background='#b8841a'" onmouseout="this.style.background='var(--gold)'">
                    إرسال : Send
                </button>
                <button wire:click="resetFilters"
                    style="padding:0.6rem 2rem; background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.25); border-radius:8px; font-family:'Tajawal',sans-serif; font-weight:800; font-size:0.88rem; cursor:pointer; transition:all 0.2s;"
                    onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                    إستعادة : Reset
                </button>
            </div>
        </div>

        {{-- ═══ النتائج ═══ --}}
        @if($searched && $movements !== null)

        {{-- رأس النتائج --}}
        <div style="background:#f8fafc; border:1px solid var(--border); border-radius:10px; padding:0.85rem 1.25rem; margin-bottom:1rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
            <div>
                <div style="font-weight:900; color:var(--navy); font-size:0.95rem; font-family:'Tajawal',sans-serif;">
                    الحركات المدخلة من
                    <span style="color:var(--primary);">{{ $fromDate }}</span>
                    الى
                    <span style="color:var(--primary);">{{ $toDate }}</span>
                </div>
                <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.2rem;">
                    {{ $movements->total() }} حركة مالية
                    @if($filterType !== 'all')
                     — <span style="color:{{ $filterType==='receipt' ? '#16a34a' : '#dc2626' }}; font-weight:800;">{{ $filterType==='receipt' ? 'سندات قبض فقط' : 'سندات صرف فقط' }}</span>
                    @endif
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
                {{-- بحث بالحساب --}}
                <div style="position:relative;">
                    <input type="text" wire:model.live.debounce.400ms="accountSearch"
                        placeholder="Account : الحساب"
                        style="padding:0.5rem 2.5rem 0.5rem 0.85rem; border:1.5px solid var(--border); border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.82rem; outline:none; width:200px; transition:border-color 0.2s;"
                        onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
                    <span style="position:absolute; right:0.75rem; top:50%; transform:translateY(-50%); opacity:0.4;">🔍</span>
                </div>
                {{-- الإجمالي --}}
                <div style="background:linear-gradient(135deg,#e8f5e9,#f1faf2); border:1px solid #c8e6c9; border-radius:8px; padding:0.5rem 1rem; text-align:center;">
                    <div style="font-size:0.7rem; font-weight:800; color:#2e7d32; letter-spacing:1px;">الإجمالي</div>
                    <div style="font-size:1.1rem; font-weight:900; color:#1b5e20; font-family:'Inter';">{{ number_format($grandTotal, 3) }} <span style="font-size:0.75rem; color:#4caf50;">د.ك</span></div>
                </div>
            </div>
        </div>

        {{-- جدول الحركات --}}
        <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden;">
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif;">
                    <thead>
                        <tr style="background:var(--navy); color:#fff;">
                            <th style="padding:0.75rem 0.7rem; text-align:center; font-size:0.75rem; font-weight:900; width:42px;">#</th>
                            <th style="padding:0.75rem 0.7rem; text-align:center; font-size:0.75rem; font-weight:900; white-space:nowrap;">التاريخ<br><span style="font-weight:400; opacity:0.65; font-size:0.65rem; letter-spacing:1px;">Date</span></th>
                            <th style="padding:0.75rem 0.7rem; text-align:center; font-size:0.75rem; font-weight:900; white-space:nowrap;">رقم السند<br><span style="font-weight:400; opacity:0.65; font-size:0.65rem; letter-spacing:1px;">Vno</span></th>
                            <th style="padding:0.75rem 1rem; text-align:right; font-size:0.75rem; font-weight:900; white-space:nowrap;">الحساب<br><span style="font-weight:400; opacity:0.65; font-size:0.65rem; letter-spacing:1px;">Patient</span></th>
                            <th style="padding:0.75rem 0.7rem; text-align:center; font-size:0.75rem; font-weight:900; white-space:nowrap;">المبلغ<br><span style="font-weight:400; opacity:0.65; font-size:0.65rem; letter-spacing:1px;">Amount</span></th>
                            <th style="padding:0.75rem 1rem; text-align:right; font-size:0.75rem; font-weight:900; white-space:nowrap;">البيان<br><span style="font-weight:400; opacity:0.65; font-size:0.65rem; letter-spacing:1px;">Desc</span></th>
                            <th style="padding:0.75rem 0.7rem; text-align:center; font-size:0.75rem; font-weight:900; white-space:nowrap;">التصنيف<br><span style="font-weight:400; opacity:0.65; font-size:0.65rem; letter-spacing:1px;">Class</span></th>
                            <th style="padding:0.75rem 0.7rem; text-align:center; font-size:0.75rem; font-weight:900; white-space:nowrap;">الموظف<br><span style="font-weight:400; opacity:0.65; font-size:0.65rem; letter-spacing:1px;">User</span></th>
                            <th style="padding:0.75rem 0.7rem; text-align:center; font-size:0.75rem; font-weight:900; white-space:nowrap;">النوع<br><span style="font-weight:400; opacity:0.65; font-size:0.65rem; letter-spacing:1px;">Receipt</span></th>
                            <th style="padding:0.75rem 0.7rem; text-align:center; font-size:0.75rem; font-weight:900; width:50px;">حذف<br><span style="font-weight:400; opacity:0.65; font-size:0.65rem; letter-spacing:1px;">Del</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $i => $mov)
                        @php
                            $isReceipt = $mov->status == 1;
                            $rowBg = 'transparent';
                        @endphp
                        <tr wire:key="mov-{{ $mov->id }}"
                            style="border-bottom:1px solid #f1f5f9; transition:background 0.15s; background:{{ $rowBg }};"
                            onmouseover="this.style.background='{{ $isReceipt ? '#f0fdf4' : '#fff8f8' }}'"
                            onmouseout="this.style.background='{{ $rowBg }}'">

                            <td style="padding:0.7rem; text-align:center; color:var(--text-muted); font-size:0.78rem; font-weight:700;">
                                {{ ($movements->currentPage()-1) * $movements->perPage() + $loop->iteration }}
                            </td>

                            <td style="padding:0.7rem; text-align:center; direction:ltr; unicode-bidi:isolate; font-size:0.85rem; font-weight:700; color:#1e40af; white-space:nowrap;">
                                {{ fmt_date($mov->pdate) }}
                            </td>

                            <td style="padding:0.7rem; text-align:center;">
                                <span style="background:#e3f2fd; color:#1565c0; padding:0.2rem 0.65rem; border-radius:6px; font-weight:900; font-size:0.82rem; border:1px solid #bbdefb; font-family:'Inter';">
                                    #{{ $mov->id }}
                                </span>
                            </td>

                            <td style="padding:0.7rem 1rem; font-weight:800; color:var(--navy); font-size:0.9rem; white-space:nowrap;">
                                @if($mov->patient_id)
                                <a href="{{ route('patients.financial-statement', $mov->patient_id) }}" wire:navigate
                                    style="color:var(--navy); text-decoration:none; transition:color 0.15s;"
                                    onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--navy)'">
                                    {{ $mov->acck_name ?? $mov->patient_name ?? '—' }}
                                </a>
                                @else
                                    <span style="color:var(--text-muted);">{{ $mov->acck_name ?? '—' }}</span>
                                @endif
                            </td>

                            <td style="padding:0.7rem; text-align:center; font-weight:900; font-size:1rem; font-family:'Inter'; color:{{ $isReceipt ? '#166534' : '#b91c1c' }};">
                                {{ number_format($mov->mov_amount, 3) }}
                            </td>

                            <td style="padding:0.7rem 1rem; font-size:0.85rem; color:var(--text-dim); max-width:180px;">
                                <div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ strip_tags($mov->pdesc) }}">
                                    {{ strip_tags($mov->pdesc) ?: '—' }}
                                </div>
                            </td>

                            <td style="padding:0.7rem; text-align:center; font-size:0.78rem; color:var(--text-muted); font-weight:700; white-space:nowrap;">
                                مطمئنة الكويت
                            </td>

                            <td style="padding:0.7rem; text-align:center; font-size:0.82rem; color:var(--text-dim); white-space:nowrap;">
                                {{ trim($mov->emp_name) ?: '—' }}
                            </td>

                            <td style="padding:0.7rem; text-align:center;">
                                @if($isReceipt)
                                <span style="display:inline-flex; align-items:center; gap:0.25rem; padding:0.25rem 0.65rem; background:#dcfce7; color:#166534; border-radius:6px; font-size:0.78rem; font-weight:900; border:1px solid #bbf7d0; white-space:nowrap;">
                                    📄 سند قبض
                                </span>
                                @else
                                <span style="display:inline-flex; align-items:center; gap:0.25rem; padding:0.25rem 0.65rem; background:#fee2e2; color:#b91c1c; border-radius:6px; font-size:0.78rem; font-weight:900; border:1px solid #fecaca; white-space:nowrap;">
                                    📄 سند صرف
                                </span>
                                @endif
                            </td>

                            <td style="padding:0.7rem; text-align:center;">
                                <button wire:click="deleteMovement({{ $mov->id }})"
                                    wire:confirm="هل أنت متأكد من حذف هذه الحركة؟"
                                    style="width:28px; height:28px; background:#fee2e2; border:1px solid #fecaca; border-radius:6px; cursor:pointer; color:#b91c1c; font-size:0.85rem; font-weight:900; display:flex; align-items:center; justify-content:center; transition:all 0.2s; margin:auto;"
                                    onmouseover="this.style.background='#fca5a5'; this.style.transform='scale(1.1)'" onmouseout="this.style.background='#fee2e2'; this.style.transform=''">
                                    ✕
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" style="padding:4rem; text-align:center; color:var(--text-muted);">
                                <div style="font-size:2.5rem; opacity:0.15; margin-bottom:0.75rem;">💳</div>
                                <div style="font-weight:800; font-size:1rem;">لا توجد حركات مالية في هذه الفترة</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ترقيم الصفحات --}}
            @if($movements->hasPages())
            <div style="padding:0.85rem 1.5rem; border-top:1px solid var(--border); background:#fcfdfe;">
                {{ $movements->links() }}
            </div>
            @endif
        </div>

        @elseif($searched)
        <div style="text-align:center; padding:4rem; background:#f9fafb; border-radius:12px; border:2px dashed #e5e7eb;">
            <div style="font-size:2.5rem; opacity:0.2; margin-bottom:1rem;">🔍</div>
            <div style="font-size:1rem; font-weight:800; color:#9ca3af; font-family:'Tajawal',sans-serif;">لا توجد نتائج</div>
        </div>
        @endif

    </div>
</div>

</div>
</div>

{{-- ══════ MODAL: إضافة حركة مالية ══════ --}}
@if($showAddModal)
<div wire:click.self="closeAddModal"
    style="position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.6); display:flex; align-items:center; justify-content:center; padding:1rem; animation:fadeIn 0.2s ease;">
    <div style="background:#fff; border-radius:16px; width:100%; max-width:520px; box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow:hidden; animation:slideUp 0.25s cubic-bezier(0.16,1,0.3,1);">

        {{-- رأس الـ Modal --}}
        <div style="background:var(--navy); padding:1.1rem 1.5rem; display:flex; align-items:center; justify-content:space-between; border-bottom:3px solid var(--gold);">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <div style="width:36px; height:36px; background:rgba(200,148,26,0.2); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1.2rem;">💳</div>
                <div>
                    <div style="color:#fff; font-weight:900; font-size:1rem; font-family:'Tajawal',sans-serif;">إضافة حركة مالية</div>
                    <div style="color:rgba(200,148,26,0.8); font-size:0.7rem; font-weight:700; letter-spacing:1px;">NEW FINANCIAL MOVEMENT</div>
                </div>
            </div>
            <button wire:click="closeAddModal"
                style="width:32px; height:32px; border-radius:8px; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); color:rgba(255,255,255,0.7); font-size:1rem; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all 0.2s;"
                onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">✕</button>
        </div>

        {{-- جسم الـ Modal --}}
        <div style="padding:1.5rem; display:flex; flex-direction:column; gap:1rem;">

            {{-- نوع الحركة --}}
            <div style="display:flex; gap:0.75rem;">
                <button wire:click="$set('newMoveType','receipt')"
                    style="flex:1; padding:0.65rem; border-radius:8px; border:2px solid {{ $newMoveType==='receipt' ? '#16a34a' : '#e5e7eb' }}; background:{{ $newMoveType==='receipt' ? '#f0fdf4' : '#fff' }}; color:{{ $newMoveType==='receipt' ? '#166534' : '#6b7280' }}; font-family:'Tajawal',sans-serif; font-weight:900; font-size:0.88rem; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; justify-content:center; gap:0.4rem;">
                    📥 سند قبض
                </button>
                <button wire:click="$set('newMoveType','payment')"
                    style="flex:1; padding:0.65rem; border-radius:8px; border:2px solid {{ $newMoveType==='payment' ? '#dc2626' : '#e5e7eb' }}; background:{{ $newMoveType==='payment' ? '#fff0f0' : '#fff' }}; color:{{ $newMoveType==='payment' ? '#b91c1c' : '#6b7280' }}; font-family:'Tajawal',sans-serif; font-weight:900; font-size:0.88rem; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; justify-content:center; gap:0.4rem;">
                    📤 سند صرف
                </button>
            </div>

            {{-- بحث العميل --}}
            <div>
                <label style="display:block; font-size:0.78rem; font-weight:800; color:#9ca3af; letter-spacing:1px; margin-bottom:0.4rem;">الحساب / العميل</label>
                <div style="position:relative;">
                    <input type="text" wire:model.live.debounce.300ms="newClientSearch"
                        placeholder="ابحث بالاسم أو الجوال أو رقم الملف..."
                        style="width:100%; padding:0.65rem 1rem; border:1.5px solid {{ $newClientId ? '#4caf50' : '#e5e7eb' }}; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.88rem; outline:none; box-sizing:border-box; transition:border-color 0.2s; background:{{ $newClientId ? '#f0fdf4' : '#fff' }};"
                        onfocus="this.style.borderColor='var(--primary)'" onblur="if(!{{$newClientId?'true':'false'}}) this.style.borderColor='#e5e7eb'">
                    @if($newClientId)
                        <span style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:#4caf50; font-size:1rem;">✓</span>
                    @endif
                    @if(!empty($newClientResults))
                    <div style="position:absolute; top:calc(100% + 4px); left:0; right:0; background:#fff; border:1px solid #e5e7eb; border-radius:10px; box-shadow:0 12px 30px rgba(0,0,0,0.12); z-index:9999; overflow:hidden; animation:dropIn 0.15s ease;">
                        @foreach($newClientResults as $res)
                        <div wire:click="selectNewClient({{ $res->id }}, '{{ addslashes($res->full_name) }}')"
                            style="padding:0.6rem 1rem; cursor:pointer; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center; transition:background 0.15s;"
                            onmouseover="this.style.background='#fef5f5'" onmouseout="this.style.background='#fff'">
                            <div>
                                <div style="font-weight:800; color:#1a1a2e; font-size:0.85rem; font-family:'Tajawal',sans-serif;">{{ $res->full_name }}</div>
                                <div style="font-size:0.72rem; color:#9ca3af; margin-top:0.1rem;">{{ $res->phone }}</div>
                            </div>
                            <span style="background:#fef5f5; color:var(--primary); font-weight:900; font-size:0.75rem; padding:0.15rem 0.5rem; border-radius:5px; border:1px solid #fdd5da;">#{{ $res->file_id ?? $res->id }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            {{-- المبلغ وطريقة الدفع --}}
            <div class="pg-2col" style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                <div>
                    <label style="display:block; font-size:0.78rem; font-weight:800; color:#9ca3af; letter-spacing:1px; margin-bottom:0.4rem;">المبلغ (د.ك)</label>
                    <input type="number" wire:model="newAmount" min="0" step="0.001"
                        placeholder="0.000"
                        style="width:100%; padding:0.65rem 1rem; border:1.5px solid #e5e7eb; border-radius:8px; font-family:'Inter','Tajawal',sans-serif; font-size:0.95rem; font-weight:700; outline:none; box-sizing:border-box; transition:border-color 0.2s; text-align:center;"
                        onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e5e7eb'">
                </div>
                <div>
                    <label style="display:block; font-size:0.78rem; font-weight:800; color:#9ca3af; letter-spacing:1px; margin-bottom:0.4rem;">طريقة الدفع</label>
                    <select wire:model="newPayMethod"
                        style="width:100%; padding:0.65rem 1rem; border:1.5px solid #e5e7eb; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.88rem; outline:none; background:#fff; cursor:pointer;">
                        @foreach($payMethods as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- التاريخ --}}
            <div>
                <label style="display:block; font-size:0.78rem; font-weight:800; color:#9ca3af; letter-spacing:1px; margin-bottom:0.4rem;">التاريخ</label>
                <div style="display:flex; gap:0.5rem;">
                    <select wire:model="newDay" style="flex:0.8; padding:0.6rem; border:1.5px solid #e5e7eb; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.82rem; outline:none; background:#fff;">
                        @for($d=1;$d<=31;$d++)<option value="{{ $d }}">{{ $d }}</option>@endfor
                    </select>
                    <select wire:model="newMonth" style="flex:1.5; padding:0.6rem; border:1.5px solid #e5e7eb; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.82rem; outline:none; background:#fff;">
                        @foreach(['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'] as $mi=>$mn)
                        <option value="{{ $mi+1 }}">{{ $mn }}</option>
                        @endforeach
                    </select>
                    <select wire:model="newYear" style="flex:1; padding:0.6rem; border:1.5px solid #e5e7eb; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.82rem; outline:none; background:#fff;">
                        @for($y=2020;$y<=2027;$y++)<option value="{{ $y }}">{{ $y }}</option>@endfor
                    </select>
                </div>
            </div>

            {{-- البيان --}}
            <div>
                <label style="display:block; font-size:0.78rem; font-weight:800; color:#9ca3af; letter-spacing:1px; margin-bottom:0.4rem;">البيان / Desc</label>
                <textarea wire:model="newDesc" rows="2"
                    placeholder="أدخل بيان الحركة..."
                    style="width:100%; padding:0.65rem 1rem; border:1.5px solid #e5e7eb; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.88rem; outline:none; resize:vertical; box-sizing:border-box; transition:border-color 0.2s;"
                    onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e5e7eb'"></textarea>
            </div>

        </div>

        {{-- ذيل الـ Modal --}}
        <div style="padding:1rem 1.5rem; background:#f8fafc; border-top:1px solid var(--border); display:flex; gap:0.75rem; justify-content:flex-end;">
            <button wire:click="closeAddModal"
                style="padding:0.6rem 1.5rem; background:#fff; color:#6b7280; border:1px solid #e5e7eb; border-radius:8px; font-family:'Tajawal',sans-serif; font-weight:700; font-size:0.88rem; cursor:pointer; transition:all 0.2s;"
                onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='#fff'">
                إلغاء
            </button>
            <button wire:click="saveMovement"
                style="padding:0.6rem 2rem; background:{{ $newMoveType==='receipt' ? 'var(--primary)' : '#dc2626' }}; color:#fff; border:none; border-radius:8px; font-family:'Tajawal',sans-serif; font-weight:900; font-size:0.88rem; cursor:pointer; box-shadow:0 3px 10px rgba(0,0,0,0.2); transition:all 0.2s;"
                onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                💾 حفظ {{ $newMoveType==='receipt' ? 'سند القبض' : 'سند الصرف' }}
            </button>
        </div>
    </div>
</div>

<style>
@keyframes slideUp {
    from { opacity:0; transform:translateY(20px) scale(0.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
@keyframes dropIn {
    from { opacity:0; transform:translateY(-6px); }
    to   { opacity:1; transform:translateY(0); }
}
</style>
@endif
