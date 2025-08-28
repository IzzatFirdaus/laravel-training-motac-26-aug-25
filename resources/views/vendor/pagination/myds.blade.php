@if ($paginator->hasPages())
<nav role="navigation" aria-label="Navigasi halaman" class="myds-pagination">
    <ul class="pagination" role="list">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true" aria-label="Sebelumnya">
                <span class="page-link">Sebelumnya</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya">Sebelumnya</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }} <span class="visually-hidden">(halaman semasa)</span></span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}" aria-label="Pergi ke halaman {{ $page }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Seterusnya">Seterusnya</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true" aria-label="Seterusnya">
                <span class="page-link">Seterusnya</span>
            </li>
        @endif
    </ul>
</nav>
@endif
