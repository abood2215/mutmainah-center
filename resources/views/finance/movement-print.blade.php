<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>{{ $mov->status == 1 ? 'سند قبض' : 'سند صرف' }} #{{ $mov->id }}</title>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700;800;900&display=swap" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body {
    font-family:'Tajawal',sans-serif;
    background:#f0f0f0;
    color:#111;
    direction:rtl;
    padding:30px 20px;
}
.page {
    max-width:600px;
    margin:0 auto;
    background:#fff;
    border:2px solid #8b1c2b;
    border-radius:4px;
}

/* رأس */
.header {
    display:flex;
    align-items:center;
    padding:14px 20px;
    border-bottom:3px solid #8b1c2b;
    gap:14px;
    background:#fff;
}
.logo-box img { height:55px; display:block; }
.header-mid { flex:1; text-align:center; }
.header-mid .ar  { font-size:18px; font-weight:900; color:#8b1c2b; }
.header-mid .en  { font-size:9px; font-weight:700; color:#999; letter-spacing:2px; margin-top:2px; }
.voucher-box {
    border:2px solid {{ $mov->status == 1 ? '#16a34a' : '#dc2626' }};
    border-radius:6px;
    padding:6px 14px;
    text-align:center;
    flex-shrink:0;
    min-width:110px;
}
.voucher-box .lbl { font-size:9px; font-weight:700; color:#888; }
.voucher-box .type { font-size:13px; font-weight:900; color:{{ $mov->status == 1 ? '#16a34a' : '#dc2626' }}; }
.voucher-box .num  { font-size:22px; font-weight:900; color:#8b1c2b; direction:ltr; }

/* الشريط */
.strip {
    background:{{ $mov->status == 1 ? '#f0fdf4' : '#fff5f5' }};
    border-bottom:1px solid {{ $mov->status == 1 ? '#bbf7d0' : '#fecaca' }};
    padding:8px 20px;
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.strip .type-label {
    font-size:15px;
    font-weight:900;
    color:{{ $mov->status == 1 ? '#15803d' : '#dc2626' }};
}
.strip .date-val {
    font-size:12px;
    font-weight:700;
    color:#555;
    direction:ltr;
}

/* البيانات */
.info-section { padding:16px 20px; }
.info-row {
    display:flex;
    align-items:flex-start;
    padding:7px 0;
    border-bottom:1px dashed #e5e7eb;
    gap:10px;
}
.info-row:last-child { border-bottom:none; }
.info-label {
    font-size:11px;
    font-weight:800;
    color:#9ca3af;
    min-width:110px;
    flex-shrink:0;
    padding-top:2px;
    text-transform:uppercase;
    letter-spacing:0.5px;
}
.info-value {
    font-size:14px;
    font-weight:800;
    color:#1a1a2e;
    flex:1;
}

/* المبلغ */
.amount-box {
    margin:0 20px 16px;
    background:{{ $mov->status == 1 ? '#f0fdf4' : '#fff5f5' }};
    border:2px solid {{ $mov->status == 1 ? '#22c55e' : '#ef4444' }};
    border-radius:8px;
    padding:12px 20px;
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.amount-label { font-size:12px; font-weight:800; color:#6b7280; }
.amount-value {
    font-size:26px;
    font-weight:900;
    color:{{ $mov->status == 1 ? '#15803d' : '#b91c1c' }};
    direction:ltr;
}
.amount-cur { font-size:14px; color:#6b7280; margin-right:4px; }

/* ref */
.ref-badge {
    display:inline-block;
    background:#dbeafe;
    color:#1d4ed8;
    font-size:11px;
    font-weight:800;
    padding:2px 8px;
    border-radius:4px;
    direction:ltr;
    font-family:monospace;
    margin-top:4px;
}

/* توقيع */
.sig-section {
    margin:0 20px 20px;
    display:flex;
    justify-content:space-between;
    gap:16px;
}
.sig-box {
    flex:1;
    border-top:1.5px solid #e5e7eb;
    padding-top:8px;
    text-align:center;
}
.sig-box .sig-label { font-size:10px; font-weight:800; color:#9ca3af; letter-spacing:1px; }
.sig-box .sig-name  { font-size:12px; font-weight:800; color:#374151; margin-top:4px; }

/* ذيل */
.footer {
    background:#1a1a2e;
    padding:8px 20px;
    text-align:center;
    color:rgba(255,255,255,0.6);
    font-size:10px;
    font-weight:700;
    letter-spacing:1px;
}
.footer span { color:#c8941a; }

/* طباعة */
.print-btn {
    display:block;
    margin:16px auto;
    max-width:600px;
    padding:10px;
    background:#8b1c2b;
    color:#fff;
    border:none;
    border-radius:6px;
    font-family:'Tajawal',sans-serif;
    font-size:15px;
    font-weight:800;
    cursor:pointer;
    text-align:center;
}
@media print {
    body { background:#fff; padding:0; }
    .print-btn { display:none; }
    .page { border:none; max-width:100%; }
}
</style>
</head>
<body>

<button class="print-btn" onclick="window.print()">🖨️ طباعة الوصل</button>

<div class="page">

    {{-- رأس --}}
    <div class="header">
        <div class="logo-box">
            <img src="{{ asset('images/logo.png') }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
            <div style="display:none; font-size:22px; font-weight:900; color:#8b1c2b; padding:4px 8px;">م</div>
        </div>
        <div class="header-mid">
            <div class="ar">مركز مطمئنة الاستشاري</div>
            <div class="en">MUTMAINAH ADVISORY CENTER</div>
        </div>
        <div class="voucher-box">
            <div class="lbl">رقم السند</div>
            <div class="type">{{ $mov->status == 1 ? 'قبض' : 'صرف' }}</div>
            <div class="num">#{{ $mov->id }}</div>
        </div>
    </div>

    {{-- شريط النوع والتاريخ --}}
    <div class="strip">
        <div class="type-label">
            {{ $mov->status == 1 ? '📥 سند قبض' : '📤 سند صرف' }}
        </div>
        <div class="date-val">
            {{ $mov->pdate }} &nbsp;|&nbsp; {{ $mov->ptime ?? '' }}
        </div>
    </div>

    {{-- المبلغ --}}
    <div class="amount-box" style="margin-top:16px;">
        <div>
            <div class="amount-label">المبلغ / AMOUNT</div>
            <div style="font-size:11px; color:#9ca3af; margin-top:2px;">
                {{ $payMethods[$mov->payment_method] ?? 'نقدا' }}
            </div>
        </div>
        <div class="amount-value">
            <span class="amount-cur">د.ك</span>{{ number_format($mov->mov_amount, 3) }}
        </div>
    </div>

    {{-- البيانات --}}
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">العميل / Client</div>
            <div class="info-value">
                {{ $mov->patient_name ?? $mov->acck_name ?? '—' }}
                @if($mov->patient_file)
                <span style="font-size:11px; color:#9ca3af; margin-right:6px;">#{{ $mov->patient_file }}</span>
                @endif
            </div>
        </div>
        @if($mov->patient_phone)
        <div class="info-row">
            <div class="info-label">الجوال / Phone</div>
            <div class="info-value" style="direction:ltr;">{{ $mov->patient_phone }}</div>
        </div>
        @endif
        <div class="info-row">
            <div class="info-label">طريقة الدفع</div>
            <div class="info-value">{{ $payMethods[$mov->payment_method] ?? 'نقدا' }}</div>
        </div>
        @if($descClean)
        <div class="info-row">
            <div class="info-label">البيان / Notes</div>
            <div class="info-value">{{ $descClean }}</div>
        </div>
        @endif
        @if($refNo)
        <div class="info-row">
            <div class="info-label">رقم المرجع</div>
            <div class="info-value">
                <span class="ref-badge">Ref: {{ $refNo }}</span>
            </div>
        </div>
        @endif
        <div class="info-row">
            <div class="info-label">سجّله</div>
            <div class="info-value">{{ trim($mov->emp_name) ?: '—' }}</div>
        </div>
    </div>

    {{-- التوقيع --}}
    <div class="sig-section">
        <div class="sig-box">
            <div class="sig-label">توقيع العميل</div>
            <div class="sig-name">Client Signature</div>
        </div>
        <div class="sig-box">
            <div class="sig-label">توقيع المُستلِم</div>
            <div class="sig-name">{{ trim($mov->emp_name) ?: 'Cashier' }}</div>
        </div>
    </div>

    {{-- ذيل --}}
    <div class="footer">
        مركز مطمئنة الاستشاري &nbsp;|&nbsp; <span>Mutmainah Advisory Center</span>
    </div>

</div>

<script>
// طباعة تلقائية بعد تحميل الخط
window.addEventListener('load', function() {
    setTimeout(() => window.print(), 600);
});
</script>
</body>
</html>
