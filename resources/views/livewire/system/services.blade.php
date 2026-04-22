<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1100px; margin:0 auto; animation:fadeIn 0.4s ease;">

{{-- رأس --}}
<div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.4rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">⚙️ إدارة الخدمات</h1>
        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:0.2rem; font-weight:600;">إضافة وتعديل وحذف خدمات المكاتب</div>
    </div>
    <button wire:click="openCreate"
        style="display:inline-flex; align-items:center; gap:0.5rem; background:var(--primary); color:#fff; padding:0.65rem 1.4rem; border-radius:10px; font-weight:800; font-size:0.9rem; border:none; cursor:pointer; font-family:'Tajawal',sans-serif;">
        ＋ إضافة خدمة
    </button>
</div>

{{-- flash --}}
@if(session()->has('svc_saved'))
<div style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0; border-radius:9px; padding:0.65rem 1.25rem; margin-bottom:1rem; font-weight:800; font-size:0.88rem;">
    ✅ {{ session('svc_saved') }}
</div>
@endif

{{-- فلاتر --}}
<div style="background:#fff; border:1px solid var(--border); border-radius:12px; padding:1rem 1.25rem; margin-bottom:1rem; display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center;">
    <div style="position:relative; flex:1; min-width:220px;">
        <input type="text" wire:model.live.debounce.300ms="search"
            placeholder="بحث باسم الخدمة..."
            class="form-input" style="padding-right:2.6rem;">
        <span style="position:absolute; right:0.85rem; top:50%; transform:translateY(-50%); opacity:0.35; font-size:1rem;">🔍</span>
    </div>
    <select wire:model.live="filterClinic" class="form-input" style="width:220px;">
        <option value="">جميع المكاتب</option>
        @foreach($clinics as $cl)
        <option value="{{ $cl->id }}">{{ $cl->name }}</option>
        @endforeach
    </select>
    @if($search || $filterClinic)
    <button wire:click="$set('search',''); $set('filterClinic','')"
        style="padding:0.55rem 1rem; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#dc2626; font-size:0.82rem; font-weight:800; cursor:pointer;">
        ✕ مسح
    </button>
    @endif
</div>

{{-- تأكيد الحذف --}}
@if($deleteId)
<div style="position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); display:flex; align-items:center; justify-content:center; padding:1rem;">
    <div style="background:#fff; border-radius:14px; padding:2rem; max-width:380px; width:100%; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <div style="font-size:2.5rem; margin-bottom:0.75rem;">🗑️</div>
        <div style="font-size:1rem; font-weight:900; color:var(--navy); margin-bottom:0.5rem;">تأكيد الحذف</div>
        <div style="font-size:0.85rem; color:var(--text-muted); margin-bottom:1.5rem;">هل أنت متأكد من حذف هذه الخدمة؟ لا يمكن التراجع.</div>
        <div style="display:flex; gap:0.75rem; justify-content:center;">
            <button wire:click="$set('deleteId', 0)"
                style="padding:0.55rem 1.25rem; background:#f1f5f9; border:1px solid #e2e8f0; border-radius:8px; font-weight:800; cursor:pointer; font-family:'Tajawal',sans-serif;">إلغاء</button>
            <button wire:click="delete"
                style="padding:0.55rem 1.5rem; background:#dc2626; color:#fff; border:none; border-radius:8px; font-weight:900; cursor:pointer; font-family:'Tajawal',sans-serif;">🗑️ حذف</button>
        </div>
    </div>
</div>
@endif

