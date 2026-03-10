<div style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1400px; margin:0 auto; background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow-sm); overflow:hidden; animation:fadeIn 0.5s ease;">

    <!-- رأس الإطار -->
    <div style="padding:1.25rem 1.75rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="width:42px; height:42px; background:var(--primary-glow); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">✨</div>
            <div>
                <h1 style="font-size:1.4rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">كشف جديد</h1>
                <div style="font-size:0.8rem; color:var(--text-muted); margin-top:0.1rem;">تسجيل زيارة جديدة للعميل</div>
            </div>
        </div>
        <a href="{{ route('checks.index') }}" wire:navigate class="btn btn-secondary">⬅ العودة للكشوف</a>
    </div>

    @if(session()->has('success'))
    <div style="margin:1.25rem 1.75rem; background:#e8f5e9; color:var(--success); padding:0.9rem 1.25rem; border-radius:8px; font-weight:700; border:1px solid #c8e6c9; display:flex; align-items:center; gap:0.5rem;">
        ✅ {{ session('success') }}
    </div>
    @endif

    <!-- بيانات العميل -->
    <div style="margin:0 1.75rem 1.25rem; border:1px solid var(--border); border-radius:12px; overflow:hidden;">
        <div style="background:var(--navy); padding:0.55rem 1.25rem;">
            <span style="color:#fff; font-weight:900; font-size:0.88rem;">بيانات العميل</span>
        </div>
        <div style="padding:0.85rem 1.25rem; background:#f8fafc; display:flex; flex-wrap:wrap; gap:1.5rem; align-items:center;">
            <div style="display:flex; gap:0.4rem; align-items:center;">
                <span style="font-size:0.78rem; color:var(--text-muted); font-weight:700;">الاسم:</span>
                <span style="font-weight:900; color:var(--navy); font-size:1rem;">{{ $patient->full_name }}</span>
                <span style="font-size:0.78rem; color:var(--text-muted);">(#{{ $patient->file_id }})</span>
            </div>
            <div style="display:flex; gap:0.4rem; align-items:center;">
                <span style="font-size:0.78rem; color:var(--text-muted); font-weight:700;">الجوال:</span>
                <span style="font-weight:700; color:var(--text-dim);">{{ $patient->phone ?: '—' }}</span>
            </div>
            <div style="display:flex; gap:0.4rem; align-items:center;">
                <span style="font-size:0.78rem; color:var(--text-muted); font-weight:700;">الهوية:</span>
                <span style="font-weight:700; color:var(--text-dim);">{{ $patient->ssn ?: '—' }}</span>
            </div>
            <div style="display:flex; gap:0.4rem; align-items:center;">
                <span style="font-size:0.78rem; color:var(--text-muted); font-weight:700;">التأمين:</span>
                <span style="font-weight:700; color:var(--text-dim);">{{ $patient->insurance ?: 'بدون تأمين' }}</span>
            </div>
        </div>
    </div>

    <!-- النموذج -->
    <div style="padding:0 1.75rem 1.75rem;">

        <!-- اختيار العيادة والخدمة -->
        <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden; margin-bottom:1.25rem;">
            <div style="background:var(--primary); padding:0.55rem 1.25rem;">
                <span style="color:#fff; font-weight:900; font-size:0.88rem;">تفاصيل الكشف</span>
            </div>
            <div style="padding:1.25rem; display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--primary); margin-bottom:0.4rem;">العيادة</label>
                    <select wire:model.live="selectedClinic" class="form-input">
                        <option value="">— اختر العيادة —</option>
                        @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--primary); margin-bottom:0.4rem;">الخدمة</label>
                    <select wire:model.live="selectedService" class="form-input">
                        <option value="">— اختر الخدمة —</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} @if($service->ccode)({{ $service->ccode }})@endif — {{ number_format($service->price, 3) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--primary); margin-bottom:0.4rem;">المبلغ</label>
                    <input type="number" wire:model="price" step="0.001" class="form-input" style="font-weight:800; font-size:1rem; color:var(--navy);">
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--primary); margin-bottom:0.4rem;">طريقة الدفع</label>
                    <select wire:model="paymentMethod" class="form-input">
                        <option value="1">نقد</option>
                        <option value="2">شيك</option>
                        <option value="3">شبكة (كي نت)</option>
                        <option value="4">تحويل بنكي</option>
                        <option value="6">فيزا</option>
                        <option value="7">مجاني</option>
                    </select>
                </div>
                <div style="grid-column:span 2;">
                    <label style="display:block; font-size:0.8rem; font-weight:800; color:var(--primary); margin-bottom:0.4rem;">ملاحظات</label>
                    <textarea wire:model="notes" rows="3" class="form-input" style="resize:vertical;" placeholder="أي ملاحظات إضافية..."></textarea>
                </div>
            </div>
        </div>

        <!-- ملخص المبلغ -->
        @if($price > 0)
        <div style="border:1px solid #d1fae5; border-radius:12px; padding:1rem 1.25rem; margin-bottom:1.25rem; background:#f0fdf4; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
            <div style="font-weight:800; color:var(--success); font-size:0.95rem;">✅ المبلغ المطلوب دفعه</div>
            <div style="font-size:2rem; font-weight:900; color:var(--success);">
                {{ number_format($price, 3) }} <span style="font-size:0.9rem; font-weight:700; opacity:0.7;">د.ك</span>
            </div>
        </div>
        @endif

        <!-- أزرار الإجراء -->
        <div style="display:flex; gap:1rem; align-items:center; justify-content:flex-start;">
            <button wire:click="save" wire:loading.attr="disabled" class="btn btn-primary" style="padding:0.7rem 3rem; font-size:1rem;">
                <span wire:loading.remove wire:target="save">💾 حفظ الكشف</span>
                <span wire:loading wire:target="save">⏳ جارٍ الحفظ...</span>
            </button>
            <a href="{{ route('checks.index') }}" wire:navigate class="btn btn-secondary" style="padding:0.7rem 1.5rem;">إلغاء</a>
        </div>

    </div>

    <!-- تحذير سفلي -->
    <div style="background:var(--navy); color:rgba(255,255,255,0.7); padding:0.75rem 1.75rem; font-size:0.82rem; font-weight:700; display:flex; align-items:center; gap:0.5rem; border-top:3px solid var(--gold);">
        ⚠️ يرجى عدم تكرار الضغط على زر الحفظ أثناء معالجة الطلب لضمان دقة البيانات
    </div>

</div>
</div>
