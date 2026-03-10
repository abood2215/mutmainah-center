@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation" style="display:flex; align-items:center; gap:6px; flex-wrap:wrap; justify-content:center; font-family:'Tajawal',sans-serif;">

    {{-- زر السابق --}}
    @if ($paginator->onFirstPage())
        <span style="display:inline-flex; align-items:center; justify-content:center; min-width:40px; height:40px; padding:0 12px; background:#f1f5f9; border:1px solid #e2e8f0; border-radius:10px; color:#94a3b8; font-size:0.85rem; cursor:not-allowed; user-select:none; font-weight:800;">السابق</span>
    @else
        <button wire:click="previousPage" wire:loading.attr="disabled"
            style="display:inline-flex; align-items:center; justify-content:center; min-width:40px; height:40px; padding:0 12px; background:#fff; border:1px solid #e2e8f0; border-radius:10px; color:var(--text-dim); font-size:0.85rem; font-weight:800; cursor:pointer; transition:all 0.2s;"
            onmouseover="this.style.background='#f8fafc'; this.style.borderColor='var(--primary)';"
            onmouseout="this.style.background='#fff'; this.style.borderColor='#e2e8f0';">
            السابق
        </button>
    @endif

    {{-- أرقام الصفحات (محدودة بـ 6 صفحات كحد أقصى تظهر) --}}
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $start = max($currentPage - 2, 1);
        $end = min($start + 5, $lastPage);
        if ($end - $start < 5 && $start > 1) {
            $start = max($end - 5, 1);
        }
    @endphp

    @for ($i = $start; $i <= $end; $i++)
        @if ($i == $currentPage)
            <span aria-current="page"
                style="display:inline-flex; align-items:center; justify-content:center; min-width:40px; height:40px; padding:0 8px; background:var(--primary); border:1px solid var(--primary); border-radius:10px; color:#fff; font-size:0.9rem; font-weight:900; user-select:none; box-shadow:0 4px 12px var(--primary-glow);">
                {{ $i }}
            </span>
        @else
            <button wire:click="gotoPage({{ $i }})"
                style="display:inline-flex; align-items:center; justify-content:center; min-width:40px; height:40px; padding:0 8px; background:#fff; border:1px solid #e2e8f0; border-radius:10px; color:var(--text-dim); font-size:0.9rem; font-weight:800; cursor:pointer; transition:all 0.2s;"
                onmouseover="this.style.background='#f8fafc'; this.style.borderColor='var(--primary)';"
                onmouseout="this.style.background='#fff'; this.style.borderColor='#e2e8f0';">
                {{ $i }}
            </button>
        @endif
    @endfor

    @if($end < $lastPage)
        <span style="display:inline-flex; align-items:center; justify-content:center; min-width:40px; height:40px; color:#94a3b8; font-weight:800;">...</span>
    @endif

    {{-- زر التالي --}}
    @if ($paginator->hasMorePages())
        <button wire:click="nextPage" wire:loading.attr="disabled"
            style="display:inline-flex; align-items:center; justify-content:center; min-width:40px; height:40px; padding:0 15px; background:#fff; border:1px solid #e2e8f0; border-radius:10px; color:var(--text-dim); font-size:0.85rem; font-weight:800; cursor:pointer; transition:all 0.2s;"
            onmouseover="this.style.background='#f8fafc'; this.style.borderColor='var(--primary)';"
            onmouseout="this.style.background='#fff'; this.style.borderColor='#e2e8f0';">
            Next
        </button>
    @else
        <span style="display:inline-flex; align-items:center; justify-content:center; min-width:40px; height:40px; padding:0 15px; background:#f1f5f9; border:1px solid #e2e8f0; border-radius:10px; color:#94a3b8; font-size:0.85rem; cursor:not-allowed; user-select:none; font-weight:800;">Next</span>
    @endif

</nav>
@endif
