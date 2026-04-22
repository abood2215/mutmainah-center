<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1400px; margin:0 auto;">

    {{-- رأس الصفحة --}}
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="width:44px; height:44px; background:var(--primary-glow); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">🏢</div>
            <div>
                <h1 style="font-size:1.4rem; font-weight:900; color:var(--primary); margin:0;">تقرير مقارنة الفروع</h1>
                <div style="font-size:0.8rem; color:var(--text-muted); font-weight:600; margin-top:0.15rem;">{{ now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}</div>
            </div>
        </div>
        <button onclick="window.print()" style="padding:0.55rem 1.25rem; background:var(--navy); color:#fff; border:none; border-radius:8px; font-family:'Tajawal',sans-serif; font-weight:800; font-size:0.88rem; cursor:pointer; display:flex; align-items:center; gap:0.5rem;">
            🖨️ طباعة
        </button>
    </div>

    {{-- فلتر التاريخ --}}
    <div style="background:#fff; border:1px solid var(--border); border-radius:12px; padding:1rem 1.25rem; margin-bottom:1.5rem; display:flex; gap:1rem; align-items:center; flex-wrap:wrap;">
        <div style="display:flex; align-items:center; gap:0.5rem;">
            <span style="font-size:0.82rem; font-weight:800; color:var(--text-dim); white-space:nowrap;">من:</span>
            <input type="date" wire:model.live="fromDate" style="padding:0.5rem 0.75rem; border:1.5px solid var(--border); border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.88rem; outline:none;">
        </div>
        <div style="display:flex; align-items:center; gap:0.5rem;">
            <span style="font-size:0.82rem; font-weight:800; color:var(--text-dim); white-space:nowrap;">إلى:</span>
            <input type="date" wire:model.live="toDate" style="padding:0.5rem 0.75rem; border:1.5px solid var(--border); border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.88rem; outline:none;">
        </div>
        <div style="font-size:0.82rem; color:var(--text-muted); font-weight:600;">
            {{ count($dateRange) }} يوم
        </div>
    </div>

    {{-- بطاقات الفروع --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:1.5rem; margin-bottom:1.5rem;">
        @foreach($stats as $i => $branch)
        @php
            $colors = [
                0 => ['bg'=>'#8b1c2b','light'=>'#fff5f6','border'=>'#fecaca','text'=>'#8b1c2b'],
                1 => ['bg'=>'#1a1a2e','light'=>'#f0f0ff','border'=>'#c7d2fe','text'=>'#1a1a2e'],
            ];
            $c = $colors[$i] ?? $colors[0];
            $pct = $totalRevenue > 0 ? round(($branch->revenue / $totalRevenue) * 100, 1) : 0;
        @endphp
        <div style="background:#fff; border:1px solid var(--border); border-radius:16px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.06);">

            {{-- رأس البطاقة --}}
            <div style="background:{{ $c['bg'] }}; padding:1.1rem 1.5rem; display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <div style="color:#fff; font-weight:900; font-size:1rem; max-width:220px; line-height:1.3;">{{ $branch->name }}</div>
                </div>
                <div style="background:rgba(255,255,255,0.15); border-radius:10px; padding:0.4rem 0.85rem; text-align:center;">
                    <div style="color:#fff; font-size:1.4rem; font-weight:900; line-height:1;">{{ $pct }}%</div>
                    <div style="color:rgba(255,255,255,0.7); font-size:0.65rem; font-weight:700;">من الإجمالي</div>
                </div>
            </div>

            {{-- شريط النسبة --}}
            <div style="height:5px; background:#e2e8f0;">
                <div style="height:100%; background:{{ $c['bg'] }}; width:{{ $pct }}%; transition:width 0.8s ease;"></div>
            </div>

            {{-- الإحصائيات --}}
            <div style="padding:1.25rem 1.5rem; display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div style="background:{{ $c['light'] }}; border:1px solid {{ $c['border'] }}; border-radius:10px; padding:0.85rem; text-align:center;">
                    <div style="font-size:1.5rem; font-weight:900; color:{{ $c['text'] }};">{{ number_format($branch->revenue, 3) }}</div>
                    <div style="font-size:0.7rem; color:var(--text-muted); font-weight:700; margin-top:0.2rem;">الإيرادات (د.ك)</div>
                </div>
                <div style="background:#f8fafc; border:1px solid var(--border); border-radius:10px; padding:0.85rem; text-align:center;">
                    <div style="font-size:1.5rem; font-weight:900; color:var(--navy);">{{ number_format($branch->checks) }}</div>
                    <div style="font-size:0.7rem; color:var(--text-muted); font-weight:700; margin-top:0.2rem;">الكشوفات</div>
                </div>
                <div style="background:#f8fafc; border:1px solid var(--border); border-radius:10px; padding:0.85rem; text-align:center;">
                    <div style="font-size:1.5rem; font-weight:900; color:#059669;">{{ number_format($branch->patients) }}</div>
                    <div style="font-size:0.7rem; color:var(--text-muted); font-weight:700; margin-top:0.2rem;">إجمالي العملاء</div>
                </div>
                <div style="background:#f8fafc; border:1px solid var(--border); border-radius:10px; padding:0.85rem; text-align:center;">
                    <div style="font-size:1.5rem; font-weight:900; color:#d97706;">{{ number_format($branch->appointments) }}</div>
                    <div style="font-size:0.7rem; color:var(--text-muted); font-weight:700; margin-top:0.2rem;">المواعيد</div>
                </div>
            </div>

            {{-- متوسط الكشف --}}
            @if($branch->checks > 0)
            <div style="margin:0 1.5rem; padding:0.65rem 1rem; background:#fafbfc; border:1px solid var(--border); border-radius:8px; display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                <span style="font-size:0.8rem; font-weight:700; color:var(--text-dim);">متوسط الكشف الواحد</span>
                <span style="font-size:0.95rem; font-weight:900; color:{{ $c['text'] }};">{{ number_format($branch->revenue / $branch->checks, 3) }} د.ك</span>
            </div>
            @endif

            {{-- أعلى المكاتب --}}
            @if($branch->topClinics->count())
            <div style="padding:0 1.5rem 1.25rem;">
                <div style="font-size:0.78rem; font-weight:800; color:var(--text-dim); margin-bottom:0.6rem; text-transform:uppercase; letter-spacing:0.5px;">أعلى المكاتب إيراداً</div>
                @foreach($branch->topClinics as $j => $clinic)
                <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom:0.4rem;">
                    <span style="width:20px; height:20px; background:{{ $c['bg'] }}; color:#fff; border-radius:4px; font-size:0.68rem; font-weight:900; display:flex; align-items:center; justify-content:center; flex-shrink:0;">{{ $j+1 }}</span>
                    <div style="flex:1; min-width:0;">
                        <div style="font-size:0.8rem; font-weight:700; color:var(--navy); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $clinic->name }}</div>
                        <div style="height:4px; background:#e2e8f0; border-radius:2px; margin-top:3px;">
                            @php $maxClinic = $branch->topClinics->first()->total ?? 1; @endphp
                            <div style="height:100%; background:{{ $c['bg'] }}; width:{{ round(($clinic->total/$maxClinic)*100) }}%; border-radius:2px;"></div>
                        </div>
                    </div>
                    <span style="font-size:0.78rem; font-weight:800; color:{{ $c['text'] }}; white-space:nowrap; flex-shrink:0;">{{ number_format($clinic->total, 3) }}</span>
                </div>
                @endforeach
            </div>
            @endif

        </div>
        @endforeach
    </div>

    {{-- جدول المقارنة الإجمالية --}}
    <div style="background:#fff; border:1px solid var(--border); border-radius:16px; overflow:hidden; margin-bottom:1.5rem;">
        <div style="background:var(--navy); padding:0.85rem 1.5rem; border-bottom:3px solid var(--gold);">
            <span style="color:#fff; font-weight:900; font-size:1rem;">📊 جدول المقارنة</span>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:2px solid var(--border);">
                        <th style="padding:0.85rem 1.25rem; text-align:right; font-size:0.8rem; font-weight:900; color:var(--text-dim);">الفرع</th>
                        <th style="padding:0.85rem 1rem; text-align:center; font-size:0.8rem; font-weight:900; color:var(--text-dim);">الإيرادات</th>
                        <th style="padding:0.85rem 1rem; text-align:center; font-size:0.8rem; font-weight:900; color:var(--text-dim);">الكشوفات</th>
                        <th style="padding:0.85rem 1rem; text-align:center; font-size:0.8rem; font-weight:900; color:var(--text-dim);">المواعيد</th>
                        <th style="padding:0.85rem 1rem; text-align:center; font-size:0.8rem; font-weight:900; color:var(--text-dim);">العملاء</th>
                        <th style="padding:0.85rem 1rem; text-align:center; font-size:0.8rem; font-weight:900; color:var(--text-dim);">النسبة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stats as $branch)
                    @php $pct = $totalRevenue > 0 ? round(($branch->revenue / $totalRevenue)*100,1) : 0; @endphp
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:1rem 1.25rem; font-weight:900; color:var(--navy); font-size:0.9rem;">{{ $branch->name }}</td>
                        <td style="padding:1rem; text-align:center; font-weight:900; color:var(--primary); font-size:1rem;">{{ number_format($branch->revenue, 3) }} <span style="font-size:0.65rem; opacity:0.6;">د.ك</span></td>
                        <td style="padding:1rem; text-align:center; font-weight:800; color:var(--navy);">{{ number_format($branch->checks) }}</td>
                        <td style="padding:1rem; text-align:center; font-weight:800; color:#d97706;">{{ number_format($branch->appointments) }}</td>
                        <td style="padding:1rem; text-align:center; font-weight:800; color:#059669;">{{ number_format($branch->patients) }}</td>
                        <td style="padding:1rem; text-align:center;">
                            <span style="background:var(--primary-glow); color:var(--primary); font-size:0.8rem; font-weight:900; padding:0.2rem 0.75rem; border-radius:20px;">{{ $pct }}%</span>
                        </td>
                    </tr>
                    @endforeach
                    {{-- صف الإجماليات --}}
                    <tr style="background:#fafbfc; border-top:2px solid var(--border);">
                        <td style="padding:1rem 1.25rem; font-weight:900; color:var(--navy);">الإجمالي</td>
                        <td style="padding:1rem; text-align:center; font-weight:900; color:var(--primary); font-size:1.1rem;">{{ number_format($totalRevenue, 3) }} <span style="font-size:0.65rem; opacity:0.6;">د.ك</span></td>
                        <td style="padding:1rem; text-align:center; font-weight:900; color:var(--navy);">{{ number_format($totalChecks) }}</td>
                        <td style="padding:1rem; text-align:center; font-weight:900; color:#d97706;">{{ number_format($totalAppointments) }}</td>
                        <td style="padding:1rem; text-align:center; font-weight:900; color:#059669;">{{ number_format($stats->sum('patients')) }}</td>
                        <td style="padding:1rem; text-align:center; font-weight:900;">100%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

@push('scripts')
<style>
@media print {
    body { background:#fff !important; }
    .pg-outer { padding:0 !important; }
    button { display:none !important; }
    input[type="date"] { border:none !important; }
    nav, header, footer { display:none !important; }
    [wire\:click], [wire\:model] { pointer-events:none; }
}
</style>
@endpush
