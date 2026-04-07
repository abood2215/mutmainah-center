<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<title>{{ $mov->status == 1 ? 'Receipt' : 'Payment Voucher' }} #{{ $mov->id }}</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Tajawal:wght@400;700;800;900&display=swap" rel="stylesheet">
<style>
@php
$branchId   = $mov->branch_id ?? 1;
$isFloor6   = $branchId == 2;
$floorAr    = $isFloor6 ? 'الدور السادس' : 'الدور الثالث';
$floorEn    = $isFloor6 ? 'Tower — 6th Floor' : 'Tower — 3rd Floor';
$deptAr     = $isFloor6 ? 'الاستشارات التربوية والتدريب' : 'الاستشارات اللغوية';
$deptEn     = $isFloor6 ? 'Educational Consulting & Training' : 'Language Consulting';
$isReceipt  = $mov->status == 1;
$accentGreen= '#16a34a';
$accentRed  = '#dc2626';
$accent     = $isReceipt ? $accentGreen : $accentRed;
$accentBg   = $isReceipt ? '#f0fdf4' : '#fff5f5';
$accentBdr  = $isReceipt ? '#bbf7d0' : '#fecaca';
@endphp

* { margin:0; padding:0; box-sizing:border-box; }

body {
    font-family:'Inter', sans-serif;
    background:#cbd5e1;
    color:#0f172a;
    padding:30px 16px 40px;
}

/* ── CARD ── */
.card {
    max-width:595px;
    margin:0 auto;
    background:#fff;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 12px 48px rgba(0,0,0,0.18);
    border:1px solid #e2e8f0;
}

