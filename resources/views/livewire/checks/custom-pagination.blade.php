@if ($paginator->hasPages())
    <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 20px; font-family: 'Tajawal', sans-serif; direction: rtl;">
        
        {{-- Previous Button --}}
        @if ($paginator->onFirstPage())
            <span style="padding: 8px 16px; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 10px; color: #94a3b8; font-weight: 800; font-size: 0.9rem; cursor: not-allowed;">السابق</span>
        @else
            <button wire:click="previousPage" style="padding: 8px 16px; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; color: var(--navy); font-weight: 800; font-size: 0.9rem; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='var(--primary)';" onmouseout="this.style.background='#fff'; this.style.borderColor='#e2e8f0';">السابق</button>
        @endif

        {{-- Page Numbers (Max 6) --}}
        @php
            $start = max(1, $paginator->currentPage() - 2);
            $end = min($paginator->lastPage(), $start + 5);
            if ($end - $start < 5) { $start = max(1, $end - 5); }
        @endphp

        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $paginator->currentPage())
                <span style="min-width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; background: var(--primary); color: #fff; border-radius: 10px; font-weight: 900; font-size: 1rem; box-shadow: 0 4px 10px var(--primary-glow);">{{ $i }}</span>
            @else
                <button wire:click="gotoPage({{ $i }})" style="min-width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; color: var(--text-dim); font-weight: 800; font-size: 1rem; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='var(--primary)'; this.style.color='var(--primary)';" onmouseout="this.style.background='#fff'; this.style.borderColor='#e2e8f0'; this.style.color='var(--text-dim)';">{{ $i }}</button>
            @endif
        @endfor

        {{-- Next Button --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" style="padding: 8px 20px; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; color: var(--navy); font-weight: 800; font-size: 0.9rem; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='var(--primary)';" onmouseout="this.style.background='#fff'; this.style.borderColor='#e2e8f0';">Next</button>
        @else
            <span style="padding: 8px 20px; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 10px; color: #94a3b8; font-weight: 800; font-size: 0.9rem; cursor: not-allowed;">Next</span>
        @endif

    </div>
@endif
