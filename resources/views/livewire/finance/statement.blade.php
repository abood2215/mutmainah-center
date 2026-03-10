<div style="min-height:80vh; padding:1.5rem 2rem; display:flex; flex-direction:column; align-items:center;">
@php
$days   = range(1, 31);
$months = [1=>'يناير',2=>'فبراير',3=>'مارس',4=>'أبريل',5=>'مايو',6=>'يونيو',
           7=>'يوليو',8=>'أغسطس',9=>'سبتمبر',10=>'أكتوبر',11=>'نوفمبر',12=>'ديسمبر'];
$years  = range(2020, now()->year + 1);
@endphp

{{-- بطاقة الفلتر --}}
<div style="width:100%; max-width:680px; animation:fadeIn 0.4s ease;">
<div style="border-radius:14px; overflow:visible; border:2px solid var(--gold); box-shadow:0 8px 25px rgba(0,0,0,0.12);">

    {{-- رأس --}}
    <div style="background:var(--navy); padding:0.9rem 1.5rem; text-align:center; border-radius:12px 12px 0 0;">
        <span style="color:#fbbf24; font-size:1.05rem; font-weight:900; font-family:'Tajawal',sans-serif;">
            بيان الحساب : Account Statement
        </span>
    </div>

    <div style="background:var(--navy);">
        <table style="width:100%; border-collapse:collapse;">

            {{-- حقل البحث الموحد --}}
            <tr style="border-bottom:1px solid rgba(255,255,255,0.1);">
                <td style="padding:0.75rem 1.25rem; text-align:left; font-weight:900; color:#fbbf24; font-size:0.88rem; width:200px;">
                    Account : الحساب
                </td>
                <td style="padding:0.65rem 1.25rem; position:relative;">
                    <input type="text" wire:model.live.debounce.300ms="searchQuery"
                        placeholder="هوية / رقم ملف / هاتف ..."
                        autocomplete="off"
                        style="width:100%; padding:0.45rem 0.65rem; border:1px solid {{ $patientId ? '#4ade80' : 'rgba(255,255,255,0.3)' }}; border-radius:5px; background:rgba(255,255,255,0.95); color:#1a1a2e; font-family:'Tajawal',sans-serif; font-size:0.9rem; outline:none; direction:ltr;">

                    @if($suggestions)
                    <div style="position:absolute; top:calc(100% - 4px); right:1.25rem; left:1.25rem; background:#fff; border:1px solid #e5e7eb; border-radius:8px; box-shadow:0 8px 24px rgba(0,0,0,0.18); z-index:200; max-height:220px; overflow-y:auto;">
                        @foreach($suggestions as $s)
                        <div wire:click="selectPatient({{ $s->id }})"
                            style="padding:0.55rem 1rem; cursor:pointer; border-bottom:1px solid #f9fafb; display:flex; justify-content:space-between; align-items:center; gap:0.5rem;"
                            onmouseover="this.style.background='#fef5f5'" onmouseout="this.style.background='#fff'">
                            <span style="font-weight:800; color:#1a1a2e; font-size:0.88rem; font-family:'Tajawal',sans-serif;">{{ $s->full_name }}</span>
                            <span style="display:flex; gap:0.5rem; flex-shrink:0;">
                                <span style="font-size:0.72rem; color:#1565c0; font-weight:700; background:#eff6ff; padding:0.15rem 0.4rem; border-radius:4px;">#{{ $s->file_id ?? $s->id }}</span>
                                @if($s->ssn)<span style="font-size:0.72rem; color:#6b7280; font-weight:600; direction:ltr;">{{ $s->ssn }}</span>@endif
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($patientId)
                    <div style="margin-top:0.3rem; font-size:0.76rem; color:#4ade80; font-weight:700; font-family:'Tajawal',sans-serif;">
                        ✓ تم اختيار العميل — اضغط بحث
                    </div>
                    @endif
                </td>
            </tr>

            {{-- من تاريخ --}}
            <tr style="border-bottom:1px solid rgba(255,255,255,0.1);">
                <td style="padding:0.75rem 1.25rem; text-align:left; font-weight:900; color:#fbbf24; font-size:0.88rem;">
                    From : من تاريخ
                </td>
                <td style="padding:0.65rem 1.25rem;">
                    <div style="display:flex; gap:0.35rem; direction:ltr;">
                        <select wire:model="day" style="padding:0.4rem 0.4rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-size:0.85rem; flex:1; outline:none;">
                            @foreach($days as $d)<option value="{{ $d }}">{{ $d }}</option>@endforeach
                        </select>
                        <select wire:model="month" style="padding:0.4rem 0.4rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-family:'Tajawal',sans-serif; font-size:0.85rem; flex:2; outline:none;">
                            @foreach($months as $n => $name)<option value="{{ $n }}">{{ $name }}</option>@endforeach
                        </select>
                        <select wire:model="year" style="padding:0.4rem 0.4rem; border:1px solid rgba(255,255,255,0.3); border-radius:5px; background:rgba(255,255,255,0.95); font-size:0.85rem; flex:2; outline:none;">
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
            بحث : Search
        </button>
        <button wire:click="resetForm"
            style="padding:0.45rem 1.5rem; background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.3); border-radius:6px; font-weight:800; font-size:0.88rem; font-family:'Tajawal',sans-serif; cursor:pointer;">
            إستعادة : Reset
        </button>
        @if($searched && $patient)
        <button type="button" onclick="printStatement()"
            style="padding:0.45rem 1.5rem; background:#16a34a; color:#fff; border:none; border-radius:6px; font-weight:800; font-size:0.88rem; font-family:'Tajawal',sans-serif; cursor:pointer;">
            🖨 طباعة
        </button>
        @endif
    </div>

    {{-- روابط --}}
    <div style="background:var(--navy); padding:0.5rem 1.5rem; display:flex; justify-content:center; gap:2rem; border-top:1px solid rgba(255,255,255,0.08); border-radius:0 0 12px 12px;">
        <a href="{{ route('dashboard') }}" wire:navigate style="color:#fbbf24; font-size:0.82rem; font-weight:700; text-decoration:none;">الرئيسية : Home</a>
        <a href="javascript:history.back()" style="color:#fbbf24; font-size:0.82rem; font-weight:700; text-decoration:none;">رجوع : Back</a>
    </div>

