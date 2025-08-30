@extends('layouts.app')

@section('title', $user->name . ' — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="user-heading">
    <div class="mx-auto content-maxwidth-lg">
        <header class="mb-3 d-flex justify-content-between align-items-start">
            <div>
                <h1 id="user-heading" class="myds-heading-md font-heading font-semibold">{{ $user->name }}</h1>
                <p class="myds-body-sm myds-text--muted mb-0">Butiran pengguna.</p>
            </div>

            <div>
                @can('update', $user)
                    <a href="{{ route('users.edit', $user->id) }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Edit pengguna">Edit</a>
                @endcan
            </div>
        </header>

        <section aria-labelledby="user-details">
            <div class="bg-surface border rounded p-4 shadow-sm">
                <dl class="row g-3" id="user-details">
                    <dt class="col-4 myds-body-sm myds-text--muted">ID</dt>
                    <dd class="col-8 myds-body-md">{{ $user->id }}</dd>

                    <dt class="col-4 myds-body-sm myds-text--muted">Nama</dt>
                    <dd class="col-8 myds-body-md">{{ $user->name }}</dd>

                    <dt class="col-4 myds-body-sm myds-text--muted">Emel</dt>
                    <dd class="col-8 myds-body-md">
                        <a href="mailto:{{ $user->email }}" class="text-primary text-decoration-none" rel="noopener">{{ $user->email }}</a>
                    </dd>

                    <dt class="col-4 myds-body-sm myds-text--muted">Dicipta</dt>
                    <dd class="col-8 myds-body-md">
                        <time datetime="{{ $user->created_at?->toIso8601String() ?? now()->toIso8601String() }}">
                            {{ $user->created_at?->format('Y-m-d H:i') ?? '—' }}
                        </time>
                    </dd>
                </dl>
            </div>
        </section>
    </div>
</main>
@endsection