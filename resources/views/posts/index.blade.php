@extends('layouts.app')

@section('title', 'Pos — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-6" role="main" tabindex="-1" aria-labelledby="posts-heading">
    <header class="mb-4">
        <h1 id="posts-heading" class="myds-heading-md font-heading font-semibold">Pos</h1>
        <p class="myds-body-sm myds-text--muted mb-0">
            Pos luaran yang diambil daripada jsonplaceholder.typicode.com — dipaparkan untuk tujuan demonstrasi dan ujian API.
        </p>
    </header>

    <section aria-labelledby="posts-list-heading" class="mt-4">
        <h2 id="posts-list-heading" class="visually-hidden">Senarai Pos</h2>

        {{-- Container card following MYDS surface token --}}
        <div class="myds-card">
            <div class="myds-card__body">
                @php
                    // Normalize posts variable: accept null, array, or collection
                    $postsList = $posts ?? [];
                @endphp

                @if(empty($postsList) || count($postsList) === 0)
                    <div class="bg-washed p-4 text-center">
                        <svg width="48" height="48" class="mb-3 mx-auto d-block myds-text--muted" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8 7V4a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                        <h3 class="myds-heading-sm font-semibold mb-1">Tiada pos ditemui</h3>
                        <p class="myds-text--muted myds-body-sm mb-0">Tiada maklumat pos tersedia pada masa ini. Sila cuba lagi kemudian.</p>
                    </div>
                @else
                    <ul class="myds-list myds-list--stacked" role="list" aria-label="Senarai pos luaran">
                        @foreach($postsList as $post)
                            @php
                                // Normalize keys and provide safe defaults
                                $postId = data_get($post, 'id', null);
                                $title = trim((string) data_get($post, 'title', 'Pos'));
                                $body = trim((string) data_get($post, 'body', ''));
                                $excerpt = \Illuminate\Support\Str::limit($body, 160);
                                $externalUrl = $postId ? "https://jsonplaceholder.typicode.com/posts/{$postId}" : '#';
                            @endphp

                            <li role="listitem" class="myds-card myds-card--compact mb-3" aria-live="polite">
                                <div class="myds-card__body d-flex justify-content-between align-items-start gap-3">
                                    <div class="flex-grow-1">
                                        <h3 class="myds-card__title mb-1">
                                            {{ e($title) }}
                                        </h3>

                                        @if(!empty($excerpt))
                                            <p class="myds-body-sm myds-text--muted mb-2">{{ e($excerpt) }}</p>
                                        @endif

                                        {{-- meta info: optional userId or fallback --}}
                                        @if(data_get($post, 'userId'))
                                            <div class="myds-body-xs myds-text--muted">Pengarang ID: {{ e(data_get($post, 'userId')) }}</div>
                                        @endif
                                    </div>

                                    <div class="d-flex flex-column align-items-end gap-2">
                                        {{-- External link opens in new tab with appropriate rel attributes for security --}}
                                        <a href="{{ $externalUrl }}"
                                           class="myds-btn myds-btn--secondary myds-btn--sm"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           aria-label="Lihat pos di sumber luar">
                                            <span>Lihat</span>
                                            <svg class="myds-icon ms-2" aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M14 3h7v7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 14L21 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 21H3V3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </a>

                                        {{-- Optional "Copy link" for quick sharing (progressive enhancement) --}}
                                        <button type="button"
                                                class="myds-btn myds-btn--tertiary myds-btn--sm"
                                                data-copy-url="{{ $externalUrl }}"
                                                aria-label="Salin pautan pos"
                                                onclick="navigator.clipboard?.writeText(this.dataset.copyUrl).then(()=>{this.innerText='Disalin'; setTimeout(()=>this.innerText='Salin pautan',1500)}).catch(()=>{ alert('Gagal menyalin pautan'); })">
                                            Salin pautan
                                        </button>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    {{-- If the source supports pagination, the controller can pass a paginator.
                         Render pagination only when the $postsList is a LengthAwarePaginator instance. --}}
                    @if($postsList instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <nav class="mt-3" role="navigation" aria-label="Navigasi halaman pos">
                            {{ $postsList->links() }}
                        </nav>
                    @endif
                @endif
            </div>
        </div>
    </section>
</main>
@endsection