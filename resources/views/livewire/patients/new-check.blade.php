<div class="nc-wrap">

<style>
.nc-wrap {
    padding: 1rem 1.5rem;
    min-height: 80vh;
    font-family: 'Tajawal', sans-serif;
}
.nc-box {
    max-width: 1300px;
    margin: 0 auto;
    background: #fff;
    border: 2px solid #c0c0c0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0,0,0,0.08);
}

/* ── رأس ── */
.nc-head {
    background: var(--primary);
    padding: 10px 20px;
    text-align: center;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 900;
    letter-spacing: .5px;
}

/* ── معلومات العميل ── */
.nc-patient {
    background: #eef2fb;
    border-bottom: 1px solid #cdd5e8;
    padding: 8px 18px;
    display: flex;
    flex-wrap: wrap;
    gap: 6px 20px;
    align-items: center;
    font-size: .83rem;
}
.nc-patient .lbl { font-weight: 800; color: var(--primary); white-space: nowrap; }
.nc-patient .val { font-weight: 700; color: var(--navy); }
.nc-patient .val.link { color: #0055cc; text-decoration: none; }
.nc-patient .val.link:hover { text-decoration: underline; }
.nc-patient .sep { color: #aaa; }

/* ── صف الإضافة ── */
.nc-add-row {
    background: #f7f8fa;
    border-bottom: 2px solid #c0c0c0;
    padding: 10px 18px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}
.nc-add-row .field-group {
    display: flex;
    align-items: center;
    gap: 5px;
    flex-shrink: 0;
}
.nc-add-row .field-group.grow { flex: 1; min-width: 160px; }
.nc-add-row label {
    font-size: .78rem;
    font-weight: 800;
    color: #444;
    white-space: nowrap;
}
.nc-add-row label.red { color: var(--primary); }
.nc-input {
    border: 1px solid #bbb;
    border-radius: 4px;
    padding: 5px 9px;
    font-family: 'Tajawal', sans-serif;
    font-size: .83rem;
    background: #fff;
    outline: none;
    transition: border .2s;
}
.nc-input:focus { border-color: var(--primary); }
.nc-select { min-width: 140px; }
.nc-qty { width: 52px; text-align: center; font-weight: 800; border: 2px solid var(--primary) !important; color: var(--primary); }

.nc-btn-add {
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 6px 22px;
    font-family: 'Tajawal', sans-serif;
    font-size: .88rem;
    font-weight: 900;
    cursor: pointer;
    white-space: nowrap;
    transition: opacity .2s;
}
.nc-btn-add:hover { opacity: .88; }

/* ── جدول الخدمات ── */
.nc-table-wrap { overflow-x: auto; }
.nc-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 600px;
}
.nc-table thead tr { background: var(--navy); }
.nc-table thead th {
    color: #fff;
    padding: 7px 10px;
    font-size: .78rem;
    font-weight: 800;
    white-space: nowrap;
    border-left: 1px solid rgba(255,255,255,.12);
}
.nc-table thead th:last-child { border-left: none; }
.nc-table tbody tr { border-bottom: 1px solid #e8e8e8; }
.nc-table tbody tr:nth-child(even) { background: #fafafa; }
.nc-table tbody tr:hover { background: #f0f4ff; }
.nc-table td {
    padding: 6px 10px;
    font-size: .83rem;
    vertical-align: middle;
}
.nc-table .td-num  { text-align: center; font-weight: 900; color: var(--primary); width: 36px; }
.nc-table .td-name { font-weight: 700; color: var(--navy); }
.nc-table .td-c    { text-align: center; color: #555; }
.nc-table .td-price { text-align: center; font-weight: 800; color: #1b5e20; direction: ltr; }
.nc-table .td-ins   { text-align: center; color: #1565c0; font-weight: 700; }
.nc-table .td-notes input {
    width: 100%;
    border: 1px solid #ddd;
    border-radius: 3px;
    padding: 3px 6px;
    font-family: 'Tajawal', sans-serif;
    font-size: .78rem;
}
.nc-table .td-del { text-align: center; width: 60px; }
.nc-del-btn {
    background: #c0392b;
    color: #fff;
    border: none;
    border-radius: 3px;
    padding: 3px 10px;
    font-family: 'Tajawal', sans-serif;
    font-size: .76rem;
    font-weight: 800;
    cursor: pointer;
}
.nc-del-btn:hover { background: #a93226; }
.nc-empty td {
    padding: 22px;
    text-align: center;
    color: #b0b0b0;
    font-size: .86rem;
    font-style: italic;
}

/* ── شريط الإجماليات ── */
.nc-totals {
    background: #eef0f8;
    border-top: 2px solid var(--primary);
    padding: 8px 18px;
    display: flex;
    flex-wrap: wrap;
    gap: 6px 22px;
    align-items: center;
    font-size: .82rem;
}
.nc-totals .tot-item { display: flex; align-items: center; gap: 5px; }
.nc-totals .tot-lbl  { font-weight: 800; color: #333; white-space: nowrap; }
.nc-totals .tot-val  { font-weight: 900; font-size: .95rem; }
.nc-totals .tv-sum   { color: var(--navy); }
.nc-totals .tv-disc  { color: #e65100; }
.nc-totals .tv-ins   { color: #1565c0; }
.nc-totals .tv-pat   { color: #1b5e20; }

/* ── قسم الدفع ── */
.nc-payment {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    border-top: 1px solid #ddd;
}
.nc-pay-left  { padding: 16px 20px; border-left: 1px solid #ddd; }
.nc-pay-right { padding: 16px 20px; background: #fdf6ec; }

.nc-field-row { margin-bottom: 12px; }
.nc-field-row label {
    display: block;
    font-size: .8rem;
    font-weight: 800;
    margin-bottom: 4px;
}
.nc-field-row label.lbl-pri { color: var(--primary); }
.nc-field-row label.lbl-gold { color: #7a4400; }
.nc-field-row .nc-input-full {
    width: 100%;
    border: 1px solid #bbb;
    border-radius: 4px;
    padding: 7px 10px;
    font-family: 'Tajawal', sans-serif;
    font-size: .88rem;
    background: #fff;
    box-sizing: border-box;
}
.nc-field-row .nc-input-full:focus { border-color: var(--primary); outline: none; }
.nc-pay-right .nc-input-full { border-color: #c8941a; background: #fffdf7; }
.nc-pay-right .nc-input-full:focus { border-color: var(--primary); }

.nc-free-badge {
    display: inline-block;
    background: #e8f5e9;
    color: #2e7d32;
    padding: 2px 10px;
    border-radius: 4px;
    font-size: .8rem;
    font-weight: 800;
    margin-right: 6px;
}
.nc-amount-box {
    background: var(--primary);
    color: #fff;
    border-radius: 6px;
    padding: 10px 16px;
    text-align: center;
    margin-top: 14px;
}
.nc-amount-box .ab-lbl { font-size: .76rem; font-weight: 700; opacity: .85; }
.nc-amount-box .ab-val { font-size: 1.5rem; font-weight: 900; }
.nc-amount-box .ab-cur { font-size: .8rem; font-weight: 700; opacity: .75; }

/* ── شريط الأزرار ── */
.nc-footer {
    background: #2e6da4;
    padding: 10px 18px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    justify-content: space-between;
    border-top: 2px solid #c8941a;
}
.nc-footer .btns { display: flex; gap: 8px; flex-wrap: wrap; }
.nc-footer .warn { color: #ff8a65; font-size: .78rem; font-weight: 800; }

.nc-btn {
    border: none;
    border-radius: 5px;
    padding: 8px 20px;
    font-family: 'Tajawal', sans-serif;
    font-size: .86rem;
    font-weight: 800;
    cursor: pointer;
    white-space: nowrap;
}
.nc-btn-ref  { background: #27ae60; color: #fff; }
.nc-btn-ref:disabled { background: #888; cursor: not-allowed; }
.nc-btn-ref:not(:disabled):hover { background: #229954; }
.nc-btn-rst  { background: #607d8b; color: #fff; }
.nc-btn-back { background: rgba(255,255,255,.15); color: #fff; text-decoration: none; display: inline-flex; align-items: center; }
.nc-btn-back:hover { background: rgba(255,255,255,.25); }

/* ── Responsive ── */
@media (max-width: 768px) {
    .nc-wrap { padding: .75rem; }
    .nc-payment { grid-template-columns: 1fr; }
    .nc-pay-left { border-left: none; border-bottom: 1px solid #ddd; }
    .nc-totals { gap: 6px 14px; font-size: .78rem; }
    .nc-footer { flex-direction: column; align-items: flex-start; }
    .nc-add-row .field-group.grow { min-width: 100%; }
    .nc-add-row .field-group { flex: 1 1 100%; }
    .nc-select { min-width: 0 !important; width: 100%; }
}
@media (max-width: 480px) {
    .nc-patient { font-size: .76rem; gap: 4px 10px; }
    .nc-btn { padding: 7px 14px; font-size: .8rem; }
}
</style>

<div class="nc-box">

    {{-- رأس --}}
    <div class="nc-head">كشف جديد &nbsp;:&nbsp; New Check</div>

    {{-- بيانات العميل --}}
    <div class="nc-patient">
        <span class="lbl">Patient : المراجع</span>
        <a href="{{ route('patients.show', $patient->id) }}" class="val link">
            {{ $patient->full_name }}*{{ $patient->file_id }}
        </a>
        <span class="sep">|</span>
        <span class="lbl">Phone : الجوال</span>
        <span class="val">{{ $patient->phone ?: '—' }}</span>
        <span class="sep">|</span>
        <span class="lbl">Insurance : التأمين</span>
        <span class="val" style="color:var(--primary);">{{ $patient->insurance ?? 'على نفقته' }}</span>
    </div>

    {{-- صف الإضافة --}}
    <div class="nc-add-row">
        {{-- العيادة --}}
        <div class="field-group">
            <label class="red">Clinic : العيادة</label>
            <select wire:model.live="filterClinic" class="nc-input nc-select">
                <option value="">— اختر العيادة —</option>
                @foreach($clinics as $clinic)
                    <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- بحث --}}
        <div class="field-group grow">
            <label>Name or Code : الاسم او الكود</label>
            <input type="text" wire:model.live.debounce.300ms="serviceSearch"
                   placeholder="ابحث..." class="nc-input" style="flex:1; min-width:100px;">
        </div>

        {{-- قائمة الخدمات --}}
        <div class="field-group grow">
            <select wire:model="selectedService" class="nc-input" style="min-width:180px; flex:1;"
                    @if(!$filterClinic) disabled style="min-width:180px; flex:1; background:#f0f0f0; color:#999;" @endif>
                @if(!$filterClinic)
                    <option value="">← اختر العيادة أولاً</option>
                @else
                    <option value="">— اختر الخدمة ({{ count($services) }}) —</option>
                    @foreach($services as $svc)
                        <option value="{{ $svc->id }}">{{ $svc->name }} — {{ number_format($svc->price, 3) }} د.ك</option>
                    @endforeach
                @endif
            </select>
        </div>

        {{-- الكمية --}}
        <div class="field-group">
            <input type="number" wire:model="qty" min="1" max="99" class="nc-input nc-qty">
            <label style="font-size:.8rem; color:#555; font-weight:700;">No</label>
        </div>

        {{-- إضافة --}}
        <button wire:click="addItem" type="button" class="nc-btn-add">
            إضافة : Add
        </button>
    </div>

    {{-- جدول الخدمات --}}
    <div class="nc-table-wrap">
        <table class="nc-table">
            <thead>
                <tr>
                    <th style="width:36px; text-align:center;">#</th>
                    <th style="text-align:right;">Service : الخدمة</th>
                    <th style="text-align:center; width:100px;">Code : الكود</th>
                    <th style="text-align:center; width:130px;">Clinic : العيادة</th>
                    <th style="text-align:center; width:95px;">Price : السعر</th>
                    <th style="text-align:center; width:120px;">Notes : الملاحظات</th>
                    <th style="text-align:center; width:80px;">Insur : التأمين</th>
                    <th style="text-align:center; width:60px;">Del : حذف</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $i => $item)
                <tr>
                    <td class="td-num">{{ $i + 1 }}</td>
                    <td class="td-name">{{ $item['service_name'] }}</td>
                    <td class="td-c">{{ $item['code'] ?: '—' }}</td>
                    <td class="td-c">{{ $item['clinic_name'] }}</td>
                    <td class="td-price">{{ number_format($item['price'], 3) }}</td>
                    <td class="td-notes">
                        <input type="text" value="{{ $item['notes'] }}"
                               wire:change="$set('items.{{ $i }}.notes', $event.target.value)">
                    </td>
                    <td class="td-ins">{{ number_format($item['insurance_val'], 3) }}</td>
                    <td class="td-del">
                        <button wire:click="removeItem({{ $i }})" type="button" class="nc-del-btn">حذف</button>
                    </td>
                </tr>
                @empty
                <tr class="nc-empty">
                    <td colspan="8">لم تتم إضافة أي خدمة بعد — اختر خدمة من الأعلى وانقر "إضافة"</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- شريط الإجماليات --}}
    <div class="nc-totals">
        <div class="tot-item">
            <span class="tot-lbl">Sum : الإجمالي ::</span>
            <span class="tot-val tv-sum">{{ number_format($total, 3) }}</span>
        </div>
        <div class="tot-item">
            <span class="tot-lbl">Discount : الخصم ::</span>
            <span class="tot-val tv-disc">{{ number_format($discount, 3) }}</span>
        </div>
        <div class="tot-item">
            <span class="tot-lbl">Insurance : التأمين ::</span>
            <span class="tot-val tv-ins">{{ number_format($insuranceTotal, 3) }}</span>
        </div>
        <div class="tot-item">
            <span class="tot-lbl">Patient : المراجع ::</span>
            <span class="tot-val tv-pat">{{ number_format($patientAmount, 3) }}</span>
        </div>
    </div>

    {{-- قسم الدفع --}}
    <div class="nc-payment">

        {{-- يسار: خصم + مجاني + ملاحظات --}}
        <div class="nc-pay-left">
            <div class="nc-field-row">
                <label class="lbl-pri">
                    Discount : خصم إجمالي الفاتورة &nbsp;<span style="color:#c00;">*</span>
                </label>
                <input type="number" wire:model.blur="totalDiscount" step="0.001" min="0"
                       @if($isFree) readonly @endif
                       class="nc-input-full"
                       style="{{ $isFree ? 'background:#eee;' : '' }}">
            </div>

            <div class="nc-field-row">
                <label class="lbl-pri">
                    Free : مجاني &nbsp;<span style="color:#c00;">*</span>
                </label>
                <div style="display:flex; align-items:center; gap:8px;">
                    <select wire:model.live="isFree" class="nc-input-full" style="width:auto; min-width:100px;">
                        <option value="0">لا</option>
                        <option value="1">✓ مجاني</option>
                    </select>
                    @if($isFree)
                        <span class="nc-free-badge">مجاني — {{ number_format($total, 3) }} د.ك</span>
                    @endif
                </div>
            </div>

            <div class="nc-field-row">
                <label class="lbl-pri">ملاحظات الكشف</label>
                <textarea wire:model="notes" rows="3" placeholder="أي ملاحظات..."
                          class="nc-input-full" style="resize:vertical;"></textarea>
            </div>
        </div>

        {{-- يمين: بيانات الدفع --}}
        <div class="nc-pay-right">
            <div class="nc-field-row">
                <label class="lbl-gold">Credit : المبلغ المدفوع</label>
                <input type="number" wire:model="credit" step="0.001" min="0"
                       placeholder="{{ number_format($patientAmount, 3) }}"
                       class="nc-input-full">
            </div>

            <div class="nc-field-row">
                <label class="lbl-gold">P Method : طريقة الدفع</label>
                <select wire:model="paymentMethod"
                        @if($isFree) disabled @endif
                        class="nc-input-full"
                        style="{{ $isFree ? 'background:#eee; opacity:.7;' : '' }}">
                    <option value="1">Cash</option>
                    <option value="2">Cheque</option>
                    <option value="3">K-Net</option>
                    <option value="4">Bank Transfer</option>
                    <option value="6">Visa</option>
                    <option value="11">MyFatoorah</option>
                    <option value="7">Free</option>
                </select>
            </div>

            <div class="nc-amount-box">
                <div class="ab-lbl">المبلغ على العميل</div>
                <div class="ab-val">{{ number_format($patientAmount, 3) }} <span class="ab-cur">د.ك</span></div>
            </div>
        </div>

    </div>

    {{-- شريط الأزرار --}}
    <div class="nc-footer">
        <div class="btns">
            <button wire:click="save" wire:loading.attr="disabled" type="button"
                    @if(empty($items)) disabled @endif
                    class="nc-btn nc-btn-ref">
                <span wire:loading.remove wire:target="save">🧾 تحويل العميل : Referral</span>
                <span wire:loading wire:target="save">⏳ جارٍ الحفظ...</span>
            </button>

            <button wire:click="$refresh" type="button" class="nc-btn nc-btn-rst">
                استعادة : Reset
            </button>

            <a href="{{ route('checks.index') }}" wire:navigate class="nc-btn nc-btn-back">
                ⬅ العودة
            </a>
        </div>

        <span class="warn">
            أثناء التحميل : لا تضغط زر تحويل العميل ولا تقوم بتحديث المتصفح : No Click Duplication
        </span>
    </div>

</div>
</div>
