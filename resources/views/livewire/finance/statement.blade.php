<style>
.st-filter-card { background:#fff; border-radius:14px; border:1px solid #e5e7eb; box-shadow:0 4px 20px rgba(0,0,0,0.07); overflow:visible; width:100%; max-width:660px; }
.st-filter-head { background:var(--primary); padding:0.85rem 1.5rem; border-radius:13px 13px 0 0; text-align:center; }
.st-filter-head h2 { color:#fff; font-size:1rem; font-weight:900; margin:0; font-family:'Tajawal',sans-serif; }
.st-filter-body { padding:1.25rem 1.5rem; display:flex; flex-direction:column; gap:1rem; }
.st-field-label { font-size:0.82rem; font-weight:800; color:#374151; margin-bottom:0.3rem; font-family:'Tajawal',sans-serif; }
.st-input { width:100%; padding:0.5rem 0.75rem; border:1px solid #d1d5db; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.9rem; color:#1a1a2e; outline:none; background:#fff; transition:border-color 0.2s; }
.st-input:focus { border-color:var(--primary); }
.st-input.selected { border-color:#16a34a; background:#f0fdf4; }
.st-select { padding:0.45rem 0.5rem; border:1px solid #d1d5db; border-radius:7px; background:#fff; font-family:'Tajawal',sans-serif; font-size:0.85rem; color:#1a1a2e; outline:none; cursor:pointer; }
.st-date-row { display:flex; gap:0.4rem; direction:ltr; }
.st-date-row .st-select:first-child { flex:1; }
.st-date-row .st-select:nth-child(2) { flex:2; }
.st-date-row .st-select:last-child { flex:2; }
.st-filter-footer { padding:0.85rem 1.5rem; border-top:1px solid #f1f5f9; display:flex; justify-content:center; gap:0.75rem; flex-wrap:wrap; }
.st-suggestions { position:absolute; top:calc(100% + 2px); right:0; left:0; background:#fff; border:1px solid #e5e7eb; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,0.13); z-index:200; max-height:220px; overflow-y:auto; }
.st-suggestion-item { padding:0.55rem 1rem; cursor:pointer; border-bottom:1px solid #f9fafb; display:flex; justify-content:space-between; align-items:center; gap:0.5rem; font-family:'Tajawal',sans-serif; }
.st-suggestion-item:hover { background:#fef5f5; }
@media (max-width:600px) {
    .st-filter-body { padding:1rem; gap:0.85rem; }
    .st-filter-footer { padding:0.75rem 1rem; }
    .pg-3col { grid-template-columns:1fr !important; }
    .st-result-table { overflow-x:auto; display:block; }
}
</style>
<div class="pg-outer st-outer" style="min-height:80vh; padding:1.25rem 1rem; display:flex; flex-direction:column; align-items:center; gap:1.5rem;">
@php
$days   = range(1, 31);
$months = [1=>'يناير',2=>'فبراير',3=>'مارس',4=>'أبريل',5=>'مايو',6=>'يونيو',
           7=>'يوليو',8=>'أغسطس',9=>'سبتمبر',10=>'أكتوبر',11=>'نوفمبر',12=>'ديسمبر'];
$years  = range(2000, now()->year + 1);
@endphp

{{-- بطاقة الفلتر --}}
<div class="st-filter-card" style="animation:fadeIn 0.4s ease;">

    <div class="st-filter-head">
        <h2>بيان الحساب</h2>
    </div>

    <div class="st-filter-body">

        {{-- حقل البحث --}}
        <div style="position:relative;">
            <div class="st-field-label">العميل</div>
            <input type="text" wire:model.live.debounce.300ms="searchQuery"
                placeholder="ابحث بالاسم / الهوية / رقم الملف / الهاتف"
                autocomplete="off"
                class="st-input {{ $patientId ? 'selected' : '' }}">

            @if($suggestions)
            <div class="st-suggestions">
                @foreach($suggestions as $s)
                <div wire:click="selectPatient({{ $s->id }})" class="st-suggestion-item">
                    <span style="font-weight:800; color:#1a1a2e; font-size:0.88rem;">{{ $s->full_name }}</span>
                    <span style="display:flex; gap:0.5rem; flex-shrink:0;">
                        <span style="font-size:0.72rem; color:#1565c0; font-weight:700; background:#eff6ff; padding:0.15rem 0.4rem; border-radius:4px;">#{{ $s->file_id ?? $s->id }}</span>
                        @if($s->ssn)<span style="font-size:0.72rem; color:#6b7280; font-weight:600; direction:ltr;">{{ $s->ssn }}</span>@endif
                    </span>
                </div>
                @endforeach
            </div>
            @endif

            @if($patientId)
            <div style="margin-top:0.3rem; font-size:0.76rem; color:#16a34a; font-weight:800; font-family:'Tajawal',sans-serif;">
                ✓ تم اختيار العميل — اضغط بحث
            </div>
            @endif
        </div>

        {{-- من تاريخ --}}
        <div>
            <div class="st-field-label">من تاريخ</div>
            <div class="st-date-row">
                <select wire:model="day" class="st-select">
                    @foreach($days as $d)<option value="{{ $d }}" @selected((int)$day===$d)>{{ $d }}</option>@endforeach
                </select>
                <select wire:model="month" class="st-select">
                    @foreach($months as $n => $name)<option value="{{ $n }}" @selected((int)$month===$n)>{{ $name }}</option>@endforeach
                </select>
                <select wire:model="year" class="st-select">
                    @foreach($years as $y)<option value="{{ $y }}" @selected((int)$year===$y)>{{ $y }}</option>@endforeach
                </select>
            </div>
        </div>

        {{-- حتى تاريخ --}}
        <div>
            <div class="st-field-label">حتى تاريخ</div>
            <div class="st-date-row">
                <select wire:model="toDay" class="st-select">
                    @foreach($days as $d)<option value="{{ $d }}" @selected((int)$toDay===$d)>{{ $d }}</option>@endforeach
                </select>
                <select wire:model="toMonth" class="st-select">
                    @foreach($months as $n => $name)<option value="{{ $n }}" @selected((int)$toMonth===$n)>{{ $name }}</option>@endforeach
                </select>
                <select wire:model="toYear" class="st-select">
                    @foreach($years as $y)<option value="{{ $y }}" @selected((int)$toYear===$y)>{{ $y }}</option>@endforeach
                </select>
            </div>
        </div>

    </div>

    {{-- أزرار --}}
    <div class="st-filter-footer">
        <button wire:click="search" class="btn btn-primary" style="padding:0.5rem 2rem; font-size:0.9rem;">
            بحث
        </button>
        <button wire:click="resetForm" class="btn" style="padding:0.5rem 1.25rem; font-size:0.9rem; background:#f3f4f6; color:#374151; border:1px solid #e5e7eb;">
            إعادة تعيين
        </button>
        @if($searched && $patient)
        <button type="button" onclick="printStatement()" class="btn" style="padding:0.5rem 1.25rem; font-size:0.9rem; background:#16a34a; color:#fff; border:none;">
            🖨 طباعة
        </button>
        @endif
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
    <div class="pg-3col" style="display:grid; grid-template-columns:repeat(3,1fr); gap:0; border-bottom:1px solid #e2e8f0;">
        <div style="padding:0.85rem 1.25rem; text-align:center; border-left:1px solid #e2e8f0;">
            <div style="font-size:0.72rem; color:#6b7280; font-weight:700; font-family:'Tajawal',sans-serif; margin-bottom:0.25rem;">إجمالي المحصّل</div>
            <div style="font-size:1.3rem; font-weight:900; color:#16a34a; font-family:'Inter';">{{ number_format($totalCredit, 3) }} <span style="font-size:0.75rem;">د.ك</span></div>
        </div>
        <div style="padding:0.85rem 1.25rem; text-align:center; border-left:1px solid #e2e8f0;">
            <div style="font-size:0.72rem; color:#6b7280; font-weight:700; font-family:'Tajawal',sans-serif; margin-bottom:0.25rem;">عدد الحركات</div>
            <div style="font-size:1.3rem; font-weight:900; color:#1d4ed8; font-family:'Inter';">{{ count($rows) }}</div>
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
        $methods = [1=>'نقد',2=>'شيك',3=>'شبكة',4=>'تحويل',5=>'سند',6=>'فيزا',7=>'مجاني',11=>'Myfatoorah',12=>'STC',14=>'Quick Pay'];
        $methodColors = [1=>'#16a34a',2=>'#0891b2',3=>'#1d4ed8',4=>'#7c3aed',5=>'#d97706',6=>'#7c3aed',7=>'#6b7280',11=>'#0891b2',12=>'#16a34a',14=>'#16a34a'];
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
                        إجمالي المبالغ المحصّلة
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

