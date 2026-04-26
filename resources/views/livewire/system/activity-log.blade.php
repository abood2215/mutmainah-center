<div>
    <div class="card">

        {{-- Header --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; flex-wrap:wrap; gap:0.75rem;">
            <h2 style="font-size:1.2rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">
                🕓 سجل النشاط الشامل
            </h2>
            <button wire:click="resetFilters" class="btn" style="font-size:0.82rem; padding:0.4rem 1rem;">مسح الفلاتر</button>
        </div>

        {{-- التبويبات --}}
        @php
            $tabs = [
                'all'          => ['label' => 'الكل',              'icon' => '📋', 'count' => $counts->total],
                'patients'     => ['label' => 'ملفات العملاء',     'icon' => '👤', 'count' => $counts->patients],
                'checks'       => ['label' => 'الكشوف والفواتير',  'icon' => '🧾', 'count' => $counts->checks],
                'appointments' => ['label' => 'المواعيد',           'icon' => '📅', 'count' => $counts->appointments],
                'payments'     => ['label' => 'المدفوعات',          'icon' => '💰', 'count' => $counts->payments],
                'auth'         => ['label' => 'الدخول للنظام',     'icon' => '🔐', 'count' => $counts->auth],
                'attachments'  => ['label' => 'المرفقات',           'icon' => '📎', 'count' => $counts->attachments],
            ];
        @endphp
        <div style="display:flex; gap:0.4rem; flex-wrap:wrap; margin-bottom:1.25rem; border-bottom:2px solid #f0f0f0; padding-bottom:0.75rem;">
            @foreach($tabs as $key => $tab)
            <button wire:click="switchTab('{{ $key }}')"
                    style="padding:0.4rem 0.85rem; border-radius:20px; font-size:0.8rem; font-weight:800;
                           font-family:'Tajawal',sans-serif; cursor:pointer; border:1.5px solid;
                           transition:all 0.15s;
                           {{ $this->tab === $key
                               ? 'background:var(--primary); color:#fff; border-color:var(--primary);'
                               : 'background:#f9fafb; color:#374151; border-color:#e5e7eb;' }}">
                {{ $tab['icon'] }} {{ $tab['label'] }}
                <span style="opacity:0.75; font-size:0.72rem;">({{ number_format($tab['count']) }})</span>
            </button>
            @endforeach
        </div>

        {{-- فلاتر --}}
        <div style="display:flex; gap:0.75rem; flex-wrap:wrap; margin-bottom:1rem;">
            <input wire:model.live.debounce.300ms="search"
                   type="text" placeholder="بحث في الوصف أو المستخدم أو العميل..."
                   class="form-input" style="flex:1; min-width:200px;">
            <input wire:model.live="filterDate" type="date" class="form-input" style="width:160px;">
            <select wire:model.live="filterUser" class="form-input" style="width:160px;">
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
                    <tr style="background:var(--navy); color:#fff;">
                        <th style="padding:0.7rem 1rem; text-align:right; font-size:0.82rem; font-weight:700; width:60px;">#</th>
                        <th style="padding:0.7rem 1rem; text-align:right; font-size:0.82rem; font-weight:700; width:120px;">الحدث</th>
                        <th style="padding:0.7rem 1rem; text-align:right; font-size:0.82rem; font-weight:700;">الوصف</th>
                        <th style="padding:0.7rem 1rem; text-align:right; font-size:0.82rem; font-weight:700; width:160px;">العميل</th>
                        <th style="padding:0.7rem 1rem; text-align:right; font-size:0.82rem; font-weight:700; width:120px;">بواسطة</th>
                        <th style="padding:0.7rem 1rem; text-align:right; font-size:0.82rem; font-weight:700; width:130px;">التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    @php
                        $icon = match($log->action) {
                            'created'       => $log->subject === 'patient' ? '👤' : '📋',
                            'uploaded'      => '📎',
                            'updated'       => '✏️',
                            'deleted'       => '🗑️',
                            'receipt'       => '💰',
                            'payment'       => '💸',
                            'cancelled'     => '❌',
                            'booked'        => '📅',
                            'discount'      => '🏷️',
                            'voided'        => '🚫',
                            'sent'          => '📨',
                            'login_success' => '✅',
                            'login_failed'  => '⛔',
                            default         => '•',
                        };
                        $color = match($log->action) {
                            'created'       => '#2e7d32',
                            'uploaded'      => '#1565c0',
                            'deleted'       => '#dc2626',
                            'receipt'       => '#166534',
                            'payment'       => '#b45309',
                            'cancelled'     => '#dc2626',
                            'booked'        => '#1d4ed8',
                            'discount'      => '#7c3aed',
                            'voided'        => '#dc2626',
                            'sent'          => '#0891b2',
                            'login_success' => '#166534',
                            'login_failed'  => '#dc2626',
                            default         => '#546e7a',
                        };
                        $actionLabel = match($log->action) {
                            'created'       => 'إنشاء',
                            'updated'       => 'تعديل',
                            'deleted'       => 'حذف',
                            'cancelled'     => 'إلغاء',
                            'booked'        => 'حجز',
                            'uploaded'      => 'رفع ملف',
                            'receipt'       => 'قبض',
                            'payment'       => 'صرف',
                            'voided'        => 'إلغاء فاتورة',
                            'discount'      => 'خصم',
                            'sent'          => 'إرسال',
                            'login_success' => 'دخول ناجح',
                            'login_failed'  => 'دخول فاشل',
                            default         => $log->action,
                        };
                    @endphp
                    <tr style="border-bottom:1px solid #f4f6f9;"
                        onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                        <td style="padding:0.65rem 1rem; font-size:0.78rem; color:#9ca3af;">{{ $log->id }}</td>
                        <td style="padding:0.65rem 1rem;">
                            <span style="background:{{ $color }}18; color:{{ $color }}; border:1px solid {{ $color }}33;
                                         padding:0.2rem 0.6rem; border-radius:20px; font-size:0.75rem;
                                         font-weight:800; white-space:nowrap;">
                                {{ $icon }} {{ $actionLabel }}
                            </span>
                        </td>
                        <td style="padding:0.65rem 1rem; font-size:0.82rem; color:var(--navy); font-weight:600; max-width:320px; word-break:break-word;">
                            {{ $log->description }}
                        </td>
                        <td style="padding:0.65rem 1rem; font-size:0.82rem;">
                            @if($log->patient_name)
                                <a href="{{ route('patients.show', $log->subject_id) }}"
                                   style="color:var(--primary); font-weight:700; text-decoration:none;">
                                    {{ $log->patient_name }}
                                    <span style="color:#9ca3af; font-size:0.73rem;"> #{{ $log->patient_file }}</span>
                                </a>
                            @else
                                <span style="color:#d1d5db; font-size:0.78rem;">—</span>
                            @endif
                        </td>
                        <td style="padding:0.65rem 1rem; font-size:0.82rem; color:#374151; font-weight:700;">
                            {{ $log->user_name ?: 'النظام' }}
                        </td>
                        <td style="padding:0.65rem 1rem; font-size:0.78rem; color:#6b7280; white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding:2.5rem; text-align:center; color:#9ca3af; font-size:0.88rem;">
                            لا توجد نتائج
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:1rem;">
            {{ $logs->links() }}
        </div>
    </div>
</div>
