<div style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1400px; margin:0 auto; animation:fadeIn 0.5s ease;">

<div id="print-area" style="background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow-sm); overflow:hidden;">

    <!-- رأس الإطار -->
    <div style="padding:1rem 1.75rem; border-bottom:1px solid var(--border); background:#fafbfc;">
        <x-print-header title="البيان المالي للعميل" />
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <div style="width:42px; height:42px; background:var(--primary-glow); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">💰</div>
                <div>
                    <h1 style="font-size:1.4rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">البيان المالي للعميل</h1>
                    <div style="font-size:0.78rem; color:var(--text-muted); font-weight:700; margin-top:0.1rem;">{{ $patient->full_name }}</div>
                </div>
            </div>
            <a href="{{ route('patients.index') }}" wire:navigate class="btn btn-secondary no-print">⬅️ العودة للبحث</a>
        </div>
    </div>
 <!-- المحتوى -->
    <!-- المحتوى -->
    <div style="padding:1.75rem;">

        <!-- بطاقات الإحصائيات - صف علوي -->
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1.25rem; margin-bottom:1rem;">
            <div style="background:linear-gradient(135deg,#fff8e1,#fffde7); border:1px solid #ffe082; border-radius:12px; padding:1.25rem 1.5rem;">
                <div style="color:#e65100; font-size:0.78rem; font-weight:800; letter-spacing:1px; margin-bottom:0.5rem;">إجمالي الخدمات | TOTAL SERVICES</div>
                <div style="font-size:1.9rem; font-weight:900; color:#bf360c; font-family:'Inter';">{{ number_format($totalServices, 3) }} <span style="font-size:0.85rem; color:#ff8f00; font-weight:600;">د.ك</span></div>
                @if($totalDirectPaid > 0)
                <div style="font-size:0.72rem; color:#9e5000; margin-top:0.35rem;">منها {{ number_format($totalDirectPaid, 3) }} د.ك مدفوعة نقداً / K-Net</div>
                @endif
            </div>
            <div style="background:linear-gradient(135deg,#e8f5e9,#f1faf2); border:1px solid #c8e6c9; border-radius:12px; padding:1.25rem 1.5rem;">
                <div style="color:#2e7d32; font-size:0.78rem; font-weight:800; letter-spacing:1px; margin-bottom:0.5rem;">إيداعات على الحساب | DEPOSITS</div>
                <div style="font-size:1.9rem; font-weight:900; color:#1b5e20; font-family:'Inter';">{{ number_format($totalDeposited, 3) }} <span style="font-size:0.85rem; color:#4caf50; font-weight:600;">د.ك</span></div>
                <div style="font-size:0.72rem; color:#2e7d32; margin-top:0.35rem;">{{ $deposits->count() }} إيداع مسجل</div>
            </div>
            <div style="background:{{ $balance >= 0 ? 'linear-gradient(135deg,#e3f2fd,#f0f8ff)' : 'linear-gradient(135deg,#ffebee,#fff5f5)' }}; border:1px solid {{ $balance >= 0 ? '#bbdefb' : '#ffcdd2' }}; border-radius:12px; padding:1.25rem 1.5rem;">
                <div style="color:{{ $balance >= 0 ? '#1565c0' : '#c62828' }}; font-size:0.78rem; font-weight:800; letter-spacing:1px; margin-bottom:0.5rem;">الرصيد المتبقي | BALANCE</div>
                <div style="font-size:1.9rem; font-weight:900; color:{{ $balance >= 0 ? '#0d47a1' : '#b71c1c' }}; font-family:'Inter';">
                    {{ number_format(abs($balance), 3) }} <span style="font-size:0.85rem; font-weight:600;">د.ك</span>
                    @if($balance < 0)<span style="font-size:0.8rem; color:#b71c1c; font-weight:800;"> (مديونية)</span>@endif
                    @if($balance == 0)<span style="font-size:0.8rem; color:#388e3c; font-weight:800;"> ✓ مسوّى</span>@endif
                </div>
            </div>
        </div>

        <!-- تفصيل حساب الرصيد -->
        <div style="background:#f8fafc; border:1px solid var(--border); border-radius:12px; padding:1.25rem 1.5rem; margin-bottom:1.75rem; direction:rtl;">
            <div style="font-size:0.8rem; font-weight:900; color:var(--text-dim); letter-spacing:1px; margin-bottom:1rem; text-transform:uppercase;">تفصيل حساب الرصيد</div>
            <div style="display:flex; flex-direction:column; gap:0; max-width:420px;">
                <!-- إجمالي الآجل -->
                <div style="display:flex; justify-content:space-between; align-items:center; padding:0.55rem 0.75rem; background:#fff; border-radius:8px 8px 0 0; border:1px solid #e2e8f0;">
                    <span style="font-size:0.88rem; color:var(--text);">خدمات على الحساب (آجل)</span>
                    <span style="font-weight:900; font-family:'Inter'; color:#c2410c; font-size:0.95rem;">{{ number_format($totalDeferred, 3) }} د.ك</span>
                </div>
                @if($totalDiscount > 0)
                <!-- الخصومات -->
                <div style="display:flex; justify-content:space-between; align-items:center; padding:0.55rem 0.75rem; background:#fdf4ff; border:1px solid #e2e8f0; border-top:none;">
                    <span style="font-size:0.88rem; color:#7b1fa2;">خصومات ممنوحة</span>
                    <span style="font-weight:900; font-family:'Inter'; color:#7b1fa2; font-size:0.95rem;">- {{ number_format($totalDiscount, 3) }} د.ك</span>
                </div>
                <!-- صافي الآجل -->
                <div style="display:flex; justify-content:space-between; align-items:center; padding:0.55rem 0.75rem; background:#fff7ed; border:1px solid #e2e8f0; border-top:none;">
                    <span style="font-size:0.88rem; color:#9a3412; font-weight:700;">صافي المطلوب على الحساب</span>
                    <span style="font-weight:900; font-family:'Inter'; color:#9a3412; font-size:0.95rem;">{{ number_format($totalCharged, 3) }} د.ك</span>
                </div>
                @endif
                <!-- الإيداعات -->
                <div style="display:flex; justify-content:space-between; align-items:center; padding:0.55rem 0.75rem; background:#f0fdf4; border:1px solid #e2e8f0; border-top:none;">
                    <span style="font-size:0.88rem; color:#166534;">إيداعات على الحساب</span>
                    <span style="font-weight:900; font-family:'Inter'; color:#166534; font-size:0.95rem;">- {{ number_format($totalDeposited, 3) }} د.ك</span>
                </div>
                <!-- الرصيد النهائي -->
                <div style="display:flex; justify-content:space-between; align-items:center; padding:0.65rem 0.75rem; background:{{ $balance < 0 ? '#fff0f0' : ($balance == 0 ? '#f0fdf4' : '#eff6ff') }}; border-radius:0 0 8px 8px; border:2px solid {{ $balance < 0 ? '#fca5a5' : ($balance == 0 ? '#86efac' : '#93c5fd') }}; border-top:2px solid {{ $balance < 0 ? '#fca5a5' : ($balance == 0 ? '#86efac' : '#93c5fd') }};">
                    <span style="font-size:0.92rem; font-weight:900; color:{{ $balance < 0 ? '#b91c1c' : ($balance == 0 ? '#15803d' : '#1d4ed8') }};">الرصيد المتبقي</span>
                    <span style="font-weight:900; font-family:'Inter'; color:{{ $balance < 0 ? '#b91c1c' : ($balance == 0 ? '#15803d' : '#1d4ed8') }}; font-size:1.05rem;">
                        {{ number_format(abs($balance), 3) }} د.ك
                        @if($balance < 0) (مديونية)
                        @elseif($balance == 0) ✓
                        @else (رصيد دائن)
                        @endif
                    </span>
                </div>
            </div>
            @if($totalDirectPaid > 0)
            <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.85rem; padding-top:0.75rem; border-top:1px dashed #e2e8f0;">
                * الخدمات المدفوعة مباشرة (نقد / K-Net / فيزا) بمبلغ <strong>{{ number_format($totalDirectPaid, 3) }} د.ك</strong> لا تدخل في حساب الرصيد
            </div>
            @endif
        </div>

        <!-- جدول الإيداعات -->
        @if($deposits->count() > 0)
        <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden; margin-bottom:1.25rem;">
            <div style="padding:0.8rem 1.25rem; border-bottom:1px solid var(--border); background:#f0fdf4; display:flex; align-items:center; justify-content:space-between;">
                <span style="font-weight:800; color:#166534; font-size:0.95rem;">💰 إيداعات على الحساب</span>
                <span style="font-size:0.78rem; color:var(--text-muted); font-weight:700;">الإجمالي: <strong style="color:#166534;">{{ number_format($totalDeposited, 3) }} د.ك</strong></span>
            </div>
            <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif;">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:2px solid var(--border);">
                        <th style="padding:0.7rem 1rem; text-align:center; font-size:0.82rem; font-weight:900; color:var(--text-dim);">سند #</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-size:0.82rem; font-weight:900; color:var(--text-dim);">التاريخ</th>
                        <th style="padding:0.7rem 1rem; text-align:right; font-size:0.82rem; font-weight:900; color:var(--text-dim);">البيان</th>
                        <th style="padding:0.7rem 1rem; text-align:center; font-size:0.82rem; font-weight:900; color:var(--text-dim);">المبلغ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deposits as $dep)
                    <tr style="border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='transparent'">
                        <td style="padding:0.7rem 1rem; text-align:center;"><span style="padding:0.25rem 0.6rem; background:#dcfce7; color:#166534; border-radius:6px; font-weight:900; font-size:0.82rem; border:1px solid #bbf7d0;">#{{ $dep->id }}</span></td>
                        <td style="padding:0.7rem 1rem; text-align:center; color:var(--text-dim); font-size:0.88rem; direction:ltr; unicode-bidi:isolate;">{{ fmt_date($dep->pdate) }}</td>
                        <td style="padding:0.7rem 1rem; color:var(--text);">{{ $dep->pdesc ?: '—' }}</td>
                        <td style="padding:0.7rem 1rem; text-align:center; font-weight:900; color:#166534; font-size:1rem; font-family:'Inter';">{{ number_format($dep->dep_amount, 3) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- جدول الخدمات -->
        <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden;">
            <div style="padding:0.8rem 1.25rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; justify-content:space-between;">
                <span style="font-weight:800; color:var(--text); font-size:0.95rem;">📋 تفاصيل الخدمات</span>
                <span style="font-size:0.78rem; color:var(--text-muted); font-weight:600;">SERVICES DETAIL</span>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif;">
                    <thead>
                        <tr style="background:#f8fafc; border-bottom:2px solid var(--border);">
                            <th style="padding:0.7rem 1rem; text-align:center; font-size:0.82rem; font-weight:900; color:var(--text-dim);">سند #</th>
                            <th style="padding:0.7rem 1rem; text-align:center; font-size:0.82rem; font-weight:900; color:var(--text-dim);">التاريخ</th>
                            <th style="padding:0.7rem 1rem; text-align:right; font-size:0.82rem; font-weight:900; color:var(--text-dim);">البيان</th>
                            <th style="padding:0.7rem 1rem; text-align:center; font-size:0.82rem; font-weight:900; color:var(--text-dim);">الطريقة</th>
                            <th style="padding:0.7rem 1rem; text-align:center; font-size:0.82rem; font-weight:900; color:var(--text-dim);">المبلغ</th>
                            @if($totalDiscount > 0)
                            <th style="padding:0.7rem 1rem; text-align:center; font-size:0.82rem; font-weight:900; color:#7b1fa2;">الخصم</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $svc)
                        @php
                            $methods = [1=>'نقدي',2=>'K-Net',3=>'فيزا',4=>'مجاني',5=>'آجل'];
                            $isDeferred = $svc->payment_method == 5;
                            $isFree = $svc->payment_method == 4 || $svc->price == 0;
                        @endphp
                        <tr style="border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background='transparent'">
                            <td style="padding:0.7rem 1rem; text-align:center;"><span style="padding:0.25rem 0.6rem; background:#e3f2fd; color:#1565c0; border-radius:6px; font-weight:900; font-size:0.82rem; border:1px solid #bbdefb;">#{{ $svc->id }}</span></td>
                            <td style="padding:0.7rem 1rem; text-align:center; color:var(--text-dim); font-size:0.88rem; direction:ltr; unicode-bidi:isolate;">{{ fmt_date($svc->pdate) }}</td>
                            <td style="padding:0.7rem 1rem; color:var(--text); font-size:0.9rem;">{{ trim(preg_replace('/\s+/', ' ', str_replace('*', '', html_entity_decode(strip_tags($svc->pdesc ?? ''))))) ?: '—' }}</td>
                            <td style="padding:0.7rem 1rem; text-align:center;">
                                @if($isFree)
                                    <span class="badge" style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; font-size:0.78rem;">مجاني</span>
                                @elseif($isDeferred)
                                    <span class="badge" style="background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; font-size:0.78rem;">آجل</span>
                                @else
                                    <span class="badge badge-gray" style="font-size:0.78rem;">{{ $methods[$svc->payment_method] ?? 'أخرى' }}</span>
                                @endif
                            </td>
                            <td style="padding:0.7rem 1rem; text-align:center;">
                                @if($isFree)
                                    <span style="color:var(--text-muted); font-size:0.88rem;">مجاني</span>
                                @else
                                    <span style="font-weight:900; color:{{ $isDeferred ? '#c2410c' : '#2e7d32' }}; font-size:1rem; font-family:'Inter';">{{ number_format($svc->price, 3) }}</span>
                                @endif
                            </td>
                            @if($totalDiscount > 0)
                            <td style="padding:0.7rem 1rem; text-align:center;">
                                @if(($svc->discount ?? 0) > 0)
                                    <span style="font-weight:900; color:#7b1fa2; font-size:0.95rem; font-family:'Inter';">- {{ number_format($svc->discount, 3) }}</span>
                                @else
                                    <span style="color:#d1d5db;">—</span>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr><td colspan="{{ $totalDiscount > 0 ? 6 : 5 }}" style="padding:4rem; text-align:center; color:var(--text-muted);">
                            <div style="font-size:2.5rem; opacity:0.2; margin-bottom:0.75rem;">📋</div>
                            لا توجد خدمات مسجلة
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- تذييل الطباعة --}}
        <div class="print-footer" style="display:none; margin-top:1.5rem; text-align:center; font-size:0.72rem; color:#9ca3af; font-family:'Tajawal',sans-serif; border-top:1px solid #e2e8f0; padding-top:0.5rem;">
            تاريخ الطباعة: {{ now()->format('d/m/Y H:i') }} &nbsp;|&nbsp; مركز مطمئنة الاستشاري
        </div>

    </div>
</div>{{-- end #print-area --}}

</div>
</div>
