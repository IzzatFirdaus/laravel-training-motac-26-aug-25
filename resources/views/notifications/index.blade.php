@extends('layouts.app')

@section('title', 'Pemberitahuan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="notifications-heading">
    <header class="mb-4">
        <h1 id="notifications-heading" class="myds-heading-md font-heading font-semibold">Pemberitahuan</h1>
        <p class="myds-body-sm text-muted mb-0">Semua pemberitahuan terkini ditunjukkan di sini. Gunakan butang "Lihat" untuk membuka butiran penuh.</p>
    </header>

    @if($notifications->isEmpty())
        <div class="bg-surface border rounded p-4 text-center">
            <svg width="48" height="48" class="mb-3 mx-auto d-block text-muted" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 2a7 7 0 0 0-7 7v4l-1 2h16l-1-2v-4a7 7 0 0 0-7-7z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h2 class="myds-heading-sm font-semibold">Tiada pemberitahuan</h2>
            <p class="text-muted myds-body-sm">Anda tidak mempunyai pemberitahuan yang belum dibaca pada masa ini.</p>
        </div>
    @else
        <ul class="myds-list myds-list--plain" role="list" aria-label="Senarai pemberitahuan">
            @foreach($notifications as $note)
                @php
                    $isUnread = is_null($note->read_at);
                    // Safely get message/title from notification payload
                    $message = data_get($note->data, 'message') ?? data_get($note->data, 'title') ?? 'Pemberitahuan';
                    $created = optional($note->created_at);
                @endphp

                <li role="listitem" class="myds-card mb-3 {{ $isUnread ? 'border-primary bg-washed' : 'border-muted' }}" aria-live="polite">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-start gap-2">
                                <div class="me-2" aria-hidden="true">
                                    @if($isUnread)
                                        <span class="myds-badge myds-badge--success" title="Baru">Baru</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="myds-body-md fw-semibold">
                                        {{ \Illuminate\Support\Str::limit($message, 120) }}
                                    </div>
                                    @if($note->data['meta'] ?? false)
                                        <div class="myds-body-xs text-muted">{{ \Illuminate\Support\Str::limit(data_get($note->data, 'meta'), 120) }}</div>
                                    @endif
                                    <div class="text-muted myds-body-xs mt-1">
                                        <time datetime="{{ $created?->toIso8601String() ?? now()->toIso8601String() }}">
                                            {{ $created ? $created->format('d/m/Y H:i') : '-' }}
                                        </time>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ms-3 d-flex gap-2 align-items-start">
                            <a href="{{ route('notifications.show', $note->id) }}" class="myds-btn myds-btn--primary myds-btn--sm" aria-label="Lihat pemberitahuan">Lihat</a>

                            @if($isUnread)
                                {{-- Mark as read action (progressive enhancement: form falls back to server) --}}
                                <form method="POST" action="{{ route('notifications.read', $note->id) }}" class="d-inline" data-myds-form>
                                    @csrf
                                    <button type="submit" class="myds-btn myds-btn--tertiary myds-btn--sm" aria-label="Tandakan sebagai dibaca">Tandakan Dibaca</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        <nav aria-label="Navigasi halaman pemberitahuan" class="mt-3" role="navigation">
            {{ $notifications->links() }}
        </nav>
    @endif
</main>
@endsection