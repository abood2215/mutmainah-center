<div style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1400px; margin:0 auto; background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow-sm); overflow:hidden; animation:fadeIn 0.5s ease;">

    <!-- رأس الإطار -->
    <div style="padding:1.25rem 1.75rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="width:42px; height:42px; background:var(--primary-glow); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">👨‍⚕️</div>
            <h1 style="font-size:1.4rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">الموظفين : Employees</h1>
        </div>
        <div style="position:relative; width:240px;">
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
                <!-- رأس عربي -->
                <tr style="background:#e8e8e8; border-bottom:1px solid #ccc;">
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#2e7d32; border-left:1px solid #ccc; width:40px;">م</th>
                    <th style="padding:0.55rem 0.75rem; text-align:right; font-weight:900; color:#2e7d32; border-left:1px solid #ccc; min-width:160px;">الاسم</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#c8401a; border-left:1px solid #ccc;">الرقم</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#c8401a; border-left:1px solid #ccc;">الوظيفة</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc;">المؤهل</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc;">التليفون</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc; min-width:140px;">البريد الالكتروني</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#0099aa; border-left:1px solid #ccc;">التفاصيل</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc; width:60px;">تعديل</th>
                    <th style="padding:0.55rem 0.75rem; text-align:center; font-weight:900; color:#555; width:60px;">حذف</th>
                </tr>
                <!-- رأس إنجليزي -->
                <tr style="background:#f5f5f5; border-bottom:2px solid #bbb;">
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#2e7d32; border-left:1px solid #ccc; font-size:0.8rem;">#</th>
                    <th style="padding:0.45rem 0.75rem; text-align:right; font-weight:900; color:#2e7d32; border-left:1px solid #ccc; font-size:0.8rem;">Name</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#c8401a; border-left:1px solid #ccc; font-size:0.8rem;">No</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#c8401a; border-left:1px solid #ccc; font-size:0.8rem;">Job</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc; font-size:0.8rem;">Qf</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc; font-size:0.8rem;">Phone</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc; font-size:0.8rem;">E.mail</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#0099aa; border-left:1px solid #ccc; font-size:0.8rem;">Details</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#555; border-left:1px solid #ccc; font-size:0.8rem;">Edit</th>
                    <th style="padding:0.45rem 0.75rem; text-align:center; font-weight:900; color:#555; font-size:0.8rem;">Del</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                <tr wire:key="emp-{{ $emp->id }}"
                    style="border-bottom:1px solid #e0e0e0; {{ $loop->even ? 'background:#fafafa;' : 'background:#fff;' }}"
                    onmouseover="this.style.background='#f0f7ff'" onmouseout="this.style.background='{{ $loop->even ? '#fafafa' : '#fff' }}'">
                    <td style="padding:0.55rem 0.75rem; text-align:center; color:#555; border-left:1px solid #eee;">{{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}</td>
                    <td style="padding:0.55rem 0.75rem; font-weight:700; color:#1a1a2e; border-left:1px solid #eee;">{{ trim(($emp->first_name ?? '') . ' ' . ($emp->last_name ?? '')) ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; font-weight:700; color:#c8401a; border-left:1px solid #eee;">{{ $emp->emp_no ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; color:#333; border-left:1px solid #eee;">{{ $emp->job_name ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; color:#555; border-left:1px solid #eee;">{{ $emp->qual_name ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; color:#333; border-left:1px solid #eee; direction:ltr;">{{ $emp->phone ?: '—' }}</td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; color:#555; font-size:0.82rem; border-left:1px solid #eee;">{{ $emp->email ?: '' }}</td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; border-left:1px solid #eee;">
                        <a href="#" style="color:#0099aa; font-weight:700; text-decoration:none; font-size:0.88rem;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">التفاصيل</a>
                    </td>
                    <td style="padding:0.55rem 0.75rem; text-align:center; border-left:1px solid #eee;">
                        <button title="تعديل" style="width:28px; height:28px; background:#fff8e1; border:1px solid #f0c040; border-radius:4px; cursor:pointer; display:inline-flex; align-items:center; justify-content:center;" onmouseover="this.style.background='#fff0b0'" onmouseout="this.style.background='#fff8e1'">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#c8941a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                    </td>
                    <td style="padding:0.55rem 0.75rem; text-align:center;">
                        <button title="حذف" style="width:28px; height:28px; background:#fff0f0; border:1px solid #e88; border-radius:4px; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; font-weight:900; color:#cc0000; font-size:0.85rem;" onmouseover="this.style.background='#ffd5d5'" onmouseout="this.style.background='#fff0f0'">✕</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" style="padding:3rem; text-align:center; color:var(--text-muted);">لا توجد بيانات موظفين</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

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
