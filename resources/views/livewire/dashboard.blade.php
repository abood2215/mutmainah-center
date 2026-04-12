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
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:1rem;">
            @foreach($branchStats as $branch)
            @php
                $isThird = str_contains($branch->name, 'الثالث');
                $isSixth = str_contains($branch->name, 'السادس');
                if ($isThird) {
                    $bg        = 'linear-gradient(135deg, #9d174d 0%, #be185d 60%, #831843 100%)';
                    $border    = 'rgba(251,113,133,0.35)';
                    $tagColor  = '#fda4af';
                    $revColor  = '#fce7f3';
                } elseif ($isSixth) {
                    $bg        = 'linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 60%, #1e40af 100%)';
                    $border    = 'rgba(147,197,253,0.35)';
                    $tagColor  = '#93c5fd';
                    $revColor  = '#dbeafe';
                } else {
                    $bg        = 'linear-gradient(135deg, var(--navy) 0%, #252550 100%)';
                    $border    = 'rgba(200,148,26,0.25)';
                    $tagColor  = '#fbbf24';
                    $revColor  = '#fde68a';
                }
            @endphp
            <div style="background:{{ $bg }}; border-radius:14px; padding:1.5rem 1.8rem; display:flex; align-items:center; justify-content:space-between; gap:1.2rem; border:1px solid {{ $border }}; box-shadow:0 6px 24px rgba(0,0,0,0.22);">
                <div style="flex:1; min-width:0; display:flex; align-items:center; gap:1rem;">
                    @if($isThird || $isSixth)
                    <div style="font-size:5rem; font-weight:900; color:rgba(255,255,255,0.15); font-family:'Inter',sans-serif; line-height:1; flex-shrink:0; user-select:none;">
                        {{ $isThird ? '3' : '6' }}
                    </div>
                    @endif
                    <div>
                        <div style="color:{{ $tagColor }}; font-size:0.68rem; font-weight:800; letter-spacing:1px; margin-bottom:0.6rem; font-family:'Tajawal',sans-serif;">🏢 فرع</div>
                        <div style="color:#fff; font-weight:900; font-size:1.05rem; font-family:'Tajawal',sans-serif; line-height:1.4;">{{ $branch->name }}</div>
                    </div>
                </div>
                <div style="flex-shrink:0; display:flex; flex-direction:column; align-items:flex-end; gap:0.6rem;">
                    <div style="background:rgba(255,255,255,0.13); border:1px solid rgba(255,255,255,0.18); border-radius:10px; padding:0.55rem 1.1rem; text-align:center; min-width:100px;">
                        <div style="color:rgba(255,255,255,0.55); font-size:0.62rem; font-weight:800; letter-spacing:.5px; margin-bottom:3px;">العملاء</div>
                        <div style="color:#fff; font-weight:900; font-size:2.2rem; font-family:'Inter'; line-height:1;">{{ number_format($branch->patients_count) }}</div>
                    </div>
                    <div style="background:rgba(255,255,255,0.13); border:1px solid rgba(255,255,255,0.18); border-radius:10px; padding:0.55rem 1.1rem; text-align:center; min-width:100px;">
                        <div style="color:rgba(255,255,255,0.55); font-size:0.62rem; font-weight:800; letter-spacing:.5px; margin-bottom:3px;">إيرادات الشهر</div>
                        <div style="color:{{ $revColor }}; font-weight:900; font-size:1.7rem; font-family:'Inter'; line-height:1;">{{ number_format($branch->monthly_revenue, 0) }}<span style="font-size:0.72rem; opacity:.75; margin-right:3px;">د.ك</span></div>
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

        {{-- مودال توزيع عيادات الشهر --}}
        @if($showMonthModal)
        <div style="position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:9999; display:flex; align-items:center; justify-content:center; padding:1rem;"
             wire:click.self="closeMonthModal">
            <div style="background:#fff; border-radius:14px; width:100%; max-width:520px; box-shadow:0 20px 60px rgba(0,0,0,0.25); overflow:hidden; animation:fadeIn .2s ease;">
                <div style="background:var(--navy); padding:1rem 1.4rem; display:flex; align-items:center; justify-content:space-between;">
                    <div>
                        <div style="color:rgba(255,255,255,.6); font-size:.7rem; font-weight:800; letter-spacing:1px; margin-bottom:3px;">توزيع العيادات</div>
                        <div style="color:#fff; font-weight:900; font-size:1rem; font-family:'Tajawal',sans-serif;">🏥 {{ $monthModalLabel }}</div>
                    </div>
                    <button wire:click="closeMonthModal" style="background:rgba(255,255,255,.15); border:none; color:#fff; border-radius:8px; width:32px; height:32px; font-size:1.1rem; cursor:pointer; display:flex; align-items:center; justify-content:center;">&times;</button>
                </div>
                <div style="padding:1.25rem;">
                    @if(count($monthModalClinics) > 0)
                    @php $maxCount = max(array_column($monthModalClinics, 'count')); @endphp
                    @foreach($monthModalClinics as $row)
                    @php $pct = $maxCount > 0 ? round(($row->count / $maxCount) * 100) : 0; @endphp
                    <div style="margin-bottom:10px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                            <span style="font-size:.84rem; font-weight:700; color:var(--navy);">{{ $row->clinic_name ?: 'غير محدد' }}</span>
                            <span style="font-size:.84rem; font-weight:900; color:var(--primary);">{{ $row->count }} كشف</span>
                        </div>
                        <div style="height:7px; background:#f0f2f5; border-radius:4px; overflow:hidden;">
                            <div style="height:100%; width:{{ $pct }}%; background:var(--primary); border-radius:4px; transition:width .4s ease;"></div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div style="text-align:center; color:var(--text-muted); padding:2rem; font-size:.9rem;">لا توجد بيانات لهذا الشهر</div>
                    @endif
                </div>
                <div style="padding:0.75rem 1.4rem; border-top:1px solid #f0f2f5; text-align:center;">
                    <button wire:click="closeMonthModal" style="background:#f4f6f9; border:1px solid #e2e8f0; border-radius:8px; padding:7px 24px; font-family:'Tajawal',sans-serif; font-size:.85rem; font-weight:800; color:#555; cursor:pointer;">إغلاق</button>
                </div>
            </div>
        </div>
        @endif

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
    document.addEventListener('livewire:updated', function() {
        // أعد رسم الـ charts بعد أي تحديث Livewire (مثل فتح/إغلاق المودال)
        if (document.getElementById('dailyRevenueChart')) {
            initCharts();
        }
    });
})();
</script>
@endif
