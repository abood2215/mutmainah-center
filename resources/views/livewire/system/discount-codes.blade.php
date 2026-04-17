<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1200px; margin:0 auto; background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow-sm); overflow:hidden; animation:fadeIn 0.5s ease;">

    {{-- رأس الصفحة --}}
    <div style="padding:1.25rem 1.75rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="width:42px; height:42px; background:var(--primary-glow); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">🏷️</div>
            <div>
                <h1 style="font-size:1.3rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">كودات الخصم</h1>
                <div style="font-size:0.78rem; color:var(--text-muted); margin-top:1px;">إدارة كودات الخصم للعملاء</div>
            </div>
        </div>
        <button wire:click="openCreate" class="btn btn-primary" style="gap:0.4rem; display:flex; align-items:center;">
            ＋ كود جديد
        </button>
    </div>

    <div style="padding:1.5rem 1.75rem;">

        {{-- رسائل --}}
        @if($successMsg)
        <div style="background:#f0fdf4; border:1px solid #86efac; border-radius:10px; padding:0.75rem 1rem; margin-bottom:1rem; color:#166534; font-weight:700; display:flex; align-items:center; gap:0.5rem;">
            ✅ {{ $successMsg }}
        </div>
        @endif

        {{-- فيلتر وبحث --}}
        <div style="display:flex; gap:0.75rem; align-items:center; margin-bottom:1.25rem; flex-wrap:wrap;">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث بالكود أو الوصف..." class="form-input" style="width:220px;">
            <div style="display:flex; gap:0.4rem;">
                @foreach(['all'=>'الكل','active'=>'الفعّالة','expired'=>'المنتهية'] as $key=>$label)
                <button wire:click="$set('filter','{{ $key }}')"
                    style="padding:0.4rem 0.9rem; border-radius:20px; font-size:0.8rem; font-weight:700; cursor:pointer; border:1px solid {{ $filter===$key ? 'var(--primary)' : 'var(--border)' }}; background:{{ $filter===$key ? 'var(--primary)' : '#fff' }}; color:{{ $filter===$key ? '#fff' : 'var(--text-dim)' }}; transition:.15s;">
                    {{ $label }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- جدول الكودات --}}
        @if($codes->isEmpty())
        <div style="padding:4rem; text-align:center; background:#f8fafc; border:1px solid var(--border); border-radius:12px;">
            <div style="font-size:3rem; opacity:0.1; margin-bottom:0.75rem;">🏷️</div>
            <div style="font-weight:800; color:var(--text-dim);">لا توجد كودات خصم</div>
            <div style="font-size:0.85rem; color:var(--text-muted); margin-top:0.4rem;">اضغط "كود جديد" لإضافة أول كود</div>
        </div>
        @else
        <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden;">
            <table style="width:100%; border-collapse:collapse; font-size:0.84rem; font-family:'Tajawal',sans-serif;">
                <thead>
                    <tr style="background:#fafbfc; border-bottom:2px solid var(--border);">
                        <th style="padding:0.75rem 1rem; font-weight:800; color:var(--text-dim); text-align:right;">الكود</th>
                        <th style="padding:0.75rem 1rem; font-weight:800; color:var(--text-dim); text-align:center;">الخصم</th>
                        <th style="padding:0.75rem 1rem; font-weight:800; color:var(--text-dim); text-align:center;">الاستخدام</th>
                        <th style="padding:0.75rem 1rem; font-weight:800; color:var(--text-dim); text-align:center;">الصلاحية</th>
                        <th style="padding:0.75rem 1rem; font-weight:800; color:var(--text-dim); text-align:right;">الوصف</th>
                        <th style="padding:0.75rem 1rem; font-weight:800; color:var(--text-dim); text-align:center;">الحالة</th>
                        <th style="padding:0.75rem 1rem; font-weight:800; color:var(--text-dim); text-align:center;"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($codes as $row)
                @php
                    $isExpired = $row->expires_at && $row->expires_at < $today;
                    $isExhausted = $row->max_uses > 0 && $row->used_count >= $row->max_uses;
                    $isValid = $row->is_active && !$isExpired && !$isExhausted;
                @endphp
                <tr style="border-bottom:1px solid #f0f2f5;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                    {{-- الكود --}}
                    <td style="padding:0.75rem 1rem;">
                        <span style="font-family:monospace; font-size:0.95rem; font-weight:900; background:{{ $isValid ? '#fef3c7' : '#f1f5f9' }}; color:{{ $isValid ? '#92400e' : '#94a3b8' }}; padding:0.25rem 0.75rem; border-radius:6px; letter-spacing:1px;">{{ $row->code }}</span>
                    </td>
                    {{-- الخصم --}}
                    <td style="padding:0.75rem 1rem; text-align:center;">
                        <span style="font-weight:900; color:var(--primary); font-size:1rem;">
                            @if($row->type === 'percent')
                                {{ $row->value + 0 }}%
                            @else
                                {{ number_format($row->value, 0) }} د.ك
                            @endif
                        </span>
                        @if($row->min_amount > 0)
                        <div style="font-size:0.72rem; color:var(--text-muted); margin-top:2px;">حد أدنى {{ number_format($row->min_amount,0) }} د.ك</div>
                        @endif
                    </td>
                    {{-- الاستخدام --}}
                    <td style="padding:0.75rem 1rem; text-align:center;">
                        <span style="font-weight:700; color:var(--navy);">{{ $row->used_count }}</span>
                        @if($row->max_uses > 0)
                        <span style="color:var(--text-muted);">/ {{ $row->max_uses }}</span>
                        @else
                        <span style="color:var(--text-muted);">/ ∞</span>
                        @endif
                    </td>
                    {{-- الصلاحية --}}
                    <td style="padding:0.75rem 1rem; text-align:center;">
                        @if($row->expires_at)
                            <span style="color:{{ $isExpired ? 'var(--danger)' : '#166534' }}; font-weight:700; font-size:0.82rem; direction:ltr; unicode-bidi:isolate;">
                                {{ \Carbon\Carbon::parse($row->expires_at)->format('d/m/Y') }}
                            </span>
                            @if($isExpired)
                            <div style="font-size:0.7rem; color:var(--danger);">منتهي</div>
                            @endif
                        @else
                            <span style="color:var(--text-muted);">—</span>
                        @endif
                    </td>
                    {{-- الوصف --}}
                    <td style="padding:0.75rem 1rem; color:var(--text-dim); max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $row->notes ?: '—' }}</td>
                    {{-- الحالة --}}
                    <td style="padding:0.75rem 1rem; text-align:center;">
                        <button wire:click="toggleActive({{ $row->id }})"
                            style="padding:0.25rem 0.85rem; border-radius:20px; font-size:0.78rem; font-weight:800; cursor:pointer; border:none; background:{{ $isValid ? '#dcfce7' : '#fee2e2' }}; color:{{ $isValid ? '#166534' : '#991b1b' }}; transition:.15s;">
                            {{ $isValid ? 'فعّال' : ($isExhausted ? 'منتهي الكمية' : ($isExpired ? 'منتهي التاريخ' : 'معطّل')) }}
                        </button>
                    </td>
                    {{-- أزرار --}}
                    <td style="padding:0.75rem 1rem; text-align:center; white-space:nowrap;">
                        <button wire:click="openEdit({{ $row->id }})"
                            style="padding:0.3rem 0.7rem; border-radius:7px; border:1px solid var(--border); background:#fff; color:var(--navy); cursor:pointer; font-size:0.8rem; margin-left:4px;">✏️</button>
                        @if($confirmDelete === $row->id)
                        <span style="display:inline-flex; gap:4px; align-items:center;">
                            <button wire:click="delete"
                                style="padding:0.3rem 0.7rem; border-radius:7px; border:none; background:var(--danger); color:#fff; cursor:pointer; font-size:0.78rem; font-weight:700;">تأكيد</button>
                            <button wire:click="deleteCancel"
                                style="padding:0.3rem 0.7rem; border-radius:7px; border:1px solid var(--border); background:#fff; color:var(--text-dim); cursor:pointer; font-size:0.78rem;">إلغاء</button>
                        </span>
                        @else
                        <button wire:click="deleteConfirm({{ $row->id }})"
                            style="padding:0.3rem 0.7rem; border-radius:7px; border:1px solid #fecaca; background:#fff5f5; color:var(--danger); cursor:pointer; font-size:0.8rem;">🗑️</button>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif

    </div>