{{-- الجدول --}}
<div style="background:#fff; border:1px solid var(--border); border-radius:14px; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,0.06);">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif;">
            <thead>
                <tr style="background:#f1f5f9; border-bottom:2px solid var(--border);">
                    <th style="padding:0.75rem 1.25rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim);">#</th>
                    <th style="padding:0.75rem 1rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim);">اسم الخدمة</th>
                    <th style="padding:0.75rem 1rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim);">المكتب / الأخصائي</th>
                    <th style="padding:0.75rem 1rem; text-align:center; font-size:0.78rem; font-weight:800; color:var(--text-dim);">سعر الجلسة</th>
                    <th style="padding:0.75rem 1rem; text-align:center; font-size:0.78rem; font-weight:800; color:var(--text-dim);">التكلفة</th>
                    <th style="padding:0.75rem 1rem; text-align:center; font-size:0.78rem; font-weight:800; color:var(--text-dim);">الحالة</th>
                    <th style="padding:0.75rem 1rem; text-align:center; font-size:0.78rem; font-weight:800; color:var(--text-dim);">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $i => $svc)
                <tr style="border-bottom:1px solid #f1f5f9; transition:background 0.15s;"
                    onmouseover="this.style.background='#fef9f9'" onmouseout="this.style.background=''">
                    <td style="padding:0.85rem 1.25rem; font-size:0.78rem; color:var(--text-muted); font-weight:700;">{{ $services->firstItem() + $loop->index }}</td>
                    <td style="padding:0.85rem 1rem;">
                        <div style="font-weight:800; color:var(--navy); font-size:0.92rem;">{{ $svc->name }}</div>
                    </td>
                    <td style="padding:0.85rem 1rem;">
                        <span style="font-size:0.85rem; color:var(--text-dim); font-weight:700;">{{ $svc->clinic_name ?: '—' }}</span>
                    </td>
                    <td style="padding:0.85rem 1rem; text-align:center;">
                        <span style="background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; padding:0.2rem 0.75rem; border-radius:20px; font-weight:800; font-size:0.85rem; font-family:'Inter';">
                            {{ number_format((float)$svc->price, 3) }} د.ك
                        </span>
                    </td>
                    <td style="padding:0.85rem 1rem; text-align:center; color:var(--text-muted); font-size:0.83rem; font-weight:700; font-family:'Inter';">
                        {{ $svc->cost > 0 ? number_format((float)$svc->cost, 3) : '—' }}
                    </td>
                    <td style="padding:0.85rem 1rem; text-align:center;">
                        @if($svc->state_id == 0)
                            <span style="background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; padding:0.2rem 0.7rem; border-radius:20px; font-size:0.75rem; font-weight:800;">فعّال</span>
                        @else
                            <span style="background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; padding:0.2rem 0.7rem; border-radius:20px; font-size:0.75rem; font-weight:800;">موقوف</span>
                        @endif
                    </td>
                    <td style="padding:0.85rem 1rem; text-align:center;">
                        <div style="display:inline-flex; gap:0.4rem;">
                            <button wire:click="openEdit({{ $svc->id }})"
                                style="padding:0.3rem 0.75rem; background:#eff6ff; color:#1565c0; border:1px solid #bfdbfe; border-radius:7px; font-size:0.75rem; font-weight:800; cursor:pointer; font-family:'Tajawal',sans-serif;"
                                onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                                ✏️ تعديل
                            </button>
                            <button wire:click="confirmDelete({{ $svc->id }})"
                                style="padding:0.3rem 0.75rem; background:#fef2f2; color:#dc2626; border:1px solid #fecaca; border-radius:7px; font-size:0.75rem; font-weight:800; cursor:pointer; font-family:'Tajawal',sans-serif;"
                                onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                                🗑️ حذف
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:4rem; text-align:center; color:var(--text-muted); font-size:0.9rem;">
                        لا توجد خدمات مطابقة
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:0.75rem 1.25rem; border-top:1px solid var(--border); background:#fafbfc;">
        <x-pg-nav :paginator="$services" />
    </div>
</div>

</div>

