@php
$branchId  = $mov->branch_id ?? 1;
$isFloor6  = $branchId == 2;
$floorAr   = $isFloor6 ? 'الدور السادس' : 'الدور الثالث';
$floorEn   = $isFloor6 ? '6th Floor' : '3rd Floor';
$deptAr    = $isFloor6 ? 'الاستشارات التربوية والتدريب' : 'الاستشارات اللغوية';
$deptEn    = $isFloor6 ? 'Educational Consulting & Training' : 'Language Consulting';
$isReceipt = $mov->status == 1;
$typeColor = $isReceipt ? '#0f172a' : '#dc2626';
$typeBg    = $isReceipt ? '#f0fdf4' : '#fff5f5';
$typeBdr   = $isReceipt ? '#bbf7d0' : '#fecaca';
$typeDot   = $isReceipt ? 'rgba(15,23,42,0.15)' : 'rgba(220,38,38,0.25)';
$typeLabel = $isReceipt ? 'CASH RECEIPT VOUCHER' : 'PAYMENT VOUCHER';
$amtLabel  = $isReceipt ? 'Amount Received' : 'Amount Paid';
@endphp
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<title>{{ $isReceipt ? 'Receipt' : 'Voucher' }} #{{ $mov->id }}</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Tajawal:wght@400;700;800;900&display=swap" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Inter',sans-serif; background:#94a3b8; color:#0f172a; padding:28px 14px 40px; }

.card { max-width:580px; margin:0 auto; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,0.25); border:2.5px solid #0f172a; }

