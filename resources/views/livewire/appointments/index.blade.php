<div>
<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1400px; margin:0 auto; animation:fadeIn 0.4s ease;">

    <!-- رأس الصفحة -->
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
        <div>
            <h1 style="font-size:1.5rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">جدول المواعيد</h1>
            <div style="font-size:0.83rem; color:var(--text-muted); margin-top:0.2rem; font-weight:600;">
                {{ now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}
            </div>
        </div>
        <a href="{{ route('appointments.book') }}" wire:navigate
            style="display:inline-flex; align-items:center; gap:0.5rem; background:var(--primary); color:#fff; padding:0.65rem 1.5rem; border-radius:10px; font-weight:800; font-size:0.9rem; text-decoration:none; transition:opacity 0.2s;"
            onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
            ＋ حجز موعد جديد
        </a>
    </div>

    <!-- إحصائيات سريعة -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
        <div style="background:#fff; border:1px solid var(--border); border-radius:12px; padding:1rem 1.25rem; display:flex; align-items:center; gap:1rem; box-shadow:0 1px 4px rgba(0,0,0,0.05);">
            <div style="width:44px; height:44px; background:#fef3c7; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0;">📋</div>
            <div>
                <div style="font-size:1.8rem; font-weight:900; color:var(--navy); line-height:1;">{{ $appointments->total() }}</div>
                <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; margin-top:0.1rem;">إجمالي المواعيد</div>
            </div>
        </div>
        <div wire:click="openTodayModal"
            style="background:#fff; border:1px solid var(--border); border-radius:12px; padding:1rem 1.25rem; display:flex; align-items:center; gap:1rem; box-shadow:0 1px 4px rgba(0,0,0,0.05); cursor:pointer; transition:all 0.2s;"
            onmouseover="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 4px 14px rgba(139,28,43,0.12)'"
            onmouseout="this.style.borderColor='var(--border)'; this.style.boxShadow='0 1px 4px rgba(0,0,0,0.05)'">
            <div style="width:44px; height:44px; background:var(--primary-glow); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0;">🏥</div>
            <div>
                <div style="font-size:1.8rem; font-weight:900; color:var(--primary); line-height:1;">{{ $todayCount }}</div>
                <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; margin-top:0.1rem;">مواعيد اليوم ← اضغط للتفاصيل</div>
            </div>
        </div>
        <div style="background:#fff; border:1px solid var(--border); border-radius:12px; padding:1rem 1.25rem; display:flex; align-items:center; gap:1rem; box-shadow:0 1px 4px rgba(0,0,0,0.05);">
            <div style="width:44px; height:44px; background:#dcfce7; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0;">✅</div>
            <div>
                <div style="font-size:1.8rem; font-weight:900; color:var(--navy); line-height:1;">{{ $doneCount }}</div>
                <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; margin-top:0.1rem;">منتهية اليوم</div>
            </div>
        </div>
        <div style="background:#fff; border:1px solid var(--border); border-radius:12px; padding:1rem 1.25rem; display:flex; align-items:center; gap:1rem; box-shadow:0 1px 4px rgba(0,0,0,0.05);">
            <div style="width:44px; height:44px; background:#ede9fe; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0;">🏢</div>
            <div>
                <div style="font-size:1.8rem; font-weight:900; color:var(--navy); line-height:1;">{{ $clinics->count() }}</div>
                <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; margin-top:0.1rem;">العيادات</div>
            </div>
        </div>
    </div>

    <!-- الجدول الرئيسي -->
    <div style="background:#fff; border:1px solid var(--border); border-radius:14px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden;">

        <!-- فلاتر -->
        <div style="padding:1rem 1.25rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center;">
            <div style="position:relative; flex:1; min-width:220px;">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="بحث باسم العميل..."
                    class="form-input" style="padding-right:2.6rem;">
                <span style="position:absolute; right:0.85rem; top:50%; transform:translateY(-50%); opacity:0.35; font-size:1rem;">🔍</span>
            </div>
            <select wire:model.live="selectedClinic" class="form-input" style="width:210px;">
                <option value="">جميع العيادات</option>
                @foreach($clinics as $clinic)
                    <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                @endforeach
            </select>
            <input type="date" wire:model.live="filterDate" class="form-input" style="width:165px;">
            @if($search || $selectedClinic || $filterDate)
            <button wire:click="$set('search',''); $set('selectedClinic',''); $set('filterDate','')"
                style="padding:0.55rem 1rem; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#dc2626; font-size:0.82rem; font-weight:800; cursor:pointer;">
                ✕ مسح
            </button>
            @endif
        </div>

        <!-- الجدول -->
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f1f5f9; border-bottom:2px solid var(--border);">
                        <th style="padding:0.75rem 1.25rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim); text-transform:uppercase; letter-spacing:0.5px; white-space:nowrap;">#</th>
                        <th style="padding:0.75rem 1rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim); text-transform:uppercase; letter-spacing:0.5px;">الوقت والتاريخ</th>
                        <th style="padding:0.75rem 1rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim); text-transform:uppercase; letter-spacing:0.5px;">العميل</th>
                        <th style="padding:0.75rem 1rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim); text-transform:uppercase; letter-spacing:0.5px;">العيادة</th>
                        <th style="padding:0.75rem 1rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim); text-transform:uppercase; letter-spacing:0.5px;">الحجز بواسطة</th>
                        <th style="padding:0.75rem 1rem; text-align:center; font-size:0.78rem; font-weight:800; color:var(--text-dim); text-transform:uppercase; letter-spacing:0.5px;">الحالة</th>
                        <th style="padding:0.75rem 1rem; text-align:center; font-size:0.78rem; font-weight:800; color:var(--text-dim); text-transform:uppercase; letter-spacing:0.5px;">تذكير واتساب</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $i => $app)
                    <tr style="border-bottom:1px solid #f1f5f9; transition:background 0.15s;"
                        onmouseover="this.style.background='#fef9f9'"
                        onmouseout="this.style.background='transparent'">
                        <td style="padding:0.85rem 1.25rem; font-size:0.8rem; color:var(--text-muted); font-weight:700;">
                            {{ $appointments->firstItem() + $loop->index }}
                        </td>
                        <td style="padding:0.85rem 1rem; white-space:nowrap;">
                            <div style="font-size:1rem; font-weight:900; color:#1565c0; direction:ltr; display:inline-block;">
                                {{ $app->rec_time ? preg_replace('/^(\d+):(\d)$/', '$1:0$2', $app->rec_time) : '--:--' }}
                            </div>
                            <div style="font-size:0.75rem; color:var(--text-muted); font-weight:600; margin-top:0.1rem;">
                                {{ fmt_date($app->rec_date) }}
                            </div>
                        </td>
                        <td style="padding:0.85rem 1rem;">
                            <div style="font-size:0.95rem; font-weight:800; color:var(--navy);">{{ $app->patient_name ?: '—' }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted); font-weight:600;">#{{ $app->id }}</div>
                        </td>
                        <td style="padding:0.85rem 1rem;">
                            <div style="font-size:0.88rem; font-weight:700; color:var(--text-dim);">{{ $app->clinic_name ?: '—' }}</div>
                        </td>
                        <td style="padding:0.85rem 1rem;">
                            <div style="font-size:0.82rem; font-weight:700; color:#1e40af; background:#eff6ff; padding:0.2rem 0.6rem; border-radius:6px; display:inline-block; max-width:160px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $app->booked_by }}">
                                {{ $app->booked_by ?: '—' }}
                            </div>
                        </td>
                        <td style="padding:0.85rem 1rem; text-align:center;">
                            @if($app->status == 1)
                                <span style="display:inline-block; background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; padding:0.25rem 0.85rem; border-radius:20px; font-size:0.78rem; font-weight:800;">✓ تم الكشف</span>
                            @else
                                <span style="display:inline-block; background:#fffbeb; color:#b45309; border:1px solid #fde68a; padding:0.25rem 0.85rem; border-radius:20px; font-size:0.78rem; font-weight:800;">محجوز</span>
                            @endif
                        </td>
                        <td style="padding:0.85rem 1rem; text-align:center;">
                            @if($app->patient_phone)
                                @php
                                    $phone = preg_replace('/[^0-9]/', '', $app->patient_phone);
                                    if (str_starts_with($phone, '0')) {
                                        $phone = '965' . substr($phone, 1);
                                    } elseif (!str_starts_with($phone, '965')) {
                                        $phone = '965' . $phone;
                                    }
                                    $time = $app->rec_time ? preg_replace('/^(\d+):(\d)$/', '$1:0$2', $app->rec_time) : '';
                                    $date = $app->rec_date ?? '';
                                    $name = $app->patient_name ?? '';
                                    $clinic = $app->clinic_name ?? '';
                                @endphp
                                <button
                                    onclick="sendWhatsAppFinalV4('{{ $phone }}', '{{ addslashes($name) }}', '{{ $date }}', '{{ $time }}', '{{ addslashes($clinic) }}')"
                                    style="display:inline-flex; align-items:center; gap:0.35rem; background:#25d366; color:#fff; padding:0.4rem 0.9rem; border-radius:8px; font-size:0.78rem; font-weight:800; text-decoration:none; transition:all 0.2s; border:none; cursor:pointer; white-space:nowrap; font-family:'Tajawal',sans-serif;"
                                    onmouseover="this.style.background='#1da851'; this.style.transform='scale(1.05)'"
                                    onmouseout="this.style.background='#25d366'; this.style.transform='scale(1)'">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="white" style="flex-shrink:0;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    تذكير
                                </button>
                            @else
                                <span style="color:var(--text-muted); font-size:0.75rem; opacity:0.5;">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding:5rem 2rem; text-align:center; color:var(--text-muted);">
                            <div style="font-size:2.5rem; margin-bottom:0.75rem; opacity:0.2;">🗓️</div>
                            <div style="font-weight:800; font-size:0.95rem;">لا توجد مواعيد</div>
                            @if($search || $selectedClinic || $filterDate)
                            <div style="font-size:0.82rem; margin-top:0.4rem;">جرب تغيير فلاتر البحث</div>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($appointments->hasPages())
        @php
            $cur   = $appointments->currentPage();
            $last  = $appointments->lastPage();
            $start = max(1, $cur - 3);
            $end   = min($last, $cur + 3);
        @endphp
        <div style="background:#1a1a2e; padding:0.55rem 1rem; display:flex; align-items:center; justify-content:center; gap:0.25rem; flex-wrap:wrap; border-top:2px solid var(--gold);">
            @if($appointments->onFirstPage())
                <span style="padding:0.3rem 0.75rem; color:rgba(255,255,255,0.3); font-size:0.82rem; font-weight:700;">السابق</span>
            @else
                <button wire:click="previousPage" style="padding:0.3rem 0.75rem; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.25); border-radius:4px; color:#fff; cursor:pointer; font-size:0.82rem; font-weight:700; font-family:'Tajawal',sans-serif;" onmouseover="this.style.background='rgba(255,255,255,0.22)'" onmouseout="this.style.background='rgba(255,255,255,0.12)'">السابق</button>
            @endif

            @if($start > 1)
                <button wire:click="gotoPage(1)" style="padding:0.3rem 0.6rem; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); border-radius:4px; color:#fff; cursor:pointer; font-size:0.82rem; min-width:30px; font-family:'Tajawal',sans-serif;" onmouseover="this.style.background='rgba(255,255,255,0.22)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">1</button>
                @if($start > 2)<span style="color:rgba(255,255,255,0.4); font-size:0.85rem; padding:0 2px;">…</span>@endif
            @endif

            @for($p = $start; $p <= $end; $p++)
                @if($p == $cur)
                    <span style="padding:0.3rem 0.6rem; background:#c8941a; border-radius:4px; color:#fff; font-weight:900; font-size:0.82rem; min-width:30px; text-align:center;">{{ $p }}</span>
                @else
                    <button wire:click="gotoPage({{ $p }})" style="padding:0.3rem 0.6rem; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); border-radius:4px; color:#fff; cursor:pointer; font-size:0.82rem; min-width:30px; font-family:'Tajawal',sans-serif;" onmouseover="this.style.background='rgba(255,255,255,0.22)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">{{ $p }}</button>
                @endif
            @endfor

            @if($end < $last)
                @if($end < $last - 1)<span style="color:rgba(255,255,255,0.4); font-size:0.85rem; padding:0 2px;">…</span>@endif
                <button wire:click="gotoPage({{ $last }})" style="padding:0.3rem 0.6rem; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); border-radius:4px; color:#fff; cursor:pointer; font-size:0.82rem; min-width:30px; font-family:'Tajawal',sans-serif;" onmouseover="this.style.background='rgba(255,255,255,0.22)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">{{ $last }}</button>
            @endif

            @if($appointments->hasMorePages())
                <button wire:click="nextPage" style="padding:0.3rem 0.75rem; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.25); border-radius:4px; color:#fff; cursor:pointer; font-size:0.82rem; font-weight:700; font-family:'Tajawal',sans-serif;" onmouseover="this.style.background='rgba(255,255,255,0.22)'" onmouseout="this.style.background='rgba(255,255,255,0.12)'">التالي</button>
            @else
                <span style="padding:0.3rem 0.75rem; color:rgba(255,255,255,0.3); font-size:0.82rem; font-weight:700;">التالي</span>
            @endif
        </div>
        @endif

    </div>

