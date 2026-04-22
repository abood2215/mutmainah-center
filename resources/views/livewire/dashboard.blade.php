<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1400px; margin:0 auto; background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow-sm); overflow:hidden; animation:fadeIn 0.5s ease;">

    <!-- رأس الإطار -->
    <div style="padding:1.25rem 1.75rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="width:42px; height:42px; background:var(--primary-glow); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">👋</div>
            <div>
                <h1 style="font-size:1.4rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">أهلاً بك في مركز مطمئنة</h1>
                <div style="font-size:0.8rem; color:var(--text-muted); margin-top:0.1rem;">{{ now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}</div>
            </div>
        </div>
        <a href="{{ route('checks.index') }}" wire:navigate class="btn btn-primary">
            📋 سجل الكشوفات
        </a>
    </div>

    <!-- المحتوى -->
    <div class="pg-inner" style="padding:1.75rem; display:flex; flex-direction:column; gap:1.25rem;">

        @if($isAdmin)
        <!-- ═══ بطاقات الفروع ═══ -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(320px,1fr)); gap:1.25rem;">
            @foreach($branchStats as $branch)
            @php
                $isThird = str_contains($branch->name, 'الثالث');
                $isSixth = str_contains($branch->name, 'السادس');
                $floorNum   = $isThird ? '3' : ($isSixth ? '6' : '');
                $floorLabel = $isThird ? 'الطابق الثالث' : ($isSixth ? 'الطابق السادس' : 'الفرع');
                if ($isThird) {
                    $bandBg  = 'linear-gradient(180deg,#db2777 0%,#9d174d 100%)';
                    $glow    = '0 4px 24px rgba(219,39,119,0.22)';
                    $tagBg   = '#fdf2f8';
                    $tagClr  = '#9d174d';
                    $dotClr  = '#db2777';
                } elseif ($isSixth) {
                    $bandBg  = 'linear-gradient(180deg,#2563eb 0%,#1e3a8a 100%)';
                    $glow    = '0 4px 24px rgba(37,99,235,0.22)';
                    $tagBg   = '#eff6ff';
                    $tagClr  = '#1e3a8a';
                    $dotClr  = '#2563eb';
                } else {
                    $bandBg  = 'linear-gradient(180deg,#374151 0%,#111827 100%)';
                    $glow    = '0 4px 24px rgba(55,65,81,0.22)';
                    $tagBg   = '#f9fafb';
                    $tagClr  = '#111827';
                    $dotClr  = '#c8941a';
                }
            @endphp
            <div style="background:#fff; border-radius:16px; overflow:hidden; box-shadow:{{ $glow }}, 0 1px 4px rgba(0,0,0,0.07); display:flex; border:2px solid {{ $dotClr }}33;">

                {{-- شريط الطابق الجانبي --}}
                <div style="width:80px; flex-shrink:0; background:{{ $bandBg }}; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:1.25rem 0; gap:4px; position:relative; overflow:hidden;">
                    {{-- خلفية زخرفية --}}
                    <div style="position:absolute; top:-18px; right:-18px; width:60px; height:60px; border-radius:50%; background:rgba(255,255,255,0.07); pointer-events:none;"></div>
                    <div style="position:absolute; bottom:-12px; left:-12px; width:44px; height:44px; border-radius:50%; background:rgba(255,255,255,0.05); pointer-events:none;"></div>
                    @if($floorNum)
                    <div style="font-size:2.6rem; font-weight:900; color:#fff; font-family:'Inter',sans-serif; line-height:1; position:relative; z-index:1;">{{ $floorNum }}</div>
                    <div style="font-size:0.55rem; font-weight:800; color:rgba(255,255,255,0.6); letter-spacing:1.5px; text-transform:uppercase; position:relative; z-index:1;">FLOOR</div>
                    @else
                    <div style="font-size:1.6rem; color:rgba(255,255,255,0.8); position:relative; z-index:1;">🏢</div>
                    @endif
                </div>

                {{-- المحتوى --}}
                <div style="flex:1; padding:1.1rem 1.25rem; display:flex; flex-direction:column; justify-content:space-between; min-width:0;">

                    {{-- العنوان --}}
                    <div style="margin-bottom:0.85rem;">
                        <div style="display:inline-flex; align-items:center; gap:5px; background:{{ $tagBg }}; color:{{ $tagClr }}; font-size:0.62rem; font-weight:800; letter-spacing:1.2px; padding:2px 8px; border-radius:20px; margin-bottom:6px;">
                            <span style="width:5px; height:5px; border-radius:50%; background:{{ $dotClr }}; display:inline-block;"></span>
                            {{ $floorLabel }}
                        </div>
                        <div style="font-size:0.97rem; font-weight:900; color:#1a1a2e; font-family:'Tajawal',sans-serif; line-height:1.3;">{{ $branch->name }}</div>
                    </div>

                    {{-- الإحصائيات --}}
                    <div style="display:flex; gap:0.6rem;">
                        <div style="flex:1; background:#f8fafc; border-radius:10px; padding:0.55rem 0.75rem; border:1px solid #e8eef4;">
                            <div style="font-size:0.62rem; font-weight:800; color:#94a3b8; margin-bottom:3px; white-space:nowrap;">👥 إجمالي العملاء</div>
                            <div style="font-size:1.5rem; font-weight:900; color:#1a1a2e; font-family:'Inter',sans-serif; line-height:1;">{{ number_format($branch->patients_count) }}</div>
                        </div>
                        <div style="flex:1; background:#f8fafc; border-radius:10px; padding:0.55rem 0.75rem; border:1px solid #e8eef4;">
                            <div style="font-size:0.62rem; font-weight:800; color:#94a3b8; margin-bottom:3px; white-space:nowrap;">📈 إيرادات الشهر</div>
                            <div style="font-size:1.3rem; font-weight:900; color:{{ $dotClr }}; font-family:'Inter',sans-serif; line-height:1;">{{ number_format($branch->monthly_revenue, 0) }}<span style="font-size:0.6rem; color:#94a3b8; margin-right:2px; font-weight:800;">د.ك</span></div>
                        </div>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- بطاقات الإحصائيات -->
        <div class="dash-stats" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1.25rem;">

            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:52px; height:52px; background:rgba(26,26,46,0.08); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; flex-shrink:0;">👥</div>
                <div>
                    <div style="font-size:0.78rem; font-weight:700; color:var(--text-muted); margin-bottom:0.2rem;">إجمالي العملاء</div>
                    <div style="font-size:2rem; font-weight:900; color:var(--navy); line-height:1;">{{ number_format($totalPatients) }}</div>
                    @if($lastPatient)
                    <div style="margin-top:0.35rem; font-size:0.73rem; color:var(--text-muted); display:flex; align-items:center; gap:0.3rem;">
                        <span style="color:var(--gold); font-weight:800;">آخر ملف:</span>
                        <a href="{{ route('patients.show', $lastPatient->id) }}" wire:navigate
                           style="color:var(--navy); font-weight:800; text-decoration:none;"
                           onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--navy)'">
                            {{ $lastPatient->full_name }}
                        </a>
                        <span style="background:#f0f4ff; color:#1565c0; font-size:0.68rem; font-weight:900; padding:0.1rem 0.4rem; border-radius:4px; font-family:'Inter';">#{{ $lastPatient->file_id ?? $lastPatient->id }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem; border-right:4px solid var(--primary);">
                <div style="width:52px; height:52px; background:var(--primary-glow); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; flex-shrink:0;">📋</div>
                <div>
                    <div style="font-size:0.78rem; font-weight:700; color:var(--text-muted); margin-bottom:0.2rem;">كشوف اليوم</div>
                    <div style="font-size:2rem; font-weight:900; color:var(--primary); line-height:1;">{{ $todayChecks }}</div>
                    <div style="font-size:0.72rem; color:var(--text-muted); margin-top:0.15rem;">
                        <span style="color:var(--success);">✔ {{ $todayDone }} تم الكشف</span>
                        &nbsp;·&nbsp;
                        <span style="color:#e65100;">⏳ {{ $todayWaiting }} انتظار</span>
                    </div>
                </div>
            </div>

            @if($isAdmin)
            <div class="card" wire:click="loadTodayRevenue" style="padding:1.25rem; display:flex; align-items:center; gap:1rem; border-right:4px solid var(--success); cursor:pointer; transition:box-shadow .2s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(46,125,50,.18)'" onmouseout="this.style.boxShadow=''">
                <div style="width:52px; height:52px; background:rgba(46,125,50,0.08); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; flex-shrink:0;">💵</div>
                <div>
                    <div style="font-size:0.78rem; font-weight:700; color:var(--text-muted); margin-bottom:0.2rem;">إيرادات اليوم</div>
                    <div style="font-size:1.7rem; font-weight:900; color:var(--success); line-height:1;">{{ number_format($todayRevenue, 0) }}</div>
                    <div style="font-size:0.72rem; color:var(--text-muted);">د.ك &nbsp;🔍</div>
                </div>
            </div>

            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem; border-right:4px solid var(--gold);">
                <div style="width:52px; height:52px; background:rgba(200,148,26,0.1); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; flex-shrink:0;">📈</div>
                <div>
                    <div style="font-size:0.78rem; font-weight:700; color:var(--text-muted); margin-bottom:0.2rem;">إيرادات الشهر</div>
                    <div style="font-size:1.7rem; font-weight:900; color:var(--gold); line-height:1;">{{ number_format($monthlyRevenue, 0) }}</div>
                    <div style="font-size:0.72rem; color:var(--text-muted);">{{ now()->locale('ar')->isoFormat('MMMM YYYY') }}</div>
                </div>
            </div>
            @endif

        </div>

        @if($isAdmin)
        <!-- ═══ الرسوم البيانية ═══ -->
        <style>
            .dash-charts-row { display:grid; grid-template-columns:2fr 1fr; gap:1.25rem; }
            .dash-bottom-row { display:grid; grid-template-columns:2fr 1fr; gap:1.25rem; }
            @media(max-width:700px) {
                .dash-charts-row { grid-template-columns:1fr; }
                .dash-bottom-row { grid-template-columns:1fr; }
                .dash-stats      { grid-template-columns:1fr 1fr !important; }
            }
        </style>

        <div class="dash-charts-row">

            <!-- منحنى الإيرادات اليومية -->
            <div class="card" wire:ignore>
                <div class="card-header">
                    <span class="card-title" id="dailyChartTitle">📈 إيرادات {{ now()->locale('ar')->isoFormat('MMMM YYYY') }}</span>
                </div>
                <div style="padding:1.25rem; position:relative; height:220px;">
                    <canvas id="dailyRevenueChart"></canvas>
                </div>
            </div>

            <!-- دونات توزيع العيادات -->
            <div class="card" wire:ignore>
                <div class="card-header">
                    <span class="card-title" id="clinicChartTitle">🏥 العيادات — <span id="clinicChartMonth">{{ now()->locale('ar')->isoFormat('MMMM YYYY') }}</span></span>
                </div>
                <div style="padding:1rem; position:relative; height:220px;">
                    <canvas id="clinicDonutChart"></canvas>
                </div>
            </div>

        </div>

        <!-- مقارنة الأشهر -->
        <div class="card" wire:ignore>
            <div class="card-header">
                <span class="card-title">📊 مقارنة الإيرادات — آخر 6 أشهر</span>
                <span style="font-size:0.75rem; color:var(--text-muted); font-weight:600;">اضغط على أي شهر لتحديث الرسوم</span>
            </div>
            <div style="padding:1.25rem; position:relative; height:200px;">
                <canvas id="monthlyCompareChart" style="cursor:pointer;"></canvas>
            </div>
        </div>

        @endif

        <!-- جدول + عيادات + روابط -->
        <div class="dash-bottom-row pg-2col">

            <!-- أحدث كشوف اليوم -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">📋 كشوف اليوم</span>
                    <a href="{{ route('checks.index') }}" wire:navigate style="font-size:0.8rem; color:var(--primary); font-weight:700; text-decoration:none;">عرض الكل ←</a>
                </div>
                <div>
                    @forelse($recentChecks as $check)
                    <div style="padding:0.75rem 1.4rem; border-bottom:1px solid #f0f2f5; display:flex; align-items:center; justify-content:space-between; gap:1rem;"
                        onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                        <div style="display:flex; align-items:center; gap:0.75rem;">
                            <div style="background:#e3f2fd; color:#1565c0; border-radius:8px; padding:0.3rem 0.65rem; font-size:0.8rem; font-weight:800; white-space:nowrap;">
                                {{ $check->rec_time ?: '--:--' }}
                            </div>
                            <div>
                                <div style="font-weight:800; color:var(--navy); font-size:0.9rem;">{{ $check->patient_name ?: 'غير محدد' }}</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">{{ $check->clinic_name ?: '—' }}</div>
                            </div>
                        </div>
                        @if($check->state_id == 1)
                            <span class="badge badge-green">منتهي</span>
                        @else
                            <span class="badge badge-amber">انتظار</span>
                        @endif
                    </div>
                    @empty
                    <div style="padding:3rem 2rem; text-align:center; color:var(--text-muted);">
                        <div style="font-size:2.5rem; margin-bottom:0.5rem; opacity:0.2;">📋</div>
                        <div style="font-weight:800;">لا توجد كشوف اليوم</div>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- يمين: عيادات + روابط -->
            <div style="display:flex; flex-direction:column; gap:1.25rem;">

                <div class="card">
                    <div class="card-header">
                        <span class="card-title">🏥 توزيع العيادات اليوم</span>
                    </div>
                    <div class="card-body" style="padding:1rem;">
                        @forelse($clinicStats as $stat)
                        <div style="display:flex; align-items:center; justify-content:space-between; padding:0.4rem 0; border-bottom:1px solid #f4f6f9;">
                            <span style="font-size:0.85rem; font-weight:700; color:var(--text-dim);">{{ $stat->clinic_name ?: 'غير محدد' }}</span>
                            <span style="background:var(--primary-glow); color:var(--primary); font-size:0.78rem; font-weight:900; padding:0.15rem 0.65rem; border-radius:20px;">{{ $stat->count }}</span>
                        </div>
                        @empty
                        <div style="text-align:center; color:var(--text-muted); padding:1rem; font-size:0.85rem;">لا توجد بيانات</div>
                        @endforelse
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span class="card-title">⚡ وصول سريع</span>
                    </div>
                    <div style="padding:0.75rem;">
                        <a href="{{ route('patients.index') }}" wire:navigate style="display:flex; align-items:center; gap:0.75rem; padding:0.65rem 0.9rem; border-radius:8px; text-decoration:none; color:var(--text-dim); font-weight:700; font-size:0.88rem; transition:all 0.2s;" onmouseover="this.style.background='#fef5f5'; this.style.color='var(--primary)'" onmouseout="this.style.background=''; this.style.color='var(--text-dim)'">
                            <span>👥</span> العملاء
                        </a>
                        <a href="{{ route('finance.invoices') }}" wire:navigate style="display:flex; align-items:center; gap:0.75rem; padding:0.65rem 0.9rem; border-radius:8px; text-decoration:none; color:var(--text-dim); font-weight:700; font-size:0.88rem; transition:all 0.2s;" onmouseover="this.style.background='#fef5f5'; this.style.color='var(--primary)'" onmouseout="this.style.background=''; this.style.color='var(--text-dim)'">
                            <span>🧾</span> الفواتير
                        </a>
                        @if($isAdmin)
                        <a href="{{ route('finance.reports') }}" wire:navigate style="display:flex; align-items:center; gap:0.75rem; padding:0.65rem 0.9rem; border-radius:8px; text-decoration:none; color:var(--text-dim); font-weight:700; font-size:0.88rem; transition:all 0.2s;" onmouseover="this.style.background='#fef5f5'; this.style.color='var(--primary)'" onmouseout="this.style.background=''; this.style.color='var(--text-dim)'">
                            <span>📊</span> التقارير
                        </a>
                        <a href="{{ route('employees.index') }}" wire:navigate style="display:flex; align-items:center; gap:0.75rem; padding:0.65rem 0.9rem; border-radius:8px; text-decoration:none; color:var(--text-dim); font-weight:700; font-size:0.88rem; transition:all 0.2s;" onmouseover="this.style.background='#fef5f5'; this.style.color='var(--primary)'" onmouseout="this.style.background=''; this.style.color='var(--text-dim)'">
                            <span>👨‍⚕️</span> الموظفين
                        </a>
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- شريط سفلي -->
    <div style="background:var(--navy); color:rgba(255,255,255,0.5); padding:0.65rem 1.75rem; font-size:0.78rem; font-weight:700; display:flex; align-items:center; gap:0.5rem; border-top:3px solid var(--gold);">
        <span style="color:var(--gold);">●</span> شركة مركز مطمئنة الكويتية للاستشارات اللغوية &mdash; نظام إدارة المركز
    </div>

    {{-- ── Modal إيرادات اليوم ── --}}
    @if($showTodayRevenueModal)
    <div style="position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;display:flex;align-items:center;justify-content:center;padding:1rem;" wire:click.self="closeTodayRevenueModal">
        <div style="background:#fff;border-radius:14px;width:100%;max-width:820px;max-height:85vh;display:flex;flex-direction:column;box-shadow:0 20px 60px rgba(0,0,0,.25);overflow:hidden;">
            <div style="background:linear-gradient(135deg,#1b5e20,#2e7d32);padding:14px 20px;display:flex;align-items:center;justify-content:space-between;">
                <div style="color:#fff;font-size:1rem;font-weight:900;">💵 تفاصيل إيرادات اليوم — {{ now()->locale('ar')->isoFormat('D MMMM YYYY') }}</div>
                <button wire:click="closeTodayRevenueModal" style="background:rgba(255,255,255,.2);border:none;color:#fff;border-radius:6px;padding:4px 12px;cursor:pointer;font-size:1rem;">✕</button>
            </div>
            <div style="overflow-y:auto;flex:1;">
                @if(empty($todayRevenueDetails))
                    <div style="text-align:center;padding:40px;color:#aaa;font-size:.9rem;">لا توجد إيرادات اليوم</div>
                @else
                <table style="width:100%;border-collapse:collapse;font-family:'Tajawal',sans-serif;font-size:.83rem;border:none;">
                    <thead>
                        <tr style="background:#f7f8fa;position:sticky;top:0;border-bottom:2px solid #e0e0e0;">
                            <th style="padding:10px 12px;text-align:right;color:#555;font-weight:800;border:none;">العميل</th>
                            <th style="padding:10px 12px;text-align:right;color:#555;font-weight:800;border:none;">العيادة</th>
                            <th style="padding:10px 12px;text-align:right;color:#555;font-weight:800;border:none;">الخدمة</th>
                            <th style="padding:10px 12px;text-align:center;color:#555;font-weight:800;border:none;">المبلغ</th>
                            <th style="padding:10px 12px;text-align:center;color:#555;font-weight:800;border:none;">الطريقة</th>
                            <th style="padding:10px 12px;text-align:center;color:#555;font-weight:800;border:none;">الوقت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todayRevenueDetails as $row)
                        <tr style="border-bottom:1px solid #f5f5f5;">
                            <td style="padding:9px 12px;font-weight:700;color:#1a1a2e;">
                                {{ $row['name'] }}
                                @if($row['file_id'])<span style="color:#aaa;font-size:.75rem;"> #{{ $row['file_id'] }}</span>@endif
                            </td>
                            <td style="padding:9px 12px;color:#555;">{{ $row['clinic'] }}</td>
                            <td style="padding:9px 12px;color:#555;">{{ $row['desc'] }}</td>
                            <td style="padding:9px 12px;text-align:center;font-weight:800;color:#1b5e20;font-family:'Inter',sans-serif;">
                                {{ number_format($row['price'], 3) }}
                                @if($row['discount'] > 0)<br><span style="color:#e65100;font-size:.75rem;">-{{ number_format($row['discount'],3) }}</span>@endif
                            </td>
                            <td style="padding:9px 12px;text-align:center;">
                                <span style="background:#e8f5e9;color:#2e7d32;padding:2px 10px;border-radius:20px;font-size:.76rem;font-weight:800;">{{ $row['method'] }}</span>
                            </td>
                            <td style="padding:9px 12px;text-align:center;color:#888;font-size:.78rem;">{{ $row['time'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
            @if(!empty($todayRevenueDetails))
            <div style="background:#f7f8fa;border-top:2px solid #e8e8e8;padding:12px 20px;display:flex;justify-content:flex-end;align-items:center;gap:8px;">
                <span style="font-size:.82rem;font-weight:700;color:#555;">الإجمالي:</span>
                <span style="font-size:1.2rem;font-weight:900;color:#1b5e20;font-family:'Inter',sans-serif;">
                    {{ number_format(array_sum(array_column($todayRevenueDetails,'price')), 3) }} د.ك
                </span>
            </div>
            @endif
        </div>
    </div>
    @endif

</div>
</div>

@if($isAdmin)
<script>
(function(){
    var monthLabels    = @json($chartMonthLabels);
    var monthData      = @json($chartMonthData);
    var allMonthsData  = @json($allMonthsData);

    var selectedIdx = allMonthsData.length - 1; // الشهر الحالي افتراضياً

    var palette = ['#8b1c2b','#1a1a2e','#c8941a','#2e7d32','#1565c0','#6a1b9a','#e65100','#00838f'];

    function destroyChart(id) {
        var c = Chart.getChart(id);
        if (c) c.destroy();
    }

    function getBarColors(activeIdx) {
        return monthData.map(function(_, i) {
            return i === activeIdx ? '#8b1c2b' : 'rgba(139,28,43,0.22)';
        });
    }

    function updateDailyChart(md) {
        var dc = Chart.getChart('dailyRevenueChart');
        if (!dc) return;
        dc.data.labels = md.dailyLabels;
        dc.data.datasets[0].data = md.dailyData;
        dc.update('active');
        var el = document.getElementById('dailyChartTitle');
        if (el) el.textContent = '📈 إيرادات ' + md.label;
    }

    function updateClinicChart(md) {
        var cc = Chart.getChart('clinicDonutChart');
        if (!cc) return;
        cc.data.labels = md.clinicLabels;
        cc.data.datasets[0].data = md.clinicCounts;
        cc.update('active');
        var el = document.getElementById('clinicChartMonth');
        if (el) el.textContent = md.label;
    }

    function updateMonthBarColors(activeIdx) {
        var mc = Chart.getChart('monthlyCompareChart');
        if (!mc) return;
        mc.data.datasets[0].backgroundColor = getBarColors(activeIdx);
        mc.update('none');
    }

    function selectMonth(idx) {
        selectedIdx = idx;
        var md = allMonthsData[idx];
        updateDailyChart(md);
        updateClinicChart(md);
        updateMonthBarColors(idx);
    }

    function initCharts() {
        if (typeof Chart === 'undefined') return;
        if (!document.getElementById('dailyRevenueChart')) return;

        Chart.defaults.font.family = "'Tajawal', sans-serif";
        Chart.defaults.color = '#546e7a';

        var md = allMonthsData[selectedIdx];

        destroyChart('dailyRevenueChart');
        new Chart(document.getElementById('dailyRevenueChart'), {
            type: 'line',
            data: {
                labels: md.dailyLabels,
                datasets: [{
                    label: 'الإيرادات (د.ك)',
                    data: md.dailyData,
                    borderColor: '#8b1c2b',
                    backgroundColor: 'rgba(139,28,43,0.08)',
                    borderWidth: 2.5,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 11 }, maxTicksLimit: 10 } },
                    y: { beginAtZero: true, grid: { color: '#f0f2f5' }, ticks: { font: { size: 11 } } }
                }
            }
        });

        destroyChart('clinicDonutChart');
        new Chart(document.getElementById('clinicDonutChart'), {
            type: 'doughnut',
            data: {
                labels: md.clinicLabels,
                datasets: [{
                    data: md.clinicCounts,
                    backgroundColor: palette,
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 11 }, padding: 8, boxWidth: 12 }
                    }
                }
            }
        });

        destroyChart('monthlyCompareChart');
        new Chart(document.getElementById('monthlyCompareChart'), {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'الإيرادات (د.ك)',
                    data: monthData,
                    backgroundColor: getBarColors(selectedIdx),
                    hoverBackgroundColor: monthData.map(function() { return '#8b1c2b'; }),
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            footer: function() { return 'اضغط لتحديث الرسوم'; }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 12 } } },
                    y: { beginAtZero: true, grid: { color: '#f0f2f5' }, ticks: { font: { size: 11 } } }
                },
                onClick: function(evt, elements) {
                    if (!elements.length) return;
                    selectMonth(elements[0].index);
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initCharts);
    document.addEventListener('livewire:navigated', initCharts);
})();
</script>
@endif

