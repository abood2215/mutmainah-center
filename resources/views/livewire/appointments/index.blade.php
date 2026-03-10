<div>
<div style="min-height:80vh; padding:1.5rem 2rem;">
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
                        <th style="padding:0.75rem 1rem; text-align:center; font-size:0.78rem; font-weight:800; color:var(--text-dim); text-transform:uppercase; letter-spacing:0.5px;">الحالة</th>
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
                        <td style="padding:0.85rem 1rem; text-align:center;">
                            @if($app->status == 1)
                                <span style="display:inline-block; background:#f1f5f9; color:#64748b; padding:0.25rem 0.85rem; border-radius:20px; font-size:0.78rem; font-weight:800;">منتهي</span>
                            @else
                                <span style="display:inline-block; background:#fffbeb; color:#b45309; border:1px solid #fde68a; padding:0.25rem 0.85rem; border-radius:20px; font-size:0.78rem; font-weight:800;">محجوز</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:5rem 2rem; text-align:center; color:var(--text-muted);">
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
        <div style="padding:0.85rem 1.25rem; border-top:1px solid var(--border); background:#fafbfc;">
            <div class="custom-pagination">{{ $appointments->links() }}</div>
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
        <div style="max-height:440px; overflow-y:auto;">
            @forelse($todayList as $i => $w)
            <div style="display:flex; align-items:center; gap:0.85rem; padding:0.85rem 1.5rem; border-bottom:1px solid #f1f5f9; transition:background 0.15s;"
                onmouseover="this.style.background='#fef9f9'"
                onmouseout="this.style.background=''">

                <div style="width:26px; height:26px; background:#f1f5f9; border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:0.72rem; font-weight:900; color:var(--text-muted); flex-shrink:0;">
                    {{ $i + 1 }}
                </div>

                <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:0.28rem 0.65rem; font-size:0.82rem; font-weight:900; color:#92400e; white-space:nowrap; flex-shrink:0; direction:ltr;">
                    {{ $w->rec_time ? preg_replace('/^(\d+):(\d)$/', '$1:0$2', $w->rec_time) : '--:--' }}
                </div>

                <div style="flex:1; min-width:0;">
                    <div style="font-weight:800; color:var(--navy); font-size:0.93rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $w->full_name ?: 'غير محدد' }}
                    </div>
                    <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.1rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
                        @if($w->clinic_name)<span>🏥 {{ $w->clinic_name }}</span>@endif
                        @if($w->phone)<span>📞 {{ $w->phone }}</span>@endif
                    </div>
                </div>

                @if($w->file_id)
                <div style="font-size:0.75rem; color:#1565c0; font-weight:800; background:#e3f2fd; padding:0.2rem 0.6rem; border-radius:6px; white-space:nowrap; flex-shrink:0;">
                    #{{ $w->file_id }}
                </div>
                @endif

                <span style="background:#fffbeb; color:#b45309; border:1px solid #fde68a; padding:0.2rem 0.65rem; border-radius:20px; font-size:0.72rem; font-weight:800; flex-shrink:0;">محجوز</span>
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

</div>{{-- end root --}}
