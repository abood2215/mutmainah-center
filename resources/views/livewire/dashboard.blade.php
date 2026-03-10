<div style="padding:1.5rem 0;">

    <!-- ترحيب -->
    <div style="margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:1.6rem; font-weight:900; color:var(--navy); margin:0;">
                أهلاً بك في مركز مطمئنة 👋
            </h1>
            <div style="font-size:0.88rem; color:var(--text-muted); margin-top:0.25rem; font-weight:600;">
                {{ now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}
            </div>
        </div>
        <a href="{{ route('checks.index') }}" wire:navigate class="btn btn-primary">
            📋 سجل الكشوفات
        </a>
    </div>

    <!-- بطاقات الإحصائيات الرئيسية -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1.25rem; margin-bottom:1.5rem;">

        <!-- إجمالي العملاء -->
        <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
            <div style="width:52px; height:52px; background:rgba(26,26,46,0.08); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; flex-shrink:0;">👥</div>
            <div>
                <div style="font-size:0.78rem; font-weight:700; color:var(--text-muted); margin-bottom:0.2rem;">إجمالي العملاء</div>
                <div style="font-size:2rem; font-weight:900; color:var(--navy); line-height:1;">{{ number_format($totalPatients) }}</div>
            </div>
        </div>

        <!-- كشوف اليوم -->
        <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem; border-right:4px solid var(--primary);">
            <div style="width:52px; height:52px; background:var(--primary-glow); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; flex-shrink:0;">📋</div>
            <div>
                <div style="font-size:0.78rem; font-weight:700; color:var(--text-muted); margin-bottom:0.2rem;">كشوف اليوم</div>
                <div style="font-size:2rem; font-weight:900; color:var(--primary); line-height:1;">{{ $todayChecks }}</div>
                <div style="font-size:0.72rem; color:var(--text-muted); margin-top:0.15rem;">
                    <span style="color:var(--success);">✔ {{ $todayDone }} منتهي</span>
                    &nbsp;·&nbsp;
                    <span style="color:#e65100;">⏳ {{ $todayWaiting }} انتظار</span>
                </div>
            </div>
        </div>

        <!-- إيرادات اليوم -->
        <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem; border-right:4px solid var(--success);">
            <div style="width:52px; height:52px; background:rgba(46,125,50,0.08); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; flex-shrink:0;">💵</div>
            <div>
                <div style="font-size:0.78rem; font-weight:700; color:var(--text-muted); margin-bottom:0.2rem;">إيرادات اليوم</div>
                <div style="font-size:1.7rem; font-weight:900; color:var(--success); line-height:1;">{{ number_format($todayRevenue, 0) }}</div>
                <div style="font-size:0.72rem; color:var(--text-muted);">د.ك</div>
            </div>
        </div>

        <!-- إيرادات الشهر -->
        <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem; border-right:4px solid var(--gold);">
            <div style="width:52px; height:52px; background:rgba(200,148,26,0.1); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; flex-shrink:0;">📈</div>
            <div>
                <div style="font-size:0.78rem; font-weight:700; color:var(--text-muted); margin-bottom:0.2rem;">إيرادات الشهر</div>
                <div style="font-size:1.7rem; font-weight:900; color:var(--gold); line-height:1;">{{ number_format($monthlyRevenue, 0) }}</div>
                <div style="font-size:0.72rem; color:var(--text-muted);">{{ now()->locale('ar')->isoFormat('MMMM YYYY') }}</div>
            </div>
        </div>

    </div>

    <!-- جدول + إحصائيات -->
    <div style="display:grid; grid-template-columns:2fr 1fr; gap:1.25rem;">

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
                        <div style="background:#e3f2fd; color:#1565c0; border-radius:8px; padding:0.3rem 0.65rem; font-size:0.8rem; font-weight:800; white-space:nowrap; font-family:'Inter';">
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

        <!-- إحصائيات العيادات + روابط سريعة -->
        <div style="display:flex; flex-direction:column; gap:1.25rem;">

            <!-- العيادات اليوم -->
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

            <!-- روابط سريعة -->
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
