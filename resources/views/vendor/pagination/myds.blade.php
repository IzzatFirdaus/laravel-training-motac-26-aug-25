@if ($paginator->hasPages())
<nav role="navigation" aria-label="Navigasi halaman" class="myds-pagination">
    <div class="d-flex justify-content-between align-items-center">
        <div class="myds-body-sm myds-text--muted">
            Memaparkan {{ $paginator->firstItem() ?? 0 }} hingga {{ $paginator->lastItem() ?? 0 }} daripada {{ $paginator->total() }} rekod
        </div>

        <ul class="d-flex list-unstyled gap-1 mb-0" role="list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true" aria-label="Sebelumnya">
                    <span class="myds-btn myds-btn--secondary opacity-50 cursor-not-allowed">
                        <i class="bi bi-chevron-left me-1" aria-hidden="true"></i>
                        Sebelumnya
                    </span>
                </li>
            @else
                <li>
                    <a class="myds-btn myds-btn--secondary" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya">
                        <i class="bi bi-chevron-left me-1" aria-hidden="true"></i>
                        Sebelumnya
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li aria-disabled="true">
                        <span class="d-flex align-items-center px-2 myds-body-sm myds-text--muted">{{ $element }}</span>
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
                        <i class="bi bi-chevron-right ms-1" aria-hidden="true"></i>
                    </a>
                </li>
            @else
                <li aria-disabled="true" aria-label="Seterusnya">
                    <span class="myds-btn myds-btn--secondary opacity-50 cursor-not-allowed">
                        Seterusnya
                        <i class="bi bi-chevron-right ms-1" aria-hidden="true"></i>
                    </span>
                </li>
            @endif
        </ul>
    </div>
</nav>
@endif
