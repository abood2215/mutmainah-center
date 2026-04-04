<style>
@media (max-width: 768px) {
    .pc-outer { padding: 0.75rem !important; }
    .pc-wrap { border-radius: 10px !important; }
    .pc-header { padding: 0.85rem 1rem !important; flex-direction: column !important; align-items: flex-start !important; gap: 0.5rem !important; }
    .pc-body { padding: 1rem !important; }
    .pc-2col { grid-template-columns: 1fr !important; }
    .pc-3col { grid-template-columns: 1fr !important; }
    .pc-span2 { grid-column: span 1 !important; }
    .pc-birthdate { grid-template-columns: 1fr 2fr 1fr !important; gap: 0.35rem !important; }
    .pc-footer-btns { flex-direction: column !important; gap: 0.75rem !important; }
    .pc-footer-btns .btn { width: 100% !important; text-align: center !important; }
}
</style>
<div class="pg-outer pc-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1100px; margin:0 auto; background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow-sm); overflow:hidden; animation:fadeIn 0.5s ease;">

    <!-- رأس الإطار -->
    <div class="pc-header" style="padding:1.25rem 1.75rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="width:42px; height:42px; background:var(--primary-glow); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">👤</div>
            <div>
                <h1 style="font-size:1.3rem; font-weight:900; color:var(--primary); margin:0;">فتح ملف جديد</h1>
                <p style="font-size:0.8rem; color:var(--text-muted); margin:0;">تسجيل بيانات عميل جديد في النظام</p>
            </div>
        </div>
        <a href="{{ route('patients.index') }}" wire:navigate class="btn btn-secondary">← العودة</a>
    </div>

    <!-- المحتوى -->
    <div class="pc-body" style="padding:1.75rem;">

        @if(session()->has('success'))
        <div style="background:#e8f5e9; color:#2e7d32; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.25rem; font-weight:700; border:1px solid #c8e6c9; display:flex; align-items:center; gap:0.75rem;">
            ✅ {{ session('success') }}
        </div>
        @endif

        <form wire:submit.prevent="save">

            <!-- البيانات الأساسية -->
            <div class="card" style="margin-bottom:1.25rem;">
                <div class="card-header">
                    <span class="card-title">👤 البيانات الأساسية</span>
                </div>
                <div class="card-body">
                    <div class="pc-2col" style="display:grid; grid-template-columns:repeat(2,1fr); gap:1.25rem;">

                        <!-- الاسم كامل -->
                        <div class="pc-span2" style="grid-column:span 2;">
                            <label style="display:block; font-size:0.85rem; font-weight:800; color:var(--text-dim); margin-bottom:0.4rem;">
                                الاسم بالكامل <span style="color:var(--danger);">*</span>
                            </label>
                            <input type="text" wire:model="name" placeholder="أدخل اسم العميل كاملاً..."
                                class="form-input" style="font-size:1rem;">
                            @error('name') <span style="color:var(--danger); font-size:0.8rem; font-weight:700; margin-top:0.3rem; display:block;">{{ $message }}</span> @enderror
                        </div>

                        <!-- رقم الهوية -->
                        <div>
                            <label style="display:block; font-size:0.85rem; font-weight:800; color:var(--text-dim); margin-bottom:0.4rem;">رقم الهوية / الإقامة</label>
                            <input type="text" wire:model="ssn" placeholder="رقم البطاقة المدنية..."
                                class="form-input">
                        </div>

                        <!-- رقم الجوال -->
                        <div>
                            <label style="display:block; font-size:0.85rem; font-weight:800; color:var(--text-dim); margin-bottom:0.4rem;">
                                رقم الجوال <span style="color:var(--danger);">*</span>
                            </label>
                            <input type="text" wire:model="phone" placeholder="05XXXXXXXX..."
                                class="form-input">
                            @error('phone') <span style="color:var(--danger); font-size:0.8rem; font-weight:700; margin-top:0.3rem; display:block;">{{ $message }}</span> @enderror
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div>
                            <label style="display:block; font-size:0.85rem; font-weight:800; color:var(--text-dim); margin-bottom:0.4rem;">البريد الإلكتروني</label>
                            <input type="email" wire:model="email" placeholder="example@mail.com"
                                class="form-input">
                        </div>

                        <!-- الجنس -->
                        <div>
                            <label style="display:block; font-size:0.85rem; font-weight:800; color:var(--text-dim); margin-bottom:0.4rem;">الجنس</label>
                            <select wire:model="gender" class="form-input">
                                <option value="1">ذكر</option>
                                <option value="2">أنثى</option>
                            </select>
                        </div>

                        <!-- الجنسية -->
                        <div>
                            <label style="display:block; font-size:0.85rem; font-weight:800; color:var(--text-dim); margin-bottom:0.4rem;">الجنسية</label>
                            <select wire:model="nationality" class="form-input">
                                <option value="1">كويتي</option>
                                <option value="2">سعودي</option>
                                <option value="3">مصري</option>
                                <option value="4">أردني</option>
                                <option value="5">لبناني</option>
                                <option value="6">سوري</option>
                                <option value="99">أخرى</option>
                            </select>
                        </div>

                        <!-- الحالة الاجتماعية -->
                        <div>
                            <label style="display:block; font-size:0.85rem; font-weight:800; color:var(--text-dim); margin-bottom:0.4rem;">الحالة الاجتماعية</label>
                            <select wire:model="social" class="form-input">
                                <option value="1">أعزب/عزباء</option>
                                <option value="2">متزوج/ة</option>
                                <option value="3">مطلق/ة</option>
                                <option value="4">أرمل/ة</option>
                            </select>
                        </div>

                        <!-- تاريخ الميلاد -->
                        <div>
                            <label style="display:block; font-size:0.85rem; font-weight:800; color:var(--text-dim); margin-bottom:0.4rem;">تاريخ الميلاد (ميلادي)</label>
                            <div style="display:grid; grid-template-columns:1fr 1.5fr 1fr; gap:0.5rem;">
                                <select wire:model="birth_day" class="form-input" style="padding:0.65rem 0.5rem;">
                                    <option value="">يوم</option>
                                    @for($i=1; $i<=31; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                                </select>
                                <select wire:model="birth_month" class="form-input" style="padding:0.65rem 0.5rem;">
                                    <option value="">شهر</option>
                                    @foreach(['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'] as $i => $m)
                                        <option value="{{ $i+1 }}">{{ $m }}</option>
                                    @endforeach
                                </select>
                                <select wire:model="birth_year" class="form-input" style="padding:0.65rem 0.5rem;">
                                    <option value="">سنة</option>
                                    @for($i=date('Y'); $i>=1940; $i--) <option value="{{ $i }}">{{ $i }}</option> @endfor
                                </select>
                            </div>
                        </div>

                        <!-- العنوان -->
                        <div class="pc-span2" style="grid-column:span 2;">
                            <label style="display:block; font-size:0.85rem; font-weight:800; color:var(--text-dim); margin-bottom:0.4rem;">العنوان</label>
                            <input type="text" wire:model="address" placeholder="المنطقة / القرية..."
                                class="form-input">
                        </div>

                    </div>
                </div>
            </div>

            <!-- بيانات التأمين -->
            <div class="card" style="margin-bottom:1.25rem;">
                <div class="card-header">
                    <span class="card-title">🏥 بيانات التأمين</span>
                </div>
                <div class="card-body">
                    <div class="pg-3col pc-3col" style="display:grid; grid-template-columns:repeat(3,1fr); gap:1.25rem;">

                        <!-- شركة التأمين -->
                        <div>
                            <label style="display:block; font-size:0.85rem; font-weight:800; color:var(--text-dim); margin-bottom:0.4rem;">شركة التأمين</label>
                            <select wire:model="com_id" class="form-input">
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- رقم البوليصة -->
                        <div>
                            <label style="display:block; font-size:0.85rem; font-weight:800; color:var(--text-dim); margin-bottom:0.4rem;">رقم البوليصة</label>
                            <input type="text" wire:model="assur_no" placeholder="Policy Number..."
                                class="form-input">
                        </div>

                        <!-- الفئة -->
                        <div>
                            <label style="display:block; font-size:0.85rem; font-weight:800; color:var(--text-dim); margin-bottom:0.4rem;">الفئة</label>
                            <select wire:model="class_id" class="form-input">
                                <option value="0">غير محدد</option>
                                <option value="1">Class A</option>
                                <option value="2">Class B</option>
                                <option value="3">Class C</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ملاحظات -->
            <div class="card" style="margin-bottom:1.75rem;">
                <div class="card-header">
                    <span class="card-title">📝 ملاحظات</span>
                </div>
                <div class="card-body">
                    <textarea wire:model="notes" placeholder="أي ملاحظات إضافية..."
                        class="form-input" style="height:80px; resize:none;"></textarea>
                </div>
            </div>

            <!-- أزرار الحفظ -->
            <div class="pc-footer-btns" style="display:flex; align-items:center; justify-content:center; gap:1rem;">
                <button type="submit" class="btn btn-primary" style="padding:0.85rem 4rem; font-size:1rem;">
                    ➕ تسجيل العميل
                </button>
                <a href="{{ route('patients.index') }}" wire:navigate class="btn btn-secondary" style="padding:0.85rem 2rem; font-size:1rem;">
                    إلغاء
                </a>
            </div>

        </form>

    </div>
</div>
</div>
