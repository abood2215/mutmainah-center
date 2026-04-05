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

        <!-- ═══ بطاقات الفروع ═══ -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:1rem;">
            @foreach($branchStats as $branch)
            <div style="background:linear-gradient(135deg, var(--navy) 0%, #252550 100%); border-radius:12px; padding:1.1rem 1.4rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; border:1px solid rgba(200,148,26,0.2);">
                <div>
                    <div style="color:#fbbf24; font-size:0.7rem; font-weight:800; letter-spacing:1px; margin-bottom:0.3rem; font-family:'Tajawal',sans-serif;">🏢 فرع</div>
                    <div style="color:#fff; font-weight:900; font-size:0.88rem; font-family:'Tajawal',sans-serif; line-height:1.35;">{{ $branch->name }}</div>
                </div>
                <div style="text-align:left; flex-shrink:0;">
                    <div style="display:flex; flex-direction:column; align-items:flex-end; gap:0.4rem;">
                        <div style="background:rgba(255,255,255,0.1); border-radius:8px; padding:0.3rem 0.75rem; text-align:center;">
                            <div style="color:rgba(255,255,255,0.6); font-size:0.65rem; font-weight:700;">العملاء</div>
                            <div style="color:#fff; font-weight:900; font-size:1.1rem; font-family:'Inter';">{{ number_format($branch->patients_count) }}</div>
                        </div>
                        <div style="background:rgba(200,148,26,0.15); border-radius:8px; padding:0.3rem 0.75rem; text-align:center; border:1px solid rgba(200,148,26,0.3);">
                            <div style="color:rgba(200,148,26,0.8); font-size:0.65rem; font-weight:700;">إيرادات الشهر</div>
                            <div style="color:#fbbf24; font-weight:900; font-size:1rem; font-family:'Inter';">{{ number_format($branch->monthly_revenue, 0) }} <span style="font-size:0.7rem;">د.ك</span></div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

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

        </div>

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
            </div>
            <div style="padding:1.25rem; position:relative; height:200px;">
                <canvas id="monthlyCompareChart"></canvas>
            </div>
        </div>

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
                        <a href="{{ route('finance.reports') }}" wire:navigate style="display:flex; align-items:center; gap:0.75rem; padding:0.65rem 0.9rem; border-radius:8px; text-decoration:none; color:var(--text-dim); font-weight:700; font-size:0.88rem; transition:all 0.2s;" onmouseover="this.style.background='#fef5f5'; this.style.color='var(--primary)'" onmouseout="this.style.background=''; this.style.color='var(--text-dim)'">
                            <span>📊</span> التقارير
                        </a>
                        <a href="{{ route('employees.index') }}" wire:navigate style="display:flex; align-items:center; gap:0.75rem; padding:0.65rem 0.9rem; border-radius:8px; text-decoration:none; color:var(--text-dim); font-weight:700; font-size:0.88rem; transition:all 0.2s;" onmouseover="this.style.background='#fef5f5'; this.style.color='var(--primary)'" onmouseout="this.style.background=''; this.style.color='var(--text-dim)'">
                            <span>👨‍⚕️</span> الموظفين
                        </a>
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

<script>
(function(){
    var dailyLabels  = @json($chartDailyLabels);
    var dailyData    = @json($chartDailyData);
    var monthLabels  = @json($chartMonthLabels);
    var monthData    = @json($chartMonthData);
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
        new Chart(document.getElementById('monthlyCompareChart'), {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'الإيرادات (د.ك)',
                    data: monthData,
                    backgroundColor: monthData.map(function(_, i){ return i === monthData.length - 1 ? '#8b1c2b' : 'rgba(139,28,43,0.25)'; }),
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 12 } } },
                    y: { beginAtZero: true, grid: { color: '#f0f2f5' }, ticks: { font: { size: 11 } } }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initCharts);
    document.addEventListener('livewire:navigated', initCharts);
})();
</script>
