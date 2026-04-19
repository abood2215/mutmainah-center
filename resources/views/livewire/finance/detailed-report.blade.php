<div class="pg-outer" style="min-height:80vh; padding:1.5rem 1rem; display:flex; justify-content:center; align-items:flex-start;">
<div style="width:100%; max-width:900px; animation:fadeIn 0.4s ease;">

@php
$days   = range(1, 31);
$months = [1=>'يناير',2=>'فبراير',3=>'مارس',4=>'أبريل',5=>'مايو',6=>'يونيو',
           7=>'يوليو',8=>'أغسطس',9=>'سبتمبر',10=>'أكتوبر',11=>'نوفمبر',12=>'ديسمبر'];
$years  = range(2000, now()->year + 1);
$payLabels = [1=>'نقد',2=>'شيك',3=>'شبكة',4=>'تحويل',5=>'سند',6=>'فيزا',7=>'مجاني',11=>'myfatoorah',12=>'stcpay',14=>'Quick Pay',23=>'مجاني - من الرصيد'];
@endphp

<div style="border-radius:14px; overflow:visible; border:2px solid var(--gold); box-shadow:0 8px 25px rgba(0,0,0,0.12);">

    <div style="background:var(--navy); padding:0.9rem 1.25rem; text-align:center; border-radius:12px 12px 0 0;">
        <span style="color:#fbbf24; font-size:1.05rem; font-weight:900; font-family:'Tajawal',sans-serif;">
            📊 التقرير التفصيلي
        </span>
    </div>

    <div style="background:var(--navy); padding:0.5rem 0;">

        {{-- العيادة --}}
        <div style="padding:0.6rem 1.25rem; border-bottom:1px solid rgba(255,255,255,0.1);">
            <div style="font-weight:900; color:#fbbf24; font-size:0.85rem; margin-bottom:0.35rem; font-family:'Tajawal',sans-serif;">Clinic : العيادة</div>
            <select wire:model="filterClinic" style="width:100%; padding:0.45rem 0.65rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-family:'Tajawal',sans-serif; font-size:0.88rem; outline:none;">
                <option value="">— الكل —</option>
                @foreach($clinics as $cl)
                <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- من تاريخ --}}
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

        {{-- حتى تاريخ --}}
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
            style="flex:1; max-width:220px; padding:0.55rem 1rem; background:#2563eb; color:#fff; border:none; border-radius:6px; font-weight:900; font-size:0.88rem; font-family:'Tajawal',sans-serif; cursor:pointer;">
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
        <a href="{{ route('finance.voided-invoices') }}" wire:navigate style="color:rgba(255,255,255,0.6); font-size:0.82rem; font-weight:700; text-decoration:none;">الفواتير الملغاة</a>
    </div>

</div>

@if($searched && $rows !== null)

{{-- إجمالي --}}
<div style="margin-top:1rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
    <div style="flex:1; min-width:140px; background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:0.75rem 1rem; text-align:center;">
        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700;">إجمالي قبل الخصم</div>
        <div style="font-size:1.1rem; font-weight:900; color:var(--navy); font-family:'Inter';">{{ number_format($totalGross, 3) }}</div>
    </div>
    <div style="flex:1; min-width:140px; background:#fff; border:1px solid #fecaca; border-radius:10px; padding:0.75rem 1rem; text-align:center;">
        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700;">إجمالي الخصم</div>
        <div style="font-size:1.1rem; font-weight:900; color:#dc2626; font-family:'Inter';">{{ number_format($totalDisc, 3) }}</div>
    </div>
    <div style="flex:1; min-width:140px; background:#fff; border:1px solid #bbf7d0; border-radius:10px; padding:0.75rem 1rem; text-align:center;">
        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700;">الصافي</div>
        <div style="font-size:1.1rem; font-weight:900; color:#16a34a; font-family:'Inter';">{{ number_format($totalNet, 3) }}</div>
    </div>
    <div style="flex:1; min-width:140px; background:#fff; border:1px solid #bfdbfe; border-radius:10px; padding:0.75rem 1rem; text-align:center;">
        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700;">عدد السجلات</div>
        <div style="font-size:1.1rem; font-weight:900; color:#1565c0; font-family:'Inter';">{{ $rows->count() }}</div>
    </div>
</div>

