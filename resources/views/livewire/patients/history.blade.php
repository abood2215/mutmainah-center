<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1400px; margin:0 auto; animation:fadeIn 0.5s ease;">

<div id="print-area" style="background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow-sm); overflow:hidden;">

    {{-- الترويسة + زر الطباعة --}}
    <div style="padding:1rem 1.75rem; border-bottom:1px solid var(--border); background:#fafbfc;">
        <x-print-header title="السجل الاستشاري" />

        <div style="display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <div style="width:42px; height:42px; background:var(--primary-glow); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">📄</div>
                <h1 style="font-size:1.4rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">السجل الاستشاري</h1>
            </div>
            <a href="{{ route('patients.index') }}" wire:navigate class="btn btn-secondary no-print">⬅ العودة للبحث</a>
        </div>
    </div>

    <div class="pg-inner" style="padding:1.75rem;">

        @if(session()->has('success'))
        <div class="no-print" style="background:#e8f5e9; color:#2e7d32; padding:0.9rem 1.25rem; border-radius:8px; margin-bottom:1.25rem; font-weight:700; border:1px solid #c8e6c9;">✅ {{ session('success') }}</div>
        @endif

        <!-- بيانات العميل -->
        <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden; margin-bottom:1.25rem;">
            <div style="background:var(--primary); padding:0.6rem 1.25rem;">
                <span style="color:#fff; font-weight:900; font-size:0.92rem;">بيانات العميل</span>
            </div>
            <div style="padding:0.75rem 1.25rem; display:flex; flex-wrap:wrap; gap:1.5rem; align-items:center; background:#fafbfc;">
                <div style="display:flex; gap:0.4rem; align-items:center;">
                    <span style="font-size:0.78rem; color:var(--text-muted); font-weight:700;">الاسم :</span>
                    <span style="font-weight:900; color:var(--navy); font-size:0.95rem;">{{ $patient->full_name }}</span>
                </div>
                <div style="display:flex; gap:0.4rem; align-items:center;">
                    <span style="font-size:0.78rem; color:var(--text-muted); font-weight:700;">رقم الملف :</span>
                    <span style="font-weight:800; color:#1565c0;">{{ $patient->file_id }}</span>
                </div>
                <div style="display:flex; gap:0.4rem; align-items:center;">
                    <span style="font-size:0.78rem; color:var(--text-muted); font-weight:700;">الهوية :</span>
                    <span style="font-weight:700; color:var(--text-dim);">{{ $patient->ssn ?: '—' }}</span>
                </div>
                <div style="display:flex; gap:0.4rem; align-items:center;">
                    <span style="font-size:0.78rem; color:var(--text-muted); font-weight:700;">الجوال :</span>
                    <span style="font-weight:700; color:var(--text-dim);">{{ $patient->phone ?: '—' }}</span>
                </div>
                <div style="display:flex; gap:0.4rem; align-items:center;">
                    <span style="font-size:0.78rem; color:var(--text-muted); font-weight:700;">الجنس :</span>
                    <span style="font-weight:700; color:var(--text-dim);">{{ $patient->gender == 1 ? 'ذكر' : 'أنثى' }}</span>
                </div>
            </div>
        </div>

        <!-- نموذج السجل الاستشاري -->
        <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden; margin-bottom:1.25rem;">
            <div style="background:var(--primary); padding:0.6rem 1.25rem;">
                <span style="color:#fff; font-weight:900; font-size:0.92rem;">السجل الاستشاري</span>
            </div>

            {{-- نسخة الشاشة (حقول قابلة للتعديل) --}}
            <div class="no-print pg-2col" style="padding:1.25rem; display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--primary); margin-bottom:0.4rem;">الشكوى الحالية</label>
                    <textarea wire:model="current_complaint" style="width:100%; height:90px; border:1.5px solid var(--border); border-radius:7px; padding:0.65rem; font-family:'Tajawal'; font-size:0.88rem; outline:none; resize:vertical;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'"></textarea>
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--primary); margin-bottom:0.4rem;">العلاجات النفسية المستخدمة حالياً وسابقاً</label>
                    <textarea wire:model="psychiatric_treatments" style="width:100%; height:90px; border:1.5px solid var(--border); border-radius:7px; padding:0.65rem; font-family:'Tajawal'; font-size:0.88rem; outline:none; resize:vertical;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'"></textarea>
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--primary); margin-bottom:0.4rem;">الانطباع</label>
                    <textarea wire:model="impression" style="width:100%; height:90px; border:1.5px solid var(--border); border-radius:7px; padding:0.65rem; font-family:'Tajawal'; font-size:0.88rem; outline:none; resize:vertical;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'"></textarea>
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--primary); margin-bottom:0.4rem;">الخطة</label>
                    <textarea wire:model="plan" style="width:100%; height:90px; border:1.5px solid var(--border); border-radius:7px; padding:0.65rem; font-family:'Tajawal'; font-size:0.88rem; outline:none; resize:vertical;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'"></textarea>
                </div>
                <div style="grid-column:span 2;">
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--primary); margin-bottom:0.4rem;">الأمراض النفسية في الأسرة</label>
                    <textarea wire:model="family_history" style="width:100%; height:80px; border:1.5px solid var(--border); border-radius:7px; padding:0.65rem; font-family:'Tajawal'; font-size:0.88rem; outline:none; resize:vertical;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'"></textarea>
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--primary); margin-bottom:0.4rem;">التعليقات على المستوى الشخصي</label>
                    <textarea wire:model="personal_history" style="width:100%; height:90px; border:1.5px solid var(--border); border-radius:7px; padding:0.65rem; font-family:'Tajawal'; font-size:0.88rem; outline:none; resize:vertical;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'"></textarea>
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--primary); margin-bottom:0.4rem;">تقييم</label>
                    <textarea wire:model="mental_state" style="width:100%; height:90px; border:1.5px solid var(--border); border-radius:7px; padding:0.65rem; font-family:'Tajawal'; font-size:0.88rem; outline:none; resize:vertical;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'"></textarea>
                </div>
                <div style="grid-column:span 2;">
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--primary); margin-bottom:0.4rem;">التوصيات / الملاحظات</label>
                    <textarea wire:model="recommendations" style="width:100%; height:80px; border:1.5px solid var(--border); border-radius:7px; padding:0.65rem; font-family:'Tajawal'; font-size:0.88rem; outline:none; resize:vertical;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'"></textarea>
                </div>
                <div style="grid-column:span 2;">
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--primary); margin-bottom:0.4rem;">الخطة المستقبلية</label>
                    <textarea wire:model="future_plan" style="width:100%; height:80px; border:1.5px solid var(--border); border-radius:7px; padding:0.65rem; font-family:'Tajawal'; font-size:0.88rem; outline:none; resize:vertical;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'"></textarea>
                </div>
            </div>

            {{-- نسخة الطباعة (divs تعرض كل النص) --}}
            <div class="print-only" style="display:none; padding:1rem; display:grid; grid-template-columns:1fr 1fr; gap:0.85rem;">
                @php
                $printFields = [
                    'الشكوى الحالية'                              => $current_complaint,
                    'العلاجات النفسية المستخدمة حالياً وسابقاً' => $psychiatric_treatments,
                    'الانطباع'                                    => $impression,
                    'الخطة'                                       => $plan,
                    'التعليقات على المستوى الشخصي'               => $personal_history,
                    'تقييم'                        => $mental_state,
                ];
                $printFieldsFull = [
                    'الأمراض النفسية في الأسرة'    => $family_history,
                    'التوصيات / الملاحظات'         => $recommendations,
                    'الخطة المستقبلية'    => $future_plan,
                ];
                @endphp
                @foreach($printFields as $label => $value)
                <div style="break-inside:avoid;">
                    <div style="font-size:0.75rem; font-weight:800; color:#8b1c2b; margin-bottom:0.25rem; font-family:'Tajawal',sans-serif;">{{ $label }}</div>
                    <div style="border:1px solid #e2e8f0; border-radius:6px; padding:0.5rem 0.65rem; font-family:'Tajawal',sans-serif; font-size:0.85rem; color:#374151; line-height:1.65; min-height:2rem; white-space:pre-wrap; word-break:break-word; background:#fafafa;">{{ $value ?: '—' }}</div>
                </div>
                @endforeach
                @foreach($printFieldsFull as $label => $value)
                <div style="grid-column:span 2; break-inside:avoid;">
                    <div style="font-size:0.75rem; font-weight:800; color:#8b1c2b; margin-bottom:0.25rem; font-family:'Tajawal',sans-serif;">{{ $label }}</div>
                    <div style="border:1px solid #e2e8f0; border-radius:6px; padding:0.5rem 0.65rem; font-family:'Tajawal',sans-serif; font-size:0.85rem; color:#374151; line-height:1.65; min-height:2rem; white-space:pre-wrap; word-break:break-word; background:#fafafa;">{{ $value ?: '—' }}</div>
                </div>
                @endforeach
            </div>

            <div class="no-print" style="padding:0.75rem 1.25rem; background:#fafbfc; border-top:1px solid var(--border);">
                <button wire:click="saveRecord" wire:loading.attr="disabled" class="btn btn-primary" style="padding:0.55rem 2.5rem;">
                    💾 حفظ
                </button>
            </div>
        </div>

        <!-- سجل الزيارات -->
        <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden;">
            <div style="background:var(--navy); padding:0.6rem 1.25rem;">
                <span style="color:#fff; font-weight:900; font-size:0.92rem;">سجل الزيارات</span>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:0.84rem; font-family:'Tajawal',sans-serif;">
                    <thead>
                        <tr style="background:#fafbfc; border-bottom:2px solid var(--border);">
                            <th style="padding:0.7rem 0.9rem; text-align:center; font-weight:800; color:var(--text-dim);">التاريخ</th>
                            <th style="padding:0.7rem 0.9rem; font-weight:800; color:var(--text-dim);">العيادة</th>
                            <th style="padding:0.7rem 0.9rem; font-weight:800; color:var(--text-dim);">الأخصائي</th>
                            <th style="padding:0.7rem 0.9rem; font-weight:800; color:var(--text-dim);">الوصف</th>
                            <th style="padding:0.7rem 0.9rem; font-weight:800; color:var(--text-dim);">تقييم الحالة</th>
                            <th style="padding:0.7rem 0.9rem; font-weight:800; color:var(--text-dim);">الأعراض</th>
                            <th style="padding:0.7rem 0.9rem; font-weight:800; color:var(--text-dim);">توصية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($visits as $v)
                        <tr style="border-bottom:1px solid #f0f2f5;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                            <td style="padding:0.65rem 0.9rem; text-align:center; white-space:nowrap;">
                                <div style="font-weight:700; color:#1565c0; direction:ltr; unicode-bidi:isolate;">{{ fmt_date($v->rec_date) }}</div>
                                <div style="font-size:0.72rem; color:var(--text-muted); direction:ltr; unicode-bidi:isolate;">{{ $v->rec_time }}</div>
                            </td>
                            <td style="padding:0.65rem 0.9rem; color:var(--text-dim);">{{ $v->clinic_name ?: '—' }}</td>
                            <td style="padding:0.65rem 0.9rem; color:var(--text-dim);">{{ trim($v->doctor_name) ?: '—' }}</td>
                            <td style="padding:0.65rem 0.9rem; color:var(--text-dim); max-width:200px;"><div style="max-height:3rem; overflow:hidden;">{{ $v->visit_notes ?: '—' }}</div></td>
                            <td style="padding:0.65rem 0.9rem; color:var(--text-dim);">{{ $v->dia ?: '—' }}</td>
                            <td style="padding:0.65rem 0.9rem; color:var(--text-dim);">{{ $v->sym ?: '—' }}</td>
                            <td style="padding:0.65rem 0.9rem; color:var(--text-dim);">{{ $v->pres ?: '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" style="padding:3rem; text-align:center; color:var(--text-muted);">
                            <div style="font-size:2rem; margin-bottom:0.5rem;">📂</div>
                            لا توجد زيارات مسجلة
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
