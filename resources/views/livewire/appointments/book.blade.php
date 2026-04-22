<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1100px; margin:0 auto; background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow-sm); animation:fadeIn 0.5s ease;">

    <!-- رأس الصفحة -->
    <div style="padding:1.25rem 1.75rem; border-bottom:1px solid var(--border); background:#fafbfc; border-radius:16px 16px 0 0; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="width:42px; height:42px; background:var(--primary-glow); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">📅</div>
            <div>
                <h1 style="font-size:1.4rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">حجز موعد جديد</h1>
                <div style="font-size:0.8rem; color:var(--text-muted); margin-top:0.1rem;">اختر العيادة والتاريخ والوقت ثم ابحث عن العميل</div>
            </div>
        </div>
        <a href="{{ route('appointments.index') }}" wire:navigate class="btn btn-secondary">⬅ العودة للمواعيد</a>
    </div>

    @if($errors->any())
    <div style="margin:1.25rem 1.75rem; background:#fef2f2; color:#dc2626; padding:0.9rem 1.25rem; border-radius:8px; font-weight:700; border:1px solid #fecaca;">
        @foreach($errors->all() as $error)
            <div>⚠️ {{ $error }}</div>
        @endforeach
    </div>
    @endif

    <div style="padding:1.75rem; display:flex; flex-direction:column; gap:1.5rem;">

        <!-- ① العيادة والتاريخ -->
        <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden;">
            <div style="background:var(--primary); padding:0.6rem 1.25rem; display:flex; align-items:center; gap:0.5rem;">
                <span style="background:rgba(255,255,255,0.25); color:#fff; font-size:0.72rem; font-weight:900; width:22px; height:22px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center;">١</span>
                <span style="color:#fff; font-weight:900; font-size:0.9rem;">اختيار العيادة والتاريخ</span>
            </div>
            <div class="pg-2col" style="padding:1.25rem; display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--primary); margin-bottom:0.4rem;">العيادة *</label>
                    <select wire:model.live="selectedClinic" class="form-input">
                        <option value="">— اختر العيادة —</option>
                        @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--primary); margin-bottom:0.4rem;">التاريخ *</label>
                    <input type="date" wire:model.live="selectedDate" class="form-input">
                </div>
            </div>
        </div>

        <!-- ② شبكة الأوقات -->
        @if($selectedClinic && $selectedDate)
        <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden;" wire:loading.class="opacity-50">
            <div style="background:var(--navy); padding:0.6rem 1.25rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem;">
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <span style="background:rgba(255,255,255,0.2); color:#fff; font-size:0.72rem; font-weight:900; width:22px; height:22px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center;">٢</span>
                    <span style="color:#fff; font-weight:900; font-size:0.9rem;">اختيار وقت الموعد</span>
                </div>
                <div style="display:flex; gap:1rem; font-size:0.75rem; font-weight:700;">
                    <span style="display:flex; align-items:center; gap:0.35rem; color:rgba(255,255,255,0.75);">
                        <span style="width:12px; height:12px; background:#22c55e; border-radius:3px; display:inline-block;"></span> متاح
                    </span>
                    <span style="display:flex; align-items:center; gap:0.35rem; color:rgba(255,255,255,0.75);">
                        <span style="width:12px; height:12px; background:#ef4444; border-radius:3px; display:inline-block;"></span> محجوز
                    </span>
                    <span style="display:flex; align-items:center; gap:0.35rem; color:rgba(255,255,255,0.75);">
                        <span style="width:12px; height:12px; background:var(--gold); border-radius:3px; display:inline-block;"></span> مختار
                    </span>
                </div>
            </div>
            <div style="padding:1.25rem;">
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(78px, 1fr)); gap:0.4rem;">
                    @foreach($timeSlots as $slot)
                        @php
                            $isBooked   = in_array($slot, $bookedSlots);
                            $isSelected = $selectedTime === $slot;
                            $bookedName = $bookedDetails[$slot] ?? null;

                            if ($isSelected) {
                                $bg = 'var(--gold)'; $color = '#fff'; $border = 'var(--gold)'; $cursor = 'pointer';
                            } elseif ($isBooked) {
                                $bg = '#fef2f2'; $color = '#dc2626'; $border = '#fecaca'; $cursor = 'not-allowed';
                            } else {
                                $bg = '#f0fdf4'; $color = '#16a34a'; $border = '#bbf7d0'; $cursor = 'pointer';
                            }
                        @endphp
                        <div
                            @if(!$isBooked) wire:click="selectTime('{{ $slot }}')" @endif
                            title="{{ $isBooked ? 'محجوز: ' . $bookedName : 'اضغط لاختيار هذا الوقت' }}"
                            style="background:{{ $bg }}; color:{{ $color }}; border:2px solid {{ $border }}; border-radius:8px; padding:0.4rem 0.25rem; text-align:center; font-weight:900; font-size:0.82rem; cursor:{{ $cursor }}; transition:all 0.15s; direction:ltr; position:relative; user-select:none;"
                            @if(!$isBooked)
                            onmouseover="this.style.transform='scale(1.04)'"
                            onmouseout="this.style.transform='scale(1)'"
                            @endif>
                            {{ $slot }}
                            @if($isBooked)
                            <div style="font-size:0.58rem; font-weight:700; margin-top:0.15rem; opacity:0.7; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; direction:rtl;">{{ Str::limit($bookedName, 10) }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($selectedTime)
                <div style="margin-top:1rem; background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:0.65rem 1rem; font-size:0.9rem; font-weight:800; color:#92400e; display:flex; align-items:center; gap:0.5rem;">
                    🕐 الوقت المختار: <span style="font-size:1.1rem; direction:ltr;">{{ $selectedTime }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- ③ البحث عن العميل -->
        <div style="border:1px solid var(--border); border-radius:12px;">
            <div style="background:#1565c0; padding:0.6rem 1.25rem; border-radius:12px 12px 0 0; display:flex; align-items:center; gap:0.5rem;">
                <span style="background:rgba(255,255,255,0.25); color:#fff; font-size:0.72rem; font-weight:900; width:22px; height:22px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center;">٣</span>
                <span style="color:#fff; font-weight:900; font-size:0.9rem;">البحث عن العميل</span>
            </div>
            <div style="padding:1.25rem;">
                @if($patient)
                <!-- العميل المختار -->
                <div style="background:#e8f5e9; border:2px solid #a5d6a7; border-radius:10px; padding:1rem 1.25rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
                    <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
                        <div style="width:44px; height:44px; background:#22c55e; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; color:#fff; flex-shrink:0;">👤</div>
                        <div>
                            <div style="font-weight:900; color:var(--navy); font-size:1.05rem;">{{ $patient->full_name }}</div>
                            <div style="font-size:0.8rem; color:var(--text-dim); margin-top:0.2rem; display:flex; gap:1rem; flex-wrap:wrap;">
                                @if($patient->phone)<span>📞 {{ $patient->phone }}</span>@endif
                                @if($patient->ssn)<span>🪪 {{ $patient->ssn }}</span>@endif
                                <span style="color:#16a34a; font-weight:800;">ملف #{{ $patient->file_id }}</span>
                            </div>
                        </div>
                    </div>
                    <button wire:click="clearPatient" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca; border-radius:8px; padding:0.4rem 0.9rem; cursor:pointer; font-size:0.82rem; font-weight:800;">
                        ✕ تغيير
                    </button>
                </div>
                @else
                <!-- حقل البحث -->
                <div style="position:relative;">
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:#1565c0; margin-bottom:0.4rem;">ابحث بالاسم أو رقم الملف أو الهوية أو الجوال *</label>
                    <div style="position:relative;">
                        <input type="text" wire:model.live.debounce.300ms="patientSearch"
                            placeholder="اكتب للبحث..."
                            class="form-input"
                            style="padding-right:2.8rem;"
                            autocomplete="off">
                        <span style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); font-size:1.1rem; opacity:0.4;">🔍</span>
                        <div wire:loading wire:target="updatedPatientSearch" style="position:absolute; left:0.8rem; top:50%; transform:translateY(-50%); font-size:0.75rem; color:var(--text-muted);">⏳</div>
                    </div>

                    @if(count($patientResults) > 0)
                    <div style="position:absolute; z-index:9999; top:100%; right:0; left:0; background:#fff; border:1px solid var(--border); border-radius:10px; box-shadow:0 8px 25px rgba(0,0,0,0.15); margin-top:0.3rem; overflow:hidden; max-height:280px; overflow-y:auto;">
                        @foreach($patientResults as $result)
                        <div wire:click="selectPatient({{ $result->id }})"
                            style="padding:0.75rem 1rem; cursor:pointer; border-bottom:1px solid var(--border); transition:background 0.15s; display:flex; align-items:center; justify-content:space-between; gap:0.75rem;"
                            onmouseover="this.style.background='#f0f7ff'"
                            onmouseout="this.style.background='#fff'">
                            <div>
                                <div style="font-weight:800; color:var(--navy); font-size:0.95rem;">{{ $result->full_name }}</div>
                                <div style="font-size:0.78rem; color:var(--text-dim); margin-top:0.15rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
                                    @if($result->phone)<span>📞 {{ $result->phone }}</span>@endif
                                    @if($result->ssn)<span>🪪 {{ $result->ssn }}</span>@endif
                                </div>
                            </div>
                            <div style="font-size:0.78rem; background:#e8f5e9; color:#16a34a; padding:0.2rem 0.6rem; border-radius:20px; font-weight:800; white-space:nowrap; flex-shrink:0;">#{{ $result->file_id }}</div>
                        </div>
                        @endforeach
                    </div>
                    @elseif(strlen($patientSearch) >= 2 && count($patientResults) === 0)
                    <div style="margin-top:0.5rem; font-size:0.83rem; color:var(--text-muted); font-weight:700;">
                        لا توجد نتائج — <a href="{{ route('patients.create') }}" wire:navigate style="color:var(--primary); font-weight:800; text-decoration:underline;">إنشاء ملف عميل جديد</a>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- ④ ملاحظات الموعد -->
        <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden;">
            <div style="background:#4a5568; padding:0.6rem 1.25rem; display:flex; align-items:center; gap:0.5rem;">
                <span style="background:rgba(255,255,255,0.25); color:#fff; font-size:0.72rem; font-weight:900; width:22px; height:22px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center;">٤</span>
                <span style="color:#fff; font-weight:900; font-size:0.9rem;">ملاحظات الموعد <span style="font-weight:500; font-size:0.78rem; opacity:0.75;">(اختياري)</span></span>
            </div>
            <div style="padding:1.25rem;">
                <textarea wire:model="notes" rows="3"
                    placeholder="أي ملاحظات إضافية على هذا الموعد..."
                    class="form-input"
                    style="resize:vertical; min-height:80px;"></textarea>
            </div>
        </div>

        <!-- ملخص الحجز -->
        @if($patientId && $selectedTime && $selectedClinic && $selectedDate)
        <div style="background:linear-gradient(135deg, var(--primary), #a02535); border-radius:12px; padding:1.25rem 1.5rem; color:#fff;">
            <div style="font-size:0.8rem; font-weight:700; opacity:0.75; margin-bottom:0.75rem; text-transform:uppercase; letter-spacing:1px;">ملخص الحجز</div>
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:0.75rem;">
                <div style="background:rgba(255,255,255,0.1); border-radius:8px; padding:0.65rem 1rem;">
                    <div style="font-size:0.72rem; opacity:0.75; margin-bottom:0.25rem;">العميل</div>
                    <div style="font-weight:900; font-size:0.95rem;">{{ $patient?->full_name }}</div>
                </div>
                <div style="background:rgba(255,255,255,0.1); border-radius:8px; padding:0.65rem 1rem;">
                    <div style="font-size:0.72rem; opacity:0.75; margin-bottom:0.25rem;">العيادة</div>
                    <div style="font-weight:900; font-size:0.95rem;">
                        {{ collect($clinics)->firstWhere('id', (int)$selectedClinic)?->name ?? '—' }}
                    </div>
                </div>
                <div style="background:rgba(255,255,255,0.1); border-radius:8px; padding:0.65rem 1rem;">
                    <div style="font-size:0.72rem; opacity:0.75; margin-bottom:0.25rem;">التاريخ والوقت</div>
                    <div style="font-weight:900; font-size:0.95rem; direction:ltr; text-align:right;">{{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }} — {{ $selectedTime }}</div>
                </div>
            </div>
            @if($notes)
            <div style="margin-top:0.75rem; background:rgba(255,255,255,0.1); border-radius:8px; padding:0.65rem 1rem;">
                <div style="font-size:0.72rem; opacity:0.75; margin-bottom:0.25rem;">الملاحظات</div>
                <div style="font-weight:700; font-size:0.9rem;">{{ $notes }}</div>
            </div>
            @endif
        </div>
        @endif

        <!-- أزرار الحفظ -->
        <div style="display:flex; gap:1rem; align-items:center;">
            <button wire:click="save" wire:loading.attr="disabled"
                class="btn btn-primary"
                style="padding:0.75rem 3rem; font-size:1rem; {{ (!$patientId || !$selectedTime || !$selectedClinic) ? 'opacity:0.5; cursor:not-allowed;' : '' }}">
                <span wire:loading.remove wire:target="save">💾 تأكيد الحجز</span>
                <span wire:loading wire:target="save">⏳ جارٍ الحفظ...</span>
            </button>
            <a href="{{ route('appointments.index') }}" wire:navigate class="btn btn-secondary" style="padding:0.75rem 1.5rem;">إلغاء</a>
        </div>

    </div>

    <!-- شريط سفلي -->
    <div style="background:var(--navy); color:rgba(255,255,255,0.7); padding:0.75rem 1.75rem; font-size:0.82rem; font-weight:700; display:flex; align-items:center; gap:0.5rem; border-top:3px solid var(--gold); border-radius:0 0 16px 16px;">
        ⚠️ يرجى التأكد من بيانات العميل والوقت المناسب قبل تأكيد الحجز
    </div>

</div>
</div>
