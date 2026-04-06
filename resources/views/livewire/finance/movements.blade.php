<div class="pg-outer" style="min-height:80vh; padding:1.25rem 1.5rem;">

{{-- رسالة نجاح --}}
@if(session('movement_saved'))
<div style="margin-bottom:1rem; background:#ecfdf5; border:2px solid #4caf50; border-radius:10px; padding:0.85rem 1.25rem; display:flex; align-items:center; gap:0.75rem; font-family:'Tajawal',sans-serif;">
    <span style="font-size:1.2rem;">✅</span>
    <span style="font-weight:800; color:#1b5e20; font-size:0.9rem;">{{ session('movement_saved') }}</span>
</div>
@endif

{{-- عنوان الصفحة --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; flex-wrap:wrap; gap:0.75rem;">
    <div style="display:flex; align-items:center; gap:0.75rem;">
        <div style="width:40px; height:40px; background:var(--primary-glow); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.3rem; flex-shrink:0;">💳</div>
        <div>
            <h1 style="font-size:1.2rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">الحركات المالية</h1>
            <div style="font-size:0.7rem; color:var(--text-muted); font-weight:700; letter-spacing:1px;">FINANCIAL MOVEMENTS</div>
        </div>
    </div>
    <a href="{{ route('dashboard') }}" wire:navigate
        style="padding:0.45rem 1rem; background:#fff; color:var(--text-dim); border:1px solid var(--border); border-radius:8px; font-family:'Tajawal',sans-serif; font-weight:700; font-size:0.82rem; text-decoration:none; display:flex; align-items:center; gap:0.3rem;"
        onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
        🏠 الرئيسية
    </a>
</div>

{{-- ═══════════════════════════════════════════
     التخطيط الرئيسي: فورم يسار + نتائج يمين
════════════════════════════════════════════ --}}
<div style="display:grid; grid-template-columns:380px 1fr; gap:1.25rem; align-items:start;">

    {{-- ═══ عمود الفورم ═══ --}}
    <div style="background:#fff; border:1px solid var(--border); border-radius:14px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.06); position:sticky; top:1rem;">

        {{-- رأس الفورم --}}
        <div style="background:var(--navy); padding:1rem 1.25rem; border-bottom:3px solid var(--gold);">
            <div style="display:flex; align-items:center; gap:0.6rem;">
                <div style="width:32px; height:32px; background:rgba(200,148,26,0.2); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1rem;">💳</div>
                <div>
                    <div style="color:#fff; font-weight:900; font-size:0.95rem; font-family:'Tajawal',sans-serif;">إضافة حركة مالية</div>
                    <div style="color:rgba(200,148,26,0.8); font-size:0.65rem; font-weight:700; letter-spacing:1px;">NEW FINANCIAL MOVEMENT</div>
                </div>
            </div>
        </div>

        {{-- جسم الفورم --}}
        <div style="padding:1.25rem; display:flex; flex-direction:column; gap:1rem;">

            {{-- نوع الحركة --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem;">
                <button wire:click="$set('newMoveType','receipt')"
                    style="padding:0.6rem; border-radius:8px; border:2px solid {{ $newMoveType==='receipt' ? '#16a34a' : '#e5e7eb' }}; background:{{ $newMoveType==='receipt' ? '#f0fdf4' : '#fff' }}; color:{{ $newMoveType==='receipt' ? '#166534' : '#6b7280' }}; font-family:'Tajawal',sans-serif; font-weight:900; font-size:0.85rem; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; justify-content:center; gap:0.3rem;">
                    📥 سند قبض
                </button>
                <button wire:click="$set('newMoveType','payment')"
                    style="padding:0.6rem; border-radius:8px; border:2px solid {{ $newMoveType==='payment' ? '#dc2626' : '#e5e7eb' }}; background:{{ $newMoveType==='payment' ? '#fff0f0' : '#fff' }}; color:{{ $newMoveType==='payment' ? '#b91c1c' : '#6b7280' }}; font-family:'Tajawal',sans-serif; font-weight:900; font-size:0.85rem; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; justify-content:center; gap:0.3rem;">
                    📤 سند صرف
                </button>
            </div>

            {{-- بحث العميل --}}
            <div>
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#6b7280; letter-spacing:0.5px; margin-bottom:0.35rem;">الحساب / العميل <span style="color:#dc2626;">*</span></label>
                <div style="position:relative;">
                    <input type="text" wire:model.live.debounce.300ms="newClientSearch"
                        placeholder="ابحث بالاسم أو الجوال أو رقم الملف..."
                        style="width:100%; padding:0.6rem 2.2rem 0.6rem 0.85rem; border:1.5px solid {{ $newClientId ? '#4caf50' : '#e5e7eb' }}; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.85rem; outline:none; box-sizing:border-box; background:{{ $newClientId ? '#f0fdf4' : '#fff' }}; transition:border-color 0.2s;"
                        onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='{{ $newClientId ? '#4caf50' : '#e5e7eb' }}'">
                    @if($newClientId)
                        <span style="position:absolute; left:0.6rem; top:50%; transform:translateY(-50%); color:#4caf50; font-size:0.9rem;">✓</span>
                    @else
                        <span style="position:absolute; left:0.6rem; top:50%; transform:translateY(-50%); opacity:0.3; font-size:0.85rem;">🔍</span>
                    @endif
                    @if(!empty($newClientResults))
                    <div style="position:absolute; top:calc(100% + 3px); left:0; right:0; background:#fff; border:1px solid #e5e7eb; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,0.12); z-index:9999; overflow:hidden;">
                        @foreach($newClientResults as $res)
                        <div wire:click="selectNewClient({{ $res->id }}, '{{ addslashes($res->full_name) }}')"
                            style="padding:0.5rem 0.85rem; cursor:pointer; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center; transition:background 0.15s;"
                            onmouseover="this.style.background='#fef5f5'" onmouseout="this.style.background='#fff'">
                            <div>
                                <div style="font-weight:800; color:#1a1a2e; font-size:0.83rem; font-family:'Tajawal',sans-serif;">{{ $res->full_name }}</div>
                                <div style="font-size:0.7rem; color:#9ca3af;">{{ $res->phone }}</div>
                            </div>
                            <span style="background:#fef5f5; color:var(--primary); font-weight:900; font-size:0.72rem; padding:0.1rem 0.45rem; border-radius:5px; border:1px solid #fdd5da;">#{{ $res->file_id ?? $res->id }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            {{-- المبلغ + طريقة الدفع --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#6b7280; letter-spacing:0.5px; margin-bottom:0.35rem;">المبلغ (د.ك) <span style="color:#dc2626;">*</span></label>
                    <input type="number" wire:model="newAmount" min="0" step="0.001" placeholder="0.000"
                        style="width:100%; padding:0.6rem 0.85rem; border:1.5px solid #e5e7eb; border-radius:8px; font-family:'Inter','Tajawal',sans-serif; font-size:0.95rem; font-weight:700; outline:none; box-sizing:border-box; text-align:center; transition:border-color 0.2s;"
                        onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e5e7eb'">
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#6b7280; letter-spacing:0.5px; margin-bottom:0.35rem;">طريقة الدفع</label>
                    <select wire:model="newPayMethod"
                        style="width:100%; padding:0.6rem 0.85rem; border:1.5px solid #e5e7eb; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.85rem; outline:none; background:#fff; cursor:pointer; box-sizing:border-box;">
                        @foreach($payMethods as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- التاريخ --}}
            <div>
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#6b7280; letter-spacing:0.5px; margin-bottom:0.35rem;">التاريخ</label>
                <div style="display:grid; grid-template-columns:0.7fr 1.3fr 1fr; gap:0.4rem;">
                    <select wire:model="newDay" style="padding:0.55rem 0.4rem; border:1.5px solid #e5e7eb; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.82rem; outline:none; background:#fff; text-align:center;">
                        @for($d=1;$d<=31;$d++)<option value="{{ $d }}">{{ $d }}</option>@endfor
                    </select>
                    <select wire:model="newMonth" style="padding:0.55rem 0.4rem; border:1.5px solid #e5e7eb; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.82rem; outline:none; background:#fff;">
                        @foreach(['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'] as $mi=>$mn)
                        <option value="{{ $mi+1 }}">{{ $mn }}</option>
                        @endforeach
                    </select>
                    <select wire:model="newYear" style="padding:0.55rem 0.4rem; border:1.5px solid #e5e7eb; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.82rem; outline:none; background:#fff; text-align:center;">
                        @for($y=2020;$y<=2027;$y++)<option value="{{ $y }}">{{ $y }}</option>@endfor
                    </select>
                </div>
            </div>

            {{-- البيان --}}
            <div>
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#6b7280; letter-spacing:0.5px; margin-bottom:0.35rem;">البيان / Desc</label>
                <textarea wire:model="newDesc" rows="2" placeholder="أدخل بيان الحركة..."
                    style="width:100%; padding:0.6rem 0.85rem; border:1.5px solid #e5e7eb; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.85rem; outline:none; resize:vertical; box-sizing:border-box; transition:border-color 0.2s;"
                    onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e5e7eb'"></textarea>
            </div>

            {{-- زر الحفظ --}}
            <button wire:click="saveMovement" wire:loading.attr="disabled"
                style="width:100%; padding:0.8rem; background:{{ $newMoveType==='receipt' ? 'var(--primary)' : '#dc2626' }}; color:#fff; border:none; border-radius:10px; font-family:'Tajawal',sans-serif; font-weight:900; font-size:0.95rem; cursor:pointer; box-shadow:0 3px 12px rgba(0,0,0,0.2); transition:all 0.2s; display:flex; align-items:center; justify-content:center; gap:0.5rem;"
                onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
                <span wire:loading.remove wire:target="saveMovement">
                    💾 حفظ {{ $newMoveType==='receipt' ? 'سند القبض' : 'سند الصرف' }}
                </span>
                <span wire:loading wire:target="saveMovement">جارٍ الحفظ...</span>
            </button>

        </div>
    </div>

    {{-- ═══ عمود النتائج ═══ --}}
    <div style="display:flex; flex-direction:column; gap:1rem;">

        {{-- فلتر البحث --}}
        <div style="background:linear-gradient(135deg, var(--navy) 0%, #252550 100%); border-radius:12px; padding:1.25rem; border:2px solid rgba(200,148,26,0.3);">
            <div style="color:rgba(255,255,255,0.6); font-size:0.7rem; font-weight:800; letter-spacing:2px; margin-bottom:1rem; text-transform:uppercase;">
                الحركات المدخلة &nbsp;:&nbsp; <span style="color:var(--gold);">Movements Display</span>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.65rem;">
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <label style="color:rgba(255,255,255,0.75); font-size:0.78rem; font-weight:800; white-space:nowrap; min-width:80px;">نوع الحركة</label>
                    <select wire:model="filterType"
                        style="flex:1; padding:0.5rem 0.75rem; border:1.5px solid rgba(200,148,26,0.4); border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.85rem; background:#1e1e3a; color:#fff; outline:none;">
                        <option value="all" style="background:#1e1e3a;">الكل</option>
                        <option value="receipt" style="background:#1e1e3a;">سند قبض</option>
                        <option value="payment" style="background:#1e1e3a;">سند صرف</option>
                    </select>
                </div>
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <label style="color:rgba(255,255,255,0.75); font-size:0.78rem; font-weight:800; white-space:nowrap; min-width:80px;">من تاريخ</label>
                    <input type="date" wire:model="fromDate"
                        style="flex:1; padding:0.5rem 0.75rem; border:1.5px solid rgba(200,148,26,0.4); border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.85rem; background:#1e1e3a; color:#fff; outline:none; color-scheme:dark;">
                </div>
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <label style="color:rgba(255,255,255,0.75); font-size:0.78rem; font-weight:800; white-space:nowrap; min-width:80px;">حتى تاريخ</label>
                    <input type="date" wire:model="toDate"
                        style="flex:1; padding:0.5rem 0.75rem; border:1.5px solid rgba(200,148,26,0.4); border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.85rem; background:#1e1e3a; color:#fff; outline:none; color-scheme:dark;">
                </div>
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <label style="color:rgba(255,255,255,0.75); font-size:0.78rem; font-weight:800; white-space:nowrap; min-width:80px;">العميل</label>
                    <input type="text" wire:model.live.debounce.400ms="accountSearch" placeholder="بحث..."
                        style="flex:1; padding:0.5rem 0.75rem; border:1.5px solid rgba(200,148,26,0.4); border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.85rem; background:#1e1e3a; color:#fff; outline:none;">
                </div>
            </div>

            <div style="display:flex; gap:0.65rem; justify-content:center; margin-top:1rem; padding-top:0.85rem; border-top:1px solid rgba(200,148,26,0.2);">
                <button wire:click="send"
                    style="padding:0.55rem 2.5rem; background:var(--gold); color:#1a1a2e; border:none; border-radius:8px; font-family:'Tajawal',sans-serif; font-weight:900; font-size:0.9rem; cursor:pointer; box-shadow:0 3px 10px rgba(200,148,26,0.35); transition:all 0.2s;">
                    إرسال
                </button>
                <button wire:click="resetFilters"
                    style="padding:0.55rem 1.75rem; background:rgba(255,255,255,0.12); color:#fff; border:1px solid rgba(255,255,255,0.2); border-radius:8px; font-family:'Tajawal',sans-serif; font-weight:800; font-size:0.88rem; cursor:pointer; transition:all 0.2s;">
                    إستعادة
                </button>
            </div>
        </div>

        {{-- النتائج --}}
        @if($searched && $movements !== null)

        {{-- رأس النتائج --}}
        <div style="background:#f8fafc; border:1px solid var(--border); border-radius:10px; padding:0.75rem 1.1rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem;">
            <div>
                <div style="font-weight:900; color:var(--navy); font-size:0.88rem; font-family:'Tajawal',sans-serif;">
                    من <span style="color:var(--primary);">{{ $fromDate }}</span> إلى <span style="color:var(--primary);">{{ $toDate }}</span>
                </div>
                <div style="font-size:0.72rem; color:var(--text-muted); margin-top:0.15rem;">{{ $movements->total() }} حركة مالية</div>
            </div>
            <div style="background:linear-gradient(135deg,#e8f5e9,#f1faf2); border:1px solid #c8e6c9; border-radius:8px; padding:0.4rem 0.9rem; text-align:center;">
                <div style="font-size:0.65rem; font-weight:800; color:#2e7d32; letter-spacing:1px;">الإجمالي</div>
                <div style="font-size:1rem; font-weight:900; color:#1b5e20; font-family:'Inter';">{{ number_format($grandTotal, 3) }} <span style="font-size:0.7rem; color:#4caf50;">د.ك</span></div>
            </div>
        </div>

        {{-- الجدول --}}
        <div style="background:#fff; border:1px solid var(--border); border-radius:12px; overflow:hidden;">
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif;">
                    <thead>
                        <tr style="background:var(--navy); color:#fff;">
                            <th style="padding:0.65rem 0.6rem; font-size:0.72rem; font-weight:900; text-align:center; white-space:nowrap;">#</th>
                            <th style="padding:0.65rem 0.6rem; font-size:0.72rem; font-weight:900; text-align:center; white-space:nowrap;">التاريخ</th>
                            <th style="padding:0.65rem 0.6rem; font-size:0.72rem; font-weight:900; text-align:center; white-space:nowrap;">السند</th>
                            <th style="padding:0.65rem 0.85rem; font-size:0.72rem; font-weight:900; text-align:right; white-space:nowrap;">الحساب</th>
                            <th style="padding:0.65rem 0.6rem; font-size:0.72rem; font-weight:900; text-align:center; white-space:nowrap;">المبلغ</th>
                            <th style="padding:0.65rem 0.85rem; font-size:0.72rem; font-weight:900; text-align:right; white-space:nowrap;">البيان</th>
                            <th style="padding:0.65rem 0.6rem; font-size:0.72rem; font-weight:900; text-align:center; white-space:nowrap;">الموظف</th>
                            <th style="padding:0.65rem 0.6rem; font-size:0.72rem; font-weight:900; text-align:center; white-space:nowrap;">النوع</th>
                            <th style="padding:0.65rem 0.6rem; font-size:0.72rem; font-weight:900; text-align:center; width:40px;">✕</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $mov)
                        @php $isReceipt = $mov->status == 1; @endphp
                        <tr wire:key="mov-{{ $mov->id }}"
                            style="border-bottom:1px solid #f1f5f9; transition:background 0.15s;"
                            onmouseover="this.style.background='{{ $isReceipt ? '#f0fdf4' : '#fff8f8' }}'"
                            onmouseout="this.style.background='transparent'">
                            <td style="padding:0.6rem; text-align:center; color:var(--text-muted); font-size:0.75rem; font-weight:700;">{{ ($movements->currentPage()-1)*$movements->perPage()+$loop->iteration }}</td>
                            <td style="padding:0.6rem; text-align:center; font-size:0.82rem; font-weight:700; color:#1e40af; white-space:nowrap; direction:ltr;">{{ fmt_date($mov->pdate) }}</td>
                            <td style="padding:0.6rem; text-align:center;">
                                <span style="background:#e3f2fd; color:#1565c0; padding:0.15rem 0.55rem; border-radius:5px; font-weight:900; font-size:0.78rem; font-family:'Inter';">#{{ $mov->id }}</span>
                            </td>
                            <td style="padding:0.6rem 0.85rem; font-weight:800; color:var(--navy); font-size:0.88rem; white-space:nowrap;">
                                @if($mov->patient_id)
                                <a href="{{ route('patients.financial-statement', $mov->patient_id) }}" wire:navigate style="color:var(--navy); text-decoration:none;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--navy)'">{{ $mov->acck_name ?? $mov->patient_name ?? '—' }}</a>
                                @else
                                <span style="color:var(--text-muted);">{{ $mov->acck_name ?? '—' }}</span>
                                @endif
                            </td>
                            <td style="padding:0.6rem; text-align:center; font-weight:900; font-size:0.95rem; font-family:'Inter'; color:{{ $isReceipt ? '#166534' : '#b91c1c' }}; white-space:nowrap;">{{ number_format($mov->mov_amount, 3) }}</td>
                            <td style="padding:0.6rem 0.85rem; font-size:0.82rem; color:var(--text-dim); max-width:160px;">
                                <div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ strip_tags($mov->pdesc) }}">{{ strip_tags($mov->pdesc) ?: '—' }}</div>
                            </td>
                            <td style="padding:0.6rem; text-align:center; font-size:0.78rem; color:var(--text-dim); white-space:nowrap;">{{ trim($mov->emp_name) ?: '—' }}</td>
                            <td style="padding:0.6rem; text-align:center;">
                                @if($isReceipt)
                                <span style="padding:0.2rem 0.55rem; background:#dcfce7; color:#166534; border-radius:5px; font-size:0.72rem; font-weight:900; border:1px solid #bbf7d0; white-space:nowrap;">قبض</span>
                                @else
                                <span style="padding:0.2rem 0.55rem; background:#fee2e2; color:#b91c1c; border-radius:5px; font-size:0.72rem; font-weight:900; border:1px solid #fecaca; white-space:nowrap;">صرف</span>
                                @endif
                            </td>
                            <td style="padding:0.6rem; text-align:center;">
                                <button wire:click="deleteMovement({{ $mov->id }})" wire:confirm="هل أنت متأكد من حذف هذه الحركة؟"
                                    style="width:26px; height:26px; background:#fee2e2; border:1px solid #fecaca; border-radius:5px; cursor:pointer; color:#b91c1c; font-size:0.8rem; font-weight:900; display:flex; align-items:center; justify-content:center; margin:auto; transition:all 0.2s;"
                                    onmouseover="this.style.background='#fca5a5'" onmouseout="this.style.background='#fee2e2'">✕</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" style="padding:3rem; text-align:center; color:var(--text-muted);">
                                <div style="font-size:2rem; opacity:0.15; margin-bottom:0.5rem;">💳</div>
                                <div style="font-weight:800; font-size:0.9rem;">لا توجد حركات مالية في هذه الفترة</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($movements->hasPages())
            <div style="padding:0.75rem 1.25rem; border-top:1px solid var(--border); background:#fcfdfe;">
                {{ $movements->links() }}
            </div>
            @endif
        </div>

        @elseif($searched)
        <div style="text-align:center; padding:3rem; background:#f9fafb; border-radius:12px; border:2px dashed #e5e7eb;">
            <div style="font-size:2rem; opacity:0.2; margin-bottom:0.75rem;">🔍</div>
            <div style="font-size:0.9rem; font-weight:800; color:#9ca3af;">لا توجد نتائج</div>
        </div>

        @else
        <div style="text-align:center; padding:3rem; background:#f9fafb; border-radius:12px; border:2px dashed #e5e7eb;">
            <div style="font-size:2rem; opacity:0.2; margin-bottom:0.75rem;">📋</div>
            <div style="font-size:0.9rem; font-weight:800; color:#9ca3af;">حدّد الفترة واضغط إرسال لعرض الحركات</div>
        </div>
        @endif

    </div>{{-- end col results --}}
</div>{{-- end grid --}}

<style>
@media (max-width: 900px) {
    .pg-outer > div[style*="grid-template-columns:380px"] {
        grid-template-columns: 1fr !important;
    }
}
</style>

</div>
