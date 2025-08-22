@if ($paginator->hasPages())
    <nav aria-label="Simple Pagination Navigation">
        <ul class="pagination pagination-sm mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">
                        <span style="font-size: 0.75rem;">‹</span>
                        @lang('pagination.previous')
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <span style="font-size: 0.75rem;">‹</span>
                        @lang('pagination.previous')
                    </a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        @lang('pagination.next')
                        <span style="font-size: 0.75rem;">›</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">
                        @lang('pagination.next')
                        <span style="font-size: 0.75rem;">›</span>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
