<div>
<div class="card" style="padding:0; overflow:hidden;">

    {{-- Header --}}
    <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
        <div>
            <h2 style="font-size:1.15rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">سجل النشاط الشامل</h2>
            <div style="font-size:0.78rem; color:var(--text-muted); margin-top:0.2rem;">{{ number_format($logs->total()) }} سجل</div>
        </div>
        <button wire:click="resetFilters" class="btn" style="font-size:0.82rem; padding:0.4rem 1rem; background:#f4f6f9; color:#374151; border:1px solid #e5e7eb;">
            ↺ مسح الفلاتر
        </button>
    </div>

    {{-- التبويبات --}}
    @php
        $tabs = [
            'all'          => ['label' => 'الكل',             'icon' => '📋', 'count' => $counts->total],
            'patients'     => ['label' => 'ملفات العملاء',    'icon' => '👤', 'count' => $counts->patients],
            'checks'       => ['label' => 'الكشوف',           'icon' => '🧾', 'count' => $counts->checks],
            'appointments' => ['label' => 'المواعيد',          'icon' => '📅', 'count' => $counts->appointments],
            'payments'     => ['label' => 'المدفوعات',         'icon' => '💰', 'count' => $counts->payments],
            'auth'         => ['label' => 'الدخول للنظام',    'icon' => '🔐', 'count' => $counts->auth],
            'attachments'  => ['label' => 'المرفقات',          'icon' => '📎', 'count' => $counts->attachments],
        ];
    @endphp
    <div style="padding:0.85rem 1.5rem; background:#f9fafb; border-bottom:1px solid var(--border); display:flex; gap:0.4rem; flex-wrap:wrap;">
        @foreach($tabs as $key => $tab)
        <button wire:click="switchTab('{{ $key }}')"
                style="padding:0.38rem 0.9rem; border-radius:8px; font-size:0.8rem; font-weight:800;
                       font-family:'Tajawal',sans-serif; cursor:pointer; border:1.5px solid; transition:all 0.15s;
                       {{ $this->tab === $key
                           ? 'background:var(--primary); color:#fff; border-color:var(--primary); box-shadow:0 2px 8px rgba(139,28,43,0.2);'
                           : 'background:#fff; color:#6b7280; border-color:#e5e7eb;' }}">
            {{ $tab['icon'] }} {{ $tab['label'] }}
            <span style="{{ $this->tab === $key ? 'opacity:0.8;' : 'color:#9ca3af;' }} font-size:0.72rem;">
                ({{ number_format($tab['count']) }})
            </span>
        </button>
        @endforeach
    </div>

    {{-- فلاتر --}}
    <div style="padding:0.85rem 1.5rem; border-bottom:1px solid var(--border); display:flex; gap:0.65rem; flex-wrap:wrap; background:#fff;">
        <div style="position:relative; flex:1; min-width:200px;">
            <span style="position:absolute; right:0.75rem; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:0.9rem; pointer-events:none;">🔍</span>
            <input wire:model.live.debounce.300ms="search"
                   type="text" placeholder="بحث في الوصف أو المستخدم أو العميل..."
                   class="form-input" style="padding-right:2.2rem; width:100%; box-sizing:border-box;">
        </div>
        <input wire:model.live="filterDate" type="date" class="form-input" style="width:155px;">
        <select wire:model.live="filterUser" class="form-input" style="width:155px;">
            <option value="">جميع المستخدمين</option>
            @foreach($users as $u)
                <option value="{{ $u }}">{{ $u }}</option>
            @endforeach
        </select>
    </div>

    {{-- الجدول --}}
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif;">
            <thead>
                <tr style="background:var(--navy); color:#fff; font-size:0.8rem;">
                    <th style="padding:0.75rem 1rem; text-align:right; font-weight:700; width:55px;">#</th>
                    <th style="padding:0.75rem 1rem; text-align:right; font-weight:700; width:130px;">الحدث</th>
                    <th style="padding:0.75rem 1rem; text-align:right; font-weight:700;">الوصف</th>
                    <th style="padding:0.75rem 1rem; text-align:right; font-weight:700; width:170px;">العميل</th>
                    <th style="padding:0.75rem 1rem; text-align:right; font-weight:700; width:120px;">بواسطة</th>
                    <th style="padding:0.75rem 1rem; text-align:right; font-weight:700; width:130px;">التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                @php
                    [$icon, $color, $actionLabel] = match($log->action) {
                        'created'       => [$log->subject === 'patient' ? '👤' : '📋', '#2e7d32', 'إنشاء'],
                        'updated'       => ['✏️',  '#1565c0', 'تعديل'],
                        'deleted'       => ['🗑️',  '#dc2626', 'حذف'],
                        'uploaded'      => ['📎',  '#0891b2', 'رفع ملف'],
                        'receipt'       => ['💰',  '#166534', 'قبض'],
                        'payment'       => ['💸',  '#b45309', 'صرف'],
                        'cancelled'     => $log->subject === 'payment'
                                            ? ['🚫', '#dc2626', 'إلغاء سند']
                                            : ['❌', '#dc2626', 'إلغاء'],
                        'booked'        => ['📅',  '#1d4ed8', 'حجز موعد'],
                        'discount'      => ['🏷️',  '#7c3aed', 'خصم'],
                        'voided'        => ['🚫',  '#dc2626', 'إلغاء فاتورة'],
                        'sent'          => ['📨',  '#0891b2', 'إرسال'],
                        'login_success' => ['✅',  '#166534', 'دخول ناجح'],
                        'login_failed'  => ['⛔',  '#dc2626', 'دخول فاشل'],
                        default         => ['•',   '#546e7a', $log->action],
                    };
                @endphp
                <tr style="border-bottom:1px solid #f4f6f9;"
                    onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">

                    <td style="padding:0.7rem 1rem; font-size:0.75rem; color:#c0c0c0; font-weight:600;">
                        {{ $log->id }}
                    </td>

                    <td style="padding:0.7rem 1rem;">
                        <span style="display:inline-flex; align-items:center; gap:0.3rem;
                                     background:{{ $color }}12; color:{{ $color }};
                                     border:1px solid {{ $color }}30; padding:0.25rem 0.65rem;
                                     border-radius:6px; font-size:0.75rem; font-weight:800; white-space:nowrap;">
                            {{ $icon }} {{ $actionLabel }}
                        </span>
                    </td>

                    <td style="padding:0.7rem 1rem; font-size:0.82rem; color:#1f2937; font-weight:500; max-width:320px; word-break:break-word; line-height:1.5;">
                        {{ $log->description }}
                    </td>

                    <td style="padding:0.7rem 1rem; font-size:0.82rem;">
                        @if($log->patient_name)
                            <a href="{{ route('patients.show', $log->subject_id) }}"
                               style="color:var(--primary); font-weight:800; text-decoration:none; display:block; line-height:1.4;"
                               onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                {{ $log->patient_name }}
                            </a>
                            <span style="color:#9ca3af; font-size:0.72rem;">#{{ $log->patient_file }}</span>
                        @else
                            <span style="color:#d1d5db;">—</span>
                        @endif
                    </td>

                    <td style="padding:0.7rem 1rem; font-size:0.82rem; color:#374151; font-weight:700;">
                        {{ $log->user_name ?: 'النظام' }}
                    </td>

                    <td style="padding:0.7rem 1rem; font-size:0.76rem; color:#6b7280; white-space:nowrap; line-height:1.5;">
                        {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y') }}<br>
                        <span style="color:#9ca3af;">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:3rem; text-align:center;">
                        <div style="font-size:2.5rem; opacity:0.15; margin-bottom:0.5rem;">🕓</div>
                        <div style="color:#9ca3af; font-weight:800; font-size:0.88rem;">لا توجد نتائج</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <x-pg-nav :paginator="$logs" />

</div>
</div>
