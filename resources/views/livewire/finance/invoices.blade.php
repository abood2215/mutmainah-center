<div style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1500px; margin:0 auto; animation:fadeIn 0.5s ease;">

{{-- زر الطباعة (يظهر فقط بعد البحث) --}}
@if($searched)
<div class="no-print" style="display:flex; justify-content:flex-end; margin-bottom:0.75rem;">
    <button type="button" onclick="window.print()"
        style="padding:0.45rem 1.4rem; background:#16a34a; color:#fff; border:none; border-radius:6px; font-weight:800; font-size:0.88rem; font-family:'Tajawal',sans-serif; cursor:pointer; display:flex; align-items:center; gap:0.4rem;"
        onmouseover="this.style.background='#15803d'" onmouseout="this.style.background='#16a34a'">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        طباعة
    </button>
</div>
@endif

@php
$days   = range(1, 31);
$months = [1=>'يناير',2=>'فبراير',3=>'مارس',4=>'أبريل',5=>'مايو',6=>'يونيو',7=>'يوليو',8=>'أغسطس',9=>'سبتمبر',10=>'أكتوبر',11=>'نوفمبر',12=>'ديسمبر'];
$years  = range(2000, now()->year + 1);
@endphp

{{-- ═══ نموذج الفلتر ═══ --}}
<div style="background:var(--navy); border-radius:14px; padding:1.25rem 1.5rem; margin-bottom:1.5rem; border:2px solid var(--gold);">

    <div style="text-align:center; margin-bottom:1rem;">
        <span style="color:#fbbf24; font-size:1.1rem; font-weight:900; font-family:'Tajawal',sans-serif;">🧾 تقارير الفواتير : Invoices Reports</span>
    </div>

    <div style="display:grid; grid-template-columns:auto 1fr; gap:0.55rem 1.25rem; align-items:center; max-width:700px; margin:0 auto;">

        {{-- العيادة --}}
        <label style="color:#fbbf24; font-weight:800; font-size:0.88rem; white-space:nowrap;">العيادة : Clinic</label>
        <select wire:model="filterClinic" style="padding:0.4rem 0.65rem; border:1px solid rgba(255,255,255,0.2); border-radius:6px; background:rgba(255,255,255,0.1); color:#fff; font-family:'Tajawal',sans-serif; font-weight:700; font-size:0.85rem; width:100%;">
            <option value="" style="color:#000;">الكل : All</option>
            @foreach($clinics as $clinic)
                <option value="{{ $clinic->id }}" style="color:#000;">{{ $clinic->name }}</option>
            @endforeach
        </select>

        {{-- الاستقبال --}}
        <label style="color:#fbbf24; font-weight:800; font-size:0.88rem; white-space:nowrap;">الاستقبال : Receptionist</label>
        <select wire:model="filterUser" style="padding:0.4rem 0.65rem; border:1px solid rgba(255,255,255,0.2); border-radius:6px; background:rgba(255,255,255,0.1); color:#fff; font-family:'Tajawal',sans-serif; font-weight:700; font-size:0.85rem; width:100%;">
            <option value="" style="color:#000;">الكل : All</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" style="color:#000;">{{ trim($emp->first_name . ' ' . $emp->middle_initial) }}</option>
            @endforeach
        </select>

        {{-- من تاريخ --}}
        <label style="color:#fbbf24; font-weight:800; font-size:0.88rem; white-space:nowrap;">من تاريخ : From</label>
        <div style="display:flex; gap:0.35rem; direction:ltr;">
            <select wire:model="fromDay" style="padding:0.4rem 0.4rem; border:1px solid rgba(255,255,255,0.2); border-radius:6px; background:rgba(255,255,255,0.1); color:#fff; font-family:'Tajawal',sans-serif; font-weight:700; font-size:0.85rem; flex:1;">
                @foreach($days as $d)
                    <option value="{{ $d }}" style="color:#000;">{{ $d }}</option>
                @endforeach
            </select>
            <select wire:model="fromMonth" style="padding:0.4rem 0.4rem; border:1px solid rgba(255,255,255,0.2); border-radius:6px; background:rgba(255,255,255,0.1); color:#fff; font-family:'Tajawal',sans-serif; font-weight:700; font-size:0.85rem; flex:2;">
                @foreach($months as $num => $name)
                    <option value="{{ $num }}" style="color:#000;">{{ $name }}</option>
                @endforeach
            </select>
            <select wire:model="fromYear" style="padding:0.4rem 0.4rem; border:1px solid rgba(255,255,255,0.2); border-radius:6px; background:rgba(255,255,255,0.1); color:#fff; font-family:'Tajawal',sans-serif; font-weight:700; font-size:0.85rem; flex:2;">
                @foreach($years as $y)
                    <option value="{{ $y }}" style="color:#000;">{{ $y }}</option>
                @endforeach
            </select>
        </div>

        {{-- حتى تاريخ --}}
        <label style="color:#fbbf24; font-weight:800; font-size:0.88rem; white-space:nowrap;">حتى تاريخ : To</label>
        <div style="display:flex; gap:0.35rem; direction:ltr;">
            <select wire:model="toDay" style="padding:0.4rem 0.4rem; border:1px solid rgba(255,255,255,0.2); border-radius:6px; background:rgba(255,255,255,0.1); color:#fff; font-family:'Tajawal',sans-serif; font-weight:700; font-size:0.85rem; flex:1;">
                @foreach($days as $d)
                    <option value="{{ $d }}" style="color:#000;">{{ $d }}</option>
                @endforeach
            </select>
            <select wire:model="toMonth" style="padding:0.4rem 0.4rem; border:1px solid rgba(255,255,255,0.2); border-radius:6px; background:rgba(255,255,255,0.1); color:#fff; font-family:'Tajawal',sans-serif; font-weight:700; font-size:0.85rem; flex:2;">
                @foreach($months as $num => $name)
                    <option value="{{ $num }}" style="color:#000;">{{ $name }}</option>
                @endforeach
            </select>
            <select wire:model="toYear" style="padding:0.4rem 0.4rem; border:1px solid rgba(255,255,255,0.2); border-radius:6px; background:rgba(255,255,255,0.1); color:#fff; font-family:'Tajawal',sans-serif; font-weight:700; font-size:0.85rem; flex:2;">
                @foreach($years as $y)
                    <option value="{{ $y }}" style="color:#000;">{{ $y }}</option>
                @endforeach
            </select>
        </div>

        {{-- طرق الدفع --}}
        <label style="color:#fbbf24; font-weight:800; font-size:0.88rem; white-space:nowrap;">طرق الدفع : Payment</label>
        <select wire:model="filterPayment" style="padding:0.4rem 0.65rem; border:1px solid rgba(255,255,255,0.2); border-radius:6px; background:rgba(255,255,255,0.1); color:#fff; font-family:'Tajawal',sans-serif; font-weight:700; font-size:0.85rem; width:100%;">
            <option value="" style="color:#000;">الكل : All</option>
            @foreach($paymentLabels as $code => $info)
                <option value="{{ $code }}" style="color:#000;">{{ $info['ar'] }}</option>
            @endforeach
        </select>

    </div>

    <div style="text-align:center; margin-top:1rem; display:flex; gap:0.75rem; justify-content:center;">
        <button wire:click="search" style="padding:0.45rem 2rem; background:#2563eb; color:#fff; border:none; border-radius:8px; font-weight:700; font-size:0.88rem; font-family:'Tajawal',sans-serif; cursor:pointer;">
            إرسال : Submit
        </button>
        <button wire:click="resetForm" style="padding:0.45rem 2rem; background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.3); border-radius:8px; font-weight:700; font-size:0.88rem; font-family:'Tajawal',sans-serif; cursor:pointer;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
            إستعادة : Reset
        </button>
    </div>

    {{-- روابط سفلية --}}
    <div style="margin-top:0.75rem; display:flex; gap:1.25rem; justify-content:center; padding-top:0.5rem; border-top:1px solid rgba(255,255,255,0.1);">
        <a href="{{ route('dashboard') }}" wire:navigate style="color:rgba(255,255,255,0.6); font-size:0.82rem; font-weight:700; text-decoration:none;" onmouseover="this.style.color='#fbbf24'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">الرئيسية</a>
        <span style="color:rgba(255,255,255,0.2);">|</span>
        <a href="#" style="color:rgba(255,255,255,0.6); font-size:0.82rem; font-weight:700; text-decoration:none;" onmouseover="this.style.color='#fbbf24'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">الفواتير الملغاة</a>
        <span style="color:rgba(255,255,255,0.2);">|</span>
        <a href="#" style="color:rgba(255,255,255,0.6); font-size:0.82rem; font-weight:700; text-decoration:none;" onmouseover="this.style.color='#fbbf24'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">السندات الملغاة</a>
        <span style="color:rgba(255,255,255,0.2);">|</span>
        <a href="#" style="color:rgba(255,255,255,0.6); font-size:0.82rem; font-weight:700; text-decoration:none;" onmouseover="this.style.color='#fbbf24'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">تفصيلي</a>
    </div>
