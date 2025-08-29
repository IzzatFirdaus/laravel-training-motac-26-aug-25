@if ($paginator->hasPages())
<nav role="navigation" aria-label="Navigasi halaman" class="myds-pagination">
    <div class="d-flex justify-content-between align-items-center">
        <div class="myds-body-sm text-muted">
            Memaparkan {{ $paginator->firstItem() ?? 0 }} hingga {{ $paginator->lastItem() ?? 0 }} daripada {{ $paginator->total() }} rekod
        </div>

        <ul class="d-flex list-unstyled gap-1 mb-0" role="list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true" aria-label="Sebelumnya">
                    <span class="myds-btn myds-btn--secondary opacity-50 cursor-not-allowed">
                        <svg width="16" height="16" class="me-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <polyline points="15,18 9,12 15,6" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        Sebelumnya
                    </span>
                </li>
            @else
                <li>
                    <a class="myds-btn myds-btn--secondary" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya">
                        <svg width="16" height="16" class="me-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <polyline points="15,18 9,12 15,6" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        Sebelumnya
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li aria-disabled="true">
                        <span class="d-flex align-items-center px-2 myds-body-sm text-muted">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li aria-current="page">
                                <span class="myds-btn myds-btn--primary">
                                    {{ $page }}
                                    <span class="sr-only">(halaman semasa)</span>
                                </span>
                            </li>
                        @else
                            <li>
                                <a class="myds-btn myds-btn--tertiary" href="{{ $url }}" aria-label="Pergi ke halaman {{ $page }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a class="myds-btn myds-btn--secondary" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman seterusnya">
                        Seterusnya
                        <svg width="16" height="16" class="ms-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <polyline points="9,18 15,12 9,6" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </a>
                </li>
            @else
                <li aria-disabled="true" aria-label="Seterusnya">
                    <span class="myds-btn myds-btn--secondary opacity-50 cursor-not-allowed">
                        Seterusnya
                        <svg width="16" height="16" class="ms-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <polyline points="9,18 15,12 9,6" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </span>
                </li>
            @endif
        </ul>
    </div>
</nav>
@endif