</div>
</div>

@if($showTodayModal)
<div wire:click.self="closeTodayModal"
    style="position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.55); display:flex; align-items:center; justify-content:center; padding:1rem; animation:fadeIn 0.2s ease;">

    <div style="background:#fff; border-radius:16px; width:100%; max-width:640px; box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow:hidden; animation:slideUp 0.25s cubic-bezier(0.16,1,0.3,1);">

        <!-- رأس -->
        <div style="background:var(--navy); padding:1.1rem 1.5rem; display:flex; align-items:center; justify-content:space-between; border-bottom:3px solid var(--primary);">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <div style="width:36px; height:36px; background:var(--primary-glow); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1.2rem;">🏥</div>
                <div>
                    <div style="color:#fff; font-weight:900; font-size:1rem;">مواعيد اليوم</div>
                    <div style="color:rgba(255,255,255,0.5); font-size:0.75rem;">{{ now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}</div>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <span style="background:var(--primary-glow); color:var(--primary); font-size:0.78rem; font-weight:900; padding:0.25rem 0.75rem; border-radius:20px; border:1px solid rgba(139,28,43,0.2);">
                    {{ count($todayList) }} موعد
                </span>
                <button wire:click="closeTodayModal"
                    style="width:32px; height:32px; border-radius:8px; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15); color:rgba(255,255,255,0.7); font-size:1rem; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                    ✕
                </button>
            </div>
        </div>

        <!-- القائمة -->
        <div style="max-height:480px; overflow-y:auto;">
            @forelse($todayList as $i => $w)
            <div style="display:flex; align-items:center; gap:0.9rem; padding:0.9rem 1.4rem; border-bottom:1px solid #f1f5f9; transition:background 0.15s;"
                onmouseover="this.style.background='#fef9f9'"
                onmouseout="this.style.background=''">

                {{-- رقم كبير --}}
                <div style="width:44px; height:44px; background:var(--navy); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; font-weight:900; color:#fff; flex-shrink:0; font-family:'Inter',sans-serif;">
                    {{ $i + 1 }}
                </div>

                {{-- الوقت --}}
                <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:0.3rem 0.7rem; font-size:0.88rem; font-weight:900; color:#92400e; white-space:nowrap; flex-shrink:0; direction:ltr; font-family:'Inter',sans-serif;">
                    {{ $w->rec_time ? preg_replace('/^(\d+):(\d)$/', '$1:0$2', $w->rec_time) : '--:--' }}
                </div>

                {{-- بيانات العميل --}}
                <div style="flex:1; min-width:0;">
                    <div style="font-weight:800; color:var(--navy); font-size:0.95rem;">
                        {{ $w->full_name ?: 'غير محدد' }}
                        @if($w->file_id)
                        <span style="font-size:0.72rem; color:#1565c0; font-weight:800; background:#e3f2fd; padding:0.1rem 0.5rem; border-radius:5px; margin-right:4px;">#{{ $w->file_id }}</span>
                        @endif
                    </div>
                    <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.2rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
                        @if($w->clinic_name)<span>🏥 {{ $w->clinic_name }}</span>@endif
                        @if($w->phone)<span>📞 {{ $w->phone }}</span>@endif
                    </div>
                </div>

                {{-- الحالة --}}
                @if($w->state_id == 1)
                    <span style="background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; padding:0.2rem 0.65rem; border-radius:20px; font-size:0.72rem; font-weight:800; flex-shrink:0;">✓ تم الكشف</span>
                @else
                    <span style="background:#fffbeb; color:#b45309; border:1px solid #fde68a; padding:0.2rem 0.65rem; border-radius:20px; font-size:0.72rem; font-weight:800; flex-shrink:0;">انتظار</span>
                @endif

                {{-- زر إلغاء الموعد --}}
                @if($w->state_id != 1)
                <button wire:click="cancelAppointment({{ $w->id }})"
                    wire:confirm="إلغاء موعد {{ $w->full_name }}؟ لا يمكن التراجع."
                    style="width:30px; height:30px; background:#fff0f0; border:1.5px solid #fca5a5; border-radius:7px; color:#dc2626; font-size:1rem; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:all .2s;"
                    onmouseover="this.style.background='#dc2626'; this.style.color='#fff'; this.style.borderColor='#dc2626'"
                    onmouseout="this.style.background='#fff0f0'; this.style.color='#dc2626'; this.style.borderColor='#fca5a5'"
                    title="إلغاء الموعد">
                    ✕
                </button>
                @endif
            </div>
            @empty
            <div style="padding:3rem; text-align:center; color:var(--text-muted);">
                <div style="font-size:2.5rem; opacity:0.15; margin-bottom:0.5rem;">📅</div>
                <div style="font-weight:800;">لا توجد مواعيد اليوم</div>
            </div>
            @endforelse
        </div>

        <!-- ذيل -->
        <div style="padding:0.85rem 1.5rem; background:#f8fafc; border-top:1px solid var(--border); display:flex; align-items:center; justify-content:space-between;">
            <a href="{{ route('appointments.book') }}" wire:navigate
                style="font-size:0.85rem; color:var(--primary); font-weight:800; text-decoration:none;">
                ＋ حجز موعد جديد
            </a>
            <button wire:click="closeTodayModal"
                style="background:var(--navy); color:#fff; border:none; border-radius:8px; padding:0.55rem 1.75rem; font-family:'Tajawal',sans-serif; font-weight:800; font-size:0.88rem; cursor:pointer;">
                إغلاق
            </button>
        </div>
    </div>
</div>

<style>
@keyframes slideUp {
    from { opacity:0; transform:translateY(20px) scale(0.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
</style>
@endif

<script>
function sendWhatsAppFinalV4(phone, name, date, time, clinic) {
    var firstName = name.trim().split(/\s+/)[0] || name;
    var clinicShort = clinic.trim().split(/\s+/).slice(0, 2).join(' ') || clinic;
    var e = encodeURIComponent;

    // أكواد ثابتة لا تتأثر بترميز الملف نهائياً
    var rose = "%F0%9F%8C%B9"; 
    var heart = "%F0%9F%92%9B";
    var hospital = "%F0%9F%8F%A5";
    var calendar = "%F0%9F%93%85";
    var clock = "%F0%9F%95%90";
    var nl = "%0A";
    
    // بناء الرسالة باستخدام التشفير اليدوي الصافي
    var text = e('\u0627\u0644\u0633\u0644\u0627\u0645 \u0639\u0644\u064A\u0643\u0645 ') + e(firstName) + ' ' + rose + nl
             + e('\u0647\u0630\u0627 \u062A\u0630\u0643\u064A\u0631 \u0628\u0645\u0648\u0639\u062F\u0643\u0645 \u0641\u064A \u0645\u0631\u0643\u0632 \u0645\u0637\u0645\u0626\u0646\u0629 \u0644\u0644\u0627\u0633\u062A\u0634\u0627\u0631\u0627\u062A \u0627\u0644\u0644\u063A\u0648\u064A\u0629 \u0648\u0627\u0644\u062A\u0631\u0628\u0648\u064A\u0629') + nl
             + hospital + ' ' + e('\u0627\u0644\u0639\u064A\u0627\u062F\u0629: ') + e(clinicShort) + nl
             + calendar + ' ' + e('\u0627\u0644\u062A\u0627\u0631\u064A\u062E: ') + e(date) + nl
             + clock + ' ' + e('\u0627\u0644\u0648\u0642\u062A: ') + e(time) + nl
             + e('\u0646\u062A\u0637\u0644\u0639 \u0644\u0632\u064A\u0627\u0631\u062A\u0643\u0645\u060C \u0648\u0641\u064A \u062D\u0627\u0644 \u0627\u0644\u0631\u063A\u0628\u0629 \u0628\u0627\u0644\u062A\u0639\u062F\u064A\u0644 \u0623\u0648 \u0627\u0644\u0625\u0644\u063A\u0627\u0621 \u0646\u0631\u062C\u0648 \u0627\u0644\u062A\u0648\u0627\u0635\u0644 \u0645\u0639\u0646\u0627.') + nl
             + e('\u0634\u0643\u0631\u0627\u064B \u0644\u0643\u0645 ') + heart;

    window.open('https://api.whatsapp.com/send?phone=' + phone + '&text=' + text, '_blank');
}
</script>

</div>{{-- end root --}}
