@if ($paginator->hasPages())
<nav class="mtm-pagination" role="navigation" aria-label="Pagination Navigation">

    <div class="mtm-pg-info">
        عرض <strong>{{ $paginator->firstItem() }}</strong> – <strong>{{ $paginator->lastItem() }}</strong>
        من <strong>{{ number_format($paginator->total()) }}</strong>
    </div>

    <div class="mtm-pg-nav">

        {{-- السابق --}}
        @if ($paginator->onFirstPage())
            <span class="mtm-pg-btn mtm-pg-disabled">&#8249;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" wire:navigate class="mtm-pg-btn">&#8249;</a>
        @endif

        {{-- الأرقام --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="mtm-pg-btn mtm-pg-dots">…</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="mtm-pg-btn mtm-pg-active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" wire:navigate class="mtm-pg-btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- التالي --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" wire:navigate class="mtm-pg-btn">&#8250;</a>
        @else
            <span class="mtm-pg-btn mtm-pg-disabled">&#8250;</span>
        @endif

    </div>

</nav>
@endif
