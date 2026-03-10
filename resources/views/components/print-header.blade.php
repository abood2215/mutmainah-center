@props(['title' => ''])

{{-- زر الطباعة (يظهر على الشاشة فقط) --}}
<div class="no-print" style="display:flex; justify-content:flex-end; margin-bottom:0.75rem;">
    <button type="button" onclick="window.print()"
        style="padding:0.45rem 1.4rem; background:#16a34a; color:#fff; border:none; border-radius:6px; font-weight:800; font-size:0.88rem; font-family:'Tajawal',sans-serif; cursor:pointer; display:flex; align-items:center; gap:0.4rem; box-shadow:0 2px 6px rgba(22,163,74,0.3);"
        onmouseover="this.style.background='#15803d'" onmouseout="this.style.background='#16a34a'">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        طباعة
    </button>
</div>

{{-- ترويسة المركز (تظهر عند الطباعة فقط) — نفس تصميم بيان الحساب --}}
<div class="print-letterhead" style="padding:0.6rem 1.5rem; border-bottom:3px solid #8b1c2b; background:#fff; margin-bottom:0.5rem;">
    <div style="display:flex; align-items:center; gap:1rem; direction:ltr;">
        {{-- الشعار على اليسار --}}
        <img src="{{ asset('logo.jpg') }}" alt="مطمئنة"
            style="height:62px; width:62px; border-radius:50%; object-fit:cover; border:2px solid #8b1c2b; flex-shrink:0;">
        {{-- خط زخرفي --}}
        <div style="flex:1; height:3px; background:linear-gradient(to right, #8b1c2b, transparent); border-radius:2px;"></div>
        {{-- النص على اليمين --}}
        <div style="text-align:right; line-height:1.2; direction:rtl;">
            <div style="font-size:0.72rem; color:#555; font-weight:600; letter-spacing:1px; font-family:'Tajawal',sans-serif;">مركز</div>
            <div style="font-size:2rem; font-weight:900; color:#8b1c2b; font-family:'Tajawal',sans-serif; letter-spacing:-1px;">مطمئنة</div>
            @if($title)
            <div style="font-size:0.78rem; color:#555; font-weight:600; font-family:'Tajawal',sans-serif; margin-top:0.1rem;">{{ $title }}</div>
            @endif
        </div>
    </div>
</div>