</div>

@if($searched)
<div id="print-area">

{{-- ترويسة الطباعة — نفس تصميم بيان الحساب --}}
<div class="print-letterhead" style="padding:0.6rem 1.5rem; border-bottom:3px solid #8b1c2b; background:#fff; margin-bottom:0.5rem;">
    <div style="display:flex; align-items:center; gap:1rem; direction:ltr;">
        <img src="{{ asset('logo.jpg') }}" alt="مطمئنة" style="height:62px; width:62px; border-radius:50%; object-fit:cover; border:2px solid #8b1c2b; flex-shrink:0;">
        <div style="flex:1; height:3px; background:linear-gradient(to right,#8b1c2b,transparent); border-radius:2px;"></div>
        <div style="text-align:right; line-height:1.2; direction:rtl;">
            <div style="font-size:0.72rem; color:#555; font-weight:600; letter-spacing:1px; font-family:'Tajawal',sans-serif;">مركز</div>
            <div style="font-size:2rem; font-weight:900; color:#8b1c2b; font-family:'Tajawal',sans-serif; letter-spacing:-1px;">مطمئنة</div>
            <div style="font-size:0.78rem; color:#555; font-weight:600; font-family:'Tajawal',sans-serif; margin-top:0.1rem;">تقرير الفواتير</div>
        </div>
    </div>
