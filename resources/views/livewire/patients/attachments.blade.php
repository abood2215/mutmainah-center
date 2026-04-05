<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:900px; margin:0 auto; animation:fadeIn 0.4s ease;">

    @if(session()->has('success'))
    <div style="background:#e8f5e9; color:#2e7d32; padding:0.85rem 1.25rem; border-radius:8px; margin-bottom:1rem; font-weight:700; border:1px solid #c8e6c9; font-family:'Tajawal',sans-serif;">
        ✅ {{ session('success') }}
    </div>
    @endif

    {{-- رأس الصفحة --}}
    <div style="background:#fff; border-radius:14px; border:1px solid var(--border); overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.06); margin-bottom:1.25rem;">
        <div style="background:var(--primary); padding:0.8rem 1.5rem; display:flex; align-items:center; justify-content:space-between;">
            <span style="color:#fff; font-weight:900; font-size:1rem; font-family:'Tajawal',sans-serif;">
                📎 مرفقات العميل
            </span>
            <a href="{{ route('patients.show', $patient->id) }}" wire:navigate
               style="color:rgba(255,255,255,0.75); font-size:0.82rem; font-weight:700; text-decoration:none; font-family:'Tajawal',sans-serif;">
                ⬅ العودة للملف
            </a>
        </div>
        <div style="padding:0.85rem 1.5rem; background:#fafbfc; display:flex; gap:1.5rem; align-items:center; flex-wrap:wrap;">
            <span style="font-weight:900; color:var(--navy); font-size:0.95rem; font-family:'Tajawal',sans-serif;">{{ $patient->full_name }}</span>
            <span style="background:#f0f4ff; color:#1565c0; font-size:0.8rem; font-weight:900; padding:0.2rem 0.7rem; border-radius:6px; font-family:'Inter';">#{{ $patient->file_id ?? $patient->id }}</span>
            <span style="color:var(--text-muted); font-size:0.8rem; font-family:'Tajawal',sans-serif;">{{ count($attachments) }} مرفق</span>
        </div>
    </div>

    {{-- منطقة الرفع --}}
    <div style="background:#fff; border-radius:14px; border:1px solid var(--border); overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.06); margin-bottom:1.25rem;">
        <div style="background:var(--navy); padding:0.7rem 1.5rem;">
            <span style="color:#fbbf24; font-weight:900; font-size:0.9rem; font-family:'Tajawal',sans-serif;">رفع مرفقات جديدة</span>
        </div>
        <div style="padding:1.5rem;">

            {{-- منطقة السحب والإفلات --}}
            <label for="fileInput"
                style="display:block; border:2px dashed #d1d5db; border-radius:12px; padding:2rem; text-align:center; cursor:pointer; transition:all 0.2s; background:#fafbfc;"
                onmouseover="this.style.borderColor='var(--primary)'; this.style.background='#fff9f9'"
                onmouseout="this.style.borderColor='#d1d5db'; this.style.background='#fafbfc'">
                <div style="font-size:2.5rem; margin-bottom:0.5rem;">📁</div>
                <div style="font-weight:800; color:var(--navy); font-size:0.95rem; font-family:'Tajawal',sans-serif;">اسحب الملفات هنا أو اضغط للاختيار</div>
                <div style="color:var(--text-muted); font-size:0.78rem; margin-top:0.3rem; font-family:'Tajawal',sans-serif;">صور (JPG, PNG) · PDF · Word — الحد الأقصى 10MB لكل ملف</div>
                <input id="fileInput" type="file" wire:model="files" multiple accept="image/*,.pdf,.doc,.docx"
                    style="display:none;">
            </label>

            {{-- معاينة الملفات المختارة --}}
            @if(!empty($files))
            <div style="margin-top:1rem; display:flex; flex-wrap:wrap; gap:0.5rem;">
                @foreach($files as $f)
                <div style="background:#f0f4ff; color:#1565c0; padding:0.3rem 0.75rem; border-radius:6px; font-size:0.8rem; font-weight:700; font-family:'Tajawal',sans-serif; border:1px solid #bfdbfe;">
                    📄 {{ $f->getClientOriginalName() }}
                </div>
                @endforeach
            </div>
            @endif

            @error('files.*')
            <div style="color:#dc2626; font-size:0.82rem; margin-top:0.5rem; font-family:'Tajawal',sans-serif;">⚠ {{ $message }}</div>
            @enderror

            <div style="margin-top:1rem; display:flex; gap:0.75rem;">
                <button wire:click="saveFiles" wire:loading.attr="disabled"
                    style="padding:0.6rem 2rem; background:var(--primary); color:#fff; border:none; border-radius:8px; font-weight:900; font-size:0.9rem; font-family:'Tajawal',sans-serif; cursor:pointer; display:flex; align-items:center; gap:0.4rem;"
                    onmouseover="this.style.background='#6e1522'" onmouseout="this.style.background='var(--primary)'">
                    <span wire:loading.remove wire:target="saveFiles">⬆ رفع الملفات</span>
                    <span wire:loading wire:target="saveFiles" style="display:none;">⏳ جاري الرفع...</span>
                </button>
            </div>

            <div wire:loading wire:target="files" style="display:none; margin-top:0.5rem; color:var(--text-muted); font-size:0.82rem; font-family:'Tajawal',sans-serif;">
                ⏳ جاري تحميل الملفات...
            </div>

        </div>
    </div>

    {{-- قائمة المرفقات --}}
    <div style="background:#fff; border-radius:14px; border:1px solid var(--border); overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.06);">
        <div style="background:var(--navy); padding:0.7rem 1.5rem;">
            <span style="color:#fbbf24; font-weight:900; font-size:0.9rem; font-family:'Tajawal',sans-serif;">المرفقات المحفوظة</span>
        </div>

        @if(count($attachments) === 0)
        <div style="padding:3rem; text-align:center; color:var(--text-muted); font-family:'Tajawal',sans-serif;">
            <div style="font-size:2.5rem; margin-bottom:0.5rem;">📂</div>
            <div style="font-weight:700;">لا توجد مرفقات بعد</div>
        </div>
        @else
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(160px, 1fr)); gap:1rem; padding:1.25rem;">
            @foreach($attachments as $att)
            @php
                $ext  = strtolower(pathinfo($att->name, PATHINFO_EXTENSION));
                $isImg = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                $url  = Storage::url($att->address);
            @endphp
            <div style="border:1px solid var(--border); border-radius:10px; overflow:hidden; position:relative; background:#fafbfc;">

                {{-- معاينة --}}
                @if($isImg)
                <a href="{{ $url }}" target="_blank">
                    <img src="{{ $url }}" alt="{{ $att->name }}"
                        style="width:100%; height:130px; object-fit:cover; display:block;">
                </a>
                @else
                <a href="{{ $url }}" target="_blank"
                    style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:130px; text-decoration:none; background:#f8fafc;">
                    <span style="font-size:2.5rem;">{{ $ext === 'pdf' ? '📕' : '📝' }}</span>
                    <span style="font-size:0.72rem; color:var(--text-muted); margin-top:0.3rem; font-family:'Inter'; font-weight:700; text-transform:uppercase;">{{ $ext }}</span>
                </a>
                @endif

                {{-- اسم الملف + حذف --}}
                <div style="padding:0.5rem 0.65rem; display:flex; align-items:center; justify-content:space-between; gap:0.3rem;">
                    <span style="font-size:0.72rem; color:var(--text-dim); font-weight:700; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1; font-family:'Tajawal',sans-serif;" title="{{ $att->name }}">
                        {{ $att->name }}
                    </span>
                    <button wire:click="confirmDelete({{ $att->id }})"
                        style="background:none; border:none; cursor:pointer; color:#dc2626; font-size:1rem; padding:0; flex-shrink:0; line-height:1;"
                        title="حذف">🗑</button>
                </div>

            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Modal تأكيد الحذف --}}
    @if($deleteId)
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; display:flex; align-items:center; justify-content:center;" wire:click.self="$set('deleteId', null)">
        <div style="background:#fff; border-radius:14px; padding:2rem; max-width:360px; width:90%; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,0.2);">
            <div style="font-size:2.5rem; margin-bottom:0.75rem;">🗑</div>
            <div style="font-weight:900; color:var(--navy); font-size:1rem; margin-bottom:0.4rem; font-family:'Tajawal',sans-serif;">حذف المرفق</div>
            <div style="color:var(--text-muted); font-size:0.85rem; margin-bottom:1.5rem; font-family:'Tajawal',sans-serif;">هل أنت متأكد؟ لا يمكن التراجع</div>
            <div style="display:flex; gap:0.75rem; justify-content:center;">
                <button wire:click="deleteAttachment"
                    style="padding:0.55rem 1.75rem; background:#dc2626; color:#fff; border:none; border-radius:8px; font-weight:900; font-size:0.88rem; font-family:'Tajawal',sans-serif; cursor:pointer;">
                    نعم، احذف
                </button>
                <button wire:click="$set('deleteId', null)"
                    style="padding:0.55rem 1.5rem; background:#f3f4f6; color:#374151; border:1px solid #e5e7eb; border-radius:8px; font-weight:700; font-size:0.88rem; font-family:'Tajawal',sans-serif; cursor:pointer;">
                    إلغاء
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
</div>