/* ── TOP BAND ── */
.top-band {
    height:6px;
    background:linear-gradient(90deg, #8b1c2b 0%, #c8941a 50%, #8b1c2b 100%);
}

/* ── HEADER ── */
.header {
    background:linear-gradient(160deg, #8b1c2b 0%, #5a1019 100%);
    padding:22px 28px;
    display:flex;
    align-items:center;
    gap:18px;
    position:relative;
    overflow:hidden;
}
.header::before {
    content:'';
    position:absolute; top:-40px; left:-40px;
    width:160px; height:160px; border-radius:50%;
    background:rgba(200,148,26,0.08);
}
.header::after {
    content:'';
    position:absolute; bottom:-30px; right:80px;
    width:100px; height:100px; border-radius:50%;
    background:rgba(255,255,255,0.04);
}
.logo-wrap {
    background:#fff;
    border-radius:10px;
    padding:6px 10px;
    flex-shrink:0;
    box-shadow:0 4px 14px rgba(0,0,0,0.25);
    position:relative; z-index:1;
}
.logo-wrap img { height:56px; width:auto; display:block; }
.header-text { flex:1; position:relative; z-index:1; }
.header-text .name-ar {
    font-family:'Tajawal',sans-serif;
    font-size:18px; font-weight:900;
    color:#fff; line-height:1.2;
}
.header-text .name-en {
    font-size:8.5px; font-weight:600;
    color:rgba(255,255,255,0.5);
    letter-spacing:3px; margin-top:4px;
    text-transform:uppercase;
}
.header-text .dept {
    font-size:9.5px; font-weight:600;
    color:#f0c040;
    margin-top:6px; letter-spacing:0.5px;
}
.vno-box {
    flex-shrink:0;
    background:rgba(0,0,0,0.3);
    border:2px solid rgba(200,148,26,0.6);
    border-radius:10px;
    padding:10px 14px;
    text-align:center;
    min-width:100px;
    position:relative; z-index:1;
    backdrop-filter:blur(4px);
}
.vno-box .vb-lbl  { font-size:7px; font-weight:700; color:rgba(255,255,255,0.45); letter-spacing:2.5px; text-transform:uppercase; }
.vno-box .vb-type { font-size:11px; font-weight:800; color:#f0c040; letter-spacing:1px; margin:4px 0; text-transform:uppercase; }
.vno-box .vb-num  { font-size:24px; font-weight:900; color:#fff; letter-spacing:0.5px; }

/* ── TYPE ROW ── */
.type-row {
    display:flex; align-items:center; justify-content:space-between;
    padding:10px 28px;
    background:{{ $accentBg }};
    border-bottom:2px solid {{ $accentBdr }};
}
.type-row .tr-left {
    display:flex; align-items:center; gap:8px;
    font-size:11px; font-weight:800;
    color:{{ $accent }};
    text-transform:uppercase; letter-spacing:1.5px;
}
.tr-dot {
    width:9px; height:9px; border-radius:50%;
    background:{{ $accent }};
    box-shadow:0 0 0 3px {{ $isReceipt ? 'rgba(22,163,74,0.2)' : 'rgba(220,38,38,0.2)' }};
}
.type-row .tr-right { font-size:11px; font-weight:600; color:#475569; text-align:right; line-height:1.6; }

/* ── AMOUNT ── */
.amount-row {
    display:flex; align-items:center; justify-content:space-between;
    padding:22px 28px 18px;
    border-bottom:1px solid #f1f5f9;
}
.amt-left .al-lbl { font-size:8.5px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:2px; margin-bottom:6px; }
.amt-left .al-val { font-size:42px; font-weight:900; line-height:1; color:{{ $accent }}; }
.amt-left .al-cur { font-size:16px; font-weight:600; color:#94a3b8; margin-right:5px; }
.method-pill {
    background:#f8fafc;
    border:1.5px solid {{ $accentBdr }};
    border-radius:12px; padding:12px 20px;
    text-align:center; min-width:130px;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
}
.method-pill .mp-lbl { font-size:7.5px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:2px; margin-bottom:5px; }
.method-pill .mp-val { font-size:15px; font-weight:800; color:#0f172a; }

/* ── DIVIDER ── */
.hr { height:1px; background:linear-gradient(90deg, transparent, #e2e8f0, transparent); margin:0 28px; }

/* ── INFO TABLE ── */
.info-section { padding:6px 28px 10px; }
.info-row {
    display:flex; align-items:flex-start; gap:14px;
    padding:10px 0; border-bottom:1px solid #f8fafc;
}
.info-row:last-child { border-bottom:none; }
.ir-key {
    font-size:8.5px; font-weight:700; color:#94a3b8;
    text-transform:uppercase; letter-spacing:1.2px;
    min-width:110px; flex-shrink:0; padding-top:3px;
}
.ir-val { font-size:13.5px; font-weight:600; color:#0f172a; flex:1; line-height:1.5; }
.ir-sub { font-size:10px; color:#94a3b8; margin-top:2px; font-weight:500; }
.ref-pill {
    display:inline-block;
    background:#eff6ff; color:#1d4ed8;
    font-size:12px; font-weight:700;
    padding:3px 14px; border-radius:20px;
    border:1px solid #bfdbfe; letter-spacing:0.5px;
}

/* ── SIGNATURES ── */
.sig-section {
    margin:8px 28px 22px;
    display:grid; grid-template-columns:1fr 1fr; gap:16px;
}
.sig-box {
    border:1px dashed #cbd5e1;
    border-radius:10px; padding:14px 12px 10px;
    text-align:center; background:#fafafa;
}
.sig-lbl { font-size:8px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:22px; }
.sig-line { border-top:1.5px solid #d1d5db; padding-top:8px; margin-top:6px; }
.sig-name { font-size:11px; font-weight:600; color:#374151; }

/* ── CONTACT FOOTER ── */
.contact-bar {
    background:#f8fafc;
    border-top:1px solid #e2e8f0;
    padding:14px 28px;
    display:flex; align-items:center; justify-content:space-between;
    gap:12px; flex-wrap:wrap;
}
.contact-item {
    display:flex; align-items:center; gap:7px;
}
.contact-item .ci-icon {
    width:28px; height:28px; border-radius:7px;
    background:#fff; border:1px solid #e2e8f0;
    display:flex; align-items:center; justify-content:center;
    font-size:13px; flex-shrink:0;
    box-shadow:0 1px 3px rgba(0,0,0,0.06);
}
.contact-item .ci-lbl { font-size:7.5px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:1px; }
.contact-item .ci-val { font-size:11px; font-weight:700; color:#334155; direction:ltr; }

/* ── BOTTOM FOOTER ── */
.footer {
    background:linear-gradient(160deg, #0f172a 0%, #1e293b 100%);
    padding:12px 28px;
    display:flex; align-items:center; justify-content:space-between; gap:10px;
}
.footer-l { font-size:9px; font-weight:500; color:rgba(255,255,255,0.35); font-style:italic; }
.footer-r { font-size:9px; font-weight:700; color:#c8941a; letter-spacing:0.5px; }

/* ── BOTTOM BAND ── */
.bottom-band {
    height:4px;
    background:linear-gradient(90deg, #8b1c2b, #c8941a, #8b1c2b);
}

/* ── PRINT BUTTON ── */
.print-btn {
    display:block; max-width:595px; margin:0 auto 18px;
    padding:12px; background:#0f172a;
    color:#fff; border:none; border-radius:10px;
    font-family:'Inter',sans-serif; font-size:14px; font-weight:700;
    cursor:pointer; text-align:center; letter-spacing:0.5px;
    transition:background 0.2s;
}
.print-btn:hover { background:#8b1c2b; }

@media print {
    * { -webkit-print-color-adjust:exact !important; print-color-adjust:exact !important; }
    body { background:#fff !important; padding:0; }
    .print-btn { display:none !important; }
    .card { box-shadow:none !important; border-radius:0; max-width:100%; margin:0; border:none; }
}
</style>
</head>
<body>
@php
$branchId  = $mov->branch_id ?? 1;
$isFloor6  = $branchId == 2;
$floorAr   = $isFloor6 ? 'الدور السادس' : 'الدور الثالث';
$floorEn   = $isFloor6 ? '6th Floor' : '3rd Floor';
$deptAr    = $isFloor6 ? 'الاستشارات التربوية والتدريب' : 'الاستشارات اللغوية';
$deptEn    = $isFloor6 ? 'Educational Consulting & Training' : 'Language Consulting';
$isReceipt = $mov->status == 1;
@endphp

<button class="print-btn" onclick="window.print()">🖨️ &nbsp; Print Receipt</button>

<div class="card">

    <div class="top-band"></div>

    {{-- Header --}}
    <div class="header">
        <div class="logo-wrap">
            <img src="{{ asset('images/logo.jpg') }}" alt="Mutmainah" onerror="this.style.display='none'">
        </div>
        <div class="header-text">
            <div class="name-ar">مركز مطمئنة الاستشاري</div>
            <div class="name-en">Mutmainah Advisory Center</div>
            <div class="dept">{{ $deptEn }} &nbsp;—&nbsp; {{ $deptAr }}</div>
        </div>
        <div class="vno-box">
            <div class="vb-lbl">Voucher No.</div>
            <div class="vb-type">{{ $isReceipt ? 'Receipt' : 'Payment' }}</div>
            <div class="vb-num">#{{ $mov->id }}</div>
        </div>
    </div>

    {{-- Type Row --}}
    <div class="type-row">
        <div class="tr-left">
            <span class="tr-dot"></span>
            {{ $isReceipt ? 'Cash Receipt Voucher' : 'Payment Voucher' }}
        </div>
        <div class="tr-right">
            {{ $mov->pdate }}@if($mov->ptime) &nbsp;|&nbsp; {{ $mov->ptime }}@endif
        </div>
    </div>

    {{-- Amount --}}
    <div class="amount-row">
        <div class="amt-left">
            <div class="al-lbl">Amount {{ $isReceipt ? 'Received' : 'Paid' }}</div>
            <div class="al-val">
                <span class="al-cur">KD</span>{{ number_format($mov->mov_amount, 3) }}
            </div>
        </div>
        <div class="method-pill">
            <div class="mp-lbl">Payment Method</div>
            <div class="mp-val">{{ $payMethods[$mov->payment_method] ?? 'Cash' }}</div>
        </div>
    </div>

    <div class="hr"></div>

    {{-- Info --}}
    <div class="info-section">

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
            <div class="ir-val" style="direction:ltr;">{{ $mov->patient_phone }}</div>
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

    <div class="hr" style="margin-bottom:16px;"></div>

    {{-- Signatures --}}
    <div class="sig-section">
        <div class="sig-box">
            <div class="sig-lbl">Client Signature</div>
            <div class="sig-line"></div>
            <div class="sig-name">____________________</div>
        </div>
        <div class="sig-box">
            <div class="sig-lbl">Received By</div>
            <div class="sig-line"></div>
            <div class="sig-name">{{ trim($mov->emp_name) ?: '____________________' }}</div>
        </div>
    </div>

    {{-- Contact Bar --}}
    <div class="contact-bar">
        <div class="contact-item">
            <div class="ci-icon">📍</div>
            <div>
                <div class="ci-lbl">Address</div>
                <div class="ci-val">Kuwait — Ahmed Al-Tour Tower, {{ $floorEn }}</div>
                <div class="ci-val" style="font-family:'Tajawal',sans-serif; font-size:10px; color:#64748b; direction:rtl; text-align:right;">
                    الكويت — برج أحمد التور، {{ $floorAr }}
                </div>
            </div>
        </div>
        <div class="contact-item">
            <div class="ci-icon">💬</div>
            <div>
                <div class="ci-lbl">WhatsApp</div>
                <div class="ci-val" style="font-size:13px; font-weight:800; color:#16a34a;" dir="ltr">+965 998 801 40</div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-l">Thank you for choosing Mutmainah Advisory Center</div>
        <div class="footer-r">© {{ date('Y') }} &nbsp; Mutmainah Advisory Center</div>
    </div>

    <div class="bottom-band"></div>

</div>

<script>
window.addEventListener('load', function() { setTimeout(() => window.print(), 700); });
</script>
</body>
</html>