{{-- جدول --}}
<div style="margin-top:1rem; background:#fff; border:1px solid var(--border); border-radius:12px; overflow:hidden; box-shadow:var(--shadow-sm);">
    <div style="background:var(--navy); padding:0.65rem 1.25rem;">
        <span style="color:#fbbf24; font-weight:900; font-size:0.92rem;">📊 التفاصيل</span>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif; font-size:0.8rem;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:2px solid var(--border);">
                    <th style="padding:0.55rem 0.6rem; text-align:center; font-weight:900; color:var(--text-dim);">#</th>
                    <th style="padding:0.55rem 0.6rem; font-weight:900; color:var(--text-dim);">العميل</th>
                    <th style="padding:0.55rem 0.6rem; font-weight:900; color:var(--text-dim);">التاريخ</th>
                    <th style="padding:0.55rem 0.6rem; font-weight:900; color:var(--text-dim);">العيادة</th>
                    <th style="padding:0.55rem 0.6rem; font-weight:900; color:var(--text-dim);">الخدمة</th>
                    <th style="padding:0.55rem 0.6rem; text-align:center; font-weight:900; color:var(--text-dim);">السعر</th>
                    <th style="padding:0.55rem 0.6rem; text-align:center; font-weight:900; color:#dc2626;">الخصم</th>
                    <th style="padding:0.55rem 0.6rem; text-align:center; font-weight:900; color:#16a34a;">الصافي</th>
                    <th style="padding:0.55rem 0.6rem; font-weight:900; color:var(--text-dim);">الدفع</th>
                    <th style="padding:0.55rem 0.6rem; font-weight:900; color:var(--text-dim);">المستخدم</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $i => $r)
                <tr style="border-bottom:1px solid #f0f2f5;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                    <td style="padding:0.5rem 0.6rem; text-align:center; color:var(--text-muted); font-size:0.75rem;">{{ $i+1 }}</td>
                    <td style="padding:0.5rem 0.6rem;">
                        <a href="{{ route('patients.show', $r->file_id) }}" wire:navigate style="font-weight:800; color:var(--navy); text-decoration:none; font-size:0.82rem;">{{ $r->patient_name ?: '—' }}</a>
                        @if($r->file_id)<br><span style="color:var(--text-muted); font-size:0.72rem;">#{{ $r->file_id }}</span>@endif
                    </td>
                    <td style="padding:0.5rem 0.6rem; color:#1565c0; font-weight:700; white-space:nowrap; font-size:0.78rem;">{{ fmt_date($r->pdate) }}</td>
                    <td style="padding:0.5rem 0.6rem; color:var(--text-dim); font-size:0.78rem;">{{ $r->clinic_name ?: '—' }}</td>
                    <td style="padding:0.5rem 0.6rem; color:var(--text-dim); font-size:0.78rem; max-width:130px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $r->service_name ?: '—' }}</td>
                    <td style="padding:0.5rem 0.6rem; text-align:center; font-weight:700; color:var(--navy); font-family:'Inter';">{{ number_format($r->price, 3) }}</td>
                    <td style="padding:0.5rem 0.6rem; text-align:center; color:#dc2626; font-weight:700; font-family:'Inter';">{{ $r->discount > 0 ? number_format($r->discount, 3) : '—' }}</td>
                    <td style="padding:0.5rem 0.6rem; text-align:center; font-weight:800; color:#16a34a; font-family:'Inter';">{{ number_format($r->net, 3) }}</td>
                    <td style="padding:0.5rem 0.6rem; font-size:0.75rem; color:var(--text-dim);">{{ $payLabels[$r->payment_method] ?? $r->payment_method }}</td>
                    <td style="padding:0.5rem 0.6rem; font-size:0.75rem; color:var(--text-muted);">{{ trim($r->cashier) ?: '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="10" style="padding:3rem; text-align:center; color:var(--text-muted);">لا توجد بيانات في هذه الفترة</td></tr>
                @endforelse
            </tbody>
            @if($rows->count() > 0)
            <tfoot>
                <tr style="background:#f0f0f0; border-top:2px solid #ccc; font-weight:900;">
                    <td colspan="5" style="padding:0.6rem 0.75rem; text-align:right; color:var(--navy);">الإجمالي</td>
                    <td style="padding:0.6rem 0.6rem; text-align:center; color:var(--navy); font-family:'Inter';">{{ number_format($totalGross, 3) }}</td>
                    <td style="padding:0.6rem 0.6rem; text-align:center; color:#dc2626; font-family:'Inter';">{{ number_format($totalDisc, 3) }}</td>
                    <td style="padding:0.6rem 0.6rem; text-align:center; color:#16a34a; font-family:'Inter';">{{ number_format($totalNet, 3) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endif

</div>
</div>
