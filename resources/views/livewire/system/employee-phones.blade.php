<div>
<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1200px; margin:0 auto; animation:fadeIn 0.4s ease;">

    <!-- رأس الصفحة -->
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
        <div>
            <h1 style="font-size:1.5rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">
                📱 أرقام هاتف الموظفين
            </h1>
            <div style="font-size:0.83rem; color:var(--text-muted); margin-top:0.2rem; font-weight:600;">
                تحديث ومراجعة أرقام الموظفين للتنبيهات
            </div>
        </div>
    </div>

    @if($successMsg)
    <div style="background:#dcfce7; border:1px solid #86efac; border-radius:10px; padding:1rem; margin-bottom:1rm; color:#166534; font-weight:600;">
        {{ $successMsg }}
    </div>
    @endif

    <!-- الجدول -->
    <div style="background:#fff; border:1px solid var(--border); border-radius:14px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden;">

        <!-- الفلاتر -->
        <div style="padding:1rem 1.25rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center;">
            <div style="position:relative; flex:1; min-width:220px;">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="ابحث باسم الموظف أو الرقم..."
                    class="form-input" style="padding-right:2.6rem;">
                <span style="position:absolute; right:0.85rem; top:50%; transform:translateY(-50%); opacity:0.35; font-size:1rem;">🔍</span>
            </div>
            <label style="display:flex; align-items:center; gap:0.5rem; font-weight:700; cursor:pointer; font-size:0.9rem;">
                <input type="checkbox" wire:model.live="filterMissing">
                بدون أرقام فقط
            </label>
            @if($search || $filterMissing)
            <button wire:click="$set('search',''); $set('filterMissing',false)"
                style="padding:0.55rem 1rem; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#dc2626; font-size:0.82rem; font-weight:800; cursor:pointer;">
                ✕ مسح الفلاتر
            </button>
            @endif
        </div>

        <!-- الجدول -->
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f1f5f9; border-bottom:2px solid var(--border);">
                        <th style="padding:0.75rem 1.25rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim); text-transform:uppercase;">#</th>
                        <th style="padding:0.75rem 1rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim); text-transform:uppercase;">الاسم</th>
                        <th style="padding:0.75rem 1rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim); text-transform:uppercase;">الوظيفة</th>
                        <th style="padding:0.75rem 1rem; text-align:center; font-size:0.78rem; font-weight:800; color:var(--text-dim); text-transform:uppercase;">رقم الهاتف</th>
                        <th style="padding:0.75rem 1rem; text-align:center; font-size:0.78rem; font-weight:800; color:var(--text-dim); text-transform:uppercase;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $i => $emp)
                    <tr style="border-bottom:1px solid #f1f5f9; transition:background 0.15s;"
                        onmouseover="this.style.background='#fef9f9'"
                        onmouseout="this.style.background='transparent'">
                        <td style="padding:0.85rem 1.25rem; font-size:0.8rem; color:var(--text-muted); font-weight:700;">
                            {{ $employees->firstItem() + $loop->index }}
                        </td>
                        <td style="padding:0.85rem 1rem;">
                            <div style="font-size:0.95rem; font-weight:800; color:var(--navy);">
                                {{ $emp->first_name ?? '' }} {{ $emp->middle_initial ?? '' }}
                            </div>
                            <div style="font-size:0.75rem; color:var(--text-muted); font-weight:600;">
                                {{ $emp->emp_no ?? '—' }}
                            </div>
                        </td>
                        <td style="padding:0.85rem 1rem;">
                            <span style="font-size:0.85rem; font-weight:600; color:#1e40af; background:#eff6ff; padding:0.2rem 0.6rem; border-radius:6px; display:inline-block;">
                                {{ $emp->role ?? '—' }}
                            </span>
                        </td>
                        <td style="padding:0.85rem 1rem; text-align:center;">
                            @if($emp->phone && trim($emp->phone) && $emp->phone !== '—')
                            <span style="display:inline-block; background:#dcfce7; color:#166534; padding:0.35rem 0.7rem; border-radius:6px; font-weight:700; font-size:0.85rem; direction:ltr; font-family:monospace;">
                                {{ preg_replace('/^965/', '+965 ', $emp->phone) }}
                            </span>
                            @else
                            <span style="display:inline-block; background:#fecaca; color:#dc2626; padding:0.35rem 0.7rem; border-radius:6px; font-weight:700; font-size:0.85rem;">
                                ❌ لا يوجد رقم
                            </span>
                            @endif
                        </td>
                        <td style="padding:0.85rem 1rem; text-align:center;">
                            <div style="display:inline-flex; align-items:center; gap:0.4rem; flex-wrap:wrap; justify-content:center;">
                                {{-- زر للنسخ --}}
                                @if($emp->phone && trim($emp->phone) && $emp->phone !== '—')
                                <button
                                    onclick="navigator.clipboard.writeText('{{ $emp->phone }}'); alert('تم نسخ الرقم ✓');"
                                    title="نسخ الرقم"
                                    style="display:inline-flex; align-items:center; gap:0.3rem; background:#3b82f6; color:#fff; padding:0.35rem 0.7rem; border-radius:7px; font-size:0.75rem; font-weight:800; border:none; cursor:pointer; font-family:'Tajawal',sans-serif;">
                                    📋 نسخ
                                </button>
                                @endif

                                {{-- زر تعديل --}}
                                <button
                                    onclick="editEmployeePhone({{ $emp->id }}, '{{ $emp->first_name }}', '{{ $emp->phone ?? '' }}')"
                                    title="تعديل الرقم"
                                    style="display:inline-flex; align-items:center; gap:0.3rem; background:#0d6efd; color:#fff; padding:0.35rem 0.7rem; border-radius:7px; font-size:0.75rem; font-weight:800; border:none; cursor:pointer; font-family:'Tajawal',sans-serif;">
                                    ✏️ تعديل
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:2rem; text-align:center; color:var(--text-muted);">
                            لا توجد نتائج
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- الترقيم -->
        <div style="padding:1rem 1.25rem; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
            <div style="font-size:0.85rem; color:var(--text-muted);">
                عرض {{ $employees->firstItem() ?? 0 }} إلى {{ $employees->lastItem() ?? 0 }} من {{ $employees->total() }}
            </div>
            <div>
                {{ $employees->links() }}
            </div>
        </div>
    </div>

</div>
</div>

<script>
function editEmployeePhone(empId, empName, currentPhone) {
    var newPhone = prompt(`برجاء إدخال رقم الهاتف لـ: ${empName}\n\nالرقم الحالي: ${currentPhone || 'لا يوجد'}\n\nأمثلة صحيحة: 96599999999 أو 0599999999 أو 599999999`, currentPhone || '');
    
    if (newPhone !== null && newPhone.trim() !== '') {
        // إرسال الطلب للبيانات
        Livewire.dispatch('editPhone', { empId: empId, newPhone: newPhone });
    }
}

// الاستماع لحدث التحديث
window.addEventListener('load', function() {
    if (window.Livewire) {
        window.Livewire.on('phoneEdited', function() {
            alert('تم تحديث رقم الهاتف بنجاح ✓');
            location.reload();
        });
    }
});
</script>
