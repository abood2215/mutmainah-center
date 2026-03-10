<div style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1200px; margin:0 auto; animation:fadeIn 0.4s ease;">

    <!-- رأس الصفحة -->
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
        <div>
            <h1 style="font-size:1.5rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">إدارة المستخدمين</h1>
            <div style="font-size:0.83rem; color:var(--text-muted); margin-top:0.2rem; font-weight:600;">
                {{ $totalActive }} نشط من أصل {{ $totalAll }} مستخدم
            </div>
        </div>
        @if(!$confirmDisableAll)
        <button wire:click="confirmDisableAll"
            style="background:#fef2f2; color:#dc2626; border:1.5px solid #fecaca; border-radius:10px; padding:0.6rem 1.25rem; font-weight:800; font-size:0.85rem; cursor:pointer; font-family:'Tajawal',sans-serif; display:flex; align-items:center; gap:0.5rem; transition:all 0.2s;"
            onmouseover="this.style.background='#dc2626'; this.style.color='#fff'"
            onmouseout="this.style.background='#fef2f2'; this.style.color='#dc2626'">
            🔒 تعطيل الجميع ما عدا حسابي
        </button>
        @endif
    </div>

    @if($successMsg)
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:0.85rem 1.25rem; margin-bottom:1.25rem; color:#15803d; font-weight:700; font-size:0.9rem;">
        ✅ {{ $successMsg }}
    </div>
    @endif

    @if($errorMsg)
    <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:0.85rem 1.25rem; margin-bottom:1.25rem; color:#dc2626; font-weight:700; font-size:0.9rem;">
        ⚠️ {{ $errorMsg }}
    </div>
    @endif

    <!-- تعطيل الجميع -->
    @if($confirmDisableAll)
    <div style="background:#fff3cd; border:2px solid #ffc107; border-radius:12px; padding:1.1rem 1.5rem; margin-bottom:1.25rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <div style="font-weight:900; color:#856404; font-size:0.95rem;">⚠️ تأكيد تعطيل جميع المستخدمين</div>
            <div style="font-size:0.82rem; color:#856404; margin-top:0.25rem;">سيتم تعطيل كل الحسابات ما عدا حسابك — فقط أنت ستتمكن من تسجيل الدخول</div>
        </div>
        <div style="display:flex; gap:0.6rem;">
            <button wire:click="disableAllExceptMe"
                style="background:#dc2626; color:#fff; border:none; border-radius:8px; padding:0.55rem 1.25rem; font-weight:900; font-size:0.88rem; cursor:pointer; font-family:'Tajawal',sans-serif;">
                نعم، عطّل الجميع
            </button>
            <button wire:click="cancelDisableAll"
                style="background:#f1f5f9; color:var(--text-dim); border:1px solid var(--border); border-radius:8px; padding:0.55rem 1rem; font-weight:700; font-size:0.88rem; cursor:pointer; font-family:'Tajawal',sans-serif;">
                إلغاء
            </button>
        </div>
    </div>
    @endif

    <!-- الجدول -->
    <div style="background:#fff; border:1px solid var(--border); border-radius:14px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.05);">

        <!-- فلاتر -->
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

        <!-- الجدول -->
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f1f5f9; border-bottom:2px solid var(--border);">
                        <th style="padding:0.75rem 1.25rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim);">#</th>
                        <th style="padding:0.75rem 1rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim);">الاسم</th>
                        <th style="padding:0.75rem 1rem; text-align:right; font-size:0.78rem; font-weight:800; color:var(--text-dim);">اسم المستخدم</th>
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
                                        class="form-input" style="width:130px; font-size:0.85rem; padding:0.4rem 0.7rem;">
                                    <input type="text" wire:model="editMiddleName" placeholder="اسم الأب"
                                        class="form-input" style="width:130px; font-size:0.85rem; padding:0.4rem 0.7rem;">
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
                                        class="form-input" style="width:150px; font-size:0.85rem; padding:0.4rem 0.7rem; direction:ltr;">
                                    <input type="password" wire:model="editPassword" placeholder="كلمة مرور جديدة (اختياري)"
                                        class="form-input" style="width:150px; font-size:0.85rem; padding:0.4rem 0.7rem; direction:ltr;">
                                </div>
                            @else
                                <span style="font-family:monospace; font-size:0.88rem; color:#1565c0; font-weight:700; direction:ltr; display:inline-block;">{{ $user->user_name }}</span>
                            @endif
                        </td>

                        <td style="padding:0.85rem 1rem; text-align:center;">
                            @if($user->state === 1)
                                <span style="display:inline-block; background:#dcfce7; color:#16a34a; border:1px solid #bbf7d0; padding:0.25rem 0.85rem; border-radius:20px; font-size:0.78rem; font-weight:800;">نشط</span>
                            @else
                                <span style="display:inline-block; background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; padding:0.25rem 0.85rem; border-radius:20px; font-size:0.78rem; font-weight:800;">معطل</span>
                            @endif
                        </td>

                        <td style="padding:0.85rem 1rem; text-align:center;">
                            @if($editingId === $user->id)
                                <div style="display:flex; gap:0.4rem; justify-content:center;">
                                    <button wire:click="saveEdit"
                                        style="background:#16a34a; color:#fff; border:none; border-radius:7px; padding:0.45rem 0.9rem; font-weight:800; font-size:0.8rem; cursor:pointer; font-family:'Tajawal',sans-serif;">
                                        ✓ حفظ
                                    </button>
                                    <button wire:click="cancelEdit"
                                        style="background:#f1f5f9; color:var(--text-dim); border:1px solid var(--border); border-radius:7px; padding:0.45rem 0.75rem; font-weight:700; font-size:0.8rem; cursor:pointer; font-family:'Tajawal',sans-serif;">
                                        إلغاء
                                    </button>
                                </div>
                            @else
                                <div style="display:flex; gap:0.4rem; justify-content:center;">
                                    <button wire:click="startEdit({{ $user->id }})"
                                        style="background:#f0f7ff; color:#1565c0; border:1px solid #bbdefb; border-radius:7px; padding:0.4rem 0.85rem; font-weight:700; font-size:0.8rem; cursor:pointer; font-family:'Tajawal',sans-serif;">
                                        ✏️ تعديل
                                    </button>
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
                        <td colspan="5" style="padding:4rem; text-align:center; color:var(--text-muted);">
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
            {{ $users->links() }}
        </div>
        @endif

    </div>

</div>
</div>
