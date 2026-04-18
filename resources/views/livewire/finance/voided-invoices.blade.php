<div class="pg-outer" style="min-height:80vh; padding:1.5rem 1rem; display:flex; justify-content:center; align-items:flex-start;">
<div style="width:100%; max-width:780px; animation:fadeIn 0.4s ease;">

@php
$days   = range(1, 31);
$months = [1=>'يناير',2=>'فبراير',3=>'مارس',4=>'أبريل',5=>'مايو',6=>'يونيو',
           7=>'يوليو',8=>'أغسطس',9=>'سبتمبر',10=>'أكتوبر',11=>'نوفمبر',12=>'ديسمبر'];
$years  = range(2000, now()->year + 1);
@endphp

<div style="border-radius:14px; overflow:visible; border:2px solid #dc2626; box-shadow:0 8px 25px rgba(220,38,38,0.12);">

    <div style="background:var(--navy); padding:0.9rem 1.25rem; text-align:center; border-radius:12px 12px 0 0;">
        <span style="color:#fca5a5; font-size:1.05rem; font-weight:900; font-family:'Tajawal',sans-serif;">
            🚫 الفواتير الملغاة
        </span>
    </div>

    <div style="background:var(--navy); padding:0.5rem 0;">

        <div style="padding:0.6rem 1.25rem; border-bottom:1px solid rgba(255,255,255,0.1);">
            <div style="font-weight:900; color:#fbbf24; font-size:0.85rem; margin-bottom:0.35rem; font-family:'Tajawal',sans-serif;">From : من تاريخ</div>
            <div style="display:flex; gap:0.35rem; direction:ltr;">
                <select wire:model="fromDay" style="flex:1; padding:0.45rem 0.3rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-size:0.85rem; outline:none; min-width:0;">
                    @foreach($days as $d)<option value="{{ $d }}">{{ $d }}</option>@endforeach
                </select>
                <select wire:model="fromMonth" style="flex:2; padding:0.45rem 0.3rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-family:'Tajawal',sans-serif; font-size:0.85rem; outline:none; min-width:0;">
                    @foreach($months as $n => $name)<option value="{{ $n }}">{{ $name }}</option>@endforeach
                </select>
                <select wire:model="fromYear" style="flex:2; padding:0.45rem 0.3rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-size:0.85rem; outline:none; min-width:0;">
                    @foreach($years as $y)<option value="{{ $y }}">{{ $y }}</option>@endforeach
                </select>
            </div>
        </div>

        <div style="padding:0.6rem 1.25rem;">
            <div style="font-weight:900; color:#fbbf24; font-size:0.85rem; margin-bottom:0.35rem; font-family:'Tajawal',sans-serif;">To : حتى تاريخ</div>
            <div style="display:flex; gap:0.35rem; direction:ltr;">
                <select wire:model="toDay" style="flex:1; padding:0.45rem 0.3rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-size:0.85rem; outline:none; min-width:0;">
                    @foreach($days as $d)<option value="{{ $d }}">{{ $d }}</option>@endforeach
                </select>
                <select wire:model="toMonth" style="flex:2; padding:0.45rem 0.3rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-family:'Tajawal',sans-serif; font-size:0.85rem; outline:none; min-width:0;">
                    @foreach($months as $n => $name)<option value="{{ $n }}">{{ $name }}</option>@endforeach
                </select>
                <select wire:model="toYear" style="flex:2; padding:0.45rem 0.3rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-size:0.85rem; outline:none; min-width:0;">
                    @foreach($years as $y)<option value="{{ $y }}">{{ $y }}</option>@endforeach
                </select>
            </div>
        </div>

    </div>

    <div style="background:var(--navy); padding:0.75rem 1.25rem; display:flex; justify-content:center; gap:0.75rem; border-top:1px solid rgba(255,255,255,0.1);">
        <button wire:click="search"
            style="flex:1; max-width:220px; padding:0.55rem 1rem; background:#dc2626; color:#fff; border:none; border-radius:6px; font-weight:900; font-size:0.88rem; font-family:'Tajawal',sans-serif; cursor:pointer;">
            إرسال : Send
        </button>
        <button wire:click="resetForm"
            style="flex:1; max-width:180px; padding:0.55rem 1rem; background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.3); border-radius:6px; font-weight:800; font-size:0.88rem; font-family:'Tajawal',sans-serif; cursor:pointer;">
            إستعادة : Reset
        </button>
    </div>

    <div style="background:var(--navy); padding:0.5rem 1.25rem; display:flex; justify-content:center; gap:2rem; border-top:1px solid rgba(255,255,255,0.08); border-radius:0 0 12px 12px;">
        <a href="{{ route('dashboard') }}" wire:navigate style="color:#fbbf24; font-size:0.82rem; font-weight:700; text-decoration:none;">الرئيسية</a>
        <a href="{{ route('finance.invoices') }}" wire:navigate style="color:#fbbf24; font-size:0.82rem; font-weight:700; text-decoration:none;">الفواتير</a>
        <a href="{{ route('finance.voided-vouchers') }}" wire:navigate style="color:rgba(255,255,255,0.6); font-size:0.82rem; font-weight:700; text-decoration:none;">السندات الملغاة</a>
    </div>

