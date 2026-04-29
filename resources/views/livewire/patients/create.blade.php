<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1100px; margin:0 auto; background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow-sm); overflow:hidden; animation:fadeIn 0.5s ease;">

    <!-- رأس الإطار -->
    <div style="padding:1.25rem 1.75rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; justify-content:space-between;">
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
    <div style="padding:1.75rem;">

        @if(session()->has('success'))
        <div style="background:#e8f5e9; color:#2e7d32; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.25rem; font-weight:700; border:1px solid #c8e6c9; display:flex; align-items:center; gap:0.75rem;">
            ✅ {{ session('success') }}
        </div>
        @endif

        <form wire:submit.prevent="save">

            <!-- اختيار الفرع -->
            <div class="card" style="margin-bottom:1.25rem; border:2px solid var(--primary);">
                <div class="card-header" style="background:var(--primary);">
                    <span class="card-title" style="color:#fff;">🏢 الفرع <span style="opacity:0.7; font-size:0.78rem;">(مطلوب)</span></span>
                </div>
                <div class="card-body" style="padding:1rem 1.25rem; display:flex; gap:1rem; flex-wrap:wrap;">
                    @foreach($branches as $branch)
                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; padding:0.65rem 1.1rem; border-radius:10px; border:2px solid {{ $branch_id == $branch->id ? 'var(--primary)' : 'var(--border)' }}; background:{{ $branch_id == $branch->id ? 'var(--primary-glow)' : '#fafbfc' }}; transition:all 0.15s; flex:1; min-width:220px;">
                        <input type="radio" wire:model="branch_id" value="{{ $branch->id }}" style="accent-color:var(--primary); width:16px; height:16px;">
                        <span style="font-weight:800; font-size:0.88rem; color:{{ $branch_id == $branch->id ? 'var(--primary)' : 'var(--text-dim)' }}; font-family:'Tajawal',sans-serif;">{{ $branch->name }}</span>
                    </label>
                    @endforeach
                </div>
                @error('branch_id') <div style="padding:0.4rem 1.25rem 0.75rem; color:var(--danger); font-size:0.82rem; font-weight:700;">⚠ {{ $message }}</div> @enderror
            </div>

            <!-- البيانات الأساسية -->
            <div class="card" style="margin-bottom:1.25rem;">
                <div class="card-header">
                    <span class="card-title">👤 البيانات الأساسية</span>
                </div>
                <div class="card-body">
                    <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:1.25rem;">

                        <!-- الاسم كامل -->
                        <div style="grid-column:span 2;">
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
                            <div style="display:flex; gap:0; border:1px solid #d1d5db; border-radius:8px; overflow:visible; background:#fff;">
                                <!-- كود الدولة -->
                                <div x-data="{
                                    open: false,
                                    search: '',
                                    selected: { flag:'🇰🇼', name:'الكويت', dial:'+965' },
                                    countries: @json($countryCodes),
                                    get filtered() {
                                        if (!this.search) return this.countries;
                                        const s = this.search;
                                        return this.countries.filter(c => c.name.includes(s) || c.dial.includes(s));
                                    },
                                    pick(c) {
                                        this.selected = c;
                                        $wire.set('phone_code', c.dial);
                                        this.open = false;
                                        this.search = '';
                                    }
                                }" @click.outside="open=false" style="position:relative; flex-shrink:0;">
                                    <button type="button" @click="open=!open"
                                        style="height:42px; padding:0 0.75rem; background:#f8fafc; border:none; border-left:1px solid #d1d5db; border-radius:0 8px 8px 0; cursor:pointer; display:flex; align-items:center; gap:0.4rem; font-family:'Tajawal',sans-serif; font-size:0.88rem; font-weight:700; color:var(--navy); white-space:nowrap; min-width:90px; justify-content:center;">
                                        <span x-text="selected.flag"></span>
                                        <span x-text="selected.dial" style="color:var(--primary);"></span>
                                        <span style="font-size:0.7rem; color:#999;">▾</span>
                                    </button>
                                    <div x-show="open" x-transition
                                        style="position:absolute; top:calc(100% + 4px); right:0; z-index:9999; background:#fff; border:1px solid #d1d5db; border-radius:10px; width:260px; box-shadow:0 8px 24px rgba(0,0,0,0.13); display:flex; flex-direction:column; overflow:hidden;">
                                        <div style="padding:0.5rem 0.6rem; border-bottom:1px solid #f0f0f0;">
                                            <input x-model="search" @click.stop
                                                placeholder="🔍 بحث بالدولة أو الكود..."
                                                style="width:100%; border:1px solid #e0e0e0; border-radius:6px; padding:0.4rem 0.6rem; font-family:'Tajawal',sans-serif; font-size:0.83rem; outline:none; box-sizing:border-box;">
                                        </div>
                                        <div style="overflow-y:auto; max-height:220px;">
                                            <template x-for="c in filtered" :key="c.name">
                                                <div @click="pick(c)"
                                                    :style="selected.name===c.name ? 'background:var(--primary-glow);' : ''"
                                                    style="padding:0.45rem 0.75rem; cursor:pointer; display:flex; align-items:center; gap:0.5rem; font-family:'Tajawal',sans-serif; transition:background 0.1s;"
                                                    onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background=''">
                                                    <span x-text="c.flag" style="font-size:1.1rem;"></span>
                                                    <span x-text="c.name" style="font-size:0.83rem; flex:1;"></span>
                                                    <span x-text="c.dial" style="font-size:0.8rem; font-weight:800; color:var(--primary);"></span>
                                                </div>
                                            </template>
                                            <div x-show="filtered.length===0" style="padding:1rem; text-align:center; color:#999; font-size:0.83rem;">لا توجد نتائج</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- رقم الجوال -->
                                <input type="text" wire:model="phone" placeholder="05XXXXXXXX"
                                    style="flex:1; border:none; outline:none; padding:0 0.85rem; font-family:'Tajawal',sans-serif; font-size:0.92rem; border-radius:8px 0 0 8px; background:transparent; min-width:0;">
                            </div>
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
                            <div x-data="{
                                open: false,
                                search: '',
                                selected: 'كويتي/ة',
                                items: @json($nationalities),
                                get filtered() {
                                    if (!this.search) return this.items;
                                    const s = this.search;
                                    return this.items.filter(i => i.nat.includes(s) || i.country.includes(s));
                                },
                                pick(nat) {
                                    this.selected = nat;
                                    $wire.set('nationality', nat);
                                    this.open = false;
                                    this.search = '';
                                }
                            }" @click.outside="open=false" style="position:relative;">
                                <button type="button" @click="open=!open"
                                    class="form-input"
                                    style="display:flex; align-items:center; justify-content:space-between; cursor:pointer; text-align:right; gap:0.5rem;">
                                    <span x-text="selected" style="flex:1;"></span>
                                    <span style="font-size:0.7rem; color:#999;">▾</span>
                                </button>
                                <div x-show="open" x-transition
                                    style="position:absolute; top:calc(100% + 4px); right:0; left:0; z-index:9998; background:#fff; border:1px solid #d1d5db; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,0.13); display:flex; flex-direction:column; overflow:hidden;">
                                    <div style="padding:0.5rem 0.6rem; border-bottom:1px solid #f0f0f0;">
                                        <input x-model="search" @click.stop
                                            placeholder="🔍 بحث بالجنسية أو الدولة..."
                                            style="width:100%; border:1px solid #e0e0e0; border-radius:6px; padding:0.4rem 0.6rem; font-family:'Tajawal',sans-serif; font-size:0.83rem; outline:none; box-sizing:border-box;">
                                    </div>
                                    <div style="overflow-y:auto; max-height:220px;">
                                        <template x-for="item in filtered" :key="item.nat">
                                            <div @click="pick(item.nat)"
                                                :style="selected===item.nat ? 'background:var(--primary-glow);' : ''"
                                                style="padding:0.45rem 0.75rem; cursor:pointer; display:flex; align-items:center; justify-content:space-between; font-family:'Tajawal',sans-serif; transition:background 0.1s;"
                                                onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background=''">
                                                <span x-text="item.nat" style="font-size:0.83rem; font-weight:700;"></span>
                                                <span x-text="item.country" style="font-size:0.78rem; color:#999;"></span>
                                            </div>
                                        </template>
                                        <div x-show="filtered.length===0" style="padding:1rem; text-align:center; color:#999; font-size:0.83rem;">لا توجد نتائج</div>
                                    </div>
                                </div>
                            </div>
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
                        <div style="grid-column:span 2;">
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
                    <div class="pg-3col" style="display:grid; grid-template-columns:repeat(3,1fr); gap:1.25rem;">

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
            <div style="display:flex; align-items:center; justify-content:center; gap:1rem;">
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