/* Gold top band */
.band-top { height:5px; background:linear-gradient(90deg,#8b1c2b,#c8941a,#8b1c2b); }

/* Header */
.hdr { background:linear-gradient(145deg,#7a1825 0%,#5a1019 100%); padding:20px 26px; display:flex; align-items:center; gap:16px; position:relative; overflow:hidden; }
.hdr::before { content:''; position:absolute; top:-50px; right:-50px; width:180px; height:180px; border-radius:50%; background:rgba(200,148,26,0.07); }
.logo { background:#fff; border-radius:9px; padding:5px 9px; flex-shrink:0; box-shadow:0 4px 16px rgba(0,0,0,0.3); z-index:1; }
.logo img { height:54px; width:auto; display:block; }
.hdr-txt { flex:1; z-index:1; }
.hdr-txt h1 { font-family:'Tajawal',sans-serif; font-size:17px; font-weight:900; color:#fff; }
.hdr-txt .en { font-size:8px; font-weight:600; color:rgba(255,255,255,0.45); letter-spacing:3px; text-transform:uppercase; margin-top:3px; }
.hdr-txt .dept { font-size:10px; font-weight:600; color:#f0c040; margin-top:7px; }
.vno { flex-shrink:0; z-index:1; background:rgba(0,0,0,0.3); border:1.5px solid rgba(200,148,26,0.55); border-radius:10px; padding:10px 14px; text-align:center; min-width:94px; }
.vno .v1 { font-size:7px; font-weight:700; color:rgba(255,255,255,0.4); letter-spacing:2.5px; text-transform:uppercase; }
.vno .v2 { font-size:11px; font-weight:800; color:#f0c040; letter-spacing:1px; margin:4px 0; text-transform:uppercase; }
.vno .v3 { font-size:22px; font-weight:900; color:#fff; }

/* Type strip */
.strip { display:flex; align-items:center; justify-content:space-between; padding:9px 26px; background:{{ $typeBg }}; border-bottom:2px solid {{ $typeBdr }}; }
.strip-l { display:flex; align-items:center; gap:8px; font-size:10.5px; font-weight:800; color:{{ $typeColor }}; text-transform:uppercase; letter-spacing:1.5px; }
.dot { width:9px; height:9px; border-radius:50%; background:{{ $typeColor }}; box-shadow:0 0 0 4px {{ $typeDot }}; flex-shrink:0; }
.strip-r { font-size:11px; font-weight:600; color:#475569; text-align:right; line-height:1.7; }

/* Amount */
.amt-row { display:flex; align-items:center; justify-content:space-between; padding:20px 26px 16px; border-bottom:1px solid #f1f5f9; background:#fafafa; }
.amt-l .lbl { font-size:8px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:2px; margin-bottom:5px; }
.amt-l .val { font-size:42px; font-weight:900; color:{{ $typeColor }}; line-height:1; }
.amt-l .cur { font-size:15px; font-weight:600; color:#94a3b8; margin-right:4px; }
.method { background:#fff; border:1.5px solid {{ $typeBdr }}; border-radius:11px; padding:12px 18px; text-align:center; min-width:130px; box-shadow:0 2px 8px rgba(0,0,0,0.06); }
.method .ml { font-size:7.5px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:2px; margin-bottom:6px; }
.method .mv { font-size:15px; font-weight:800; color:#0f172a; }

/* Divider */
.divider { height:1px; background:linear-gradient(90deg,transparent,#e2e8f0,transparent); margin:0 26px; }

/* Info rows */
.info { padding:4px 26px 8px; }
.row { display:flex; align-items:flex-start; gap:14px; padding:11px 0; border-bottom:1px solid #f8fafc; }
.row:last-child { border-bottom:none; }
.row-key { font-size:8.5px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1.2px; min-width:108px; flex-shrink:0; padding-top:2px; }
.row-val { font-size:13.5px; font-weight:600; color:#0f172a; flex:1; line-height:1.5; }
.row-sub { font-size:10px; color:#94a3b8; margin-top:2px; }
.ref { display:inline-block; background:#eff6ff; color:#1d4ed8; font-size:12px; font-weight:700; padding:3px 14px; border-radius:20px; border:1px solid #bfdbfe; }

/* Signatures */
.sigs { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin:10px 26px 20px; }
.sig { border:1px dashed #cbd5e1; border-radius:10px; padding:13px 12px 10px; text-align:center; background:#fafafa; }
.sig-l { font-size:8px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:20px; }
.sig-n { font-size:11px; font-weight:600; color:#334155; border-top:1.5px solid #cbd5e1; padding-top:7px; margin-top:4px; }

/* Contact bar */
.contact { background:#f8fafc; border-top:1px solid #e2e8f0; padding:13px 26px; display:flex; align-items:flex-start; justify-content:space-between; gap:12px; flex-wrap:wrap; }
.ci { display:flex; align-items:flex-start; gap:9px; }
.ci-icon { width:30px; height:30px; border-radius:8px; background:#fff; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; box-shadow:0 1px 4px rgba(0,0,0,0.06); margin-top:1px; }
.ci-lbl { font-size:7.5px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:1px; margin-bottom:3px; }
.ci-val { font-size:11.5px; font-weight:700; color:#1e293b; line-height:1.5; }
.ci-val-ar { font-family:'Tajawal',sans-serif; font-size:10.5px; color:#475569; font-weight:600; direction:rtl; }
.wa-val { font-size:14px; font-weight:800; color:#16a34a; direction:ltr; }

/* Footer */
.footer { background:#0f172a; padding:12px 26px; display:flex; align-items:center; justify-content:space-between; }
.ft-l { font-size:8.5px; font-weight:400; color:rgba(255,255,255,0.3); font-style:italic; }
.ft-r { font-size:8.5px; font-weight:700; color:#c8941a; letter-spacing:0.5px; }

/* Bottom band */
.band-bot { height:4px; background:linear-gradient(90deg,#8b1c2b,#c8941a,#8b1c2b); }

/* Print Button */
.btn { display:block; max-width:580px; margin:0 auto 16px; padding:12px; background:#0f172a; color:#fff; border:none; border-radius:10px; font-family:'Inter',sans-serif; font-size:14px; font-weight:700; cursor:pointer; text-align:center; letter-spacing:0.5px; }
.btn:hover { background:#8b1c2b; }

@media print {
    * { -webkit-print-color-adjust:exact !important; print-color-adjust:exact !important; }
    body { background:#fff !important; padding:0; }
    .btn { display:none !important; }
    .card { box-shadow:none !important; border-radius:0; max-width:100%; margin:0; }
}
</style>
</head>
<body>

<button class="btn" onclick="window.print()">🖨️ &nbsp; Print Receipt</button>

<div class="card">
<div class="band-top"></div>

{{-- Header --}}
<div class="hdr">
    <div class="logo">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" onerror="this.style.display='none'">
    </div>
    <div class="hdr-txt">
        <h1>شركة مركز مطمئنة الكويتية للاستشارات اللغوية</h1>
        <div class="en">Mutmainah Advisory Center</div>
        <div class="dept">{{ $deptEn }} &nbsp;·&nbsp; {{ $deptAr }}</div>
    </div>
    <div class="vno">
        <div class="v1">Voucher No.</div>
        <div class="v2">{{ $isReceipt ? 'Receipt' : 'Payment' }}</div>
        <div class="v3">#{{ $mov->id }}</div>
    </div>
</div>

{{-- Type Strip --}}
<div class="strip">
    <div class="strip-l"><span class="dot"></span>{{ $typeLabel }}</div>
    <div class="strip-r">{{ $mov->pdate }}@if($mov->ptime)<br>{{ $mov->ptime }}@endif</div>
</div>

{{-- Amount --}}
<div class="amt-row">
    <div class="amt-l">
        <div class="lbl">{{ $amtLabel }}</div>
        <div class="val"><span class="cur">KD</span>{{ number_format($mov->mov_amount, 3) }}</div>
    </div>
    <div class="method">
        <div class="ml">Payment Method</div>
        <div class="mv">{{ $payMethods[$mov->payment_method] ?? 'Cash' }}</div>
    </div>
</div>

<div class="divider"></div>

{{-- Info --}}
<div class="info">
    <div class="row">
        <div class="row-key">Client</div>
        <div class="row-val">
            {{ $mov->patient_name ?? $mov->acck_name ?? '—' }}
            @if($mov->patient_file)<div class="row-sub">File No. #{{ $mov->patient_file }}</div>@endif
        </div>
    </div>
    @if($mov->patient_phone)
    <div class="row">
        <div class="row-key">Phone</div>
        <div class="row-val" style="direction:ltr;">{{ $mov->patient_phone }}</div>
    </div>
    @endif
    @if($descClean)
    <div class="row">
        <div class="row-key">Description</div>
        <div class="row-val">{{ $descClean }}</div>
    </div>
    @endif
    @if($refNo)
    <div class="row">
        <div class="row-key">Reference No.</div>
        <div class="row-val"><span class="ref">{{ $refNo }}</span></div>
    </div>
    @endif
    <div class="row">
        <div class="row-key">Recorded By</div>
        <div class="row-val">{{ trim($mov->emp_name) ?: '—' }}</div>
    </div>
</div>

<div class="divider" style="margin-bottom:14px;"></div>

{{-- Signatures --}}
<div class="sigs">
    <div class="sig">
        <div class="sig-l">Client Signature</div>
        <div class="sig-n">____________________</div>
    </div>
    <div class="sig">
        <div class="sig-l">Received By</div>
        <div class="sig-n">{{ trim($mov->emp_name) ?: '____________________' }}</div>
    </div>
</div>

{{-- Contact Bar --}}
<div class="contact">
    <div class="ci">
        <div class="ci-icon">📍</div>
        <div>
            <div class="ci-lbl">Address</div>
            <div class="ci-val">Kuwait — Ahmed Al-Tour Tower</div>
            <div class="ci-val">{{ $floorEn }}</div>
            <div class="ci-val-ar">الكويت — برج أحمد التور، {{ $floorAr }}</div>
        </div>
    </div>
    <div class="ci">
        <div class="ci-icon">💬</div>
        <div>
            <div class="ci-lbl">WhatsApp — للتواصل والاستفسار</div>
            <div class="wa-val">+965 998 801 40</div>
        </div>
    </div>
</div>

{{-- Footer --}}
<div class="footer">
    <div class="ft-l">Thank you for choosing Mutmainah Advisory Center</div>
    <div class="ft-r">© {{ date('Y') }} &nbsp; Mutmainah Advisory Center</div>
</div>
<div class="band-bot"></div>

</div>
<script>window.addEventListener('load',()=>setTimeout(()=>window.print(),700));</script>
</body>
</html>