</div>
</div>

{{-- لم يُوجد --}}
@if($searched && $notFound)
<div style="width:100%; max-width:680px; margin-top:1.5rem;">
    <div style="background:#fff5f5; border:1px solid #fecaca; border-radius:10px; padding:1.5rem; text-align:center; color:#dc2626; font-weight:800; font-family:'Tajawal',sans-serif;">
        لم يُعثر على عميل بهذا البيان. تحقق من المدخلات وأعد المحاولة.
    </div>
</div>
@endif

{{-- النتائج — كشف رسمي --}}
@if($searched && $patient)
<div id="print-area" style="width:100%; max-width:800px; margin-top:1.75rem; background:#fff; border:1px solid #e2e8f0; border-radius:14px; box-shadow:0 4px 24px rgba(0,0,0,0.08); overflow:hidden; animation:fadeIn 0.4s ease;">

    {{-- ترويسة المركز — شعار على اليسار --}}
    <div style="padding:0.6rem 1.5rem; border-bottom:3px solid var(--primary); background:#fff;">
        <div style="display:flex; align-items:center; gap:1rem; direction:ltr;">
            <img src="{{ asset('logo.jpg') }}" alt="مطمئنة"
                style="height:62px; width:62px; border-radius:50%; object-fit:cover; border:2px solid var(--primary); flex-shrink:0;">
            <div style="flex:1; height:3px; background:linear-gradient(to right, var(--primary), transparent); border-radius:2px;"></div>
            <div style="text-align:right; line-height:1.2; direction:rtl;">
                <div style="font-size:0.72rem; color:#555; font-weight:600; letter-spacing:1px; font-family:'Tajawal',sans-serif;">مركز</div>
                <div style="font-size:2rem; font-weight:900; color:var(--primary); font-family:'Tajawal',sans-serif; letter-spacing:-1px;">مطمئنة</div>
            </div>
        </div>
    </div>

    {{-- عنوان الكشف + بيانات العميل --}}
    <div style="padding:1rem 1.5rem; border-bottom:1px solid #e2e8f0; background:#fafbfc; display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:0.75rem;">
        <div>
            <div style="font-size:1rem; font-weight:900; color:var(--navy); font-family:'Tajawal',sans-serif;">بيان الحساب المالي</div>
            <div style="font-size:0.78rem; color:#6b7280; margin-top:0.15rem;">
                الفترة:
                {{ $day }}/{{ $month }}/{{ $year }}
                —
                {{ $toDay }}/{{ $toMonth }}/{{ $toYear }}
            </div>
        </div>
        <div style="text-align:right; font-family:'Tajawal',sans-serif; font-size:0.85rem; color:#374151; line-height:1.8;">
            <div><span style="color:#6b7280; font-size:0.76rem;">الاسم:</span> <strong>{{ $patient->full_name }}</strong></div>
            <div>
                <span style="color:#6b7280; font-size:0.76rem;">رقم الملف:</span>
                <strong style="color:var(--primary);">#{{ $patient->file_id ?? $patient->id }}</strong>
                @if($patient->ssn)
                &nbsp;&nbsp;
                <span style="color:#6b7280; font-size:0.76rem;">الهوية:</span>
                <strong>{{ $patient->ssn }}</strong>
                @endif
            </div>
            @if($patient->phone)
            <div><span style="color:#6b7280; font-size:0.76rem;">الهاتف:</span> <strong>{{ $patient->phone }}</strong></div>
            @endif
        </div>
    </div>

    {{-- ملخص مالي --}}
    @php $totalAll = collect($rows)->sum('amount'); @endphp
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:0; border-bottom:1px solid #e2e8f0;">
        <div style="padding:0.85rem 1.25rem; text-align:center; border-left:1px solid #e2e8f0;">
            <div style="font-size:0.72rem; color:#6b7280; font-weight:700; font-family:'Tajawal',sans-serif; margin-bottom:0.25rem;">إجمالي المحصّل</div>
            <div style="font-size:1.3rem; font-weight:900; color:#16a34a; font-family:'Inter';">{{ number_format($totalCredit, 3) }} <span style="font-size:0.75rem;">د.ك</span></div>
        </div>
        <div style="padding:0.85rem 1.25rem; text-align:center; border-left:1px solid #e2e8f0;">
            <div style="font-size:0.72rem; color:#6b7280; font-weight:700; font-family:'Tajawal',sans-serif; margin-bottom:0.25rem;">إجمالي الآجل</div>
            <div style="font-size:1.3rem; font-weight:900; color:#dc2626; font-family:'Inter';">{{ number_format($totalDebit, 3) }} <span style="font-size:0.75rem;">د.ك</span></div>
        </div>
        <div style="padding:0.85rem 1.25rem; text-align:center; background:#f8fafc;">
            <div style="font-size:0.72rem; color:#6b7280; font-weight:700; font-family:'Tajawal',sans-serif; margin-bottom:0.25rem;">إجمالي الخدمات</div>
            <div style="font-size:1.3rem; font-weight:900; color:var(--navy); font-family:'Inter';">
                {{ number_format($totalAll, 3) }} <span style="font-size:0.75rem;">د.ك</span>
            </div>
        </div>
    </div>

    {{-- جدول الحركات --}}
    @php
        $methods = [1=>'نقدي',2=>'K-Net',3=>'فيزا',4=>'مجاني',5=>'آجل'];
        $methodColors = [1=>'#16a34a',2=>'#1d4ed8',3=>'#7c3aed',4=>'#6b7280',5=>'#dc2626'];
    @endphp
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif; font-size:0.84rem;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                    <th style="padding:0.65rem 1rem; text-align:center; font-weight:900; color:#374151; font-size:0.78rem;">#</th>
                    <th style="padding:0.65rem 1rem; text-align:center; font-weight:900; color:#374151; font-size:0.78rem;">سند</th>
                    <th style="padding:0.65rem 1rem; text-align:center; font-weight:900; color:#374151; font-size:0.78rem;">التاريخ</th>
                    <th style="padding:0.65rem 1rem; font-weight:900; color:#374151; font-size:0.78rem;">البيان</th>
                    <th style="padding:0.65rem 1rem; text-align:center; font-weight:900; color:#374151; font-size:0.78rem;">طريقة الدفع</th>
                    <th style="padding:0.65rem 1rem; text-align:center; font-weight:900; color:#374151; font-size:0.78rem;">المبلغ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $i => $r)
                @php
                    $m   = $r->payment_method ?? 1;
                    $clr = $methodColors[$m] ?? '#374151';
                    $desc = trim(preg_replace('/\s+/',' ', strip_tags(html_entity_decode($r->pdesc ?? ''))));
                @endphp
                <tr style="border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                    <td style="padding:0.6rem 1rem; text-align:center; color:#9ca3af; font-size:0.8rem;">{{ $i + 1 }}</td>
                    <td style="padding:0.6rem 1rem; text-align:center;">
                        <span style="background:#eff6ff; color:#1d4ed8; font-size:0.76rem; font-weight:800; padding:0.15rem 0.45rem; border-radius:4px; border:1px solid #bfdbfe;">#{{ $r->serial_no ?: $r->id }}</span>
                    </td>
                    <td style="padding:0.6rem 1rem; text-align:center; color:#1d4ed8; font-weight:700; direction:ltr; unicode-bidi:isolate; font-size:0.85rem;">{{ fmt_date($r->pdate) }}</td>
                    <td style="padding:0.6rem 1rem; color:#374151; max-width:240px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $desc }}">{{ $desc ?: '—' }}</td>
                    <td style="padding:0.6rem 1rem; text-align:center;">
                        <span style="font-size:0.75rem; font-weight:800; color:{{ $clr }}; background:{{ $clr }}18; padding:0.15rem 0.5rem; border-radius:4px; border:1px solid {{ $clr }}33;">
                            {{ $methods[$m] ?? 'أخرى' }}
                        </span>
                    </td>
                    <td style="padding:0.6rem 1rem; text-align:center; font-weight:900; color:#374151; font-family:'Inter'; font-size:0.95rem;">
                        {{ $r->amount > 0 ? number_format($r->amount, 3) : '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:3rem; text-align:center; color:#9ca3af; font-family:'Tajawal',sans-serif;">
                        لا توجد حركات مالية في هذه الفترة
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if(count($rows) > 0)
            <tfoot>
                <tr style="background:#f1f5f9; border-top:2px solid #cbd5e1; font-weight:900;">
                    <td colspan="5" style="padding:0.7rem 1rem; text-align:right; color:var(--navy); font-family:'Tajawal',sans-serif; font-size:0.88rem;">
                        إجمالي المبالغ المحصّلة (نقدي/كنت/فيزا)
                    </td>
                    <td style="padding:0.7rem 1rem; text-align:center; color:#16a34a; font-family:'Inter';">{{ number_format($totalCredit, 3) }}</td>
                </tr>
                @if($totalDebit > 0)
                <tr style="background:#fff5f5; border-top:1px solid #fecaca; font-weight:900;">
                    <td colspan="5" style="padding:0.65rem 1rem; text-align:right; color:#b91c1c; font-family:'Tajawal',sans-serif; font-size:0.88rem;">
                        إجمالي المبالغ الآجلة (غير مسددة)
                    </td>
                    <td style="padding:0.65rem 1rem; text-align:center; color:#dc2626; font-family:'Inter';">{{ number_format($totalDebit, 3) }}</td>
                </tr>
                @endif
            </tfoot>
            @endif
        </table>
    </div>

    {{-- تذييل الكشف --}}
    <div style="padding:0.75rem 1.5rem; border-top:1px solid #e2e8f0; background:#fafbfc; display:flex; justify-content:space-between; align-items:center; font-size:0.75rem; color:#9ca3af; font-family:'Tajawal',sans-serif;">
        <span>تاريخ الطباعة: {{ now()->format('d/m/Y H:i') }}</span>
        <span>مركز مطمئنة الاستشاري</span>
    </div>

</div>
@endif

</div>

