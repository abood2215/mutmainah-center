<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1400px; margin:0 auto; background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow-sm); overflow:hidden; animation:fadeIn 0.5s ease;">

    <!-- رأس الإطار -->
    <div style="padding:1.25rem 1.75rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="width:42px; height:42px; background:var(--primary-glow); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">👨‍⚕️</div>
            <h1 style="font-size:1.4rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">الموظفين : Employees</h1>
        </div>
        <div class="pg-sw" style="position:relative; width:240px;">
            <input type="text" wire:model.live.debounce.400ms="search"
                placeholder="بحث بالاسم أو الرقم..."
                style="width:100%; padding:0.55rem 2.5rem 0.55rem 0.9rem; border:1.5px solid var(--border); border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.9rem; outline:none; transition:border-color 0.2s;"
                onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
            <span style="position:absolute; right:0.75rem; top:50%; transform:translateY(-50%); opacity:0.4; font-size:0.9rem;">🔍</span>
        </div>
    </div>

    <!-- الجدول -->
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif; font-size:0.92rem;">
            <thead>
                <tr style="background:#e8e8e8; border-bottom:1px solid #ccc;">
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#2e7d32; border-left:1px solid #ccc; width:40px;">م</th>
                    <th style="padding:0.55rem 0.75rem; text-align:right; font-weight:900; color:#2e7d32; border-left:1px solid #ccc; min-width:170px;">الاسم</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#c8401a; border-left:1px solid #ccc;">الرقم</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#c8401a; border-left:1px solid #ccc;">الوظيفة</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc;">المؤهل</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc;">التليفون</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc; min-width:140px;">البريد الالكتروني</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc;">الحالة</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#0099aa; border-left:1px solid #ccc;">العيادات</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc; width:60px;">تعديل</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#555; width:60px;">حذف</th>
                </tr>
                <tr style="background:#f5f5f5; border-bottom:2px solid #bbb;">
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#2e7d32; border-left:1px solid #ccc; font-size:0.8rem;">#</th>
                    <th style="padding:0.45rem 0.75rem; text-align:right; font-weight:900; color:#2e7d32; border-left:1px solid #ccc; font-size:0.8rem;">Name</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#c8401a; border-left:1px solid #ccc; font-size:0.8rem;">No</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#c8401a; border-left:1px solid #ccc; font-size:0.8rem;">Job</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc; font-size:0.8rem;">Qf</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc; font-size:0.8rem;">Phone</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc; font-size:0.8rem;">E.mail</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc; font-size:0.8rem;">State</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#0099aa; border-left:1px solid #ccc; font-size:0.8rem;">Clinics</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc; font-size:0.8rem;">Edit</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#555; font-size:0.8rem;">Del</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                @php $fullName = trim(($emp->first_name ?? '') . ' ' . ($emp->middle_initial ?? '')); @endphp
                <tr wire:key="emp-{{ $emp->id }}"
                    style="border-bottom:1px solid #e0e0e0; {{ $loop->even ? 'background:#fafafa;' : 'background:#fff;' }}"
                    onmouseover="this.style.background='#f0f7ff'" onmouseout="this.style.background='{{ $loop->even ? '#fafafa' : '#fff' }}'">
                    <td style="padding:0.55rem 0.75rem; text-align:center; color:#555; border-left:1px solid #eee;">{{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}</td>
                    <td style="padding:0.55rem 0.75rem; font-weight:700; color:#1a1a2e; border-left:1px solid #eee;">{{ $fullName ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; font-weight:700; color:#c8401a; border-left:1px solid #eee;">{{ $emp->emp_no ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; color:#333; border-left:1px solid #eee;">{{ $emp->job_name ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; color:#555; border-left:1px solid #eee;">{{ $emp->qual_name ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; color:#333; border-left:1px solid #eee; direction:ltr;">{{ $emp->phone ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; color:#555; font-size:0.82rem; border-left:1px solid #eee;">{{ $emp->email ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; border-left:1px solid #eee;">
                        @if($emp->state == 1)
                            <span style="background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; border-radius:20px; padding:0.15rem 0.6rem; font-size:0.78rem; font-weight:800;">فعّال</span>
                        @else
                            <span style="background:#fce4ec; color:#c62828; border:1px solid #f48fb1; border-radius:20px; padding:0.15rem 0.6rem; font-size:0.78rem; font-weight:800;">متوقف</span>
                        @endif
                    </td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; border-left:1px solid #eee;">
                        <button wire:click="openClinics({{ $emp->id }}, '{{ addslashes($fullName) }}')"
                            style="padding:0.3rem 0.7rem; background:{{ $selectedEmpId === $emp->id ? '#8b1c2b' : '#e8f4fd' }}; border:1.5px solid {{ $selectedEmpId === $emp->id ? '#8b1c2b' : '#0099aa' }}; border-radius:5px; color:{{ $selectedEmpId === $emp->id ? '#fff' : '#0099aa' }}; cursor:pointer; font-size:0.82rem; font-weight:800; font-family:'Tajawal',sans-serif; white-space:nowrap;"
                            title="عرض عيادات الموظف">
                            🏥 العيادات
                        </button>
                    </td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; border-left:1px solid #eee;">
                        <button wire:click="openEdit({{ $emp->id }})" title="تعديل"
                            style="width:30px; height:30px; background:#fff8e1; border:1px solid #f0c040; border-radius:4px; cursor:pointer; display:inline-flex; align-items:center; justify-content:center;"
                            onmouseover="this.style.background='#fff0b0'" onmouseout="this.style.background='#fff8e1'">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#c8941a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                    </td>
                    <td style="padding:0.55rem 0.75rem; text-align:center;">
                        <button wire:click="confirmDelete({{ $emp->id }}, '{{ addslashes($fullName) }}')" title="حذف"
                            style="width:30px; height:30px; background:#fff0f0; border:1px solid #e88; border-radius:4px; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; font-weight:900; color:#cc0000; font-size:0.85rem;"
                            onmouseover="this.style.background='#ffd5d5'" onmouseout="this.style.background='#fff0f0'">✕</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="11" style="padding:3rem; text-align:center; color:var(--text-muted);">لا توجد بيانات موظفين</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- قسم العيادات -->
    @if($selectedEmpId)
    <div style="margin:0; border-top:3px solid #8b1c2b; background:#fafbfc; padding:1.25rem 1.75rem; animation:fadeIn 0.3s ease;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; flex-wrap:wrap; gap:0.5rem;">
            <div style="display:flex; align-items:center; gap:0.6rem;">
                <span style="font-size:1.3rem;">🏥</span>
                <span style="font-size:1.1rem; font-weight:900; color:#8b1c2b; font-family:'Tajawal',sans-serif;">
                    عيادات : {{ $selectedEmpName }}
                </span>
                <span style="background:#8b1c2b; color:#fff; border-radius:20px; padding:0.1rem 0.6rem; font-size:0.8rem; font-weight:800;">
                    {{ count($empClinics) }}
                </span>
            </div>
            <button wire:click="openClinics({{ $selectedEmpId }}, '')"
                style="padding:0.3rem 0.8rem; background:#f5f5f5; border:1px solid #ccc; border-radius:5px; cursor:pointer; font-size:0.85rem; color:#555; font-family:'Tajawal',sans-serif;">
                ✕ إغلاق
            </button>
        </div>

        @if(count($empClinics) === 0)
            <div style="text-align:center; padding:2rem; color:#999; font-family:'Tajawal',sans-serif;">
                لا توجد عيادات مرتبطة بهذا الموظف
            </div>
        @else
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:0.75rem;">
            @foreach($empClinics as $clinic)
            <div style="background:#fff; border:1.5px solid {{ $clinic['active'] == 1 ? '#c8e6c9' : '#ffcdd2' }}; border-radius:8px; padding:0.75rem 1rem; display:flex; align-items:center; justify-content:space-between; gap:0.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="font-family:'Tajawal',sans-serif;">
                    <div style="font-weight:800; color:#1a1a2e; font-size:0.9rem;">{{ $clinic['name'] }}</div>
                    <div style="font-size:0.75rem; color:{{ $clinic['active'] == 1 ? '#2e7d32' : '#c62828' }}; font-weight:700; margin-top:0.2rem;">
                        {{ $clinic['active'] == 1 ? '● مفعلة' : '● غير مفعلة' }}
                    </div>
                </div>
                <button wire:click="toggleClinic({{ $clinic['id'] }})"
                    style="padding:0.35rem 0.85rem; background:{{ $clinic['active'] == 1 ? '#fff8e1' : '#e8f5e9' }}; border:1.5px solid {{ $clinic['active'] == 1 ? '#f0c040' : '#66bb6a' }}; border-radius:6px; cursor:pointer; font-size:0.8rem; font-weight:800; color:{{ $clinic['active'] == 1 ? '#c8941a' : '#2e7d32' }}; font-family:'Tajawal',sans-serif; white-space:nowrap; flex-shrink:0;"
                    title="{{ $clinic['active'] == 1 ? 'إيقاف العيادة' : 'تفعيل العيادة' }}">
                    {{ $clinic['active'] == 1 ? '⏸ إيقاف' : '▶ تفعيل' }}
                </button>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    <!-- Pagination -->
    <div style="background:#1a5276; padding:0.6rem 1rem; display:flex; align-items:center; justify-content:center; gap:0.3rem; flex-wrap:wrap;">
        @if($employees->onFirstPage())
            <span style="padding:0.3rem 0.7rem; color:rgba(255,255,255,0.4); font-size:0.85rem; font-weight:700;">السابق</span>
        @else
            <button wire:click="previousPage" style="padding:0.3rem 0.7rem; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.3); border-radius:3px; color:#fff; cursor:pointer; font-size:0.85rem; font-weight:700; font-family:'Tajawal',sans-serif;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">السابق</button>
        @endif
        @foreach($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
            @if($page == $employees->currentPage())
                <span style="padding:0.3rem 0.7rem; background:#fff; border-radius:3px; color:#1a5276; font-weight:900; font-size:0.85rem; min-width:32px; text-align:center;">{{ $page }}</span>
            @else
                <button wire:click="gotoPage({{ $page }})" style="padding:0.3rem 0.7rem; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.25); border-radius:3px; color:#fff; cursor:pointer; font-size:0.85rem; min-width:32px; font-family:'Tajawal',sans-serif;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">{{ $page }}</button>
            @endif
        @endforeach
        @if($employees->hasMorePages())
            <button wire:click="nextPage" style="padding:0.3rem 0.7rem; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.3); border-radius:3px; color:#fff; cursor:pointer; font-size:0.85rem; font-weight:700; font-family:'Tajawal',sans-serif;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">التالي Next</button>
        @else
            <span style="padding:0.3rem 0.7rem; color:rgba(255,255,255,0.4); font-size:0.85rem; font-weight:700;">التالي Next</span>
        @endif
    </div>

</div>
</div>

{{-- ═══════════ مودال التعديل ═══════════ --}}
@if($showEditModal)
<div style="position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; display:flex; align-items:center; justify-content:center; padding:1rem;" wire:click.self="closeEdit">
    <div style="background:#fff; border-radius:14px; width:100%; max-width:520px; box-shadow:0 20px 60px rgba(0,0,0,0.3); font-family:'Tajawal',sans-serif; animation:fadeIn 0.25s ease;">
        <!-- رأس المودال -->
        <div style="background:#8b1c2b; padding:1rem 1.5rem; border-radius:14px 14px 0 0; display:flex; align-items:center; justify-content:space-between;">
            <span style="font-size:1.1rem; font-weight:900; color:#fff;">✏️ تعديل بيانات الموظف</span>
            <button wire:click="closeEdit" style="background:rgba(255,255,255,0.15); border:none; color:#fff; border-radius:6px; width:30px; height:30px; cursor:pointer; font-size:1rem; display:flex; align-items:center; justify-content:center;">✕</button>
        </div>
        <!-- الحقول -->
        <div style="padding:1.5rem; display:grid; gap:0.85rem;">
            <div class="pg-2col" style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:800; color:#555; margin-bottom:0.3rem;">الاسم الأول</label>
                    <input wire:model="editFirstName" type="text" style="width:100%; padding:0.5rem 0.75rem; border:1.5px solid #ddd; border-radius:7px; font-family:'Tajawal',sans-serif; font-size:0.9rem; outline:none; box-sizing:border-box;" onfocus="this.style.borderColor='#8b1c2b'" onblur="this.style.borderColor='#ddd'">
                </div>
                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:800; color:#555; margin-bottom:0.3rem;">اسم الأب</label>
                    <input wire:model="editMiddle" type="text" style="width:100%; padding:0.5rem 0.75rem; border:1.5px solid #ddd; border-radius:7px; font-family:'Tajawal',sans-serif; font-size:0.9rem; outline:none; box-sizing:border-box;" onfocus="this.style.borderColor='#8b1c2b'" onblur="this.style.borderColor='#ddd'">
                </div>
            </div>
            <div class="pg-2col" style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:800; color:#555; margin-bottom:0.3rem;">رقم الموظف</label>
                    <input wire:model="editEmpNo" type="text" style="width:100%; padding:0.5rem 0.75rem; border:1.5px solid #ddd; border-radius:7px; font-family:'Tajawal',sans-serif; font-size:0.9rem; outline:none; box-sizing:border-box;" onfocus="this.style.borderColor='#8b1c2b'" onblur="this.style.borderColor='#ddd'">
                </div>
                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:800; color:#555; margin-bottom:0.3rem;">التليفون</label>
                    <input wire:model="editPhone" type="text" dir="ltr" style="width:100%; padding:0.5rem 0.75rem; border:1.5px solid #ddd; border-radius:7px; font-family:'Tajawal',sans-serif; font-size:0.9rem; outline:none; box-sizing:border-box;" onfocus="this.style.borderColor='#8b1c2b'" onblur="this.style.borderColor='#ddd'">
                </div>
            </div>
            <div>
                <label style="display:block; font-size:0.82rem; font-weight:800; color:#555; margin-bottom:0.3rem;">البريد الإلكتروني</label>
                <input wire:model="editEmail" type="email" dir="ltr" style="width:100%; padding:0.5rem 0.75rem; border:1.5px solid #ddd; border-radius:7px; font-family:'Tajawal',sans-serif; font-size:0.9rem; outline:none; box-sizing:border-box;" onfocus="this.style.borderColor='#8b1c2b'" onblur="this.style.borderColor='#ddd'">
            </div>
            <div class="pg-2col" style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:800; color:#555; margin-bottom:0.3rem;">اسم المستخدم</label>
                    <input wire:model="editUserName" type="text" dir="ltr" style="width:100%; padding:0.5rem 0.75rem; border:1.5px solid #ddd; border-radius:7px; font-family:'Tajawal',sans-serif; font-size:0.9rem; outline:none; box-sizing:border-box;" onfocus="this.style.borderColor='#8b1c2b'" onblur="this.style.borderColor='#ddd'">
                </div>
                <div>
                    <label style="display:block; font-size:0.82rem; font-weight:800; color:#555; margin-bottom:0.3rem;">كلمة المرور (اتركها فارغة للإبقاء)</label>
                    <input wire:model="editPassword" type="password" dir="ltr" style="width:100%; padding:0.5rem 0.75rem; border:1.5px solid #ddd; border-radius:7px; font-family:'Tajawal',sans-serif; font-size:0.9rem; outline:none; box-sizing:border-box;" onfocus="this.style.borderColor='#8b1c2b'" onblur="this.style.borderColor='#ddd'">
                </div>
            </div>
            <div>
                <label style="display:block; font-size:0.82rem; font-weight:800; color:#555; margin-bottom:0.3rem;">الحالة</label>
                <select wire:model="editState" style="width:100%; padding:0.5rem 0.75rem; border:1.5px solid #ddd; border-radius:7px; font-family:'Tajawal',sans-serif; font-size:0.9rem; outline:none; box-sizing:border-box; background:#fff;" onfocus="this.style.borderColor='#8b1c2b'" onblur="this.style.borderColor='#ddd'">
                    <option value="1">فعّال</option>
                    <option value="8">متوقف</option>
                </select>
            </div>
        </div>
        <!-- أزرار -->
        <div style="padding:0 1.5rem 1.5rem; display:flex; gap:0.75rem; justify-content:flex-start;">
            <button wire:click="saveEdit"
                style="padding:0.6rem 1.5rem; background:#8b1c2b; color:#fff; border:none; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.95rem; font-weight:800; cursor:pointer;"
                onmouseover="this.style.background='#6d1622'" onmouseout="this.style.background='#8b1c2b'">
                💾 حفظ
            </button>
            <button wire:click="closeEdit"
                style="padding:0.6rem 1.2rem; background:#f5f5f5; color:#555; border:1px solid #ddd; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.95rem; font-weight:700; cursor:pointer;"
                onmouseover="this.style.background='#eee'" onmouseout="this.style.background='#f5f5f5'">
                إلغاء
            </button>
        </div>
    </div>
</div>
@endif

{{-- ═══════════ مودال الحذف ═══════════ --}}
@if($showDeleteModal)
<div style="position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; display:flex; align-items:center; justify-content:center; padding:1rem;" wire:click.self="closeDelete">
    <div style="background:#fff; border-radius:14px; width:100%; max-width:400px; box-shadow:0 20px 60px rgba(0,0,0,0.3); font-family:'Tajawal',sans-serif; animation:fadeIn 0.25s ease; text-align:center;">
        <div style="padding:2rem 1.5rem 1rem;">
            <div style="font-size:3rem; margin-bottom:0.75rem;">⚠️</div>
            <div style="font-size:1.15rem; font-weight:900; color:#1a1a2e; margin-bottom:0.5rem;">تأكيد الحذف</div>
            <div style="font-size:0.9rem; color:#555;">هل أنت متأكد من حذف الموظف</div>
            <div style="font-size:1rem; font-weight:900; color:#8b1c2b; margin:0.4rem 0;">{{ $deleteEmpName }}</div>
            <div style="font-size:0.85rem; color:#999;">لا يمكن التراجع عن هذا الإجراء</div>
        </div>
        <div style="padding:1rem 1.5rem 1.5rem; display:flex; gap:0.75rem; justify-content:center;">
            <button wire:click="doDelete"
                style="padding:0.6rem 1.5rem; background:#c62828; color:#fff; border:none; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.95rem; font-weight:800; cursor:pointer;"
                onmouseover="this.style.background='#b71c1c'" onmouseout="this.style.background='#c62828'">
                🗑️ حذف
            </button>
            <button wire:click="closeDelete"
                style="padding:0.6rem 1.2rem; background:#f5f5f5; color:#555; border:1px solid #ddd; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.95rem; font-weight:700; cursor:pointer;"
                onmouseover="this.style.background='#eee'" onmouseout="this.style.background='#f5f5f5'">
                إلغاء
            </button>
        </div>
    </div>
</div>
@endif
