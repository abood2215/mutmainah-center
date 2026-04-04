<style>
.vc-filter-card { background:#fff; border-radius:14px; border:1px solid #e5e7eb; box-shadow:0 4px 20px rgba(0,0,0,0.07); width:100%; max-width:660px; }
.vc-filter-head { background:var(--primary); padding:0.85rem 1.5rem; border-radius:13px 13px 0 0; text-align:center; }
.vc-filter-head h2 { color:#fff; font-size:1rem; font-weight:900; margin:0; font-family:'Tajawal',sans-serif; }
.vc-filter-body { padding:1.25rem 1.5rem; display:flex; flex-direction:column; gap:1rem; }
.vc-field-label { font-size:0.82rem; font-weight:800; color:#374151; margin-bottom:0.3rem; font-family:'Tajawal',sans-serif; }
.vc-select { width:100%; padding:0.5rem 0.75rem; border:1px solid #d1d5db; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.88rem; color:#1a1a2e; outline:none; background:#fff; cursor:pointer; }
.vc-select:focus { border-color:var(--primary); }
.vc-date-sel { padding:0.45rem 0.5rem; border:1px solid #d1d5db; border-radius:7px; background:#fff; font-family:'Tajawal',sans-serif; font-size:0.85rem; color:#1a1a2e; outline:none; cursor:pointer; }
.vc-date-row { display:flex; gap:0.4rem; direction:ltr; }
.vc-date-row .vc-date-sel:first-child { flex:1; }
.vc-date-row .vc-date-sel:nth-child(2) { flex:2; }
.vc-date-row .vc-date-sel:last-child { flex:2; }
.vc-filter-footer { padding:0.85rem 1.5rem; border-top:1px solid #f1f5f9; display:flex; justify-content:center; gap:0.75rem; flex-wrap:wrap; }
@media (max-width:600px) {
    .vc-filter-body { padding:1rem; gap:0.85rem; }
    .vc-filter-footer { padding:0.75rem 1rem; }
    .vc-result-table { overflow-x:auto; display:block; }
}
</style>
<div class="pg-outer vc-outer" style="min-height:80vh; padding:1.25rem 1rem; display:flex; flex-direction:column; align-items:center; gap:1.5rem;">

@php
$days   = range(1, 31);
$months = [1=>'يناير',2=>'فبراير',3=>'مارس',4=>'أبريل',5=>'مايو',6=>'يونيو',
           7=>'يوليو',8=>'أغسطس',9=>'سبتمبر',10=>'أكتوبر',11=>'نوفمبر',12=>'ديسمبر'];
$years  = range(2000, now()->year + 1);
@endphp

<div class="vc-filter-card" style="animation:fadeIn 0.4s ease;">

    <div class="vc-filter-head">
        <h2>تقارير السندات</h2>
    </div>

    <div class="vc-filter-body">

        {{-- التصنيف --}}
        <div>
            <div class="vc-field-label">التصنيف</div>
            <select wire:model="classId" class="vc-select">
                <option value="mutmainah">مركز مطمئنة الكويت</option>
            </select>
        </div>

        {{-- النوع --}}
        <div>
            <div class="vc-field-label">نوع السند</div>
            <select wire:model="voucherType" class="vc-select">
                <option value="receipt">سند قبض</option>
                <option value="payment">سند صرف</option>
                <option value="">الكل</option>
            </select>
        </div>

        {{-- من تاريخ --}}
        <div>
            <div class="vc-field-label">من تاريخ</div>
            <div class="vc-date-row">
                <select wire:model="fromDay" class="vc-date-sel">
                    @foreach($days as $d)<option value="{{ $d }}" @selected((int)$fromDay===$d)>{{ $d }}</option>@endforeach
                </select>
                <select wire:model="fromMonth" class="vc-date-sel">
                    @foreach($months as $n => $name)<option value="{{ $n }}" @selected((int)$fromMonth===$n)>{{ $name }}</option>@endforeach
                </select>
                <select wire:model="fromYear" class="vc-date-sel">
                    @foreach($years as $y)<option value="{{ $y }}" @selected((int)$fromYear===$y)>{{ $y }}</option>@endforeach
                </select>
            </div>
        </div>

        {{-- حتى تاريخ --}}
        <div>
            <div class="vc-field-label">حتى تاريخ</div>
            <div class="vc-date-row">
                <select wire:model="toDay" class="vc-date-sel">
                    @foreach($days as $d)<option value="{{ $d }}" @selected((int)$toDay===$d)>{{ $d }}</option>@endforeach
                </select>
                <select wire:model="toMonth" class="vc-date-sel">
                    @foreach($months as $n => $name)<option value="{{ $n }}" @selected((int)$toMonth===$n)>{{ $name }}</option>@endforeach
                </select>
                <select wire:model="toYear" class="vc-date-sel">
                    @foreach($years as $y)<option value="{{ $y }}" @selected((int)$toYear===$y)>{{ $y }}</option>@endforeach
                </select>
            </div>
        </div>

    </div>

    {{-- أزرار --}}
    <div class="vc-filter-footer">
        <button wire:click="search" class="btn btn-primary" style="padding:0.5rem 2rem; font-size:0.9rem;">
            بحث
        </button>
        <button wire:click="resetForm" class="btn" style="padding:0.5rem 1.25rem; font-size:0.9rem; background:#f3f4f6; color:#374151; border:1px solid #e5e7eb;">
            إعادة تعيين
        </button>
    </div>

</div>

{{-- النتائج --}}
@if($searched && $rows)
<div style="width:100%; max-width:860px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; box-shadow:0 4px 16px rgba(0,0,0,0.06);">
    <div style="background:var(--primary); padding:0.65rem 1.25rem; display:flex; justify-content:space-between; align-items:center;">
        <span style="color:#fff; font-weight:900; font-size:0.92rem; font-family:'Tajawal',sans-serif;">
            {{ $voucherType === 'receipt' ? 'سندات القبض' : ($voucherType === 'payment' ? 'سندات الصرف' : 'جميع السندات') }}
        </span>
        <div style="display:flex; gap:1rem;">
            <span style="color:#bbf7d0; font-size:0.82rem; font-weight:800;">قبض: {{ number_format($totalCredit, 3) }}</span>
            <span style="color:#fecaca; font-size:0.82rem; font-weight:800;">صرف: {{ number_format($totalDebit, 3) }}</span>
        </div>
    </div>
    <div class="vc-result-table">
        <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif; font-size:0.83rem;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                    <th style="padding:0.6rem 0.75rem; text-align:center; font-weight:900; color:#6b7280;">#</th>
                    <th style="padding:0.6rem 0.75rem; font-weight:900; color:#374151;">العميل</th>
                    <th style="padding:0.6rem 0.75rem; font-weight:900; color:#374151;">التاريخ</th>
                    <th style="padding:0.6rem 0.75rem; font-weight:900; color:#374151;">النوع</th>
                    <th style="padding:0.6rem 0.75rem; font-weight:900; color:#374151;">البيان</th>
                    <th style="padding:0.6rem 0.75rem; text-align:center; font-weight:900; color:#16a34a;">دائن</th>
                    <th style="padding:0.6rem 0.75rem; text-align:center; font-weight:900; color:#dc2626;">مدين</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $i => $r)
                <tr style="border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                    <td style="padding:0.55rem 0.75rem; text-align:center; color:#9ca3af;">{{ $i+1 }}</td>
                    <td style="padding:0.55rem 0.75rem; font-weight:800; color:#1a1a2e;">{{ $r->client_name ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; color:#1565c0; font-weight:700; direction:ltr; unicode-bidi:isolate;">{{ fmt_date($r->pdate) }}</td>
                    <td style="padding:0.55rem 0.75rem; color:#6b7280;">{{ $r->ptype ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; color:#374151; max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $r->pdesc ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; font-weight:800; color:#16a34a;">{{ $r->credit > 0 ? number_format($r->credit, 3) : '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; font-weight:800; color:#dc2626;">{{ $r->debit > 0 ? number_format($r->debit, 3) : '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" style="padding:3rem; text-align:center; color:#9ca3af;">لا توجد سندات في هذه الفترة</td></tr>
                @endforelse
            </tbody>
            @if($rows->count() > 0)
            <tfoot>
                <tr style="background:#f8fafc; border-top:2px solid #e2e8f0; font-weight:900;">
                    <td colspan="5" style="padding:0.6rem 0.75rem; text-align:right; color:#1a1a2e; font-family:'Tajawal',sans-serif;">الإجمالي</td>
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
