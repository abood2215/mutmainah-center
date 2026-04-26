<div>
    <div class="card" style="margin-bottom:1.5rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; flex-wrap:wrap; gap:0.75rem;">
            <h2 style="font-size:1.2rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">
                🕓 سجل النشاط الشامل
            </h2>
            <button wire:click="resetFilters" class="btn" style="font-size:0.82rem; padding:0.4rem 1rem;">
                مسح الفلاتر
            </button>
        </div>

        {{-- فلاتر --}}
        <div style="display:flex; gap:0.75rem; flex-wrap:wrap; margin-bottom:1rem;">
            <input wire:model.live.debounce.300ms="search"
                   type="text" placeholder="بحث في الوصف أو المستخدم أو العميل..."
                   class="form-input" style="flex:1; min-width:200px;">

            <input wire:model.live="filterDate"
                   type="date" class="form-input" style="width:160px;">

            <select wire:model.live="filterUser" class="form-input" style="width:160px;">
                <option value="">جميع المستخدمين</option>
                @foreach($users as $u)
                    <option value="{{ $u }}">{{ $u }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterAction" class="form-input" style="width:140px;">
                <option value="">جميع الأحداث</option>
                <option value="created">إنشاء</option>
                <option value="updated">تعديل</option>
                <option value="deleted">حذف</option>
                <option value="cancelled">إلغاء</option>
                <option value="booked">حجز</option>
                <option value="uploaded">رفع ملف</option>
                <option value="receipt">قبض</option>
                <option value="payment">صرف</option>
                <option value="voided">إلغاء فاتورة</option>
                <option value="discount">خصم</option>
                <option value="sent">إرسال</option>
            </select>
        </div>

        {{-- الجدول --}}
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif;">
                <thead>
                    <tr style="background:var(--navy); color:#fff;">
                        <th style="padding:0.7rem 1rem; text-align:right; font-size:0.82rem; font-weight:700;">#</th>
                        <th style="padding:0.7rem 1rem; text-align:right; font-size:0.82rem; font-weight:700;">الحدث</th>
                        <th style="padding:0.7rem 1rem; text-align:right; font-size:0.82rem; font-weight:700;">الوصف</th>
                        <th style="padding:0.7rem 1rem; text-align:right; font-size:0.82rem; font-weight:700;">العميل</th>
                        <th style="padding:0.7rem 1rem; text-align:right; font-size:0.82rem; font-weight:700;">بواسطة</th>
                        <th style="padding:0.7rem 1rem; text-align:right; font-size:0.82rem; font-weight:700;">التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    @php
                        $icon = match($log->action) {
                            'created'   => $log->subject === 'patient' ? '👤' : '📋',
                            'uploaded'  => '📎',
                            'updated'   => '✏️',
                            'deleted'   => '🗑️',
                            'receipt'   => '💰',
                            'payment'   => '💸',
                            'cancelled' => '❌',
                            'booked'    => '📅',
                            'discount'  => '🏷️',
                            'voided'    => '🚫',
                            'sent'      => '📨',
                            default     => '•',
                        };
                        $color = match($log->action) {
                            'created'   => '#2e7d32',
                            'uploaded'  => '#1565c0',
                            'deleted'   => '#dc2626',
                            'receipt'   => '#166534',
                            'payment'   => '#b45309',
                            'cancelled' => '#dc2626',
                            'booked'    => '#1d4ed8',
                            'discount'  => '#7c3aed',
                            'voided'    => '#dc2626',
                            'sent'      => '#0891b2',
                            default     => '#546e7a',
                        };
                        $actionLabel = match($log->action) {
                            'created'   => 'إنشاء',
                            'updated'   => 'تعديل',
                            'deleted'   => 'حذف',
                            'cancelled' => 'إلغاء',
                            'booked'    => 'حجز',
                            'uploaded'  => 'رفع ملف',
                            'receipt'   => 'قبض',
                            'payment'   => 'صرف',
                            'voided'    => 'إلغاء فاتورة',
                            'discount'  => 'خصم',
                            'sent'      => 'إرسال',
                            default     => $log->action,
                        };
                    @endphp
                    <tr style="border-bottom:1px solid #f4f6f9;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                        <td style="padding:0.65rem 1rem; font-size:0.78rem; color:#9ca3af;">{{ $log->id }}</td>
                        <td style="padding:0.65rem 1rem;">
                            <span style="background:{{ $color }}18; color:{{ $color }}; border:1px solid {{ $color }}33; padding:0.2rem 0.6rem; border-radius:20px; font-size:0.75rem; font-weight:800; white-space:nowrap;">
                                {{ $icon }} {{ $actionLabel }}
                            </span>
                        </td>
                        <td style="padding:0.65rem 1rem; font-size:0.82rem; color:var(--navy); font-weight:600; max-width:350px;">
                            {{ $log->description }}
                        </td>
                        <td style="padding:0.65rem 1rem; font-size:0.82rem;">
                            @if($log->patient_name)
                                <a href="{{ route('patients.show', $log->subject_id) }}"
                                   style="color:var(--primary); font-weight:700; text-decoration:none;">
                                    {{ $log->patient_name }}
                                    <span style="color:#9ca3af; font-size:0.75rem;">#{{ $log->patient_file }}</span>
                                </a>
                            @else
                                <span style="color:#d1d5db;">—</span>
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
                        <td colspan="6" style="padding:2rem; text-align:center; color:#9ca3af; font-size:0.88rem;">
                            لا توجد نتائج
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div style="margin-top:1rem;">
            {{ $logs->links() }}
        </div>
    </div>
</div>
