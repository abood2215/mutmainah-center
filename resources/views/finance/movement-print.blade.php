<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<title>{{ $mov->status == 1 ? 'Receipt' : 'Payment Voucher' }} #{{ $mov->id }}</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Tajawal:wght@400;700;800&display=swap" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; }

body {
    font-family:'Inter', sans-serif;
    background:#e8e8e8;
    color:#111;
    padding:28px 16px;
}

.page {
    max-width:580px;
    margin:0 auto;
    background:#fff;
    border-radius:8px;
    overflow:hidden;
    box-shadow:0 4px 20px rgba(0,0,0,0.15);
}

/* ── Header ── */
.header {
    background:#8b1c2b;
    padding:18px 24px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
}
.header-logo img {
    height:62px;
    width:auto;
    display:block;
    background:#fff;
    border-radius:6px;
    padding:4px 8px;
}
.header-center { text-align:center; flex:1; }
.header-center .name-ar {
    font-family:'Tajawal',sans-serif;
    font-size:16px;
    font-weight:900;
    color:#fff;
    line-height:1.3;
}
.header-center .name-en {
    font-size:9px;
    font-weight:700;
    color:rgba(255,255,255,0.6);
    letter-spacing:2px;
    margin-top:3px;
    text-transform:uppercase;
}
.header-voucher {
    text-align:center;
    background:rgba(0,0,0,0.25);
    border-radius:8px;
    padding:8px 14px;
    min-width:90px;
    flex-shrink:0;
    border:1.5px solid rgba(200,148,26,0.5);
}
.header-voucher .v-label { font-size:8px; font-weight:700; color:rgba(255,255,255,0.6); letter-spacing:2px; text-transform:uppercase; }
.header-voucher .v-type  { font-size:11px; font-weight:800; color:#f0c040; margin:2px 0; text-transform:uppercase; }
.header-voucher .v-num   { font-size:20px; font-weight:900; color:#fff; letter-spacing:1px; }

/* ── Type Strip ── */
.type-strip {
    background:{{ $mov->status == 1 ? '#dcfce7' : '#fee2e2' }};
    border-bottom:3px solid {{ $mov->status == 1 ? '#22c55e' : '#ef4444' }};
    padding:9px 24px;
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.type-strip .t-left  { font-size:14px; font-weight:900; color:{{ $mov->status == 1 ? '#15803d' : '#b91c1c' }}; display:flex; align-items:center; gap:6px; }
.type-strip .t-right { font-size:11px; font-weight:700; color:#6b7280; text-align:right; }
.type-dot { width:10px; height:10px; border-radius:50%; background:{{ $mov->status == 1 ? '#22c55e' : '#ef4444' }}; display:inline-block; }

/* ── Amount ── */
.amount-section {
    padding:20px 24px 14px;
    border-bottom:1px solid #f1f5f9;
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.amount-label { font-size:10px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px; }
.amount-value { font-size:34px; font-weight:900; color:{{ $mov->status == 1 ? '#15803d' : '#b91c1c' }}; line-height:1; }
.amount-currency { font-size:14px; font-weight:700; color:#9ca3af; margin-right:4px; }
.amount-method {
    text-align:center;
    background:{{ $mov->status == 1 ? '#f0fdf4' : '#fff5f5' }};
    border:1.5px solid {{ $mov->status == 1 ? '#86efac' : '#fca5a5' }};
    border-radius:8px;
    padding:10px 16px;
}
.amount-method .m-label { font-size:9px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; }
.amount-method .m-value { font-size:14px; font-weight:800; color:#374151; margin-top:3px; }

/* ── Info ── */
.info-section { padding:14px 24px; }
.info-row {
    display:flex;
    align-items:flex-start;
    padding:8px 0;
    border-bottom:1px solid #f8fafc;
    gap:12px;
}
.info-row:last-child { border-bottom:none; }
.info-key {
    font-size:10px;
    font-weight:700;
    color:#9ca3af;
    text-transform:uppercase;
    letter-spacing:0.8px;
    min-width:100px;
    flex-shrink:0;
    padding-top:2px;
}
.info-val {
    font-size:13px;
    font-weight:700;
    color:#111827;
    flex:1;
}
.info-sub { font-size:11px; color:#9ca3af; margin-top:2px; }
.ref-badge {
    display:inline-block;
    background:#dbeafe;
    color:#1d4ed8;
    font-size:12px;
    font-weight:800;
    padding:3px 10px;
    border-radius:5px;
    letter-spacing:0.5px;
}

/* ── Signature ── */
.sig-section {
    margin:4px 24px 20px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
}
.sig-box {
    border:1.5px dashed #d1d5db;
    border-radius:8px;
    padding:14px 12px 8px;
    text-align:center;
    min-height:60px;
}
.sig-key   { font-size:9px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px; }
.sig-name  { font-size:11px; font-weight:700; color:#374151; margin-top:auto; }
.sig-line  { border-top:1px solid #e5e7eb; padding-top:6px; margin-top:10px; }

/* ── Footer ── */
.footer {
    background:#1a1a2e;
    padding:10px 24px;
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.footer-left  { font-size:9px; font-weight:700; color:rgba(255,255,255,0.45); letter-spacing:1px; }
.footer-right { font-size:9px; font-weight:700; color:#c8941a; letter-spacing:0.5px; }

/* ── Print Button ── */
.print-btn {
    display:block;
    max-width:580px;
    margin:0 auto 16px;
    padding:11px;
    background:#8b1c2b;
    color:#fff;
    border:none;
    border-radius:8px;
    font-family:'Inter',sans-serif;
    font-size:14px;
    font-weight:700;
    cursor:pointer;
    text-align:center;
    letter-spacing:0.5px;
}
.print-btn:hover { background:#6d1622; }

@media print {
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
    body { background:#fff !important; padding:0; margin:0; }
    .print-btn { display:none !important; }
    .page { box-shadow:none !important; border-radius:0; max-width:100%; margin:0; }
}
</style>
</head>
<body>

<button class="print-btn" onclick="window.print()">🖨️ &nbsp; Print Receipt</button>

<div class="page">

    {{-- Header --}}
    <div class="header">
        <div class="header-logo">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo"
                onerror="this.style.display='none'">
        </div>
        <div class="header-center">
            <div class="name-ar">مركز مطمئنة الاستشاري</div>
            <div class="name-en">Mutmainah Advisory Center</div>
        </div>
        <div class="header-voucher">
            <div class="v-label">Voucher No.</div>
            <div class="v-type">{{ $mov->status == 1 ? 'Receipt' : 'Payment' }}</div>
            <div class="v-num">#{{ $mov->id }}</div>
        </div>
    </div>

    {{-- Type Strip --}}
    <div class="type-strip">
        <div class="t-left">
            <span class="type-dot"></span>
            {{ $mov->status == 1 ? 'CASH RECEIPT VOUCHER' : 'PAYMENT VOUCHER' }}
        </div>
        <div class="t-right">
            <div>{{ $mov->pdate }}</div>
            @if($mov->ptime)<div>{{ $mov->ptime }}</div>@endif
        </div>
    </div>

    {{-- Amount --}}
    <div class="amount-section">
        <div>
            <div class="amount-label">Amount Received</div>
            <div class="amount-value">
                <span class="amount-currency">KD</span>{{ number_format($mov->mov_amount, 3) }}
            </div>
        </div>
        <div class="amount-method">
            <div class="m-label">Payment Method</div>
            <div class="m-value">{{ $payMethods[$mov->payment_method] ?? 'Cash' }}</div>
        </div>
    </div>

    {{-- Info Rows --}}
    <div class="info-section">

        <div class="info-row">
            <div class="info-key">Client</div>
            <div class="info-val">
                {{ $mov->patient_name ?? $mov->acck_name ?? '—' }}
                @if($mov->patient_file)
                <div class="info-sub">File No. #{{ $mov->patient_file }}</div>
                @endif
            </div>
        </div>

        @if($mov->patient_phone)
        <div class="info-row">
            <div class="info-key">Phone</div>
            <div class="info-val">{{ $mov->patient_phone }}</div>
        </div>
        @endif

        @if($descClean)
        <div class="info-row">
            <div class="info-key">Description</div>
            <div class="info-val">{{ $descClean }}</div>
        </div>
        @endif

        @if($refNo)
        <div class="info-row">
            <div class="info-key">Reference No.</div>
            <div class="info-val"><span class="ref-badge">{{ $refNo }}</span></div>
        </div>
        @endif

        <div class="info-row">
            <div class="info-key">Recorded By</div>
            <div class="info-val">{{ trim($mov->emp_name) ?: '—' }}</div>
        </div>

    </div>

    {{-- Signatures --}}
    <div class="sig-section">
        <div class="sig-box">
            <div class="sig-key">Client Signature</div>
            <div class="sig-line"></div>
            <div class="sig-name">____________________</div>
        </div>
        <div class="sig-box">
            <div class="sig-key">Received By</div>
            <div class="sig-line"></div>
            <div class="sig-name">{{ trim($mov->emp_name) ?: '____________________' }}</div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-left">Thank you for your trust</div>
        <div class="footer-right">Mutmainah Advisory Center &nbsp;©&nbsp; {{ date('Y') }}</div>
    </div>

</div>

<script>
window.addEventListener('load', function() {
    setTimeout(() => window.print(), 700);
});
</script>
</body>
</html>
