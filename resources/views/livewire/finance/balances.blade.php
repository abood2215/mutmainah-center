<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1100px; margin:0 auto; animation:fadeIn 0.5s ease;">

    <!-- رأس الصفحة -->
    <div style="background:#fff; border:1px solid var(--border); border-radius:16px; overflow:hidden; box-shadow:var(--shadow-sm); margin-bottom:1.25rem;">
        <div style="padding:1.1rem 1.5rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <div style="width:42px; height:42px; background:#f0fdf4; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">💰</div>
                <div>
                    <h1 style="font-size:1.3rem; font-weight:900; color:#166534; margin:0;">أرصدة العملاء</h1>
                    <div style="font-size:0.78rem; color:var(--text-muted); font-weight:600; margin-top:0.1rem;">الأرصدة الدائنة والمديونيات</div>
                </div>
            </div>
            <!-- إجمالي الأرصدة -->
            <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:0.6rem 1.25rem; text-align:center;">
                <div style="font-size:0.65rem; font-weight:800; color:#166534; margin-bottom:2px;">إجمالي الأرصدة</div>
                <div style="font-size:1.3rem; font-weight:900; color:#15803d; font-family:'Inter',sans-serif;">
                    {{ number_format($totalBalance, 3) }} <span style="font-size:0.7rem; font-weight:700;">د.ك</span>
                </div>
            </div>
        </div>

        <!-- بحث -->
        <div style="padding:0.85rem 1.5rem; background:#fff; display:flex; align-items:center; gap:0.75rem;">
            <div style="position:relative; flex:1; max-width:380px;">
                <span style="position:absolute; right:0.75rem; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:0.9rem;">🔍</span>
                <input type="text"
                    wire:model.live.debounce.350ms="search"
                    placeholder="بحث بالاسم أو رقم الملف أو الجوال..."
                    class="form-input"
                    style="padding-right:2.25rem; width:100%;">
            </div>
            <div style="font-size:0.82rem; color:var(--text-muted); font-weight:700;">
                {{ $rows->total() }} عميل
            </div>
        </div>
    </div>

    <!-- الجدول -->
    <div style="background:#fff; border:1px solid var(--border); border-radius:16px; overflow:hidden; box-shadow:var(--shadow-sm);">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif;">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:2px solid var(--border);">
                        <th style="padding:0.75rem 1.25rem; text-align:right; font-size:0.8rem; font-weight:900; color:var(--text-dim);">العميل</th>
                        <th style="padding:0.75rem 1rem; text-align:center; font-size:0.8rem; font-weight:900; color:var(--text-dim);">الجوال</th>
                        <th style="padding:0.75rem 1rem; text-align:center; font-size:0.8rem; font-weight:900; color:#166534;">إجمالي الإيداعات</th>
                        <th style="padding:0.75rem 1rem; text-align:center; font-size:0.8rem; font-weight:900; color:#9a3412;">المسحوب</th>
                        <th style="padding:0.75rem 1rem; text-align:center; font-size:0.8rem; font-weight:900; color:#1d4ed8;">الرصيد المتبقي</th>
                        <th style="padding:0.75rem 1rem; text-align:center; font-size:0.8rem; font-weight:900; color:var(--text-dim);"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $r)
                    <tr style="border-bottom:1px solid #f0f2f5;"
                        onmouseover="this.style.background='#f0fdf4'"
                        onmouseout="this.style.background=''">
                        <td style="padding:0.75rem 1.25rem;">
                            <div style="font-weight:800; color:var(--navy); font-size:0.95rem;">{{ $r->full_name }}</div>
                            <div style="font-size:0.72rem; color:#1565c0; font-weight:700; margin-top:2px; font-family:'Inter',sans-serif;">#{{ $r->file_id }}</div>
                        </td>
                        <td style="padding:0.75rem 1rem; text-align:center; color:var(--text-dim); font-size:0.88rem;">
                            {{ $r->phone ?: '—' }}
                        </td>
                        <td style="padding:0.75rem 1rem; text-align:center; font-family:'Inter',sans-serif; font-size:0.9rem; color:#166534; font-weight:700;">
                            {{ number_format($r->deposited, 3) }}
                        </td>
                        <td style="padding:0.75rem 1rem; text-align:center; font-family:'Inter',sans-serif; font-size:0.9rem; color:#9a3412; font-weight:700;">
                            @php $totalCharged = ($r->charged_svc ?? 0) + ($r->charged_old ?? 0); @endphp
                            {{ $totalCharged > 0 ? number_format($totalCharged, 3) : '—' }}
                        </td>
                        <td style="padding:0.75rem 1rem; text-align:center;">
                            @if($r->balance > 0)
                            <span style="background:#f0fdf4; color:#15803d; font-weight:900; font-size:1rem; font-family:'Inter',sans-serif; padding:4px 14px; border-radius:20px; border:1px solid #bbf7d0; white-space:nowrap;">
                                {{ number_format($r->balance, 3) }} <span style="font-size:0.65rem; opacity:0.8;">د.ك</span>
                            </span>
                            @else
                            <span style="background:#fef2f2; color:#b91c1c; font-weight:900; font-size:1rem; font-family:'Inter',sans-serif; padding:4px 14px; border-radius:20px; border:1px solid #fecaca; white-space:nowrap;">
                                {{ number_format($r->balance, 3) }} <span style="font-size:0.65rem; opacity:0.8;">د.ك</span>
                            </span>
                            @endif
                        </td>
                        <td style="padding:0.75rem 1rem; text-align:center;">
                            <a href="{{ route('patients.financial-statement', $r->id) }}" wire:navigate
                               style="font-size:0.78rem; font-weight:800; color:var(--primary); text-decoration:none; padding:4px 10px; border:1px solid var(--primary); border-radius:6px; white-space:nowrap;"
                               onmouseover="this.style.background='var(--primary)'; this.style.color='#fff'"
                               onmouseout="this.style.background=''; this.style.color='var(--primary)'">
                                البيان المالي
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding:4rem; text-align:center; color:var(--text-muted);">
                            <div style="font-size:2.5rem; opacity:0.2; margin-bottom:0.75rem;">💰</div>
                            <div style="font-weight:800;">
                                @if($search)
                                    لا توجد نتائج للبحث "{{ $search }}"
                                @else
                                    لا يوجد عملاء برصيد متبقٍ
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-pg-nav :paginator="$rows" />
    </div>

</div>
</div>