</div>

@if($searched && $rows !== null)
<div style="margin-top:1.5rem; background:#fff; border:1px solid var(--border); border-radius:12px; overflow:hidden; box-shadow:var(--shadow-sm);">
    <div style="background:#7f1d1d; padding:0.65rem 1.25rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
        <span style="color:#fca5a5; font-weight:900; font-size:0.92rem;">🚫 الفواتير الملغاة</span>
        <span style="color:#fff; font-size:0.82rem; font-weight:800;">{{ $rows->count() }} سجل</span>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif; font-size:0.83rem;">
            <thead>
                <tr style="background:#fff5f5; border-bottom:2px solid #fecaca;">
                    <th style="padding:0.6rem 0.75rem; text-align:center; font-weight:900; color:#991b1b;">#</th>
                    <th style="padding:0.6rem 0.75rem; font-weight:900; color:#991b1b;">العميل</th>
                    <th style="padding:0.6rem 0.75rem; font-weight:900; color:#991b1b;">التاريخ</th>
                    <th style="padding:0.6rem 0.75rem; font-weight:900; color:#991b1b;">البيان</th>
                    <th style="padding:0.6rem 0.75rem; font-weight:900; color:#991b1b;">بواسطة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $i => $r)
                <tr style="border-bottom:1px solid #fef2f2;" onmouseover="this.style.background='#fff5f5'" onmouseout="this.style.background=''">
                    <td style="padding:0.55rem 0.75rem; text-align:center; color:var(--text-muted);">{{ $i+1 }}</td>
                    <td style="padding:0.55rem 0.75rem;">
                        @if($r->full_name)
                        <a href="{{ route('patients.show', $r->subject_id) }}" wire:navigate style="font-weight:800; color:var(--navy); text-decoration:none;">{{ $r->full_name }}</a>
                        @if($r->file_id) <span style="color:var(--text-muted); font-size:0.78rem;">#{{ $r->file_id }}</span>@endif
                        @else
                        <span style="color:var(--text-muted);">—</span>
                        @endif
                    </td>
                    <td style="padding:0.55rem 0.75rem; color:#1565c0; font-weight:700; white-space:nowrap;">
                        {{ \Carbon\Carbon::parse($r->created_at)->format('d/m/Y H:i') }}
                    </td>
                    <td style="padding:0.55rem 0.75rem; color:#7f1d1d; font-size:0.8rem; max-width:250px;">{{ $r->description }}</td>
                    <td style="padding:0.55rem 0.75rem; font-weight:700; color:#dc2626;">{{ $r->user_name ?: 'النظام' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="padding:3rem; text-align:center; color:var(--text-muted);">لا توجد فواتير ملغاة في هذه الفترة</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

</div>
</div>
