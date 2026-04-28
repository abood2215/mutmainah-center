<div class="ncv2-wrap">
<style>
/* ════════════════════════════════
   New Check v2 — Redesign
   ════════════════════════════════ */
.ncv2-wrap {
    padding: 1.25rem 1.5rem;
    min-height: 80vh;
    font-family: 'Tajawal', sans-serif;
    background: #f0f2f7;
}

/* ── الصندوق الرئيسي ── */
.ncv2 {
    max-width: 1300px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* ── بطاقة عامة ── */
.nc-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,.07);
    overflow: hidden;
}

/* ── رأس الصفحة ── */
.ncv2-header {
    background: linear-gradient(135deg, var(--primary) 0%, #6b1520 100%);
    border-radius: 10px;
    padding: 14px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.ncv2-header .hd-title {
    color: #fff;
    font-size: 1.15rem;
    font-weight: 900;
    letter-spacing: .3px;
}
.ncv2-header .hd-title span { opacity: .7; font-weight: 500; font-size: .9rem; margin-right: 8px; }
.ncv2-header .hd-back {
    background: rgba(255,255,255,.18);
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 6px 16px;
    font-family: 'Tajawal', sans-serif;
    font-size: .83rem;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    transition: background .2s;
}
.ncv2-header .hd-back:hover { background: rgba(255,255,255,.28); }

/* ── بطاقة معلومات العميل ── */
.nc-patient-card {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,.07);
}
.nc-patient-card .pc-head {
    background: var(--navy);
    padding: 8px 18px;
    font-size: .75rem;
    font-weight: 800;
    color: rgba(255,255,255,.7);
    letter-spacing: 1px;
    text-transform: uppercase;
}
.nc-patient-card .pc-body {
    background: #fff;
    padding: 12px 18px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px 24px;
    align-items: center;
}
.nc-pfield {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 5px 12px;
    background: #f5f7fc;
    border-radius: 20px;
    border: 1px solid #e4e9f5;
}
.nc-pfield .pf-lbl {
    font-size: .73rem;
    font-weight: 800;
    color: #888;
    white-space: nowrap;
}
.nc-pfield .pf-val {
    font-size: .85rem;
    font-weight: 800;
    color: var(--navy);
}
.nc-pfield .pf-val.link { color: var(--primary); text-decoration: none; }
.nc-pfield .pf-val.link:hover { text-decoration: underline; }
.nc-pfield.pf-balance-ok  { background: #e8f5e9; border-color: #a5d6a7; }
.nc-pfield.pf-balance-ok .pf-val  { color: #1b5e20; }
.nc-pfield.pf-balance-low { background: #fff3e0; border-color: #ffcc80; }
.nc-pfield.pf-balance-low .pf-val { color: #e65100; }
.nc-pfield.pf-balance-neg { background: #ffebee; border-color: #ffcdd2; }
.nc-pfield.pf-balance-neg .pf-val { color: #b71c1c; }

/* ── قسم إضافة الخدمة ── */
.nc-add-card .ac-head {
    background: #f7f8fa;
    border-bottom: 1px solid #e8e8e8;
    padding: 8px 18px;
    font-size: .75rem;
    font-weight: 800;
    color: #666;
    letter-spacing: 1px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 6px;
}
.nc-add-card .ac-head::before {
    content: '';
    display: inline-block;
    width: 3px;
    height: 14px;
    background: var(--primary);
    border-radius: 2px;
}
.nc-add-card .ac-body {
    padding: 12px 18px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: flex-end;
}
.ac-field {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.ac-field.grow { flex: 1; min-width: 160px; }
.ac-field label {
    font-size: .74rem;
    font-weight: 800;
    color: #555;
    white-space: nowrap;
}
.ac-field label.lbl-red { color: var(--primary); }
.nc-inp {
    border: 1.5px solid #dde2ee;
    border-radius: 6px;
    padding: 7px 10px;
    font-family: 'Tajawal', sans-serif;
    font-size: .84rem;
    background: #fff;
    outline: none;
    transition: border .2s, box-shadow .2s;
    color: var(--navy);
}
.nc-inp:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(139,28,43,.1); }
.nc-inp-qty {
    width: 60px;
    text-align: center;
    font-weight: 900;
    font-size: .95rem;
    color: var(--primary);
    border-color: var(--primary) !important;
}
.nc-inp-service { min-width: 220px; }

.nc-btn-add {
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 8px 24px;
    font-family: 'Tajawal', sans-serif;
    font-size: .9rem;
    font-weight: 900;
    cursor: pointer;
    white-space: nowrap;
    transition: all .2s;
    box-shadow: 0 2px 8px rgba(139,28,43,.3);
    align-self: flex-end;
}
.nc-btn-add:hover { background: #6b1520; box-shadow: 0 4px 12px rgba(139,28,43,.4); }

/* ── جدول الخدمات ── */
.nc-tbl-head {
    background: #f7f8fa;
    border-bottom: 1px solid #e8e8e8;
    padding: 8px 18px;
    font-size: .75rem;
    font-weight: 800;
    color: #666;
    letter-spacing: 1px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 6px;
}
.nc-tbl-head::before {
    content: '';
    display: inline-block;
    width: 3px;
    height: 14px;
    background: var(--navy);
    border-radius: 2px;
}
.nc-table-wrap { overflow-x: auto; }
.nc-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 640px;
}
.nc-table thead tr { background: var(--navy); }
.nc-table thead th {
    color: #fff;
    padding: 9px 12px;
    font-size: .76rem;
    font-weight: 800;
    white-space: nowrap;
    border-left: 1px solid rgba(255,255,255,.1);
}
.nc-table thead th:last-child { border-left: none; }
.nc-table tbody tr { border-bottom: 1px solid #f0f0f0; transition: background .15s; }
.nc-table tbody tr:last-child { border-bottom: none; }
.nc-table tbody tr:hover { background: #f5f7ff; }
.nc-table td { padding: 8px 12px; font-size: .83rem; vertical-align: middle; }
.td-num  { text-align:center; font-weight:900; color:var(--primary); width:40px; }
.td-name { font-weight:700; color:var(--navy); }
.td-code { text-align:center; color:#777; font-size:.78rem; }
.td-clinic { text-align:center; color:#555; font-size:.8rem; }
.td-price { text-align:center; font-weight:800; color:#1b5e20; direction:ltr; font-size:.9rem; }
.td-ins   { text-align:center; color:#1565c0; font-weight:700; }
.td-notes input {
    width:100%;
    border:1.5px solid #dde2ee;
    border-radius:5px;
    padding:4px 8px;
    font-family:'Tajawal', sans-serif;
    font-size:.78rem;
    outline:none;
    transition:border .2s;
}
.td-notes input:focus { border-color: var(--primary); }
.td-del { text-align:center; width:64px; }
.nc-del-btn {
    background:#fff;
    color:#c0392b;
    border:1.5px solid #e0b0b0;
    border-radius:5px;
    padding:4px 12px;
    font-family:'Tajawal', sans-serif;
    font-size:.76rem;
    font-weight:800;
    cursor:pointer;
    transition:all .2s;
}
.nc-del-btn:hover { background:#c0392b; color:#fff; border-color:#c0392b; }
.nc-empty td {
    padding:30px;
    text-align:center;
    color:#bbb;
    font-size:.86rem;
}

/* ── بطاقات الإجماليات ── */
.nc-totals-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: .75rem;
}
.nc-tot-card {
    background: #fff;
    border-radius: 10px;
    padding: 14px 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
    border-top: 3px solid transparent;
    text-align: center;
}
.nc-tot-card .tc-lbl { font-size: .72rem; font-weight: 800; color: #888; letter-spacing: .5px; margin-bottom: 6px; }
.nc-tot-card .tc-val { font-size: 1.2rem; font-weight: 900; font-family: 'Inter', sans-serif; }
.nc-tot-card.tc-sum   { border-color: var(--navy); }
.nc-tot-card.tc-sum   .tc-val { color: var(--navy); }
.nc-tot-card.tc-disc  { border-color: #e65100; }
.nc-tot-card.tc-disc  .tc-val { color: #e65100; }
.nc-tot-card.tc-ins   { border-color: #1565c0; }
.nc-tot-card.tc-ins   .tc-val { color: #1565c0; }
.nc-tot-card.tc-pat   { border-color: #1b5e20; }
.nc-tot-card.tc-pat   .tc-val { color: #1b5e20; }
.nc-tot-card .tc-sub  { font-size: .72rem; color: #aaa; margin-top: 2px; }

/* ── قسم الدفع ── */
.nc-payment-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .75rem;
}
.nc-pay-section { background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.06); overflow: hidden; }
.nc-pay-section .ps-head {
    padding: 9px 18px;
    font-size: .75rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: 1px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 6px;
}
.ps-head-left  { background: var(--primary); }
.ps-head-right { background: #7a4400; }
.nc-pay-section .ps-body { padding: 16px 18px; }

.nc-frow { margin-bottom: 14px; }
.nc-frow:last-child { margin-bottom: 0; }
.nc-frow label {
    display: block;
    font-size: .78rem;
    font-weight: 800;
    margin-bottom: 5px;
    color: #555;
}
.nc-frow .nc-full {
    width: 100%;
    border: 1.5px solid #dde2ee;
    border-radius: 6px;
    padding: 8px 11px;
    font-family: 'Tajawal', sans-serif;
    font-size: .88rem;
    background: #fff;
    box-sizing: border-box;
    outline: none;
    transition: border .2s, box-shadow .2s;
    color: var(--navy);
}
.nc-frow .nc-full:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(139,28,43,.1); }
.nc-frow .nc-full[readonly] { background: #f5f5f5; color: #888; }

.nc-free-badge {
    display: inline-block;
    background: #e8f5e9;
    color: #2e7d32;
    padding: 3px 12px;
    border-radius: 20px;
    font-size: .79rem;
    font-weight: 800;
    margin-right: 8px;
}

/* بطاقة الرصيد */
.nc-balance-card {
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}
.nc-balance-card.bc-ok  { background: #e8f5e9; border: 1.5px solid #a5d6a7; }
.nc-balance-card.bc-low { background: #fff3e0; border: 1.5px solid #ffcc80; }
.nc-balance-card.bc-neg { background: #ffebee; border: 1.5px solid #ffcdd2; }
.nc-balance-card .bc-label { font-size: .78rem; font-weight: 800; color: #555; }
.nc-balance-card .bc-amount { font-size: 1rem; font-weight: 900; font-family: 'Inter', sans-serif; }
.bc-ok  .bc-amount { color: #1b5e20; }
.bc-low .bc-amount { color: #e65100; }
.bc-neg .bc-amount { color: #b71c1c; }

/* مربع المبلغ النهائي */
.nc-amount-final {
    background: linear-gradient(135deg, var(--primary) 0%, #6b1520 100%);
    color: #fff;
    border-radius: 8px;
    padding: 12px 16px;
    text-align: center;
    margin-top: 14px;
    box-shadow: 0 4px 12px rgba(139,28,43,.3);
}
.nc-amount-final.af-danger { background: linear-gradient(135deg, #b71c1c 0%, #7f0000 100%); }
.nc-amount-final .af-lbl { font-size: .74rem; font-weight: 700; opacity: .8; margin-bottom: 4px; }
.nc-amount-final .af-val { font-size: 1.6rem; font-weight: 900; font-family: 'Inter', sans-serif; }
.nc-amount-final .af-cur { font-size: .82rem; opacity: .7; margin-right: 4px; }

/* ── تنبيه الرصيد ── */
.nc-alert {
    border-radius: 8px;
    padding: 10px 16px;
    font-size: .82rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 8px;
}
.nc-alert.alert-danger { background: #b71c1c; color: #fff; }
.nc-alert.alert-warn   { background: #fff3e0; color: #e65100; border: 1px solid #ffcc80; }

/* ── الشريط السفلي ── */
.nc-footer {
    background: linear-gradient(135deg, #1e4d8c 0%, #2e6da4 100%);
    border-radius: 10px;
    padding: 12px 20px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    justify-content: space-between;
    box-shadow: 0 4px 14px rgba(30,77,140,.35);
}
.nc-footer .btns { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
.nc-footer .warn-txt { color: #ffcc80; font-size: .76rem; font-weight: 700; }

.ncbtn {
    border: none;
    border-radius: 7px;
    padding: 9px 22px;
    font-family: 'Tajawal', sans-serif;
    font-size: .87rem;
    font-weight: 800;
    cursor: pointer;
    white-space: nowrap;
    transition: all .2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.ncbtn-save  { background: #27ae60; color: #fff; box-shadow: 0 2px 8px rgba(39,174,96,.4); }
.ncbtn-save:hover:not(:disabled) { background: #219a52; }
.ncbtn-save:disabled  { background: #888; cursor: not-allowed; box-shadow: none; }
.ncbtn-reset { background: rgba(255,255,255,.15); color: #fff; }
.ncbtn-reset:hover { background: rgba(255,255,255,.25); }
.ncbtn-back  { background: rgba(255,255,255,.1); color: rgba(255,255,255,.85); text-decoration: none; }
.ncbtn-back:hover { background: rgba(255,255,255,.2); }

/* ── Responsive ── */
@media (max-width: 900px) {
    .nc-totals-row    { grid-template-columns: repeat(2,1fr); }
    .nc-payment-grid  { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .ncv2-wrap { padding: .75rem; }
    .nc-totals-row { grid-template-columns: repeat(2,1fr); }
    .nc-add-card .ac-body { gap: 8px; }
    .ac-field.grow { min-width: 100%; }
}
</style>

<div class="ncv2">

    {{-- ── رأس الصفحة ── --}}
    <div class="ncv2-header">
        <div class="hd-title">
            كشف جديد <span>/ New Check</span>
        </div>
        <a href="{{ route('checks.index') }}" wire:navigate class="hd-back">
            &#8592; العودة
        </a>
    </div>

    {{-- ── بطاقة معلومات العميل ── --}}
    <div class="nc-patient-card">
        <div class="pc-head">بيانات العميل — Patient Info</div>
        <div class="pc-body">
            <div class="nc-pfield">
                <span class="pf-lbl">المراجع</span>
                <a href="{{ route('patients.show', $patient->id) }}" class="pf-val link">
                    {{ $patient->full_name }} &nbsp;#{{ $patient->file_id }}
                </a>
            </div>
            <div class="nc-pfield">
                <span class="pf-lbl">الجوال</span>
                <span class="pf-val">{{ $patient->phone ?: '—' }}</span>
            </div>
            <div class="nc-pfield">
                <span class="pf-lbl">التأمين</span>
                <span class="pf-val" style="color:var(--primary);">{{ $patient->insurance ?? 'على نفقته' }}</span>
            </div>
            @if($hasAccount)
                @php
                    $bClass = $balance > 0 ? 'pf-balance-ok' : ($balance == 0 ? '' : 'pf-balance-neg');
                @endphp
                <div class="nc-pfield {{ $bClass }}">
                    <span class="pf-lbl">رصيد الحساب</span>
                    <span class="pf-val">{{ number_format($balance, 3) }} د.ك</span>
                </div>
            @endif
        </div>
    </div>

    {{-- ── إضافة خدمة ── --}}
    <div class="nc-card nc-add-card">
        <div class="ac-head">إضافة خدمة — Add Service</div>
        <div class="ac-body">

            <div class="ac-field">
                <label class="lbl-red">المكتب</label>
                <select wire:model.live="filterClinic" class="nc-inp" style="min-width:150px;">
                    <option value="">— اختر المكتب —</option>
                    @foreach($clinics as $clinic)
                        <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="ac-field grow">
                <label>بحث بالاسم أو الكود</label>
                <input type="text" wire:model.live.debounce.300ms="serviceSearch"
                       placeholder="اكتب للبحث..." class="nc-inp">
            </div>

            <div class="ac-field grow">
                <label>الخدمة @if($filterClinic)({{ count($services) }})@endif</label>
                <select wire:model="selectedService" class="nc-inp nc-inp-service"
                        @if(!$filterClinic) disabled @endif
                        style="{{ !$filterClinic ? 'background:#f5f5f5;color:#aaa;' : '' }}">
                    @if(!$filterClinic)
                        <option value="">← اختر المكتب أولاً</option>
                    @else
                        <option value="">— اختر الخدمة —</option>
                        @foreach($services as $svc)
                            <option value="{{ $svc->id }}">{{ $svc->name }} — {{ number_format($svc->price, 3) }} د.ك</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="ac-field">
                <label>العدد</label>
                <input type="number" wire:model="qty" min="1" max="99"
                       class="nc-inp nc-inp-qty">
            </div>

            <button wire:click="addItem" type="button" class="nc-btn-add">
                + إضافة
            </button>
        </div>
    </div>

    {{-- ── جدول الخدمات ── --}}
    <div class="nc-card">
        <div class="nc-tbl-head">الخدمات المضافة — Services</div>
        <div class="nc-table-wrap">
            <table class="nc-table">
                <thead>
                    <tr>
                        <th style="width:40px; text-align:center;">#</th>
                        <th style="text-align:right;">الخدمة</th>
                        <th style="text-align:center; width:100px;">الكود</th>
                        <th style="text-align:center; width:130px;">المكتب</th>
                        <th style="text-align:center; width:100px;">السعر</th>
                        <th style="text-align:center; width:130px;">ملاحظات</th>
                        <th style="text-align:center; width:80px;">التأمين</th>
                        <th style="text-align:center; width:64px;">حذف</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $i => $item)
                    <tr>
                        <td class="td-num">{{ $i + 1 }}</td>
                        <td class="td-name">{{ $item['service_name'] }}</td>
                        <td class="td-code">{{ $item['code'] ?: '—' }}</td>
                        <td class="td-clinic">{{ $item['clinic_name'] }}</td>
                        <td class="td-price">{{ number_format($item['price'], 3) }}</td>
                        <td class="td-notes">
                            <input type="text" value="{{ $item['notes'] }}"
                                   wire:change="$set('items.{{ $i }}.notes', $event.target.value)"
                                   placeholder="ملاحظة...">
                        </td>
                        <td class="td-ins">{{ number_format($item['insurance_val'], 3) }}</td>
                        <td class="td-del">
                            <button wire:click="removeItem({{ $i }})" type="button" class="nc-del-btn">حذف</button>
                        </td>
                    </tr>
                    @empty
                    <tr class="nc-empty">
                        <td colspan="8" style="color:#ccc; padding:32px; text-align:center;">
                            <div style="font-size:1.8rem; margin-bottom:6px;">📋</div>
                            لم تُضَف أي خدمة بعد — اختر عيادة وخدمة ثم اضغط إضافة
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── بطاقات الإجماليات ── --}}
    <div class="nc-totals-row">
        <div class="nc-tot-card tc-sum">
            <div class="tc-lbl">الإجمالي / Sum</div>
            <div class="tc-val">{{ number_format($total, 3) }}</div>
            <div class="tc-sub">د.ك</div>
        </div>
        <div class="nc-tot-card tc-disc">
            <div class="tc-lbl">الخصم / Discount</div>
            <div class="tc-val">{{ number_format($discount, 3) }}</div>
            <div class="tc-sub">د.ك</div>
        </div>
        <div class="nc-tot-card tc-ins">
            <div class="tc-lbl">التأمين / Insurance</div>
            <div class="tc-val">{{ number_format($insuranceTotal, 3) }}</div>
            <div class="tc-sub">د.ك</div>
        </div>
        <div class="nc-tot-card tc-pat">
            <div class="tc-lbl">على العميل / Patient</div>
            <div class="tc-val">{{ number_format($patientAmount, 3) }}</div>
            <div class="tc-sub">د.ك</div>
        </div>
    </div>

    {{-- ── قسم الدفع ── --}}
    <div class="nc-payment-grid">

        {{-- يمين: الخصم + مجاني + ملاحظات --}}
        <div class="nc-pay-section">
            <div class="ps-head ps-head-left">إعدادات الفاتورة</div>
            <div class="ps-body">
                {{-- كود الخصم --}}
                <div class="nc-frow">
                    <label>كود الخصم</label>
                    @if($codeApplied && !empty($appliedCode))
                    <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                        <span style="background:#fef3c7; color:#92400e; border:1.5px solid #fcd34d; border-radius:8px; padding:0.3rem 0.9rem; font-family:monospace; font-weight:900; font-size:0.95rem; letter-spacing:1px;">{{ $appliedCode['code'] }}</span>
                        <span style="background:#dcfce7; color:#166534; border-radius:6px; padding:0.25rem 0.6rem; font-size:0.8rem; font-weight:800;">
                            -{{ $appliedCode['type'] === 'percent' ? ($appliedCode['value'] + 0).'%' : number_format($appliedCode['value'],0).' د.ك' }}
                        </span>
                        <button type="button" wire:click="removeCode"
                            style="background:none; border:none; color:var(--danger); cursor:pointer; font-size:0.8rem; font-weight:700; padding:0 0.3rem;">✕ إزالة</button>
                    </div>
                    @else
                    <div style="display:flex; gap:0.5rem; align-items:center;">
                        <input type="text" wire:model="discountCode" placeholder="أدخل الكود..."
                               style="flex:1; padding:0.5rem 0.75rem; border:1.5px solid var(--border); border-radius:8px; font-family:monospace; font-size:0.9rem; letter-spacing:1px; text-transform:uppercase; outline:none;"
                               wire:keydown.enter="applyCode">
                        <button type="button" wire:click="applyCode"
                            style="padding:0.5rem 1rem; background:var(--navy); color:#fff; border:none; border-radius:8px; font-size:0.82rem; font-weight:800; cursor:pointer; white-space:nowrap; font-family:'Tajawal',sans-serif;">تطبيق</button>
                    </div>
                    @if($codeMsg)
                    <div style="font-size:0.78rem; margin-top:4px; color:{{ $codeApplied ? '#166534' : 'var(--danger)' }}; font-weight:700;">
                        {{ $codeApplied ? '✅' : '⚠️' }} {{ $codeMsg }}
                    </div>
                    @endif
                    @endif
                </div>

                <div class="nc-frow">
                    <label>خصم إجمالي الفاتورة (د.ك)</label>
                    <input type="number" wire:model.live.debounce.400ms="totalDiscount" step="0.001" min="0"
                           @if($isFree || $codeApplied) readonly @endif
                           class="nc-full"
                           style="{{ ($isFree || $codeApplied) ? 'background:#f5f5f5;color:#aaa;' : '' }}">
                </div>

                <div class="nc-frow">
                    <label>كشف مجاني</label>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <select wire:model.live="isFree" class="nc-full" style="width:auto; min-width:110px;">
                            <option value="0">لا</option>
                            <option value="1">✓ مجاني</option>
                        </select>
                        @if($isFree)
                            <span class="nc-free-badge">مجاني — {{ number_format($total, 3) }} د.ك</span>
                        @endif
                    </div>
                </div>

                <div class="nc-frow">
                    <label>ملاحظات الكشف</label>
                    <textarea wire:model="notes" rows="4" placeholder="أي ملاحظات على الكشف..."
                              class="nc-full" style="resize:vertical;"></textarea>
                </div>
            </div>
        </div>

        {{-- يسار: بيانات الدفع --}}
        <div class="nc-pay-section">
            <div class="ps-head ps-head-right">بيانات الدفع</div>
            <div class="ps-body">

                @if($hasAccount)
                @php
                    $bcClass = $balance > $patientAmount ? 'bc-ok' : ($balance > 0 ? 'bc-low' : 'bc-neg');
                @endphp
                <div class="nc-balance-card {{ $bcClass }}">
                    <span class="bc-label">رصيد الحساب المتاح</span>
                    <span class="bc-amount">{{ number_format($balance, 3) }} <span style="font-size:.75rem; opacity:.7;">د.ك</span></span>
                </div>
                @endif

                <div class="nc-frow">
                    <label>المبلغ المدفوع (د.ك)</label>
                    <input type="number" wire:model="credit" step="0.001" min="0"
                           placeholder="{{ number_format($patientAmount, 3) }}"
                           class="nc-full">
                </div>

                <div class="nc-frow">
                    <label>طريقة الدفع</label>
                    <select wire:model.live="paymentMethod"
                            @if($isFree) disabled @endif
                            class="nc-full"
                            style="{{ $isFree ? 'background:#f5f5f5; opacity:.7;' : '' }}">
                        <option value="1">نقدي — Cash</option>
                        <option value="3">شبكة — K-Net</option>
                        <option value="4">تحويل بنكي — Bank Transfer</option>
                        <option value="6">فيزا — Visa</option>
                        <option value="11">MyFatoorah</option>
                        @if($balance > 0)
                        <option value="5">من الرصيد ({{ number_format($balance, 3) }} د.ك)</option>
                        @endif
                        <option value="8">آجل — Deferred</option>
                        <option value="7">مجاني — Free</option>
                        <option value="23">مجاني - من الرصيد — Free - from Balance</option>
                    </select>
                </div>

                @php
                    $afDanger = $hasAccount && !$isFree && (int)$paymentMethod === 5 && $patientAmount > 0 && $balance < $patientAmount;
                @endphp
                <div class="nc-amount-final {{ $afDanger ? 'af-danger' : '' }}">
                    <div class="af-lbl">{{ $afDanger ? '⛔ الرصيد غير كافٍ' : 'المبلغ على العميل' }}</div>
                    <div class="af-val">{{ number_format($patientAmount, 3) }} <span class="af-cur">د.ك</span></div>
                    @if($afDanger)
                    <div style="font-size:.76rem; opacity:.85; margin-top:4px;">
                        ينقص {{ number_format($patientAmount - $balance, 3) }} د.ك
                    </div>
                    @endif
                </div>

            </div>
        </div>

    </div>

    {{-- ── الشريط السفلي ── --}}
    @php
        $balanceInsufficient = $hasAccount && !$isFree && (int)$paymentMethod === 5 && $patientAmount > 0 && $balance < $patientAmount;
    @endphp

    <div class="nc-footer">
        <div class="btns">

            @if(session('balance_error'))
            <div class="nc-alert alert-danger">
                ⛔ {{ session('balance_error') }}
            </div>
            @endif

            @if($balanceInsufficient)
            <div class="nc-alert alert-danger">
                ⛔ لا يمكن الحجز — الرصيد غير كافٍ
                (متاح: {{ number_format($balance, 3) }} / مطلوب: {{ number_format($patientAmount, 3) }})
            </div>
            @endif

            <button wire:click="save" wire:loading.attr="disabled" type="button"
                    @if(empty($items) || $balanceInsufficient) disabled @endif
                    class="ncbtn ncbtn-save">
                <span wire:loading.remove wire:target="save">&#128203; تحويل العميل</span>
                <span wire:loading wire:target="save">&#8987; جارٍ الحفظ...</span>
            </button>

            <button wire:click="$refresh" type="button" class="ncbtn ncbtn-reset">
                ↺ استعادة
            </button>

            <a href="{{ route('checks.index') }}" wire:navigate class="ncbtn ncbtn-back">
                &#8592; العودة
            </a>
        </div>

        <span class="warn-txt">
            ⚠ لا تضغط تحويل العميل أكثر من مرة ولا تُحدِّث المتصفح أثناء الحفظ
        </span>
    </div>

</div>
</div>
