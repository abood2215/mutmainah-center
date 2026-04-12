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
                $floorNum = $isThird ? '3' : ($isSixth ? '6' : '');
                if ($isThird) {
                    $bg1 = '#9d174d'; $bg2 = '#db2777';
                    $accent = '#f9a8d4'; $accentDim = 'rgba(249,168,212,0.15)';
                } elseif ($isSixth) {
                    $bg1 = '#1e3a8a'; $bg2 = '#2563eb';
                    $accent = '#93c5fd'; $accentDim = 'rgba(147,197,253,0.15)';
                } else {
                    $bg1 = '#1a1a2e'; $bg2 = '#252550';
                    $accent = '#fbbf24'; $accentDim = 'rgba(251,191,36,0.15)';
                }
            @endphp
            <div style="background:linear-gradient(145deg, {{ $bg1 }} 0%, {{ $bg2 }} 100%); border-radius:16px; overflow:hidden; box-shadow:0 8px 32px rgba(0,0,0,0.2); position:relative;">

                {{-- رقم الدور الخلفي الزخرفي --}}
                @if($floorNum)
                <div style="position:absolute; left:-10px; top:50%; transform:translateY(-50%); font-size:9rem; font-weight:900; color:rgba(255,255,255,0.06); font-family:'Inter',sans-serif; line-height:1; user-select:none; pointer-events:none;">
                    {{ $floorNum }}
                </div>
                @endif

                {{-- خط علوي ملون --}}
                <div style="height:3px; background:linear-gradient(90deg, {{ $accent }}, transparent);"></div>

                <div style="padding:1.4rem 1.6rem; display:flex; align-items:stretch; gap:1rem; position:relative;">

                    {{-- رقم الدور البارز --}}
                    @if($floorNum)
                    <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; background:{{ $accentDim }}; border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:0.5rem 1rem; flex-shrink:0; min-width:56px;">
                        <div style="font-size:2.8rem; font-weight:900; color:{{ $accent }}; font-family:'Inter',sans-serif; line-height:1;">{{ $floorNum }}</div>
                        <div style="font-size:0.58rem; color:rgba(255,255,255,0.5); font-weight:700; letter-spacing:1px; margin-top:2px;">FLOOR</div>
                    </div>
                    @endif

                    {{-- اسم الفرع + الإحصائيات --}}
                    <div style="flex:1; min-width:0; display:flex; flex-direction:column; justify-content:space-between; gap:0.75rem;">
                        <div>
                            <div style="color:{{ $accent }}; font-size:0.65rem; font-weight:800; letter-spacing:1.5px; margin-bottom:0.3rem; opacity:0.85;">BRANCH · فرع</div>
                            <div style="color:#fff; font-weight:900; font-size:0.95rem; font-family:'Tajawal',sans-serif; line-height:1.35;">{{ $branch->name }}</div>
                        </div>

                        <div style="display:flex; gap:0.6rem;">
                            <div style="flex:1; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:0.6rem 0.8rem; text-align:center;">
                                <div style="color:rgba(255,255,255,0.5); font-size:0.6rem; font-weight:800; letter-spacing:1px; margin-bottom:4px;">العملاء</div>
                                <div style="color:#fff; font-weight:900; font-size:1.75rem; font-family:'Inter',sans-serif; line-height:1;">{{ number_format($branch->patients_count) }}</div>
                            </div>
                            <div style="flex:1; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:0.6rem 0.8rem; text-align:center;">
                                <div style="color:rgba(255,255,255,0.5); font-size:0.6rem; font-weight:800; letter-spacing:1px; margin-bottom:4px;">إيرادات الشهر</div>
                                <div style="color:{{ $accent }}; font-weight:900; font-size:1.45rem; font-family:'Inter',sans-serif; line-height:1;">{{ number_format($branch->monthly_revenue, 0) }}<span style="font-size:0.65rem; opacity:0.7; margin-right:2px;">د.ك</span></div>
                            </div>
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
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem; border-right:4px solid var(--success);">
                <div style="width:52px; height:52px; background:rgba(46,125,50,0.08); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; flex-shrink:0;">💵</div>
                <div>
                    <div style="font-size:0.78rem; font-weight:700; color:var(--text-muted); margin-bottom:0.2rem;">إيرادات اليوم</div>
                    <div style="font-size:1.7rem; font-weight:900; color:var(--success); line-height:1;">{{ number_format($todayRevenue, 0) }}</div>
                    <div style="font-size:0.72rem; color:var(--text-muted);">د.ك</div>
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
            <div class="card">
                <div class="card-header">
                    <span class="card-title">📈 إيرادات {{ now()->locale('ar')->isoFormat('MMMM YYYY') }}</span>
                </div>
                <div style="padding:1.25rem; position:relative; height:220px;">
                    <canvas id="dailyRevenueChart"></canvas>
                </div>
            </div>

            <!-- دونات توزيع العيادات -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">🏥 العيادات هذا الشهر</span>
                </div>
                <div style="padding:1rem; position:relative; height:220px;">
                    <canvas id="clinicDonutChart"></canvas>
                </div>
            </div>

        </div>

        <!-- مقارنة الأشهر -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">📊 مقارنة الإيرادات — آخر 6 أشهر</span>
                <span style="font-size:0.75rem; color:var(--text-muted); font-weight:600;">اضغط على أي شهر لعرض توزيع العيادات</span>
            </div>
            <div style="padding:1.25rem; position:relative; height:200px;">
                <canvas id="monthlyCompareChart" style="cursor:pointer;"></canvas>
            </div>
        </div>

        @endif

        {{-- مودال توزيع عيادات الشهر — خارج layout --}}
        @teleport('body')
        @if($showMonthModal)
        <div id="month-modal-backdrop"
             style="position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:99999; display:flex; align-items:center; justify-content:center; padding:1rem; font-family:'Tajawal',sans-serif;"
             wire:click.self="closeMonthModal">
            <div style="background:#fff; border-radius:14px; width:100%; max-width:520px; max-height:90vh; display:flex; flex-direction:column; box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow:hidden; animation:fadeIn .2s ease;">

                {{-- رأس --}}
                <div style="background:var(--navy); padding:1rem 1.4rem; display:flex; align-items:center; justify-content:space-between; flex-shrink:0;">
                    <div>
                        <div style="color:rgba(255,255,255,.6); font-size:.7rem; font-weight:800; letter-spacing:1px; margin-bottom:3px;">توزيع العيادات</div>
                        <div style="color:#fff; font-weight:900; font-size:1rem;">🏥 {{ $monthModalLabel }}</div>
                    </div>
                    <button wire:click="closeMonthModal"
                        style="background:rgba(255,255,255,.15); border:none; color:#fff; border-radius:8px; width:34px; height:34px; font-size:1.2rem; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;">&times;</button>
                </div>

                {{-- محتوى قابل للتمرير --}}
                <div style="padding:1.25rem; overflow-y:auto; flex:1;">
                    @if(count($monthModalClinics) > 0)
                    @php $maxCount = max(array_column($monthModalClinics, 'count')); @endphp
                    @foreach($monthModalClinics as $row)
                    @php $pct = $maxCount > 0 ? round(($row->count / $maxCount) * 100) : 0; @endphp
                    <div style="margin-bottom:12px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:5px;">
                            <span style="font-size:.84rem; font-weight:700; color:var(--navy); flex:1; padding-left:8px;">{{ $row->clinic_name ?: 'غير محدد' }}</span>
                            <span style="font-size:.84rem; font-weight:900; color:var(--primary); white-space:nowrap;">{{ $row->count }} كشف</span>
                        </div>
                        <div style="height:8px; background:#f0f2f5; border-radius:4px; overflow:hidden;">
                            <div style="height:100%; width:{{ $pct }}%; background:var(--primary); border-radius:4px;"></div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div style="text-align:center; color:var(--text-muted); padding:2rem; font-size:.9rem;">لا توجد بيانات لهذا الشهر</div>
                    @endif
                </div>

                {{-- ذيل --}}
                <div style="padding:0.75rem 1.4rem; border-top:1px solid #f0f2f5; text-align:center; flex-shrink:0;">
                    <button wire:click="closeMonthModal"
                        style="background:var(--navy); color:#fff; border:none; border-radius:8px; padding:8px 28px; font-family:'Tajawal',sans-serif; font-size:.85rem; font-weight:800; cursor:pointer;">إغلاق</button>
                </div>
            </div>
        </div>
        @endif
        @endteleport

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
        <span style="color:var(--gold);">●</span> مركز مطمئنة الاستشاري &mdash; نظام إدارة المركز
    </div>

