<div style="min-height:80vh; padding:1.5rem 2rem; display:flex; justify-content:center; align-items:flex-start;">
<div style="width:100%; max-width:680px; animation:fadeIn 0.4s ease;">

@php
$days   = range(1, 31);
$months = [1=>'يناير',2=>'فبراير',3=>'مارس',4=>'أبريل',5=>'مايو',6=>'يونيو',
           7=>'يوليو',8=>'أغسطس',9=>'سبتمبر',10=>'أكتوبر',11=>'نوفمبر',12=>'ديسمبر'];
$years  = range(2000, now()->year + 1);
@endphp

<div style="border-radius:14px; overflow:visible; border:2px solid var(--gold); box-shadow:0 8px 25px rgba(0,0,0,0.12);">

    {{-- رأس --}}
    <div style="background:var(--navy); padding:0.9rem 1.5rem; text-align:center; border-radius:12px 12px 0 0;">
        <span style="color:#fbbf24; font-size:1.05rem; font-weight:900; font-family:'Tajawal',sans-serif;">
            تقارير السندات : Vouchers Reports
        </span>
    </div>

    <div style="background:var(--navy); opacity:0.97;">
        <table style="width:100%; border-collapse:collapse;">

            {{-- التصنيف --}}
            <tr style="border-bottom:1px solid rgba(255,255,255,0.1);">
                <td style="padding:0.75rem 1.25rem; text-align:left; font-weight:900; color:#fbbf24; font-size:0.88rem; width:200px;">
                    Class : التصنيف
                </td>
                <td style="padding:0.65rem 1.25rem;">
                    <select wire:model="classId" style="padding:0.4rem 0.65rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-family:'Tajawal',sans-serif; font-size:0.88rem; width:100%; outline:none;">
                        <option value="mutmainah">مركز مطمئنة الكويت</option>
                    </select>
                </td>
            </tr>

            {{-- النوع --}}
            <tr style="border-bottom:1px solid rgba(255,255,255,0.1);">
                <td style="padding:0.75rem 1.25rem; text-align:left; font-weight:900; color:#fbbf24; font-size:0.88rem;">
                    Type : النوع
                </td>
                <td style="padding:0.65rem 1.25rem;">
                    <select wire:model="voucherType" style="padding:0.4rem 0.65rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-family:'Tajawal',sans-serif; font-size:0.88rem; width:100%; outline:none;">
                        <option value="receipt">سند قبض : Receipt</option>
                        <option value="payment">سند صرف : Payment</option>
                        <option value="">الكل : All</option>
                    </select>
                </td>
            </tr>

            {{-- من تاريخ --}}
            <tr style="border-bottom:1px solid rgba(255,255,255,0.1);">
                <td style="padding:0.75rem 1.25rem; text-align:left; font-weight:900; color:#fbbf24; font-size:0.88rem;">
                    From : من تاريخ
                </td>
                <td style="padding:0.65rem 1.25rem;">
                    <div style="display:flex; gap:0.35rem; direction:ltr;">
                        <select wire:model="fromDay" style="padding:0.4rem 0.4rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-size:0.85rem; flex:1; outline:none;">
                            @foreach($days as $d)<option value="{{ $d }}">{{ $d }}</option>@endforeach
                        </select>
                        <select wire:model="fromMonth" style="padding:0.4rem 0.4rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-family:'Tajawal',sans-serif; font-size:0.85rem; flex:2; outline:none;">
                            @foreach($months as $n => $name)<option value="{{ $n }}">{{ $name }}</option>@endforeach
                        </select>
                        <select wire:model="fromYear" style="padding:0.4rem 0.4rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-size:0.85rem; flex:2; outline:none;">
                            @foreach($years as $y)<option value="{{ $y }}">{{ $y }}</option>@endforeach
                        </select>
                    </div>
                </td>
            </tr>

            {{-- حتى تاريخ --}}
            <tr>
                <td style="padding:0.75rem 1.25rem; text-align:left; font-weight:900; color:#fbbf24; font-size:0.88rem;">
                    To : حتى تاريخ
                </td>
                <td style="padding:0.65rem 1.25rem;">
                    <div style="display:flex; gap:0.35rem; direction:ltr;">
                        <select wire:model="toDay" style="padding:0.4rem 0.4rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-size:0.85rem; flex:1; outline:none;">
                            @foreach($days as $d)<option value="{{ $d }}">{{ $d }}</option>@endforeach
                        </select>
                        <select wire:model="toMonth" style="padding:0.4rem 0.4rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-family:'Tajawal',sans-serif; font-size:0.85rem; flex:2; outline:none;">
                            @foreach($months as $n => $name)<option value="{{ $n }}">{{ $name }}</option>@endforeach
                        </select>
                        <select wire:model="toYear" style="padding:0.4rem 0.4rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-size:0.85rem; flex:2; outline:none;">
                            @foreach($years as $y)<option value="{{ $y }}">{{ $y }}</option>@endforeach
                        </select>
                    </div>
                </td>
            </tr>

        </table>
    </div>

    {{-- أزرار --}}
    <div style="background:var(--navy); padding:0.75rem 1.5rem; display:flex; justify-content:center; gap:1rem; border-top:1px solid rgba(255,255,255,0.1);">
        <button wire:click="search"
            style="padding:0.45rem 2rem; background:#2563eb; color:#fff; border:none; border-radius:6px; font-weight:900; font-size:0.88rem; font-family:'Tajawal',sans-serif; cursor:pointer;"
            onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
            إرسال : Send
        </button>
        <button wire:click="resetForm"
            style="padding:0.45rem 1.5rem; background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.3); border-radius:6px; font-weight:800; font-size:0.88rem; font-family:'Tajawal',sans-serif; cursor:pointer;">
            إستعادة : Reset
        </button>
    </div>

    {{-- روابط --}}
    <div style="background:var(--navy); padding:0.5rem 1.5rem; display:flex; justify-content:center; gap:2rem; border-top:1px solid rgba(255,255,255,0.08); border-radius:0 0 12px 12px;">
        <a href="{{ route('dashboard') }}" wire:navigate style="color:#fbbf24; font-size:0.82rem; font-weight:700; text-decoration:none;">الرئيسية : Home</a>
        <a href="javascript:history.back()" style="color:#fbbf24; font-size:0.82rem; font-weight:700; text-decoration:none;">رجوع : Back</a>
    </div>

