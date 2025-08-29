@extends('layouts.app')

@section('title', $user->name . ' — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main">
    <div class="desktop:col-span-8 tablet:col-span-8 mobile:col-span-4 mx-auto" style="max-width:760px;">
        <header class="mb-3 d-flex justify-content-between align-items-start">
            <div>
                <h1 class="myds-heading-md font-heading font-semibold">{{ $user->name }}</h1>
                <p class="myds-body-sm text-muted mb-0">Butiran pengguna.</p>
            </div>
            <div>
                @can('update', $user)
                    <a href="{{ route('users.edit', $user->id) }}" class="myds-btn myds-btn--secondary myds-btn--sm myds-btn--outline">Edit</a>
                @endcan
            </div>
        </header>

        <div class="bg-surface border rounded-m p-4 shadow-sm">
            <dl class="row g-3">
                <dt class="col-4 myds-body-sm text-muted">ID</dt>
                <dd class="col-8 myds-body-md">{{ $user->id }}</dd>

                <dt class="col-4 myds-body-sm text-muted">Nama</dt>
                <dd class="col-8 myds-body-md">{{ $user->name }}</dd>

                <dt class="col-4 myds-body-sm text-muted">Emel</dt>
                <dd class="col-8 myds-body-md">{{ $user->email }}</dd>

                <dt class="col-4 myds-body-sm text-muted">Dicipta</dt>
                <dd class="col-8 myds-body-md">{{ $user->created_at?->format('Y-m-d H:i') ?? '—' }}</dd>
            </dl>
        </div>
    </div>
</main>
@endsection