</div>
</div>

@if($isAdmin)
<script>
(function(){
    var dailyLabels  = @json($chartDailyLabels);
    var dailyData    = @json($chartDailyData);
    var monthLabels  = @json($chartMonthLabels);
    var monthData    = @json($chartMonthData);
    var monthKeys    = @json($chartMonthKeys);
    var clinicLabels = @json($clinicChartData->pluck('clinic_name'));
    var clinicCounts = @json($clinicChartData->pluck('count'));

    function destroyChart(id) {
        var existing = Chart.getChart(id);
        if (existing) existing.destroy();
    }

    function initCharts() {
        if (typeof Chart === 'undefined') return;
        if (!document.getElementById('dailyRevenueChart')) return;

        Chart.defaults.font.family = "'Tajawal', sans-serif";
        Chart.defaults.color = '#546e7a';

        var palette = ['#8b1c2b','#1a1a2e','#c8941a','#2e7d32','#1565c0','#6a1b9a','#e65100','#00838f'];

        destroyChart('dailyRevenueChart');
        new Chart(document.getElementById('dailyRevenueChart'), {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'الإيرادات (د.ك)',
                    data: dailyData,
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
                labels: clinicLabels,
                datasets: [{
                    data: clinicCounts,
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
        var monthChart = new Chart(document.getElementById('monthlyCompareChart'), {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'الإيرادات (د.ك)',
                    data: monthData,
                    backgroundColor: monthData.map(function(_, i){ return i === monthData.length - 1 ? '#8b1c2b' : 'rgba(139,28,43,0.25)'; }),
                    hoverBackgroundColor: monthData.map(function(){ return '#8b1c2b'; }),
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
                            footer: function() { return 'اضغط لعرض توزيع العيادات'; }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 12 } } },
                    y: { beginAtZero: true, grid: { color: '#f0f2f5' }, ticks: { font: { size: 11 } } }
                },
                onClick: function(evt, elements) {
                    if (!elements.length) return;
                    var idx = elements[0].index;
                    var key = monthKeys[idx];
                    if (!key) return;
                    @this.call('loadMonthClinics', key.year, key.month, key.label);
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initCharts);
    document.addEventListener('livewire:navigated', initCharts);

    // أعد رسم الـ charts فقط إذا كانت الـ canvas موجودة ولا تحتوي chart
    document.addEventListener('livewire:updated', function() {
        var canvas = document.getElementById('dailyRevenueChart');
        if (!canvas) return;
        var existing = Chart.getChart('dailyRevenueChart');
        if (!existing) {
            initCharts();
        }
    });
})();
</script>
@endif
