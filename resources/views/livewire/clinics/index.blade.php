<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:900px; margin:0 auto; animation:fadeIn 0.4s ease;">

    <!-- رأس الصفحة -->
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
        <div>
            <h1 style="font-size:1.5rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">إدارة العيادات</h1>
            <div style="font-size:0.83rem; color:var(--text-muted); margin-top:0.2rem; font-weight:600;">
                {{ $clinics->count() }} عيادة مسجلة
            </div>
        </div>
    </div>

    @if($successMsg)
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:0.85rem 1.25rem; margin-bottom:1.25rem; color:#15803d; font-weight:700; font-size:0.9rem; display:flex; align-items:center; gap:0.5rem;">
        ✅ {{ $successMsg }}
    </div>
    @endif

    @if($errorMsg)
    <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:0.85rem 1.25rem; margin-bottom:1.25rem; color:#dc2626; font-weight:700; font-size:0.9rem; display:flex; align-items:center; gap:0.5rem;">
        ⚠️ {{ $errorMsg }}
    </div>
    @endif

    <!-- إضافة عيادة جديدة -->
    <div style="background:#fff; border:1px solid var(--border); border-radius:14px; margin-bottom:1.5rem; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.05);">
        <div style="background:var(--primary); padding:0.7rem 1.25rem; border-radius:14px 14px 0 0;">
            <span style="color:#fff; font-weight:800; font-size:0.9rem;">➕ إضافة عيادة جديدة</span>
        </div>
        <div style="padding:1.1rem 1.25rem; display:flex; gap:0.75rem; align-items:center;">
            <input type="text" wire:model="newName" wire:keydown.enter="addClinic"
                placeholder="اكتب اسم العيادة الجديدة..."
                class="form-input" style="flex:1;">
            <button wire:click="addClinic"
                style="background:var(--primary); color:#fff; border:none; border-radius:8px; padding:0.65rem 1.5rem; font-weight:800; font-size:0.88rem; cursor:pointer; white-space:nowrap; font-family:'Tajawal',sans-serif; transition:opacity 0.2s;"
                onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
                حفظ
            </button>
        </div>
    </div>

    <!-- قائمة العيادات -->
    <div style="background:#fff; border:1px solid var(--border); border-radius:14px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.05);">
        <div style="padding:0.8rem 1.25rem; background:#f8fafc; border-bottom:1px solid var(--border); border-radius:14px 14px 0 0;">
            <span style="font-weight:800; color:var(--text); font-size:0.9rem;">🏥 قائمة العيادات</span>
        </div>

        <div>
            @foreach($clinics as $i => $clinic)
            <div style="display:flex; align-items:center; gap:1rem; padding:0.85rem 1.25rem; border-bottom:{{ !$loop->last ? '1px solid #f1f5f9' : 'none' }}; transition:background 0.15s;"
                onmouseover="this.style.background='#fafafa'"
                onmouseout="this.style.background='transparent'">

                <!-- رقم + اسم -->
                <div style="width:36px; height:36px; background:var(--primary-glow); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:0.78rem; font-weight:900; color:var(--primary); flex-shrink:0;">
                    {{ $clinic->id }}
                </div>

                @if($editingId === $clinic->id)
                    <!-- وضع التعديل -->
                    <input type="text" wire:model="editingName" wire:keydown.enter="saveEdit" wire:keydown.escape="cancelEdit"
                        class="form-input" style="flex:1; font-weight:700;"
                        autofocus>
                    <button wire:click="saveEdit"
                        style="background:#16a34a; color:#fff; border:none; border-radius:8px; padding:0.5rem 1rem; font-weight:800; font-size:0.82rem; cursor:pointer; font-family:'Tajawal',sans-serif; white-space:nowrap;">
                        ✓ حفظ
                    </button>
                    <button wire:click="cancelEdit"
                        style="background:#f1f5f9; color:var(--text-dim); border:1px solid var(--border); border-radius:8px; padding:0.5rem 0.85rem; font-weight:700; font-size:0.82rem; cursor:pointer; font-family:'Tajawal',sans-serif;">
                        إلغاء
                    </button>
                @elseif($confirmDelete === $clinic->id)
                    <!-- تأكيد الحذف -->
                    <span style="flex:1; font-size:0.9rem; font-weight:700; color:#dc2626;">هل أنت متأكد من حذف "<strong>{{ $clinic->name }}</strong>" ؟</span>
                    <button wire:click="deleteClinic"
                        style="background:#dc2626; color:#fff; border:none; border-radius:8px; padding:0.5rem 1rem; font-weight:800; font-size:0.82rem; cursor:pointer; font-family:'Tajawal',sans-serif; white-space:nowrap;">
                        نعم، احذف
                    </button>
                    <button wire:click="cancelDelete"
                        style="background:#f1f5f9; color:var(--text-dim); border:1px solid var(--border); border-radius:8px; padding:0.5rem 0.85rem; font-weight:700; font-size:0.82rem; cursor:pointer; font-family:'Tajawal',sans-serif;">
                        إلغاء
                    </button>
                @else
                    <!-- وضع العرض -->
                    <span style="flex:1; font-size:0.95rem; font-weight:700; color:var(--navy);">{{ $clinic->name }}</span>
                    <button wire:click="startEdit({{ $clinic->id }}, {{ \Illuminate\Support\Js::from($clinic->name) }})"
                        style="background:#f0f7ff; color:#1565c0; border:1px solid #bbdefb; border-radius:8px; padding:0.45rem 1rem; font-weight:700; font-size:0.82rem; cursor:pointer; font-family:'Tajawal',sans-serif; transition:all 0.15s; white-space:nowrap;"
                        onmouseover="this.style.background='#1565c0'; this.style.color='#fff'"
                        onmouseout="this.style.background='#f0f7ff'; this.style.color='#1565c0'">
                        ✏️ تعديل
                    </button>
                    <button wire:click="askDelete({{ $clinic->id }})"
                        style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca; border-radius:8px; padding:0.45rem 0.85rem; font-weight:700; font-size:0.82rem; cursor:pointer; font-family:'Tajawal',sans-serif; transition:all 0.15s; white-space:nowrap;"
                        onmouseover="this.style.background='#dc2626'; this.style.color='#fff'"
                        onmouseout="this.style.background='#fef2f2'; this.style.color='#dc2626'">
                        🗑️
                    </button>
                @endif
            </div>
            @endforeach
        </div>
    </div>

</div>
</div>
