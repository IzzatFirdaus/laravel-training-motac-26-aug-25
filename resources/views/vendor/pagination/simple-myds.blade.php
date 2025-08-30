@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="myds-pagination">
        <div class="myds-body-sm myds-text--muted" aria-live="polite">
            Memaparkan {{ $paginator->firstItem() ?? 0 }} hingga {{ $paginator->lastItem() ?? 0 }} daripada {{ $paginator->total() }} rekod
        </div>
        <ul role="list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span class="myds-btn myds-btn--secondary opacity-50 cursor-not-allowed">
                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                        <span class="visually-hidden">{{ __('pagination.previous') }}</span>
                    </span>
                </li>
            @else
                <li>
                    <a class="myds-btn myds-btn--secondary" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}">
                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                        <span class="visually-hidden">{{ __('pagination.previous') }}</span>
                    </a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a class="myds-btn myds-btn--secondary" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}">
                        <span class="visually-hidden">{{ __('pagination.next') }}</span>
                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                    </a>
                </li>
            @else
                <li aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span class="myds-btn myds-btn--secondary opacity-50 cursor-not-allowed">
                        <span class="visually-hidden">{{ __('pagination.next') }}</span>
                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
