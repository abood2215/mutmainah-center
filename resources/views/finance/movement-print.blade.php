<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<title>{{ $mov->status == 1 ? 'Receipt' : 'Payment Voucher' }} #{{ $mov->id }}</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Tajawal:wght@700;800;900&display=swap" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; }

body {
    font-family:'Inter',sans-serif;
    background:#d1d5db;
    color:#1f2937;
    padding:32px 16px;
}

/* ─────────── PAGE ─────────── */
.page {
    max-width:560px;
    margin:0 auto;
    background:#fff;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 8px 32px rgba(0,0,0,0.18);
}

/* ─────────── HEADER ─────────── */
.header {
    background:linear-gradient(135deg, #8b1c2b 0%, #6b1220 100%);
    padding:20px 24px;
    display:flex;
    align-items:center;
    gap:16px;
    position:relative;
    overflow:hidden;
}
.header::after {
    content:'';
    position:absolute;
    top:-30px; right:-30px;
    width:120px; height:120px;
    border-radius:50%;
    background:rgba(200,148,26,0.12);
}
.logo-wrap {
    background:#fff;
    border-radius:8px;
    padding:5px 8px;
    flex-shrink:0;
    box-shadow:0 2px 8px rgba(0,0,0,0.2);
}
.logo-wrap img { height:52px; width:auto; display:block; }
.header-text { flex:1; text-align:center; }
.header-text .ar { font-family:'Tajawal',sans-serif; font-size:17px; font-weight:900; color:#fff; }
.header-text .en { font-size:9px; font-weight:600; color:rgba(255,255,255,0.55); letter-spacing:2.5px; margin-top:3px; }
.voucher-badge {
    flex-shrink:0;
    background:rgba(0,0,0,0.3);
    border:1.5px solid #c8941a;
    border-radius:8px;
    padding:8px 12px;
    text-align:center;
    min-width:88px;
    position:relative; z-index:1;
}
.voucher-badge .vb-lbl  { font-size:7px; font-weight:700; color:rgba(255,255,255,0.5); letter-spacing:2px; text-transform:uppercase; }
.voucher-badge .vb-type { font-size:10px; font-weight:800; color:#f0c040; letter-spacing:1px; margin:3px 0 2px; text-transform:uppercase; }
.voucher-badge .vb-num  { font-size:22px; font-weight:900; color:#fff; letter-spacing:0.5px; }

/* ─────────── TYPE BAR ─────────── */
.type-bar {
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:10px 24px;
    background:{{ $mov->status == 1 ? '#f0fdf4' : '#fff5f5' }};
    border-bottom:2px solid {{ $mov->status == 1 ? '#86efac' : '#fca5a5' }};
}
.type-bar .tb-left {
    display:flex; align-items:center; gap:8px;
    font-size:12px; font-weight:800;
    color:{{ $mov->status == 1 ? '#166534' : '#991b1b' }};
    text-transform:uppercase; letter-spacing:1px;
}
.tb-dot {
    width:9px; height:9px; border-radius:50%;
    background:{{ $mov->status == 1 ? '#22c55e' : '#ef4444' }};
    flex-shrink:0;
    box-shadow:0 0 0 3px {{ $mov->status == 1 ? 'rgba(34,197,94,0.2)' : 'rgba(239,68,68,0.2)' }};
}
.type-bar .tb-right { font-size:11px; font-weight:600; color:#6b7280; text-align:right; line-height:1.5; }

/* ─────────── AMOUNT ─────────── */
.amount-section {
    padding:22px 24px 18px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    border-bottom:1px solid #f3f4f6;
    background:#fafafa;
}
.amount-left .al-label { font-size:9px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:6px; }
.amount-left .al-value {
    font-size:38px; font-weight:900; line-height:1;
    color:{{ $mov->status == 1 ? '#15803d' : '#b91c1c' }};
}
.amount-left .al-cur { font-size:16px; font-weight:700; color:#6b7280; margin-right:4px; }
.method-card {
    text-align:center;
    background:#fff;
    border:1.5px solid {{ $mov->status == 1 ? '#86efac' : '#fca5a5' }};
    border-radius:10px;
    padding:12px 18px;
    box-shadow:0 1px 4px rgba(0,0,0,0.06);
    min-width:120px;
}
.method-card .mc-lbl { font-size:8px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:5px; }
.method-card .mc-val { font-size:15px; font-weight:800; color:#1f2937; }

/* ─────────── INFO TABLE ─────────── */
.info-table { padding:0 24px 4px; }
.info-row {
    display:flex;
    align-items:flex-start;
    gap:16px;
    padding:10px 0;
    border-bottom:1px solid #f3f4f6;
}
.info-row:last-child { border-bottom:none; }
.ir-key {
    font-size:9px; font-weight:700;
    color:#9ca3af; text-transform:uppercase;
    letter-spacing:1px; min-width:110px;
    flex-shrink:0; padding-top:3px;
}
.ir-val { font-size:13px; font-weight:600; color:#111827; flex:1; line-height:1.5; }
.ir-sub { font-size:10px; color:#9ca3af; margin-top:2px; }
.ref-pill {
    display:inline-block;
    background:#eff6ff; color:#1d4ed8;
    font-size:12px; font-weight:700;
    padding:3px 12px; border-radius:20px;
    letter-spacing:0.5px;
    border:1px solid #bfdbfe;
}

/* ─────────── DIVIDER ─────────── */
.divider {
    margin:16px 24px;
    display:flex; align-items:center; gap:12px;
}
.divider-line { flex:1; height:1px; background:#e5e7eb; }
.divider-text { font-size:9px; font-weight:700; color:#d1d5db; text-transform:uppercase; letter-spacing:2px; white-space:nowrap; }

/* ─────────── SIGNATURES ─────────── */
.sig-grid {
    margin:0 24px 20px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px;
}
.sig-box {
    background:#f9fafb;
    border:1px solid #e5e7eb;
    border-radius:8px;
    padding:14px 12px 10px;
    text-align:center;
}
.sig-lbl  { font-size:8px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:20px; }
.sig-name { font-size:11px; font-weight:600; color:#374151; border-top:1.5px solid #d1d5db; padding-top:8px; margin-top:4px; }

/* ─────────── FOOTER ─────────── */
.footer {
    background:#1a1a2e;
    padding:11px 24px;
    display:flex; align-items:center; justify-content:space-between;
}
.footer-l { font-size:9px; font-weight:600; color:rgba(255,255,255,0.4); letter-spacing:1px; font-style:italic; }
.footer-r { font-size:9px; font-weight:700; color:#c8941a; letter-spacing:0.5px; }

/* ─────────── PRINT BUTTON ─────────── */
.print-btn {
    display:block; max-width:560px; margin:0 auto 18px;
    padding:12px; background:#1a1a2e;
    color:#fff; border:none; border-radius:8px;
    font-family:'Inter',sans-serif; font-size:14px; font-weight:700;
    cursor:pointer; letter-spacing:0.5px; text-align:center;
    transition:background 0.2s;
}
.print-btn:hover { background:#8b1c2b; }

/* ─────────── PRINT ─────────── */
@media print {
    * { -webkit-print-color-adjust:exact !important; print-color-adjust:exact !important; }
    body { background:#fff !important; padding:0; }
    .print-btn { display:none !important; }
    .page { box-shadow:none; border-radius:0; max-width:100%; margin:0; }
}
</style>
</head>
<body>

<button class="print-btn" onclick="window.print()">🖨️ &nbsp; Print Receipt</button>

<div class="page">

    {{-- Header --}}
    <div class="header">
        <div class="logo-wrap">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" onerror="this.style.display='none'">
        </div>
        <div class="header-text">
            <div class="ar">مركز مطمئنة الاستشاري</div>
            <div class="en">Mutmainah Advisory Center</div>
        </div>
        <div class="voucher-badge">
            <div class="vb-lbl">Voucher No.</div>
            <div class="vb-type">{{ $mov->status == 1 ? 'Receipt' : 'Payment' }}</div>
            <div class="vb-num">#{{ $mov->id }}</div>
        </div>
    </div>

    {{-- Type Bar --}}
    <div class="type-bar">
        <div class="tb-left">
            <span class="tb-dot"></span>
            {{ $mov->status == 1 ? 'Cash Receipt Voucher' : 'Payment Voucher' }}
        </div>
        <div class="tb-right">
            {{ $mov->pdate }}@if($mov->ptime) &nbsp;|&nbsp; {{ $mov->ptime }}@endif
        </div>
    </div>

    {{-- Amount --}}
    <div class="amount-section">
        <div class="amount-left">
            <div class="al-label">Amount {{ $mov->status == 1 ? 'Received' : 'Paid' }}</div>
            <div class="al-value">
                <span class="al-cur">KD</span>{{ number_format($mov->mov_amount, 3) }}
            </div>
        </div>
        <div class="method-card">
            <div class="mc-lbl">Payment Method</div>
            <div class="mc-val">{{ $payMethods[$mov->payment_method] ?? 'Cash' }}</div>
        </div>
    </div>

    {{-- Info --}}
    <div class="info-table" style="margin-top:6px;">

        <div class="info-row">
            <div class="ir-key">Client</div>
            <div class="ir-val">
                {{ $mov->patient_name ?? $mov->acck_name ?? '—' }}
                @if($mov->patient_file)<div class="ir-sub">File No. &nbsp;#{{ $mov->patient_file }}</div>@endif
            </div>
        </div>

        @if($mov->patient_phone)
        <div class="info-row">
            <div class="ir-key">Phone</div>
            <div class="ir-val">{{ $mov->patient_phone }}</div>
        </div>
        @endif

        @if($descClean)
        <div class="info-row">
            <div class="ir-key">Description</div>
            <div class="ir-val">{{ $descClean }}</div>
        </div>
        @endif

        @if($refNo)
        <div class="info-row">
            <div class="ir-key">Reference No.</div>
            <div class="ir-val"><span class="ref-pill">{{ $refNo }}</span></div>
        </div>
        @endif

        <div class="info-row">
            <div class="ir-key">Recorded By</div>
            <div class="ir-val">{{ trim($mov->emp_name) ?: '—' }}</div>
        </div>

    </div>

    {{-- Divider --}}
    <div class="divider">
        <div class="divider-line"></div>
        <div class="divider-text">Authorized Signatures</div>
        <div class="divider-line"></div>
    </div>

    {{-- Signatures --}}
    <div class="sig-grid">
        <div class="sig-box">
            <div class="sig-lbl">Client Signature</div>
            <div class="sig-name">____________________</div>
        </div>
        <div class="sig-box">
            <div class="sig-lbl">Received By</div>
            <div class="sig-name">{{ trim($mov->emp_name) ?: '____________________' }}</div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-l">Thank you for your trust</div>
        <div class="footer-r">Mutmainah Advisory Center &nbsp;©&nbsp; {{ date('Y') }}</div>
    </div>

</div>

<script>
window.addEventListener('load', function() { setTimeout(() => window.print(), 700); });
</script>
</body>
</html>
