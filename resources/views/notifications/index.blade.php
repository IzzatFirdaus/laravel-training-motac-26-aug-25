@extends('layouts.app')

@section('title', 'Pemberitahuan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="notifications-heading">
    <header class="mb-4">
        <h1 id="notifications-heading" class="myds-heading-md font-heading font-semibold">Pemberitahuan</h1>
        <p class="myds-body-sm myds-text--muted mb-0">
            Semua pemberitahuan terkini ditunjukkan di sini. Gunakan butang "Lihat" untuk membuka butiran penuh atau tandakan sebagai dibaca.
        </p>
    </header>

    {{-- Flash messages --}}
    @if(session('status'))
        <div class="myds-alert myds-alert--success mb-3" role="status" aria-live="polite">
            {{ session('status') }}
        </div>
    @endif

    @php
        // Ensure we operate on a collection for counting unread notifications.
        $collection = $notifications instanceof \Illuminate\Pagination\AbstractPaginator
            ? $notifications->getCollection()
            : collect($notifications);
        $unreadCount = $collection->whereNull('read_at')->count();
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="myds-body-sm myds-text--muted" aria-live="polite" id="notifications-count">
            @if($collection->count() > 0)
                Memaparkan {{ $collection->count() }} pemberitahuan{{ $unreadCount ? ' — ' . $unreadCount . ' baru' : '' }}.
            @else
                Tiada pemberitahuan untuk dipaparkan.
            @endif
        </div>

        <div class="d-flex gap-2">
            @if($unreadCount > 0 && Route::has('notifications.readAll'))
                <form method="POST" action="{{ route('notifications.readAll') }}" class="d-inline" data-myds-form aria-label="Tandakan semua pemberitahuan sebagai dibaca">
                    @csrf
                    <button type="submit" class="myds-btn myds-btn--tertiary myds-btn--sm" data-confirm="Tandakan semua pemberitahuan sebagai dibaca?">
                        Tandakan Semua Dibaca
                    </button>
                </form>
            @endif

            <a href="{{ url()->current() }}" class="myds-btn myds-btn--outline myds-btn--sm" aria-label="Segarkan pemberitahuan">Segarkan</a>
        </div>
    </div>

    @if($collection->isEmpty())
        <div class="myds-card" role="status" aria-live="polite">
            <div class="myds-card__body text-center">
                <svg width="48" height="48" class="mb-3 mx-auto d-block myds-text--muted" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
                    <path d="M12 2a7 7 0 0 0-7 7v4l-1 2h16l-1-2v-4a7 7 0 0 0-7-7z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <h2 class="myds-heading-sm font-semibold">Tiada pemberitahuan</h2>
                <p class="myds-text--muted myds-body-sm">Anda tidak mempunyai pemberitahuan yang belum dibaca pada masa ini.</p>
            </div>
        </div>
    @else
        <ul id="notifications-list" class="myds-list myds-list--plain" role="list" aria-label="Senarai pemberitahuan" aria-live="polite" aria-atomic="false">
            @foreach($notifications as $note)
                @php
                    $isUnread = is_null($note->read_at);
                    $message = data_get($note->data, 'message') ?? data_get($note->data, 'title') ?? 'Pemberitahuan';
                    $meta = data_get($note->data, 'meta');
                    $created = optional($note->created_at);
                @endphp

                <li role="listitem" class="myds-card mb-3 {{ $isUnread ? 'myds-card--highlight' : '' }}">
                    <article class="d-flex justify-content-between align-items-start" aria-labelledby="note-{{ $note->id }}-title">
                        <div class="flex-grow-1">
                            <div class="d-flex gap-2 align-items-start">
                                <div class="me-2" aria-hidden="true">
                                    @if($isUnread)
                                        <span class="myds-badge myds-badge--success" title="Baru">Baru</span>
                                    @endif
                                </div>

                                <div>
                                    <h3 id="note-{{ $note->id }}-title" class="myds-body-md fw-semibold mb-1" style="margin:0;">
                                        {{ \Illuminate\Support\Str::limit($message, 120) }}
                                    </h3>

                                    @if($meta)
                                        <div class="myds-body-xs myds-text--muted mb-1">{{ \Illuminate\Support\Str::limit($meta, 140) }}</div>
                                    @endif

                                    <div class="myds-text--muted myds-body-xs">
                                        <time datetime="{{ $created?->toIso8601String() ?? now()->toIso8601String() }}">
                                            {{ $created ? $created->format('d/m/Y H:i') : '-' }}
                                        </time>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ms-3 d-flex gap-2 align-items-start">
                            <a href="{{ route('notifications.show', $note->id) }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Lihat pemberitahuan {{ $note->id }}">
                                Lihat
                            </a>

                            @if($isUnread)
                                <form method="POST" action="{{ route('notifications.read', $note->id) }}" class="d-inline" data-myds-form aria-label="Tandakan pemberitahuan sebagai dibaca">
                                    @csrf
                                    <button type="submit" class="myds-btn myds-btn--tertiary myds-btn--sm" aria-label="Tandakan sebagai dibaca">
                                        Tandakan Dibaca
                                    </button>
                                </form>
                            @else
                                {{-- Optionally allow marking as unread if route exists --}}
                                @if(Route::has('notifications.unread'))
                                    <form method="POST" action="{{ route('notifications.unread', $note->id) }}" class="d-inline" data-myds-form aria-label="Tandakan pemberitahuan sebagai belum dibaca">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="myds-btn myds-btn--outline myds-btn--sm" aria-label="Tandakan sebagai belum dibaca">Tandakan Belum Dibaca</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </article>
                </li>
            @endforeach
        </ul>

        <nav aria-label="Navigasi halaman pemberitahuan" class="mt-3" role="navigation">
            {{ method_exists($notifications, 'withQueryString') ? $notifications->withQueryString()->links() : $notifications->links() }}
        </nav>
    @endif
</main>
@endsection
