@if($paginator->hasPages())
@php
    $cur   = $paginator->currentPage();
    $last  = $paginator->lastPage();
    $start = max(1, $cur - 4);
    $end   = min($last, $cur + 4);
@endphp
<div style="background:#1a5276; padding:0.6rem 1rem; display:flex; align-items:center; justify-content:center; gap:0.3rem; flex-wrap:wrap;">

    {{-- السابق --}}
    @if($paginator->onFirstPage())
        <span style="padding:0.3rem 0.7rem; color:rgba(255,255,255,0.35); font-size:0.85rem; font-weight:700;">السابق</span>
    @else
        <button wire:click="previousPage" style="padding:0.3rem 0.7rem; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.3); border-radius:3px; color:#fff; cursor:pointer; font-size:0.85rem; font-weight:700; font-family:'Tajawal',sans-serif;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">السابق</button>
    @endif

    {{-- الصفحة الأولى + نقاط --}}
    @if($start > 1)
        <button wire:click="gotoPage(1)" style="padding:0.3rem 0.6rem; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.25); border-radius:3px; color:#fff; cursor:pointer; font-size:0.85rem; min-width:32px; font-family:'Tajawal',sans-serif;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">1</button>
        @if($start > 2)
            <span style="color:rgba(255,255,255,0.5); font-size:0.9rem; padding:0 2px;">…</span>
        @endif
    @endif

    {{-- نطاق الصفحات --}}
    @for($p = $start; $p <= $end; $p++)
        @if($p == $cur)
            <span style="padding:0.3rem 0.7rem; background:#fff; border-radius:3px; color:#1a5276; font-weight:900; font-size:0.85rem; min-width:32px; text-align:center;">{{ $p }}</span>
        @else
            <button wire:click="gotoPage({{ $p }})" style="padding:0.3rem 0.6rem; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.25); border-radius:3px; color:#fff; cursor:pointer; font-size:0.85rem; min-width:32px; font-family:'Tajawal',sans-serif;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">{{ $p }}</button>
        @endif
    @endfor

    {{-- الصفحة الأخيرة + نقاط --}}
    @if($end < $last)
        @if($end < $last - 1)
            <span style="color:rgba(255,255,255,0.5); font-size:0.9rem; padding:0 2px;">…</span>
        @endif
        <button wire:click="gotoPage({{ $last }})" style="padding:0.3rem 0.6rem; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.25); border-radius:3px; color:#fff; cursor:pointer; font-size:0.85rem; min-width:32px; font-family:'Tajawal',sans-serif;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">{{ $last }}</button>
    @endif

    {{-- التالي --}}
    @if($paginator->hasMorePages())
        <button wire:click="nextPage" style="padding:0.3rem 0.7rem; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.3); border-radius:3px; color:#fff; cursor:pointer; font-size:0.85rem; font-weight:700; font-family:'Tajawal',sans-serif;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">التالي</button>
    @else
        <span style="padding:0.3rem 0.7rem; color:rgba(255,255,255,0.35); font-size:0.85rem; font-weight:700;">التالي</span>
    @endif

</div>
@endif
