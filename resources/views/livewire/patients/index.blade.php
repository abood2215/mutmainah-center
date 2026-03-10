<div style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1400px; margin:0 auto; background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow-sm); overflow:hidden; animation:fadeIn 0.5s ease;">

    <!-- رأس الإطار -->
    <div style="padding:1.25rem 1.75rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="width:42px; height:42px; background:var(--primary-glow); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">👥</div>
            <h1 style="font-size:1.4rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">قاعدة بيانات العملاء</h1>
        </div>
        <a href="{{ route('patients.create') }}" wire:navigate class="btn btn-primary">➕ ملف جديد</a>
    </div>

    <!-- المحتوى -->
    <div style="padding:1.75rem;">

        <!-- Search -->
        <div style="max-width:700px; margin:0 auto 2rem auto;">
            <div style="position:relative;">
                <input type="text" wire:model.live.debounce.300ms="search"
                    wire:keydown.enter="performSearch"
                    placeholder="ابحث بالاسم، الهوية، الجوال أو رقم الملف..."
                    style="width:100%; padding:0.9rem 3.2rem 0.9rem 1.2rem; border:2px solid var(--border); border-radius:12px; font-family:'Tajawal',sans-serif; font-size:1rem; outline:none; transition:border-color 0.25s, box-shadow 0.25s; background:#fff; color:var(--text);"
                    onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px var(--primary-glow)'"
                    onblur="setTimeout(()=>{ this.style.borderColor='var(--border)'; this.style.boxShadow='none'; }, 200)">
                <span style="position:absolute; right:1.1rem; top:50%; transform:translateY(-50%); font-size:1.1rem; opacity:0.4;">🔍</span>

                @if(!empty($suggestions))
                <div style="position:absolute; top:calc(100% + 6px); left:0; right:0; background:#fff; border:1px solid var(--border); border-radius:12px; box-shadow:0 12px 32px rgba(0,0,0,0.12); z-index:100; overflow:hidden; animation:dropIn 0.15s ease;">
                    @foreach($suggestions as $suggest)
                    <div wire:click="selectPatient({{ $suggest->id }}, '{{ $suggest->name }}')"
                        style="padding:0.8rem 1.25rem; cursor:pointer; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; transition:background 0.15s;"
                        onmouseover="this.style.background='#fef5f5'" onmouseout="this.style.background='#fff'">
                        <div>
                            <div style="font-weight:800; color:var(--text); font-size:0.95rem;">{{ $suggest->name }}</div>
                            <div style="font-size:0.78rem; color:var(--text-muted);">#{{ $suggest->file_id ?? $suggest->id }}</div>
                        </div>
                        <div style="color:#1565c0; font-weight:700; font-size:0.88rem; direction:ltr;">{{ $suggest->phone }}</div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            <div style="display:flex; gap:0.75rem; justify-content:center; margin-top:1rem;">
                <button wire:click="performSearch" class="btn btn-primary" style="padding:0.65rem 3rem;">بحث</button>
                <button wire:click="resetSearch" class="btn btn-secondary" style="padding:0.65rem 2rem;">مسح</button>
            </div>
        </div>

        <!-- Results -->
        @if($searchPerformed)
        @if(count($patients) > 0)
        <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden;">
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-family:'Tajawal',sans-serif;">
                    <thead>
                        <tr style="background:#f8fafc; border-bottom:2px solid var(--border);">
                            <th style="padding:0.9rem 1.25rem; text-align:right; font-size:0.82rem; font-weight:900; color:var(--text-dim);">الاسم الكامل</th>
                            <th style="padding:0.9rem 1.25rem; text-align:center; font-size:0.82rem; font-weight:900; color:var(--text-dim);">رقم الملف</th>
                            <th style="padding:0.9rem 1.25rem; text-align:right; font-size:0.82rem; font-weight:900; color:var(--text-dim);">الهوية</th>
                            <th style="padding:0.9rem 1.25rem; text-align:right; font-size:0.82rem; font-weight:900; color:var(--text-dim);">الجوال</th>
                            <th style="padding:0.9rem 1.25rem; text-align:center; font-size:0.82rem; font-weight:900; color:var(--text-dim);">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                        <tr style="border-bottom:1px solid #f1f5f9; transition:background 0.15s;"
                            onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background='transparent'">
                            <td style="padding:1rem 1.25rem; font-weight:800; font-size:1rem; color:var(--text);">{{ $patient->name }}</td>
                            <td style="padding:1rem 1.25rem; text-align:center;">
                                <span style="padding:0.3rem 0.8rem; background:#e3f2fd; color:#1565c0; border-radius:7px; font-weight:900; border:1px solid #bbdefb; font-size:0.85rem;">#{{ $patient->file_id ?? $patient->id }}</span>
                            </td>
                            <td style="padding:1rem 1.25rem; color:var(--text-dim); font-size:0.9rem;">{{ $patient->identity_number }}</td>
                            <td style="padding:1rem 1.25rem; color:var(--text-dim); direction:ltr; font-size:0.9rem;">{{ $patient->phone }}</td>
                            <td style="padding:1rem 1.25rem;">
                                <div style="display:flex; gap:0.6rem; justify-content:center;">
                                    <a href="{{ route('patients.financial-statement', $patient->id) }}" wire:navigate
                                        style="padding:0.4rem 0.9rem; border-radius:8px; text-decoration:none; font-weight:700; font-size:0.82rem; background:#e8f5e9; color:#2e7d32; border:1px solid #c8e6c9; transition:all 0.2s; display:flex; align-items:center; gap:0.3rem;"
                                        onmouseover="this.style.background='#2e7d32'; this.style.color='#fff'" onmouseout="this.style.background='#e8f5e9'; this.style.color='#2e7d32'">
                                        💰 مالي
                                    </a>
                                    <a href="{{ route('patients.medical-history', $patient->id) }}" wire:navigate
                                        style="padding:0.4rem 0.9rem; border-radius:8px; text-decoration:none; font-weight:700; font-size:0.82rem; background:#e3f2fd; color:#1565c0; border:1px solid #bbdefb; transition:all 0.2s; display:flex; align-items:center; gap:0.3rem;"
                                        onmouseover="this.style.background='#1565c0'; this.style.color='#fff'" onmouseout="this.style.background='#e3f2fd'; this.style.color='#1565c0'">
                                        📄 سجل
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($patients->hasPages())
            <div style="padding:1rem 1.5rem; border-top:1px solid var(--border); background:#fcfdfe; display:flex; justify-content:flex-end;">
                <div class="custom-pagination">{{ $patients->links() }}</div>
            </div>
            @endif
        </div>
        @else
        <div style="text-align:center; padding:5rem 2rem; background:#f8fafc; border:2px dashed var(--border); border-radius:12px;">
            <div style="font-size:3rem; margin-bottom:1rem; opacity:0.2;">📂</div>
            <div style="font-size:1.2rem; font-weight:800; color:var(--text-muted);">لا توجد نتائج مطابقة</div>
            <button wire:click="resetSearch" class="btn btn-secondary" style="margin-top:1.25rem;">إعادة التعيين</button>
        </div>
        @endif
        @endif

    </div>
</div>
</div>