</div>

{{-- Modal الإضافة/التعديل --}}
@if($showForm)
<div style="position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1000; display:flex; align-items:center; justify-content:center; padding:1rem;" wire:click.self="closeForm">
<div style="background:#fff; border-radius:16px; width:100%; max-width:560px; box-shadow:0 20px 60px rgba(0,0,0,.2); overflow:hidden; animation:fadeIn .2s ease;">

    <div style="padding:1.1rem 1.5rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; justify-content:space-between;">
        <h2 style="margin:0; font-size:1.1rem; font-weight:900; color:var(--primary); font-family:'Tajawal',sans-serif;">
            {{ $editId ? 'تعديل كود' : 'كود خصم جديد' }}
        </h2>
        <button wire:click="closeForm" style="background:none; border:none; font-size:1.3rem; cursor:pointer; color:var(--text-muted); line-height:1;">×</button>
    </div>

    <div style="padding:1.5rem;">

        @if($errorMsg)
        <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:0.65rem 1rem; margin-bottom:1rem; color:#991b1b; font-weight:700; font-size:0.85rem;">
            ⚠️ {{ $errorMsg }}
        </div>
        @endif

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">

            {{-- الكود --}}
            <div style="grid-column:1/-1;">
                <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--text-dim); margin-bottom:0.35rem;">الكود <span style="color:var(--danger);">*</span></label>
                <input type="text" wire:model="code" placeholder="مثال: WELCOME20"
                    style="width:100%; padding:0.6rem 0.85rem; border:1.5px solid var(--border); border-radius:8px; font-family:monospace; font-size:1rem; letter-spacing:1px; text-transform:uppercase; box-sizing:border-box; outline:none;">
                <div style="font-size:0.72rem; color:var(--text-muted); margin-top:3px;">سيتحول لحروف كبيرة تلقائياً</div>
            </div>

            {{-- نوع الخصم --}}
            <div>
                <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--text-dim); margin-bottom:0.35rem;">نوع الخصم <span style="color:var(--danger);">*</span></label>
                <select wire:model.live="type" class="form-input" style="width:100%;">
                    <option value="percent">نسبة مئوية %</option>
                    <option value="fixed">مبلغ ثابت د.ك</option>
                </select>
            </div>

            {{-- القيمة --}}
            <div>
                <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--text-dim); margin-bottom:0.35rem;">
                    القيمة <span style="color:var(--danger);">*</span>
                    <span style="font-weight:400; color:var(--text-muted);">({{ $type === 'percent' ? 'بالنسبة المئوية' : 'بالدينار الكويتي' }})</span>
                </label>
                <input type="number" wire:model="value" step="0.001" min="0"
                    placeholder="{{ $type === 'percent' ? '20' : '5.000' }}"
                    class="form-input" style="width:100%;">
            </div>

            {{-- تاريخ الانتهاء --}}
            <div>
                <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--text-dim); margin-bottom:0.35rem;">تاريخ الانتهاء <span style="color:var(--text-muted); font-weight:400;">(اختياري)</span></label>
                <input type="date" wire:model="expiresAt" class="form-input" style="width:100%;">
            </div>

            {{-- الحد الأقصى --}}
            <div>
                <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--text-dim); margin-bottom:0.35rem;">الحد الأقصى للاستخدام <span style="color:var(--text-muted); font-weight:400;">(0 = غير محدود)</span></label>
                <input type="number" wire:model="maxUses" min="0" class="form-input" style="width:100%;">
            </div>

            {{-- العيادة --}}
            <div>
                <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--text-dim); margin-bottom:0.35rem;">العيادة <span style="color:var(--text-muted); font-weight:400;">(اختياري)</span></label>
                <select wire:model="clinicId" class="form-input" style="width:100%;">
                    <option value="0">كل العيادات</option>
                    @foreach($clinics as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- الحد الأدنى --}}
            <div>
                <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--text-dim); margin-bottom:0.35rem;">الحد الأدنى للفاتورة (د.ك) <span style="color:var(--text-muted); font-weight:400;">(0 = بدون حد)</span></label>
                <input type="number" wire:model="minAmount" step="0.001" min="0" class="form-input" style="width:100%;">
            </div>

            {{-- الوصف --}}
            <div style="grid-column:1/-1;">
                <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--text-dim); margin-bottom:0.35rem;">وصف / ملاحظة</label>
                <input type="text" wire:model="notes" placeholder="مثال: للعملاء الجدد، كود د.خلف..." class="form-input" style="width:100%;">
            </div>

            {{-- الحالة --}}
            <div style="grid-column:1/-1; display:flex; align-items:center; gap:0.75rem;">
                <label style="font-size:0.85rem; font-weight:800; color:var(--text-dim);">فعّال</label>
                <button type="button" wire:click="$set('isActive', {{ $isActive ? 0 : 1 }})"
                    style="width:46px; height:26px; border-radius:13px; border:none; cursor:pointer; transition:.2s; background:{{ $isActive ? 'var(--primary)' : '#cbd5e1' }}; position:relative;">
                    <span style="position:absolute; top:3px; {{ $isActive ? 'left:23px' : 'left:3px' }}; width:20px; height:20px; background:#fff; border-radius:50%; transition:.2s; display:block;"></span>
                </button>
                <span style="font-size:0.82rem; color:{{ $isActive ? 'var(--primary)' : 'var(--text-muted)' }}; font-weight:700;">{{ $isActive ? 'فعّال' : 'معطّل' }}</span>
            </div>
        </div>

        <div style="display:flex; gap:0.75rem; justify-content:flex-end; margin-top:1.5rem; border-top:1px solid var(--border); padding-top:1rem;">
            <button wire:click="closeForm" class="btn btn-secondary">إلغاء</button>
            <button wire:click="save" class="btn btn-primary">{{ $editId ? 'حفظ التعديلات' : 'إضافة الكود' }}</button>
        </div>
    </div>
</div>
</div>
@endif

</div>
