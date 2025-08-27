@extends('layouts.app')

@section('title', 'Home — ' . config('app.name', 'second-crud'))

@section('content')
    <main class="container py-4">
        <header class="mb-3">
            <h1 class="h4">Dashboard</h1>
            @if (session('status'))
                <div class="alert alert-success" role="status">{{ session('status') }}</div>
            @endif
            <p class="mb-0 text-muted">You are logged in!</p>
        </header>

        <section aria-labelledby="home-quick-links">
            <h2 id="home-quick-links" class="visually-hidden">Quick links</h2>

            <div class="row g-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <a class="card h-100 text-decoration-none text-body" href="{{ route('inventories.index') }}">
                        <div class="card-body">
                            <h3 class="h6">Inventories</h3>
                            <p class="mb-0 text-muted">Browse and manage inventory items.</p>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <a class="card h-100 text-decoration-none text-body" href="{{ route('inventories.create') }}">
                        <div class="card-body">
                            <h3 class="h6">Create Inventory</h3>
                            <p class="mb-0 text-muted">Add new inventory items.</p>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <a class="card h-100 text-decoration-none text-body" href="{{ route('vehicles.index') }}">
                        <div class="card-body">
                            <h3 class="h6">Vehicles</h3>
                            <p class="mb-0 text-muted">View vehicle records and details.</p>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <a class="card h-100 text-decoration-none text-body" href="{{ route('vehicles.create') }}">
                        <div class="card-body">
                            <h3 class="h6">Create Vehicle</h3>
                            <p class="mb-0 text-muted">Add a new vehicle record.</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    </main>
@endsection
