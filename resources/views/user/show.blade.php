@extends('layouts.app')

@section('title', $user->name . ' — ' . config('app.name', 'second-crud'))

@section('content')
<main class="container" id="main-content" tabindex="-1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <header class="mb-3 d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="h3">{{ $user->name }}</h1>
                    <p class="text-muted mb-0">Butiran pengguna.</p>
                </div>
                <div>
                    <a href="{{ route('users.edit', $user->id) }}" class="myds-btn myds-btn--secondary myds-btn--sm myds-btn--outline">Sunting</a>
                </div>
            </header>

            <div class="card">
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">ID</dt>
                        <dd class="col-sm-9">{{ $user->id }}</dd>

                        <dt class="col-sm-3">Nama</dt>
                        <dd class="col-sm-9">{{ $user->name }}</dd>

                        <dt class="col-sm-3">Emel</dt>
                        <dd class="col-sm-9">{{ $user->email }}</dd>

                        <dt class="col-sm-3">Dicipta</dt>
                        <dd class="col-sm-9">{{ $user->created_at?->format('Y-m-d H:i') ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
