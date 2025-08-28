@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Pemberitahuan</h1>

    @if($notifications->isEmpty())
        <p>Tiada pemberitahuan.</p>
    @else
        <ul class="list-group">
            @foreach($notifications as $note)
                @php $isUnread = is_null($note->read_at); @endphp
                <li class="list-group-item d-flex justify-content-between align-items-start {{ $isUnread ? 'bg-light border-primary' : '' }}">
                    <div>
                        <div class="fw-bold">{{ $note->data['message'] ?? 'Pemberitahuan' }}
                            @if($isUnread)
                                <span class="badge bg-success ms-2">Baru</span>
                            @endif
                        </div>
                        <div class="text-muted small">{{ $note->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div>
                        <a href="{{ route('notifications.show', $note->id) }}" class="btn btn-sm btn-primary">Lihat</a>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