{{-- modal إضافة/تعديل --}}
@if($showModal)
<div wire:click.self="$set('showModal', false)"
    style="position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.55); display:flex; align-items:center; justify-content:center; padding:1rem; animation:fadeIn 0.2s ease;">
    <div style="background:#fff; border-radius:16px; width:100%; max-width:500px; box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow:hidden; animation:slideUp 0.25s cubic-bezier(0.16,1,0.3,1);">

        <div style="background:var(--navy); padding:1rem 1.5rem; display:flex; align-items:center; justify-content:space-between; border-bottom:3px solid var(--primary);">
            <div style="display:flex; align-items:center; gap:0.65rem;">
                <span style="font-size:1.2rem;">{{ $editId ? '✏️' : '➕' }}</span>
                <div style="color:#fff; font-weight:900; font-size:0.95rem;">
                    {{ $editId ? 'تعديل خدمة' : 'إضافة خدمة جديدة' }}
                </div>
            </div>
            <button wire:click="$set('showModal', false)"
                style="width:30px; height:30px; border-radius:7px; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15); color:rgba(255,255,255,0.7); font-size:1rem; cursor:pointer;">✕</button>
        </div>

        <div style="padding:1.5rem; display:flex; flex-direction:column; gap:1rem;">

            {{-- اسم الخدمة --}}
            <div>
                <label style="font-size:0.8rem; font-weight:800; color:var(--text-dim); display:block; margin-bottom:0.35rem;">اسم الخدمة <span style="color:var(--primary);">*</span></label>
                <input type="text" wire:model="formName" placeholder="مثال: جلسة استشارة فردية"
                    class="form-input" style="width:100%;">
                @error('formName') <div style="color:#dc2626; font-size:0.75rem; margin-top:0.25rem;">{{ $message }}</div> @enderror
            </div>

            {{-- المكتب / الأخصائي --}}
            <div>
                <label style="font-size:0.8rem; font-weight:800; color:var(--text-dim); display:block; margin-bottom:0.35rem;">المكتب / الأخصائي <span style="color:var(--primary);">*</span></label>
                <select wire:model="formClinic" class="form-input" style="width:100%;">
                    <option value="">— اختر —</option>
                    @foreach($clinics as $cl)
                    <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                    @endforeach
                </select>
                @error('formClinic') <div style="color:#dc2626; font-size:0.75rem; margin-top:0.25rem;">{{ $message }}</div> @enderror
            </div>

            {{-- السعر والتكلفة --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div>
                    <label style="font-size:0.8rem; font-weight:800; color:var(--text-dim); display:block; margin-bottom:0.35rem;">سعر الجلسة (د.ك) <span style="color:var(--primary);">*</span></label>
                    <input type="number" wire:model="formPrice" placeholder="0.000" step="0.001" min="0"
                        class="form-input" style="width:100%; direction:ltr;">
                    @error('formPrice') <div style="color:#dc2626; font-size:0.75rem; margin-top:0.25rem;">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label style="font-size:0.8rem; font-weight:800; color:var(--text-dim); display:block; margin-bottom:0.35rem;">التكلفة (اختياري)</label>
                    <input type="number" wire:model="formCost" placeholder="0.000" step="0.001" min="0"
                        class="form-input" style="width:100%; direction:ltr;">
                </div>
            </div>

        </div>

        <div style="padding:1rem 1.5rem; border-top:1px solid #f1f5f9; display:flex; gap:0.75rem; justify-content:flex-end;">
            <button wire:click="$set('showModal', false)"
                style="padding:0.55rem 1.25rem; background:#f1f5f9; border:1px solid #e2e8f0; border-radius:8px; font-weight:800; font-size:0.85rem; cursor:pointer; font-family:'Tajawal',sans-serif;">
                إلغاء
            </button>
            <button wire:click="save"
                style="padding:0.55rem 1.5rem; background:var(--primary); color:#fff; border:none; border-radius:8px; font-weight:900; font-size:0.85rem; cursor:pointer; font-family:'Tajawal',sans-serif;">
                💾 {{ $editId ? 'حفظ التعديل' : 'إضافة الخدمة' }}
            </button>
        </div>

    </div>
</div>
@endif
</div>