</div>

{{-- النتائج --}}
@if($searched && $rows)
<div style="margin-top:1.5rem; background:#fff; border:1px solid var(--border); border-radius:12px; overflow:hidden; box-shadow:var(--shadow-sm);">
    <div style="background:var(--navy); padding:0.65rem 1.25rem; display:flex; justify-content:space-between; align-items:center;">
        <span style="color:#fbbf24; font-weight:900; font-size:0.92rem;">
            {{ $voucherType === 'receipt' ? 'سندات القبض' : ($voucherType === 'payment' ? 'سندات الصرف' : 'جميع السندات') }}
        </span>
        <div style="display:flex; gap:1rem;">
            <span style="color:#86efac; font-size:0.82rem; font-weight:800;">قبض: {{ number_format($totalCredit, 3) }}</span>
            <span style="color:#fca5a5; font-size:0.82rem; font-weight:800;">صرف: {{ number_format($totalDebit, 3) }}</span>
        </div>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif; font-size:0.83rem;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:2px solid var(--border);">
                    <th style="padding:0.6rem 0.75rem; text-align:center; font-weight:900; color:var(--text-dim);">#</th>
                    <th style="padding:0.6rem 0.75rem; font-weight:900; color:var(--text-dim);">العميل</th>
                    <th style="padding:0.6rem 0.75rem; font-weight:900; color:var(--text-dim);">التاريخ</th>
                    <th style="padding:0.6rem 0.75rem; font-weight:900; color:var(--text-dim);">النوع</th>
                    <th style="padding:0.6rem 0.75rem; font-weight:900; color:var(--text-dim);">البيان</th>
                    <th style="padding:0.6rem 0.75rem; text-align:center; font-weight:900; color:#16a34a;">دائن</th>
                    <th style="padding:0.6rem 0.75rem; text-align:center; font-weight:900; color:#dc2626;">مدين</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $i => $r)
                <tr style="border-bottom:1px solid #f0f2f5;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                    <td style="padding:0.55rem 0.75rem; text-align:center; color:var(--text-muted);">{{ $i+1 }}</td>
                    <td style="padding:0.55rem 0.75rem; font-weight:800; color:var(--navy);">{{ $r->client_name ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; color:#1565c0; font-weight:700; direction:ltr; unicode-bidi:isolate;">{{ fmt_date($r->pdate) }}</td>
                    <td style="padding:0.55rem 0.75rem; color:var(--text-dim);">{{ $r->ptype ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; color:var(--text-dim); max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $r->pdesc ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; font-weight:800; color:#16a34a;">{{ $r->credit > 0 ? number_format($r->credit, 3) : '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; font-weight:800; color:#dc2626;">{{ $r->debit > 0 ? number_format($r->debit, 3) : '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" style="padding:3rem; text-align:center; color:var(--text-muted);">لا توجد سندات في هذه الفترة</td></tr>
                @endforelse
            </tbody>
            @if($rows->count() > 0)
            <tfoot>
                <tr style="background:#f0f0f0; border-top:2px solid #ccc; font-weight:900;">
                    <td colspan="5" style="padding:0.6rem 0.75rem; text-align:right; color:var(--navy);">الإجمالي</td>
                    <td style="padding:0.6rem 0.75rem; text-align:center; color:#16a34a;">{{ number_format($totalCredit, 3) }}</td>
                    <td style="padding:0.6rem 0.75rem; text-align:center; color:#dc2626;">{{ number_format($totalDebit, 3) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endif

</div>
</div>
