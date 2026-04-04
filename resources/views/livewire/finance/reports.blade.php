<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1400px; margin:0 auto; background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow-sm); overflow:hidden; animation:fadeIn 0.5s ease;">

    @php
    $titles = [
        'income'=>['label'=>'الدخل','icon'=>'💵'],
        'invoices'=>['label'=>'الفواتير','icon'=>'💳'],
        'vouchers'=>['label'=>'السندات','icon'=>'📑'],
        'pb'=>['label'=>'أرصدة العملاء','icon'=>'💰'],
        'services'=>['label'=>'الخدمات','icon'=>'🏥'],
        'appointments'=>['label'=>'المواعيد','icon'=>'📅'],
        'clinics'=>['label'=>'العيادات','icon'=>'🏛️'],
        'claims'=>['label'=>'المطالبات','icon'=>'📋'],
        'pfs'=>['label'=>'البيان المالي','icon'=>'📊'],
        'patients'=>['label'=>'العملاء','icon'=>'👥'],
        'till'=>['label'=>'الدرج','icon'=>'🗃️'],
    ];
    $current = $titles[$reportType] ?? $titles['income'];
    @endphp

    <!-- رأس الإطار -->
    <div style="padding:1.25rem 1.75rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="width:42px; height:42px; background:var(--primary-glow); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">{{ $current['icon'] }}</div>
            <h1 style="font-size:1.4rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">تقرير {{ $current['label'] }}</h1>
        </div>
        <button wire:click="resetFilters" class="btn btn-secondary">🔄 مسح الفلاتر</button>
    </div>

    <div style="padding:1.75rem;">

        <!-- فلاتر -->
        <div style="border:1px solid var(--border); border-radius:12px; padding:1rem 1.25rem; margin-bottom:1.25rem; background:#f8fafc;">
            <div style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end;">
                <div>
                    <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); margin-bottom:0.3rem;">من تاريخ</div>
                    <input type="date" wire:model="dateFrom" class="form-input" style="width:160px;">
                </div>
                <div>
                    <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); margin-bottom:0.3rem;">إلى تاريخ</div>
                    <input type="date" wire:model="dateTo" class="form-input" style="width:160px;">
                </div>
                @if(!in_array($reportType, ['pb', 'patients', 'services']))
                <div>
                    <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); margin-bottom:0.3rem;">العيادة</div>
                    <select wire:model="filterClinic" class="form-input" style="width:210px;">
                        <option value="">جميع العيادات</option>
                        @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                @if(in_array($reportType, ['invoices','appointments','patients','pfs','claims','pb','vouchers','services']))
                <div>
                    <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); margin-bottom:0.3rem;">بحث</div>
                    <input type="text" wire:model.live.debounce.400ms="search" placeholder="اسم أو رقم ملف..." class="form-input" style="width:200px;">
                </div>
                @endif
                <div style="display:flex; align-items:flex-end;">
                    <button wire:click="runSearch" class="btn btn-primary" style="height:38px;">🔍 بحث</button>
                </div>
            </div>
        </div>

        @if(!$searched)
        <div style="padding:4rem 2rem; text-align:center; background:#f8fafc; border:1px solid var(--border); border-radius:12px;">
            <div style="font-size:3.5rem; opacity:0.12; margin-bottom:1rem;">🔍</div>
            <div style="font-size:1.05rem; font-weight:800; color:var(--text-dim); font-family:'Tajawal',sans-serif;">حدّد الفترة والفلاتر ثم اضغط <span style="color:var(--primary);">بحث</span> لعرض النتائج</div>
        </div>
        @else

        <!-- ملخص -->
        @if(!empty($summary))
        <div style="display:flex; gap:0.75rem; margin-bottom:1.25rem; flex-wrap:wrap;">
            @if(isset($summary['total']))
            <div class="badge" style="background:var(--primary); color:#fff; padding:0.6rem 1.5rem; border-radius:10px; font-weight:900; font-size:0.92rem;">الإجمالي: {{ number_format($summary['total'], 0) }}</div>
            @endif
            @if(isset($summary['count']))
            <div class="badge" style="background:var(--navy); color:#fff; padding:0.6rem 1.5rem; border-radius:10px; font-weight:900; font-size:0.92rem;">العدد: {{ number_format($summary['count']) }}</div>
            @endif
            @if(isset($summary['credit']))
            <div class="badge" style="background:var(--success); color:#fff; padding:0.6rem 1.5rem; border-radius:10px; font-weight:900; font-size:0.92rem;">دائن: {{ number_format($summary['credit'], 0) }}</div>
            <div class="badge" style="background:var(--danger); color:#fff; padding:0.6rem 1.5rem; border-radius:10px; font-weight:900; font-size:0.92rem;">مدين: {{ number_format($summary['debit'], 0) }}</div>
            @endif
        </div>
        @endif

        <!-- الجدول -->
        <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden;">
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:0.84rem; font-family:'Tajawal',sans-serif;">

                    @if($reportType === 'income')
                    <thead><tr style="background:#fafbfc; border-bottom:2px solid var(--border);">
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">التاريخ</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">العيادة</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--text-dim);">العدد</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--primary);">الإجمالي</th>
                    </tr></thead>
                    <tbody>@forelse($rows as $r)
                        <tr style="border-bottom:1px solid #f0f2f5;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                            <td style="padding:0.65rem 1rem; font-weight:700; color:#1565c0; direction:ltr; unicode-bidi:isolate;">{{ fmt_date($r->pdate) }}</td>
                            <td style="padding:0.65rem 1rem; color:var(--text-dim);">{{ $r->clinic_name ?: '—' }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; color:var(--text-dim);">{{ $r->count }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; font-weight:900; color:var(--primary);">{{ number_format($r->total, 0) }}</td>
                        </tr>
                    @empty<tr><td colspan="4" style="padding:3rem; text-align:center; color:var(--text-muted);">لا توجد بيانات</td></tr>@endforelse</tbody>

                    @elseif($reportType === 'invoices')
                    <thead><tr style="background:#fafbfc; border-bottom:2px solid var(--border);">
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">#</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">العميل</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">التاريخ</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">العيادة</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">البيان</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--primary);">المبلغ</th>
                    </tr></thead>
                    <tbody>@forelse($rows as $r)
                        <tr style="border-bottom:1px solid #f0f2f5;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                            <td style="padding:0.65rem 1rem; color:var(--text-muted);">{{ $r->serial_no }}</td>
                            <td style="padding:0.65rem 1rem; font-weight:800; color:var(--navy);">{{ $r->patient_name ?: '—' }}@if($r->file_id)<div style="font-size:0.74rem; color:var(--text-muted);">#{{ $r->file_id }}</div>@endif</td>
                            <td style="padding:0.65rem 1rem; color:#1565c0; font-weight:700; white-space:nowrap; direction:ltr; unicode-bidi:isolate;">{{ fmt_date($r->pdate) }}</td>
                            <td style="padding:0.65rem 1rem; color:var(--text-dim);">{{ $r->clinic_name ?: '—' }}</td>
                            <td style="padding:0.65rem 1rem; color:var(--text-dim); max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $r->pdesc ?: '—' }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; font-weight:900; color:var(--primary);">{{ number_format($r->price, 0) }}</td>
                        </tr>
                    @empty<tr><td colspan="6" style="padding:3rem; text-align:center; color:var(--text-muted);">لا توجد بيانات</td></tr>@endforelse</tbody>

                    @elseif($reportType === 'vouchers')
                    <thead><tr style="background:#fafbfc; border-bottom:2px solid var(--border);">
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">#</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">العميل</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">التاريخ</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">البيان</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--success);">دائن</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--danger);">مدين</th>
                    </tr></thead>
                    <tbody>@forelse($rows as $r)
                        <tr style="border-bottom:1px solid #f0f2f5;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                            <td style="padding:0.65rem 1rem; color:var(--text-muted);">{{ $r->vno ?: $r->id }}</td>
                            <td style="padding:0.65rem 1rem; font-weight:700; color:var(--navy);">{{ $r->patient_name ?: '—' }}</td>
                            <td style="padding:0.65rem 1rem; color:#1565c0; font-weight:700; white-space:nowrap; direction:ltr; unicode-bidi:isolate;">{{ fmt_date($r->pdate) }}</td>
                            <td style="padding:0.65rem 1rem; color:var(--text-dim); max-width:250px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $r->pdesc ?: '—' }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; font-weight:800; color:var(--success);">{{ $r->credit > 0 ? number_format($r->credit, 0) : '—' }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; font-weight:800; color:var(--danger);">{{ $r->debit > 0 ? number_format($r->debit, 0) : '—' }}</td>
                        </tr>
                    @empty<tr><td colspan="6" style="padding:3rem; text-align:center; color:var(--text-muted);">لا توجد بيانات</td></tr>@endforelse</tbody>

                    @elseif($reportType === 'pb')
                    <thead><tr style="background:#fafbfc; border-bottom:2px solid var(--border);">
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">رقم الملف</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">العميل</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">الجوال</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--text-dim);">الزيارات</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--primary);">إجمالي المدفوع</th>
                    </tr></thead>
                    <tbody>@forelse($rows as $r)
                        <tr style="border-bottom:1px solid #f0f2f5;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                            <td style="padding:0.65rem 1rem; color:#1565c0; font-weight:700;">#{{ $r->file_id }}</td>
                            <td style="padding:0.65rem 1rem; font-weight:800; color:var(--navy);">{{ $r->full_name }}</td>
                            <td style="padding:0.65rem 1rem; color:var(--text-dim);">{{ $r->phone ?: '—' }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; color:var(--text-dim);">{{ $r->visits }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; font-weight:900; color:var(--primary);">{{ number_format($r->total_paid, 0) }}</td>
                        </tr>
                    @empty<tr><td colspan="5" style="padding:3rem; text-align:center; color:var(--text-muted);">لا توجد بيانات</td></tr>@endforelse</tbody>

                    @elseif($reportType === 'services')
                    <thead><tr style="background:#fafbfc; border-bottom:2px solid var(--border);">
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">الخدمة</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">العيادة</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--primary);">السعر</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--text-dim);">التكلفة</th>
                    </tr></thead>
                    <tbody>@forelse($rows as $r)
                        <tr style="border-bottom:1px solid #f0f2f5;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                            <td style="padding:0.65rem 1rem; font-weight:700; color:var(--navy);">{{ $r->name }}</td>
                            <td style="padding:0.65rem 1rem; color:var(--text-dim);">{{ $r->clinic_name ?: '—' }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; font-weight:800; color:var(--primary);">{{ number_format($r->price, 0) }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; color:var(--text-dim);">{{ $r->cost ? number_format($r->cost, 0) : '—' }}</td>
                        </tr>
                    @empty<tr><td colspan="4" style="padding:3rem; text-align:center; color:var(--text-muted);">لا توجد بيانات</td></tr>@endforelse</tbody>

                    @elseif($reportType === 'appointments')
                    <thead><tr style="background:#fafbfc; border-bottom:2px solid var(--border);">
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">#</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">العميل</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">الجوال</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">التاريخ</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">الوقت</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">العيادة</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--text-dim);">الحالة</th>
                    </tr></thead>
                    <tbody>@forelse($rows as $r)
                        <tr style="border-bottom:1px solid #f0f2f5;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                            <td style="padding:0.65rem 1rem; color:#1565c0; font-weight:700;">{{ $r->file_id ? '#'.$r->file_id : $r->id }}</td>
                            <td style="padding:0.65rem 1rem; font-weight:800; color:var(--navy);">{{ $r->patient_name ?: '—' }}</td>
                            <td style="padding:0.65rem 1rem; color:var(--text-dim); direction:ltr; unicode-bidi:isolate;">{{ $r->phone ?: '—' }}</td>
                            <td style="padding:0.65rem 1rem; color:#1565c0; font-weight:700; white-space:nowrap;">{{ fmt_date($r->rec_date) }}</td>
                            <td style="padding:0.65rem 1rem; font-weight:700; color:var(--navy); direction:ltr; unicode-bidi:isolate;">{{ $r->rec_time ? preg_replace('/^(\d+):(\d)$/', '$1:0$2', $r->rec_time) : '—' }}</td>
                            <td style="padding:0.65rem 1rem; color:var(--text-dim);">{{ $r->clinic_name ?: '—' }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center;"><span style="background:#fffbeb; color:#b45309; border:1px solid #fde68a; padding:0.2rem 0.75rem; border-radius:20px; font-size:0.78rem; font-weight:800;">محجوز</span></td>
                        </tr>
                    @empty<tr><td colspan="7" style="padding:3rem; text-align:center; color:var(--text-muted);">لا توجد بيانات</td></tr>@endforelse</tbody>

                    @elseif($reportType === 'clinics')
                    <thead><tr style="background:#fafbfc; border-bottom:2px solid var(--border);">
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">العيادة</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--text-dim);">الزيارات</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--primary);">الإيراد</th>
                    </tr></thead>
                    <tbody>@forelse($rows as $r)
                        <tr style="border-bottom:1px solid #f0f2f5;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                            <td style="padding:0.65rem 1rem; font-weight:800; color:var(--navy);">{{ $r->name }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; color:var(--text-dim);">{{ number_format($r->visits) }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; font-weight:900; color:var(--primary);">{{ number_format($r->revenue, 0) }}</td>
                        </tr>
                    @empty<tr><td colspan="3" style="padding:3rem; text-align:center; color:var(--text-muted);">لا توجد بيانات</td></tr>@endforelse</tbody>

                    @elseif($reportType === 'claims')
                    <thead><tr style="background:#fafbfc; border-bottom:2px solid var(--border);">
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">العميل</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">شركة التأمين</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">التاريخ</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">العيادة</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--success);">مبلغ التأمين</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--primary);">الإجمالي</th>
                    </tr></thead>
                    <tbody>@forelse($rows as $r)
                        <tr style="border-bottom:1px solid #f0f2f5;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                            <td style="padding:0.65rem 1rem; font-weight:800; color:var(--navy);">{{ $r->patient_name ?: '—' }}@if($r->file_id)<div style="font-size:0.74rem; color:var(--text-muted);">#{{ $r->file_id }}</div>@endif</td>
                            <td style="padding:0.65rem 1rem; color:var(--text-dim);">{{ $r->insurance_name ?: '—' }}</td>
                            <td style="padding:0.65rem 1rem; color:#1565c0; font-weight:700; white-space:nowrap; direction:ltr; unicode-bidi:isolate;">{{ fmt_date($r->pdate) }}</td>
                            <td style="padding:0.65rem 1rem; color:var(--text-dim);">{{ $r->clinic_name ?: '—' }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; font-weight:800; color:var(--success);">{{ $r->insur_amount ? number_format($r->insur_amount, 0) : '—' }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; font-weight:900; color:var(--primary);">{{ number_format($r->price, 0) }}</td>
                        </tr>
                    @empty<tr><td colspan="6" style="padding:3rem; text-align:center; color:var(--text-muted);">لا توجد بيانات</td></tr>@endforelse</tbody>

                    @elseif($reportType === 'pfs')
                    <thead><tr style="background:#fafbfc; border-bottom:2px solid var(--border);">
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">رقم الملف</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">العميل</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--text-dim);">الزيارات</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">آخر دفعة</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--primary);">إجمالي المدفوع</th>
                    </tr></thead>
                    <tbody>@forelse($rows as $r)
                        <tr style="border-bottom:1px solid #f0f2f5;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                            <td style="padding:0.65rem 1rem; color:#1565c0; font-weight:700;">#{{ $r->file_id }}</td>
                            <td style="padding:0.65rem 1rem; font-weight:800; color:var(--navy);">{{ $r->patient_name }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; color:var(--text-dim);">{{ $r->visits }}</td>
                            <td style="padding:0.65rem 1rem; color:var(--text-dim); white-space:nowrap; direction:ltr; unicode-bidi:isolate;">{{ $r->last_payment ?: '—' }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; font-weight:900; color:var(--primary);">{{ number_format($r->total_paid, 0) }}</td>
                        </tr>
                    @empty<tr><td colspan="5" style="padding:3rem; text-align:center; color:var(--text-muted);">لا توجد بيانات</td></tr>@endforelse</tbody>

                    @elseif($reportType === 'patients')
                    <thead><tr style="background:#fafbfc; border-bottom:2px solid var(--border);">
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">رقم الملف</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">الاسم</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">الجوال</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--text-dim);">الجنس</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">التأمين</th>
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">تاريخ التسجيل</th>
                    </tr></thead>
                    <tbody>@forelse($rows as $r)
                        <tr style="border-bottom:1px solid #f0f2f5;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                            <td style="padding:0.65rem 1rem; color:#1565c0; font-weight:700;">#{{ $r->file_id }}</td>
                            <td style="padding:0.65rem 1rem; font-weight:800; color:var(--navy);">{{ $r->full_name }}</td>
                            <td style="padding:0.65rem 1rem; color:var(--text-dim);">{{ $r->phone ?: '—' }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center;">@if($r->gender==1)<span class="badge badge-blue">ذكر</span>@else<span class="badge badge-red">أنثى</span>@endif</td>
                            <td style="padding:0.65rem 1rem; color:var(--text-dim);">{{ $r->insurance ?: '—' }}</td>
                            <td style="padding:0.65rem 1rem; color:var(--text-dim); white-space:nowrap; direction:ltr; unicode-bidi:isolate;">{{ fmt_date($r->reg_date) }}</td>
                        </tr>
                    @empty<tr><td colspan="6" style="padding:3rem; text-align:center; color:var(--text-muted);">لا توجد بيانات</td></tr>@endforelse</tbody>

                    @elseif($reportType === 'till')
                    <thead><tr style="background:#fafbfc; border-bottom:2px solid var(--border);">
                        <th style="padding:0.7rem 1rem; font-weight:800; color:var(--text-dim);">التاريخ</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--text-dim);">المعاملات</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--success);">نقد</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:#1565c0;">بطاقة</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--text-dim);">أخرى</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-weight:800; color:var(--primary);">الإجمالي</th>
                    </tr></thead>
                    <tbody>@forelse($rows as $r)
                        <tr style="border-bottom:1px solid #f0f2f5;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                            <td style="padding:0.65rem 1rem; font-weight:700; color:#1565c0; direction:ltr; unicode-bidi:isolate;">{{ fmt_date($r->pdate) }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; color:var(--text-dim);">{{ $r->transactions }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; font-weight:700; color:var(--success);">{{ $r->cash > 0 ? number_format($r->cash, 0) : '—' }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; font-weight:700; color:#1565c0;">{{ $r->card > 0 ? number_format($r->card, 0) : '—' }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; color:var(--text-dim);">{{ $r->other > 0 ? number_format($r->other, 0) : '—' }}</td>
                            <td style="padding:0.65rem 1rem; text-align:center; font-weight:900; color:var(--primary);">{{ number_format($r->total, 0) }}</td>
                        </tr>
                    @empty<tr><td colspan="6" style="padding:3rem; text-align:center; color:var(--text-muted);">لا توجد بيانات</td></tr>@endforelse</tbody>
                    @endif

                </table>
            </div>

            @if($rows instanceof \Illuminate\Pagination\LengthAwarePaginator && $rows->hasPages())
            <div style="padding:0.85rem 1.25rem; border-top:1px solid var(--border); background:#fafbfc;">
                {{ $rows->links() }}
            </div>
            @endif
        </div>

    </div>
        @endif {{-- end $searched --}}
</div>
</div>