</div>

{{-- ═══ الجدول الرئيسي ═══ --}}
<div style="background:#fff; border:1px solid var(--border); border-radius:12px; overflow:hidden; margin-bottom:1.5rem; box-shadow:var(--shadow-sm);">

    {{-- عنوان التقرير --}}
    <div style="background:#fafbfc; border-bottom:2px solid var(--border); padding:0.85rem 1.5rem;">
        <div style="font-size:1.05rem; font-weight:900; color:var(--primary); font-family:'Tajawal',sans-serif;">
            الفواتير :: {{ $clinicName }}
        </div>
        @if($dateLabel)
        <div style="font-size:0.85rem; font-weight:700; color:var(--text-dim); margin-top:0.15rem; direction:ltr; unicode-bidi:isolate;">{{ $dateLabel }}</div>
        @endif
    </div>

    {{-- جدول الفواتير --}}
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif; font-size:0.82rem;">
            <thead>
                {{-- الرأس العربي --}}
                <tr style="background:#e8e8e8; border-bottom:1px solid #bbb;">
                    <th style="padding:0.5rem 0.6rem; text-align:center; font-weight:900; color:#2e7d32; border-left:1px solid #ccc; width:38px;">م</th>
                    <th style="padding:0.5rem 0.75rem; text-align:right; font-weight:900; color:#2e7d32; border-left:1px solid #ccc; min-width:150px;">العميل</th>
                    <th style="padding:0.5rem 0.55rem; text-align:center; font-weight:900; color:#c8401a; border-left:1px solid #ccc;">الملف</th>
                    <th style="padding:0.5rem 0.55rem; text-align:center; font-weight:900; color:#c8401a; border-left:1px solid #ccc;">الفاتورة</th>
                    <th style="padding:0.5rem 0.55rem; text-align:center; font-weight:900; color:#1565c0; border-left:1px solid #ccc;">التاريخ</th>
                    <th style="padding:0.5rem 0.55rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc;">القيمة</th>
                    <th style="padding:0.5rem 0.55rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc;">الدفع</th>
                    <th style="padding:0.5rem 0.55rem; text-align:center; font-weight:900; color:#c62828; border-left:1px solid #ccc;">الخصم</th>
                    <th style="padding:0.5rem 0.55rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc;">الضريبة</th>
                    <th style="padding:0.5rem 0.55rem; text-align:center; font-weight:900; color:var(--primary); border-left:1px solid #ccc;">الإجمالي</th>
                    <th style="padding:0.5rem 0.55rem; text-align:center; font-weight:900; color:#555;">الاستقبال</th>
                </tr>
                {{-- الرأس الإنجليزي --}}
                <tr style="background:#f5f5f5; border-bottom:2px solid #bbb;">
                    <th style="padding:0.35rem 0.6rem; text-align:center; font-weight:900; color:#2e7d32; border-left:1px solid #ccc; font-size:0.73rem;">#</th>
                    <th style="padding:0.35rem 0.75rem; text-align:right; font-weight:900; color:#2e7d32; border-left:1px solid #ccc; font-size:0.73rem;">Patient</th>
                    <th style="padding:0.35rem 0.55rem; text-align:center; font-weight:900; color:#c8401a; border-left:1px solid #ccc; font-size:0.73rem;">Fno</th>
                    <th style="padding:0.35rem 0.55rem; text-align:center; font-weight:900; color:#c8401a; border-left:1px solid #ccc; font-size:0.73rem;">Vno</th>
                    <th style="padding:0.35rem 0.55rem; text-align:center; font-weight:900; color:#1565c0; border-left:1px solid #ccc; font-size:0.73rem;">Date</th>
                    <th style="padding:0.35rem 0.55rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc; font-size:0.73rem;">Amount</th>
                    <th style="padding:0.35rem 0.55rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc; font-size:0.73rem;">Method</th>
                    <th style="padding:0.35rem 0.55rem; text-align:center; font-weight:900; color:#c62828; border-left:1px solid #ccc; font-size:0.73rem;">Disc</th>
                    <th style="padding:0.35rem 0.55rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc; font-size:0.73rem;">VAT</th>
                    <th style="padding:0.35rem 0.55rem; text-align:center; font-weight:900; color:var(--primary); border-left:1px solid #ccc; font-size:0.73rem;">Total</th>
                    <th style="padding:0.35rem 0.55rem; text-align:center; font-weight:900; color:#555; font-size:0.73rem;">REP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $i => $inv)
                <tr style="border-bottom:1px solid #ebebeb; {{ $i % 2 == 0 ? 'background:#fff;' : 'background:#fafafa;' }}"
                    onmouseover="this.style.background='#fff8e1'" onmouseout="this.style.background='{{ $i % 2 == 0 ? '#fff' : '#fafafa' }}'">
                    <td style="padding:0.4rem 0.6rem; text-align:center; color:#888; font-size:0.78rem; border-left:1px solid #eee;">{{ $i + 1 }}</td>
                    <td style="padding:0.4rem 0.75rem; font-weight:700; color:#1a1a2e; border-left:1px solid #eee;">{{ $inv->patient_name ?: '—' }}</td>
                    <td style="padding:0.4rem 0.55rem; text-align:center; color:#c8401a; font-weight:800; border-left:1px solid #eee; font-size:0.79rem;">{{ $inv->file_id ?: '—' }}</td>
                    <td style="padding:0.4rem 0.55rem; text-align:center; border-left:1px solid #eee;">
                        <span style="color:#c8401a; font-weight:800; font-size:0.8rem;">{{ $inv->vno ?: $inv->serial_no ?: '—' }}</span>
                    </td>
                    <td style="padding:0.4rem 0.55rem; text-align:center; color:#1565c0; font-weight:700; border-left:1px solid #eee; white-space:nowrap; font-size:0.8rem; direction:ltr; unicode-bidi:isolate;">{{ fmt_date($inv->pdate) }}</td>
                    <td style="padding:0.4rem 0.55rem; text-align:left; font-weight:700; color:#333; border-left:1px solid #eee; font-size:0.81rem; direction:ltr;">{{ number_format($inv->price, 2) }}</td>
                    <td style="padding:0.4rem 0.55rem; text-align:center; border-left:1px solid #eee;">
                        <span style="color:{{ in_array($inv->payment_method,[1,7]) ? '#2e7d32' : (in_array($inv->payment_method,[3,4]) ? '#1565c0' : '#c8401a') }}; font-weight:700; font-size:0.79rem;">
                            {{ $paymentLabels[$inv->payment_method]['ar'] ?? '—' }}
                        </span>
                    </td>
                    <td style="padding:0.4rem 0.55rem; text-align:left; color:#c62828; border-left:1px solid #eee; font-size:0.8rem; direction:ltr;">{{ number_format($inv->discount ?? 0, 2) }}</td>
                    <td style="padding:0.4rem 0.55rem; text-align:left; color:#888; border-left:1px solid #eee; font-size:0.8rem; direction:ltr;">{{ number_format($inv->tax_value ?? 0, 2) }}</td>
                    <td style="padding:0.4rem 0.55rem; text-align:left; font-weight:900; color:var(--primary); border-left:1px solid #eee; font-size:0.86rem; direction:ltr;">{{ number_format($inv->net, 2) }}</td>
                    <td style="padding:0.4rem 0.55rem; text-align:center; color:#555; font-size:0.77rem;">{{ trim($inv->rep_name) ?: '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" style="padding:3rem; text-align:center; color:var(--text-muted);">
                        <div style="font-size:2.5rem; opacity:0.15; margin-bottom:0.5rem;">📂</div>
                        لا توجد فواتير في هذه الفترة
                    </td>
                </tr>
                @endforelse
            </tbody>

            {{-- لا يوجد tfoot هنا — الإجمالي يظهر مرة واحدة في آخر الكشف --}}
        </table>
    </div>
</div>

{{-- ═══ جدول السندات ═══ --}}
<div style="background:#fff; border:1px solid var(--border); border-radius:12px; overflow:hidden; margin-bottom:1.5rem; box-shadow:var(--shadow-sm);">

    <div style="background:var(--navy); padding:0.65rem 1.5rem; display:flex; align-items:center; gap:0.5rem;">
        <span style="color:#fbbf24; font-weight:900; font-size:0.92rem;">فواتير السندات</span>
        @if($vouchers->count() > 0)
            <span style="color:rgba(255,255,255,0.45); font-size:0.78rem;">({{ $vouchers->count() }})</span>
        @endif
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif; font-size:0.82rem;">
            <thead>
                <tr style="background:#e8e8e8; border-bottom:1px solid #bbb;">
                    <th style="padding:0.5rem 0.6rem; text-align:center; font-weight:900; color:#2e7d32; border-left:1px solid #ccc; width:38px;">م</th>
                    <th style="padding:0.5rem 0.6rem; text-align:center; font-weight:900; color:#1565c0; border-left:1px solid #ccc;">التاريخ</th>
                    <th style="padding:0.5rem 0.75rem; text-align:right; font-weight:900; color:#2e7d32; border-left:1px solid #ccc; min-width:140px;">الحساب</th>
                    <th style="padding:0.5rem 0.6rem; text-align:center; font-weight:900; color:#2e7d32; border-left:1px solid #ccc;">المبلغ</th>
                    <th style="padding:0.5rem 0.6rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc;">الدفع</th>
                    <th style="padding:0.5rem 0.75rem; text-align:right; font-weight:900; color:#555; border-left:1px solid #ccc; min-width:200px;">البيان</th>
                    <th style="padding:0.5rem 0.6rem; text-align:center; font-weight:900; color:#c8401a;">السند</th>
                </tr>
                <tr style="background:#f5f5f5; border-bottom:2px solid #bbb;">
                    <th style="padding:0.35rem 0.6rem; text-align:center; color:#2e7d32; border-left:1px solid #ccc; font-size:0.73rem; font-weight:900;">#</th>
                    <th style="padding:0.35rem 0.6rem; text-align:center; color:#1565c0; border-left:1px solid #ccc; font-size:0.73rem; font-weight:900;">Date</th>
                    <th style="padding:0.35rem 0.75rem; color:#2e7d32; border-left:1px solid #ccc; font-size:0.73rem; font-weight:900;">Account</th>
                    <th style="padding:0.35rem 0.6rem; text-align:center; color:#2e7d32; border-left:1px solid #ccc; font-size:0.73rem; font-weight:900;">Amount</th>
                    <th style="padding:0.35rem 0.6rem; text-align:center; color:#555; border-left:1px solid #ccc; font-size:0.73rem; font-weight:900;">Method</th>
                    <th style="padding:0.35rem 0.75rem; color:#555; border-left:1px solid #ccc; font-size:0.73rem; font-weight:900;">Desc</th>
                    <th style="padding:0.35rem 0.6rem; text-align:center; color:#c8401a; font-size:0.73rem; font-weight:900;">Voucher</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $vi => $v)
                <tr style="border-bottom:1px solid #ebebeb; {{ $vi % 2 == 0 ? 'background:#fff;' : 'background:#fafafa;' }}"
                    onmouseover="this.style.background='#fff8e1'" onmouseout="this.style.background='{{ $vi % 2 == 0 ? '#fff' : '#fafafa' }}'">
                    <td style="padding:0.4rem 0.6rem; text-align:center; color:#888; font-size:0.78rem; border-left:1px solid #eee;">{{ $vi + 1 }}</td>
                    <td style="padding:0.4rem 0.6rem; text-align:center; color:#1565c0; font-weight:700; border-left:1px solid #eee; white-space:nowrap; font-size:0.8rem; direction:ltr; unicode-bidi:isolate;">{{ fmt_date($v->pdate) }}</td>
                    <td style="padding:0.4rem 0.75rem; font-weight:700; color:#1a1a2e; border-left:1px solid #eee;">{{ $v->patient_name ?: '—' }}</td>
                    <td style="padding:0.4rem 0.6rem; text-align:left; font-weight:800; color:#2e7d32; border-left:1px solid #eee; direction:ltr; font-size:0.83rem;">
                        {{ number_format($v->credit > 0 ? $v->credit : $v->debit, 2) }}
                    </td>
                    <td style="padding:0.4rem 0.6rem; text-align:center; border-left:1px solid #eee;">
                        <span style="color:#c8401a; font-weight:700; font-size:0.78rem;">{{ $v->notes ?: ($v->ptype ?: 'سند قبض') }}</span>
                    </td>
                    <td style="padding:0.4rem 0.75rem; color:#555; border-left:1px solid #eee; font-size:0.79rem; max-width:280px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $v->pdesc ?: '—' }}</td>
                    <td style="padding:0.4rem 0.6rem; text-align:center;">
                        <span style="color:#c8401a; font-weight:700; font-size:0.79rem;">{{ $v->serial_no ?: $v->id }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:2rem; text-align:center; color:var(--text-muted);">لا توجد سندات في هذه الفترة</td>
                </tr>
                @endforelse
            </tbody>

            @if($vouchers->count() > 0)
            <tfoot>
                <tr style="background:#e0e0e0; border-top:2px solid #aaa; font-weight:900;">
                    <td colspan="3" style="padding:0.55rem 0.75rem; text-align:right; color:#2e7d32; font-size:0.84rem;">
                        إجمالي سندات القبض =
                        <span style="direction:ltr; display:inline-block; margin-right:0.5rem;">{{ number_format($vTotals['credit'], 2) }}</span>
                    </td>
                    <td colspan="4" style="padding:0.55rem 0.75rem; text-align:right; color:#c62828; font-size:0.84rem;">
                        إجمالي سندات الصرف =
                        <span style="direction:ltr; display:inline-block; margin-right:0.5rem;">{{ number_format($vTotals['debit'], 2) }}</span>
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

{{-- ═══ تفصيل طرق الدفع ═══ --}}
@if($invoices->count() > 0 || $vouchers->count() > 0)
<div style="background:#fff; border:1px solid var(--border); border-radius:12px; overflow:hidden; box-shadow:var(--shadow-sm);">

    <div style="background:#f8fafc; border-bottom:2px solid var(--border); padding:0.65rem 1.5rem;">
        <span style="font-weight:900; color:var(--navy); font-size:0.92rem;">تفصيل طرق الدفع</span>
    </div>

    <div style="padding:0.25rem 0;">
        @foreach($payBreak as $code => $pb)
        @if($pb['total'] > 0)
        <div style="display:flex; align-items:center; justify-content:space-between; padding:0.45rem 2rem; border-bottom:1px solid #f0f2f5;"
            onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
            <div style="font-weight:800; color:#1a1a2e; font-size:0.88rem; display:flex; align-items:center; gap:2rem;">
                <span style="color:var(--text-muted); font-size:0.77rem; min-width:90px; font-weight:600; direction:ltr; unicode-bidi:embed;">{{ $pb['en'] }}</span>
                <span>إجمالي {{ $pb['label'] }} ::</span>
            </div>
            <div style="font-weight:900; color:var(--primary); font-size:0.95rem; direction:ltr; font-family:'Inter';">
                {{ number_format($pb['total'], 2) }}
                <span style="font-size:0.7rem; color:var(--text-muted); font-weight:400; font-family:'Tajawal'; margin-right:2px;">د.ك</span>
            </div>
        </div>
        @endif
        @endforeach

        @if(isset($vTotals['credit']) && $vTotals['credit'] > 0)
        <div style="display:flex; align-items:center; justify-content:space-between; padding:0.45rem 2rem; border-bottom:1px solid #f0f2f5; background:#f0fdf4;">
            <div style="font-weight:800; color:#166534; font-size:0.88rem; display:flex; align-items:center; gap:2rem;">
                <span style="color:#16a34a; font-size:0.77rem; min-width:90px; font-weight:600;">Vouchers</span>
                <span>السندات ::</span>
            </div>
            <div style="font-weight:900; color:#166534; font-size:0.95rem; direction:ltr; font-family:'Inter';">
                {{ number_format($vTotals['credit'], 2) }}
                <span style="font-size:0.7rem; color:var(--text-muted); font-weight:400; font-family:'Tajawal'; margin-right:2px;">د.ك</span>
            </div>
        </div>
        @endif

    </div>
</div>
@endif

{{-- ═══ إجمالي الفواتير — يظهر مرة واحدة في آخر الكشف ═══ --}}
@if($invoices->count() > 0)
<div style="background:#fff; border:1px solid var(--border); border-radius:12px; overflow:hidden; margin-top:1rem; box-shadow:var(--shadow-sm);">
    {{-- صف مجاميع الأعمدة --}}
    <div style="background:#e8e8e8; border-bottom:2px solid #aaa; padding:0.55rem 1rem; display:flex; align-items:center; justify-content:space-between; font-weight:900; font-family:'Tajawal',sans-serif;">
        <span style="color:#1a1a2e; font-size:0.88rem;">الإجمالي : Total &nbsp; <span style="color:#555; font-size:0.82rem;">({{ $totals['count'] }} فاتورة)</span></span>
        <div style="display:flex; gap:2rem; direction:ltr; font-family:'Inter';">
            <span style="color:#c62828; font-size:0.88rem;">خصم: {{ number_format($totals['discount'], 2) }}</span>
            <span style="color:#888; font-size:0.88rem;">ضريبة: {{ number_format($totals['tax'], 2) }}</span>
            <span style="color:var(--primary); font-size:1rem; font-weight:900;">{{ number_format($totals['net'], 2) }}</span>
        </div>
    </div>
    {{-- صف المراجعين الرئيسي --}}
    <div style="background:var(--navy); padding:0.65rem 1.5rem; display:flex; align-items:center; justify-content:space-between;">
        <div style="font-weight:900; color:#fbbf24; font-size:0.95rem; font-family:'Tajawal',sans-serif;">المراجعين :: Clients</div>
        <div style="font-weight:900; color:#fbbf24; font-size:1.3rem; direction:ltr; font-family:'Inter';">
            {{ number_format($totals['net'], 2) }}
            <span style="font-size:0.78rem; color:rgba(255,255,255,0.5); font-weight:400; font-family:'Tajawal'; margin-right:3px;">د.ك</span>
        </div>
    </div>
</div>
@endif

{{-- تذييل الطباعة --}}
<div class="print-footer" style="display:none; margin-top:1rem; text-align:center; font-size:0.72rem; color:#9ca3af; font-family:'Tajawal',sans-serif; border-top:1px solid #e2e8f0; padding-top:0.5rem;">
    تاريخ الطباعة: {{ now()->format('d/m/Y H:i') }} &nbsp;|&nbsp; مركز مطمئنة الاستشاري
</div>

<style>
@media print {
    /* ═══ Landscape لجدول الفواتير الواسع ═══ */
    @page { size: A4 landscape; margin: 0.8cm 1cm; }

    /* تقليص الجدول ليتناسب مع عرض الورقة */
    #print-area table {
        font-size: 0.68rem !important;
        width: 100% !important;
        table-layout: fixed !important;
    }
    #print-area table th,
    #print-area table td {
        padding: 0.28rem 0.3rem !important;
        word-break: break-word !important;
        overflow: hidden !important;
    }
    /* العميل — أعطيه مساحة أكبر نسبياً */
    #print-area table th:nth-child(2),
    #print-area table td:nth-child(2) {
        width: 18% !important;
        min-width: 0 !important;
    }
    /* الأعمدة الرقمية — أضيق */
    #print-area table th:nth-child(n+3),
    #print-area table td:nth-child(n+3) {
        width: 8% !important;
    }
    /* الاستقبال — أضيق */
    #print-area table th:last-child,
    #print-area table td:last-child {
        width: 7% !important;
    }
}
</style>

</div>{{-- end #print-area --}}

@else
{{-- placeholder قبل البحث --}}
<div style="background:#fff; border:1px solid var(--border); border-radius:14px; padding:4rem 2rem; text-align:center; box-shadow:var(--shadow-sm);">
    <div style="font-size:3.5rem; opacity:0.12; margin-bottom:1rem;">🔍</div>
    <div style="font-size:1.05rem; font-weight:800; color:var(--text-dim); font-family:'Tajawal',sans-serif;">حدّد الفترة والفلاتر ثم اضغط <span style="color:var(--primary);">بحث</span> لعرض النتائج</div>
</div>
@endif

</div>
</div>
