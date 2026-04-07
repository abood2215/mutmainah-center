<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1200px; margin:0 auto; animation:fadeIn 0.4s ease;">

    {{-- رأس الصفحة --}}
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
        <div>
            <h1 style="font-size:1.5rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">إدارة المستخدمين</h1>
            <div style="font-size:0.83rem; color:var(--text-muted); margin-top:0.2rem; font-weight:600;">
                {{ $totalActive }} نشط من أصل {{ $totalAll }} مستخدم
            </div>
        </div>
        <button wire:click="toggleAddForm"
            style="background:{{ $showAddForm ? '#f1f5f9' : 'var(--primary)' }}; color:{{ $showAddForm ? 'var(--text-dim)' : '#fff' }}; border:{{ $showAddForm ? '1px solid var(--border)' : 'none' }}; border-radius:10px; padding:0.6rem 1.4rem; font-weight:800; font-size:0.88rem; cursor:pointer; font-family:'Tajawal',sans-serif;">
            {{ $showAddForm ? '✕ إغلاق' : '+ مستخدم جديد' }}
        </button>
    </div>

    {{-- رسائل --}}
    @if($successMsg)
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:0.85rem 1.25rem; margin-bottom:1.25rem; color:#15803d; font-weight:700; font-size:0.9rem;">✅ {{ $successMsg }}</div>
    @endif
    @if($errorMsg)
    <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:0.85rem 1.25rem; margin-bottom:1.25rem; color:#dc2626; font-weight:700; font-size:0.9rem;">⚠️ {{ $errorMsg }}</div>
    @endif

    {{-- فورم إضافة مستخدم جديد --}}
    @if($showAddForm)
    <div style="background:#fff; border:2px solid var(--primary); border-radius:14px; overflow:hidden; box-shadow:0 4px 16px rgba(139,28,43,0.1); margin-bottom:1.5rem; animation:fadeIn 0.25s ease;">
        <div style="background:var(--navy); padding:0.85rem 1.5rem; display:flex; align-items:center; gap:0.65rem; border-bottom:3px solid var(--gold);">
            <span style="font-size:1.1rem;">👤</span>
            <span style="color:#fff; font-weight:900; font-size:1rem; font-family:'Tajawal',sans-serif;">إضافة مستخدم جديد</span>
        </div>
        <div style="padding:1.25rem;">
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(170px, 1fr)); gap:1rem; margin-bottom:1.1rem;">
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#6b7280; margin-bottom:0.35rem;">الاسم <span style="color:#dc2626;">*</span></label>
                    <input type="text" wire:model="newFirstName" placeholder="الاسم الأول" class="form-input" style="width:100%; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#6b7280; margin-bottom:0.35rem;">اسم الأب</label>
                    <input type="text" wire:model="newMiddleName" placeholder="اختياري" class="form-input" style="width:100%; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#6b7280; margin-bottom:0.35rem;">اسم المستخدم <span style="color:#dc2626;">*</span></label>
                    <input type="text" wire:model="newUserName" placeholder="username" class="form-input" style="width:100%; box-sizing:border-box; direction:ltr;">
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#6b7280; margin-bottom:0.35rem;">كلمة المرور <span style="color:#dc2626;">*</span></label>
                    <input type="password" wire:model="newPassword" placeholder="••••••••" class="form-input" style="width:100%; box-sizing:border-box; direction:ltr;">
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#6b7280; margin-bottom:0.35rem;">الصلاحية <span style="color:#dc2626;">*</span></label>
                    <select wire:model="newRole" class="form-input" style="width:100%; box-sizing:border-box;">
                        <option value="reception">استقبال</option>
                        <option value="admin">مدير</option>
                    </select>
                </div>
                @if($hasBranchId)
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#6b7280; margin-bottom:0.35rem;">الفرع / الدور</label>
                    <select wire:model="newBranchId" class="form-input" style="width:100%; box-sizing:border-box;">
                        <option value="1">الدور الثالث — الاستشارات اللغوية</option>
                        <option value="2">الدور السادس — التربوية والتدريب</option>
                    </select>
                </div>
                @endif
            </div>
            <button wire:click="createUser" wire:loading.attr="disabled"
                style="background:var(--primary); color:#fff; border:none; border-radius:9px; padding:0.65rem 2.5rem; font-weight:900; font-size:0.9rem; cursor:pointer; font-family:'Tajawal',sans-serif;">
                <span wire:loading.remove wire:target="createUser">💾 حفظ المستخدم</span>
                <span wire:loading wire:target="createUser">جارٍ الحفظ...</span>
            </button>
        </div>
    </div>
    @endif

    {{-- الجدول --}}
    <div style="background:#fff; border:1px solid var(--border); border-radius:14px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.05);">

        {{-- فلاتر --}}
        <div style="padding:0.85rem 1.25rem; background:#f8fafc; border-bottom:1px solid var(--border); display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center;">
            <div style="position:relative; flex:1; min-width:200px;">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="بحث بالاسم أو اسم المستخدم..."
                    class="form-input" style="padding-right:2.5rem;">
                <span style="position:absolute; right:0.85rem; top:50%; transform:translateY(-50%); opacity:0.4;">🔍</span>
            </div>
            <select wire:model.live="filterState" class="form-input" style="width:160px;">
                <option value="">جميع المستخدمين</option>
                <option value="active">النشطون فقط</option>
                <option value="inactive">غير النشطين</option>
            </select>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f1f5f9; border-bottom:2px solid var(--border);">
                        <th style="padding:0.75rem 1.25rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim);">#</th>
                        <th style="padding:0.75rem 1rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim);">الاسم</th>
                        <th style="padding:0.75rem 1rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim);">اسم المستخدم</th>
                        @if($hasRole)
                        <th style="padding:0.75rem 1rem; text-align:center; font-size:0.78rem; font-weight:800; color:var(--text-dim);">الصلاحية</th>
                        @endif
                        @if($hasBranchId)
                        <th style="padding:0.75rem 1rem; text-align:center; font-size:0.78rem; font-weight:800; color:var(--text-dim);">الفرع</th>
                        @endif
                        <th style="padding:0.75rem 1rem; text-align:center; font-size:0.78rem; font-weight:800; color:var(--text-dim);">الحالة</th>
                        <th style="padding:0.75rem 1rem; text-align:center; font-size:0.78rem; font-weight:800; color:var(--text-dim);">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr style="border-bottom:1px solid #f1f5f9; transition:background 0.15s;"
                        onmouseover="this.style.background='#fafafa'"
                        onmouseout="this.style.background='transparent'">

                        <td style="padding:0.85rem 1.25rem; font-size:0.8rem; color:var(--text-muted); font-weight:700;">{{ $user->id }}</td>

                        <td style="padding:0.85rem 1rem;">
                            @if($editingId === $user->id)
                                <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                                    <input type="text" wire:model="editFirstName" placeholder="الاسم الأول"
                                        class="form-input" style="width:120px; font-size:0.85rem; padding:0.4rem 0.7rem;">
                                    <input type="text" wire:model="editMiddleName" placeholder="اسم الأب"
                                        class="form-input" style="width:120px; font-size:0.85rem; padding:0.4rem 0.7rem;">
                                </div>
                            @else
                                <div style="font-weight:800; color:var(--navy); font-size:0.92rem;">
                                    {{ trim($user->first_name . ' ' . $user->middle_initial) }}
                                </div>
                            @endif
                        </td>

                        <td style="padding:0.85rem 1rem;">
                            @if($editingId === $user->id)
                                <div style="display:flex; gap:0.5rem; flex-direction:column;">
                                    <input type="text" wire:model="editUserName" placeholder="اسم المستخدم"
                                        class="form-input" style="width:140px; font-size:0.85rem; padding:0.4rem 0.7rem; direction:ltr;">
                                    <input type="password" wire:model="editPassword" placeholder="كلمة مرور جديدة (اختياري)"
                                        class="form-input" style="width:140px; font-size:0.85rem; padding:0.4rem 0.7rem; direction:ltr;">
                                </div>
                            @else
                                <span style="font-family:monospace; font-size:0.88rem; color:#1565c0; font-weight:700; direction:ltr; display:inline-block;">{{ $user->user_name }}</span>
                            @endif
                        </td>

                        @if($hasRole)
                        <td style="padding:0.85rem 1rem; text-align:center;">
                            @if($editingId === $user->id)
                                <select wire:model="editRole" class="form-input" style="font-size:0.82rem; padding:0.35rem 0.6rem;">
                                    <option value="">— بدون —</option>
                                    <option value="admin">مدير</option>
                                    <option value="reception">استقبال</option>
                                </select>
                            @else
                                @if(($user->role ?? '') === 'admin')
                                    <span style="background:#fef3c7; color:#92400e; border:1px solid #fde68a; padding:0.2rem 0.75rem; border-radius:20px; font-size:0.75rem; font-weight:800;">مدير</span>
                                @elseif(($user->role ?? '') === 'reception')
                                    <span style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; padding:0.2rem 0.75rem; border-radius:20px; font-size:0.75rem; font-weight:800;">استقبال</span>
                                @else
                                    <span style="color:#d1d5db; font-size:0.78rem;">—</span>
                                @endif
                            @endif
                        </td>
                        @endif

                        @if($hasBranchId)
                        <td style="padding:0.85rem 1rem; text-align:center;">
                            @if($editingId === $user->id)
                                <select wire:model="editBranchId" class="form-input" style="font-size:0.82rem; padding:0.35rem 0.6rem; min-width:170px;">
                                    <option value="1">الدور الثالث</option>
                                    <option value="2">الدور السادس</option>
                                </select>
                            @else
                                @php $bId = (int)($user->branch_id ?? 1); @endphp
                                @if($bId === 2)
                                    <span style="background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; padding:0.2rem 0.75rem; border-radius:20px; font-size:0.75rem; font-weight:800;">الدور السادس</span>
                                @else
                                    <span style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; padding:0.2rem 0.75rem; border-radius:20px; font-size:0.75rem; font-weight:800;">الدور الثالث</span>
                                @endif
                            @endif
                        </td>
                        @endif

                        <td style="padding:0.85rem 1rem; text-align:center;">
                            @if($user->state === 1)
                                <span style="background:#dcfce7; color:#16a34a; border:1px solid #bbf7d0; padding:0.25rem 0.85rem; border-radius:20px; font-size:0.78rem; font-weight:800;">نشط</span>
                            @else
                                <span style="background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; padding:0.25rem 0.85rem; border-radius:20px; font-size:0.78rem; font-weight:800;">معطل</span>
                            @endif
                        </td>

                        <td style="padding:0.85rem 1rem; text-align:center;">
                            @if($editingId === $user->id)
                                <div style="display:flex; gap:0.4rem; justify-content:center;">
                                    <button wire:click="saveEdit"
                                        style="background:#16a34a; color:#fff; border:none; border-radius:7px; padding:0.45rem 0.9rem; font-weight:800; font-size:0.8rem; cursor:pointer; font-family:'Tajawal',sans-serif;">✓ حفظ</button>
                                    <button wire:click="cancelEdit"
                                        style="background:#f1f5f9; color:var(--text-dim); border:1px solid var(--border); border-radius:7px; padding:0.45rem 0.75rem; font-weight:700; font-size:0.8rem; cursor:pointer; font-family:'Tajawal',sans-serif;">إلغاء</button>
                                </div>
                            @else
                                <div style="display:flex; gap:0.4rem; justify-content:center;">
                                    <button wire:click="startEdit({{ $user->id }})"
                                        style="background:#f0f7ff; color:#1565c0; border:1px solid #bbdefb; border-radius:7px; padding:0.4rem 0.85rem; font-weight:700; font-size:0.8rem; cursor:pointer; font-family:'Tajawal',sans-serif;">✏️ تعديل</button>
                                    <button wire:click="toggleState({{ $user->id }}, {{ $user->state }})"
                                        style="background:{{ $user->state === 1 ? '#fef2f2' : '#f0fdf4' }}; color:{{ $user->state === 1 ? '#dc2626' : '#16a34a' }}; border:1px solid {{ $user->state === 1 ? '#fecaca' : '#bbf7d0' }}; border-radius:7px; padding:0.4rem 0.75rem; font-weight:700; font-size:0.8rem; cursor:pointer; font-family:'Tajawal',sans-serif;">
                                        {{ $user->state === 1 ? 'تعطيل' : 'تفعيل' }}
                                    </button>
                                </div>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ 5 + ($hasRole ? 1 : 0) + ($hasBranchId ? 1 : 0) }}" style="padding:4rem; text-align:center; color:var(--text-muted);">
                            <div style="font-size:2rem; opacity:0.2; margin-bottom:0.5rem;">👤</div>
                            <div style="font-weight:800;">لا توجد نتائج</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div style="padding:0.85rem 1.25rem; border-top:1px solid var(--border); background:#fafbfc;">
            <div class="custom-pagination">{{ $users->links() }}</div>
        </div>
        @endif

    </div>

</div>
</div>
