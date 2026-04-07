<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>فاتورة {{ $rec->id }}</title>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700;800;900&display=swap" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Tajawal', sans-serif;
    background: #f5f5f5;
    color: #111;
    direction: rtl;
    padding: 30px 20px;
}

.page {
    max-width: 720px;
    margin: 0 auto;
    background: #fff;
    border: 2px solid #8b1c2b;
}

/* ─── الرأس ─── */
.header {
    display: flex;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 3px solid #8b1c2b;
    gap: 16px;
    background: #fff;
}
.logo-box {
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 4px 8px;
    flex-shrink: 0;
}
.logo-box img { height: 60px; display: block; }

.header-mid {
    flex: 1;
    text-align: center;
}
.header-mid .ar  { font-size: 20px; font-weight: 900; color: #8b1c2b; }
.header-mid .en  { font-size: 10px; font-weight: 700; color: #888; letter-spacing: 2px; margin-top: 2px; }

.inv-no-box {
    border: 2px solid #8b1c2b;
    border-radius: 6px;
    padding: 6px 14px;
    text-align: center;
    flex-shrink: 0;
    min-width: 110px;
}
.inv-no-box .lbl { font-size: 10px; font-weight: 700; color: #888; }
.inv-no-box .num { font-size: 20px; font-weight: 900; color: #8b1c2b; direction: ltr; }

/* ─── شريط الفاتورة ─── */
.inv-bar {
    background: #8b1c2b;
    padding: 7px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.inv-bar .title { font-size: 15px; font-weight: 900; color: #fff; letter-spacing: 1px; }
.inv-bar .date  { font-size: 13px; font-weight: 700; color: #ffcdd2; direction: ltr; }

/* ─── جدول البيانات ─── */
.info-wrap { padding: 16px 20px 0; }

.info-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 16px;
}
.info-table td {
    padding: 7px 12px;
    font-size: 13px;
    border: 1px solid #e0e0e0;
    vertical-align: middle;
}
.info-table .lbl {
    background: #fafafa;
    font-weight: 800;
    color: #8b1c2b;
    width: 110px;
    font-size: 12px;
    white-space: nowrap;
}
.info-table .val { font-weight: 700; color: #111; }
.info-table .val.big { font-size: 16px; font-weight: 900; }
.info-table .val.blue { color: #1565c0; font-weight: 900; }
.info-table .val.ltr { direction: ltr; text-align: left; }

/* ─── جدول الخدمات ─── */
.srv-wrap { padding: 0 20px; }

.srv-table {
    width: 100%;
    border-collapse: collapse;
}
.srv-table th {
    background: #1a1a2e;
    color: #fff;
    padding: 9px 12px;
    font-size: 12px;
    font-weight: 800;
    text-align: center;
    border-left: 1px solid rgba(255,255,255,0.1);
}
.srv-table th:first-child { border-left: none; }
.srv-table th.r { text-align: right; }

.srv-table td {
    padding: 9px 12px;
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    border: 1px solid #e0e0e0;
    border-top: none;
}
.srv-table td.r   { text-align: right; font-weight: 700; }
.srv-table td.num { font-weight: 900; color: #8b1c2b; }
.srv-table td.price { font-weight: 800; color: #1b5e20; direction: ltr; }

/* ─── صف الإجماليات ─── */
.totals-row {
    background: #f5f5f5;
    border-top: 2px solid #8b1c2b;
}
.totals-row td {
    padding: 10px 12px !important;
    font-size: 13px !important;
    font-weight: 800 !important;
    color: #111 !important;
    border: 1px solid #e0e0e0 !important;
    border-top: none !important;
    text-align: center !important;
}
.totals-row .t-big { font-size: 16px; font-weight: 900; color: #8b1c2b; }

/* ─── وسيلة الدفع / ملاحظات ─── */
.pay-row, .notes-row {
    margin: 0 20px;
    padding: 8px 14px;
    border: 1px solid #e0e0e0;
    border-top: none;
    background: #fafafa;
    display: flex;
    gap: 6px;
    align-items: center;
    font-size: 13px;
}
.pay-row .lbl, .notes-row .lbl { font-weight: 800; color: #8b1c2b; white-space: nowrap; }
.pay-row .val  { font-weight: 700; background: #fff; border: 1px solid #e0e0e0; padding: 2px 12px; border-radius: 4px; }
.notes-row .val { font-weight: 600; color: #333; flex: 1; }

/* ─── التوقيعات ─── */
.sign-row {
    margin: 0 20px;
    padding: 10px 14px;
    border: 1px solid #e0e0e0;
    border-top: none;
    background: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
    gap: 8px;
}
.sign-row .cashier { font-weight: 800; color: #8b1c2b; }
.sign-row .sign-box {
    text-align: center;
    font-weight: 700;
    color: #555;
    flex: 1;
    border-right: 1px dashed #ddd;
    padding: 0 10px;
}
.sign-row .sign-box:last-child { border-right: none; }

/* ─── التذييل ─── */
.footer {
    margin-top: 16px;
    background: #1a1a2e;
    padding: 8px 20px;
    text-align: center;
    font-size: 11px;
    color: rgba(255,255,255,0.5);
    font-weight: 600;
    letter-spacing: 1px;
}
.footer b { color: #c8941a; font-weight: 700; }

/* ─── أزرار ─── */
.btns {
    max-width: 720px;
    margin: 14px auto 0;
    display: flex;
    gap: 8px;
    justify-content: center;
}
.btn-p {
    padding: 10px 36px;
    background: #8b1c2b;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 900;
    cursor: pointer;
    font-family: 'Tajawal', sans-serif;
}
.btn-c {
    padding: 10px 22px;
    background: #607d8b;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Tajawal', sans-serif;
}

/* ─── طباعة ─── */
@media print {
    body { background: #fff; padding: 0; }
    .page { border: 1.5px solid #8b1c2b; max-width: 100%; }
    .btns { display: none !important; }
    @page { margin: 10mm; size: A4 portrait; }
}
</style>
</head>
<body>

<div class="page">

    {{-- رأس --}}
    <div class="header">
        <div class="logo-box">
            <img src="{{ asset('logo.jpg') }}" alt="مطمئنة">
        </div>
        <div class="header-mid">
            <div class="ar">مركـز مطمئنة الاستشاري</div>
            <div class="en">MUTMAINAH ADVISORY CENTER</div>
        </div>
        <div class="inv-no-box">
            <div class="lbl">رقم الفاتورة</div>
            <div class="num">{{ $rec->id }}</div>
            <div class="lbl">Invoice No</div>
        </div>
    </div>

    {{-- شريط عنوان --}}
    <div class="inv-bar">
        <span class="title">الفاتورة &nbsp; INVOICE</span>
        <span class="date">{{ fmt_date_ar($rec->rec_date) }}</span>
    </div>

    {{-- بيانات العميل --}}
    <div class="info-wrap">
        <table class="info-table">
            <tr>
                <td class="lbl">الاسم</td>
                <td class="val big" colspan="3">{{ $patient->full_name ?? '—' }}</td>
            </tr>
            <tr>
                <td class="lbl">رقم الملف</td>
                <td class="val blue">{{ $patient->file_id ?? '—' }}</td>
                <td class="lbl">التاريخ</td>
                <td class="val ltr">{{ fmt_date_ar($rec->rec_date) }}</td>
            </tr>
            <tr>
                <td class="lbl">الوقت</td>
                <td class="val ltr">
                    @php
                        $displayTime = '—';
                        $pt = trim($invoice->ptime ?? $rec->rec_time ?? '');
                        if ($pt) {
                            if (preg_match('/AM|PM/i', $pt)) {
                                // صيغة النظام الأصلي: "06:54 PM"
                                $displayTime = strtoupper($pt);
                            } else {
                                // صيغة 24 ساعة: "9:30" أو "14:00"
                                $t = preg_replace('/^(\d+):(\d)$/', '$1:0$2', $pt);
                                try {
                                    $displayTime = \Carbon\Carbon::createFromFormat('G:i', substr($t,0,5))->format('h:i A');
                                } catch(\Exception $e) {
                                    $displayTime = $pt;
                                }
                            }
                        }
                        echo $displayTime;
                    @endphp
                </td>
                <td class="lbl">رقم الإحالة</td>
                <td class="val ltr">{{ $invoice->serial_no ?? '—' }}</td>
            </tr>
            <tr>
                <td class="lbl">الشركة</td>
                <td class="val">{{ $patient->insurance ?? 'على نفقته' }}</td>
                <td class="lbl">رقم البوليصة</td>
                <td class="val ltr">{{ $patient->policy_no ?: '—' }}</td>
            </tr>
            <tr>
                <td class="lbl">المكتب</td>
                <td class="val">{{ $clinicName }}</td>
                <td class="lbl">المستشار</td>
                <td class="val">{{ $clinicName }}</td>
            </tr>
            @if($branchName)
            <tr>
                <td class="lbl">الفرع</td>
                <td class="val" colspan="3">{{ $branchName }}</td>
            </tr>
            @endif
            @if($cashierName)
            <tr>
                <td class="lbl">الاستقبال</td>
                <td class="val" colspan="3">{{ $cashierName }}</td>
            </tr>
            @endif
        </table>
    </div>

    {{-- جدول الخدمات --}}
    <div class="srv-wrap">
        <table class="srv-table">
            <thead>
                <tr>
                    <th style="width:36px;">م</th>
                    <th class="r">الخدمة : Service</th>
                    <th>الكود : Code</th>
                    <th style="width:95px;">السعر : Price</th>
                    <th style="width:80px;">التأمين % : % Insur</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $i => $item)
                <tr>
                    <td class="num">{{ $i + 1 }}</td>
                    <td class="r">{{ $item->pdesc_clean ?? '—' }}</td>
                    <td>{{ '—' }}</td>
                    <td class="price">{{ number_format($item->price, 3) }}</td>
                    <td>{{ number_format($item->insur_amount ?? 0, 0) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:20px; color:#9e9e9e;">لا توجد خدمات مسجلة</td>
                </tr>
                @endforelse

                <tr class="totals-row">
                    <td colspan="2" style="text-align:right !important;">
                        الإجمالي :
                        <span class="t-big">{{ number_format($total, 3) }} د.ك</span>
                        &nbsp;&nbsp;
                        <span style="font-size:11px; color:#666; font-weight:700;">خصم: {{ number_format($totalDiscount, 3) }}</span>
                    </td>
                    <td>
                        <span style="font-size:11px; color:#555;">العميل</span><br>
                        <strong style="color:#1b5e20;">{{ number_format($clientAmount, 3) }}</strong>
                    </td>
                    <td colspan="2">
                        <span style="font-size:11px; color:#555;">التأمين</span><br>
                        <strong>{{ number_format($insuranceAmount, 0) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- وسيلة الدفع --}}
    <div class="pay-row">
        <span class="lbl">وسيلة الدفع :</span>
        <span class="val">{{ $paymentLabel }}</span>
    </div>

    {{-- ملاحظات --}}
    <div class="notes-row">
        <span class="lbl">ملاحظات :</span>
        <span class="val">{{ $recNotes }}</span>
    </div>


    {{-- تذييل --}}
    <div class="footer">
        مركز مطمئنة الاستشاري &nbsp;|&nbsp; <b>Mutmainah Advisory Center</b>
    </div>

</div>

<div class="btns">
    <button class="btn-p" onclick="window.print()">🖨️ طباعة</button>
    <button class="btn-c" onclick="window.close()">✕ إغلاق</button>
</div>

</body>
</html>
