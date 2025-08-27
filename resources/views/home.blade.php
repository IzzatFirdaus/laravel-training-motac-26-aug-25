@extends('layouts.app')

@section('title', 'Home — ' . config('app.name', 'second-crud'))

@section('content')
    <main class="container py-4">
        <header class="mb-3">
                <h1 class="h4">Papan Pemuka</h1>
            @if (session('status'))
                <div class="alert alert-success" role="status">{{ session('status') }}</div>
            @endif
            <p class="mb-0 text-muted">You are logged in!</p>
        </header>

        <section aria-labelledby="home-quick-links">
            <h2 id="home-quick-links" class="visually-hidden">Pautan Pantas Papan Pemuka</h2>

            @php
                // Count quick stats; safe if tables are missing (returns 0)
                try {
                    $inventoriesCount = \DB::table('inventories')->count();
                } catch (\Throwable $e) {
                    $inventoriesCount = 0;
                }

                try {
                    $vehiclesCount = \DB::table('vehicles')->count();
                } catch (\Throwable $e) {
                    $vehiclesCount = 0;
                }
            @endphp

            <div class="row g-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Inventori</h5>
                            <p class="text-muted mb-2">Jumlah item inventori dalam pangkalan data.</p>
                            <div class="mb-3">
                                <strong class="h3">{{ $inventoriesCount }}</strong>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('inventories.index') }}" class="btn btn-primary">Senarai</a>
                                <a href="{{ route('inventories.create') }}" class="btn btn-outline-primary">Cipta</a>
                                <a href="{{ route('inventories.show', 1) }}" class="btn btn-outline-secondary">Lihat</a>
                                <a href="{{ route('inventories.edit', 1) }}" class="btn btn-outline-secondary">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Kenderaan</h5>
                            <p class="text-muted mb-2">Jumlah rekod kenderaan dalam pangkalan data.</p>
                            <div class="mb-3">
                                <strong class="h3">{{ $vehiclesCount }}</strong>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('vehicles.index') }}" class="btn btn-primary">Senarai</a>
                                <a href="{{ route('vehicles.create') }}" class="btn btn-outline-primary">Cipta</a>
                                <a href="{{ route('vehicles.show', 1) }}" class="btn btn-outline-secondary">Lihat</a>
                                <a href="{{ route('vehicles.edit', 1) }}" class="btn btn-outline-secondary">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Contoh / Demo</h5>
                                            <p class="text-muted mb-2">Sekiranya anda ingin melihat contoh tindakan (Edit/Show/Destroy), gunakan halaman Welcome.</p>
                                            <div class="d-flex gap-2">
                                                <a href="{{ url('/') }}" class="btn btn-outline-primary">Pergi ke Contoh</a>
                                                <a href="{{ url('/') }}#examples-heading" class="btn btn-outline-secondary">Pergi ke Contoh (khas)</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                --}}
            </div>
        </section>
    </main>
@endsection
