@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}
$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
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
            <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="mtm-pg-btn">&#8249;</button>
        @endif

        {{-- الأرقام --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="mtm-pg-btn mtm-pg-dots">…</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                        @if ($page == $paginator->currentPage())
                            <span class="mtm-pg-btn mtm-pg-active">{{ $page }}</span>
                        @else
                            <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="mtm-pg-btn">{{ $page }}</button>
                        @endif
                    </span>
                @endforeach
            @endif
        @endforeach

        {{-- التالي --}}
        @if ($paginator->hasMorePages())
            <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="mtm-pg-btn">&#8250;</button>
        @else
            <span class="mtm-pg-btn mtm-pg-disabled">&#8250;</span>
        @endif

    </div>

</nav>
@endif
</div>
