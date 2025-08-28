@extends('layouts.app')

@section('title', 'Permohonan — ' . config('app.name', 'second-crud'))

@section('content')
<main id="main-content" class="container" tabindex="-1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10">
            <header class="d-flex align-items-start justify-content-between mb-3">
                <div>
                    <h1 class="h3">Permohonan</h1>
                    <p class="text-muted mb-0">Senarai permohonan dalam sistem.</p>
                </div>
                <div class="text-end">
                    @can('create', App\Models\Application::class)
                        <a href="{{ route('applications.create') }}" class="myds-btn myds-btn--primary">Cipta permohonan</a>
                    @endcan
                </div>
            </header>
            <div class="mb-3">
                <form method="GET" action="{{ route('applications.index') }}" class="d-flex" role="search">
                    <input name="q" class="form-control me-2" placeholder="Cari permohonan" value="{{ old('q', $q ?? '') }}">
                    <button class="myds-btn myds-btn--secondary" type="submit">Cari</button>
                </form>
            </div>

            <section aria-labelledby="applications-heading">
                <h2 id="applications-heading" class="visually-hidden">Jadual permohonan</h2>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <caption class="visually-hidden">Senarai permohonan; gunakan tindakan untuk lihat atau edit permohonan.</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Dibuat</th>
                                        <th scope="col">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($applications as $application)
                                        <tr>
                                            <td>{{ $application->id }}</td>
                                            <td>{{ $application->name ?? '—' }}</td>
                                            <td>{{ $application->created_at?->format('Y-m-d') ?? '—' }}</td>
                                            <td class="text-nowrap">
                                                <x-action-buttons
                                                    :model="$application"
                                                    :showRoute="route('applications.show', $application->id)"
                                                    :editRoute="route('applications.edit', $application->id)"
                                                    :destroyRoute="route('applications.destroy', $application->id)"
                                                    :label="$application->name ?? 'Permohonan'"
                                                    :id="$application->id"
                                                    :extraItems="[
                                                        ['label' => 'Cari Inventori untuk aplikasi', 'route' => route('inventories.index', ['q' => $application->name])]
                                                    ]"
                                                />
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <div role="status" class="p-3 text-center">
                                                    <p class="mb-2">Tiada permohonan dijumpai.</p>
                                                    @can('create', App\Models\Application::class)
                                                        <a href="{{ route('applications.create') }}" class="myds-btn myds-btn--primary">Cipta permohonan pertama</a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if (method_exists($applications, 'links'))
                            <nav class="mt-3" aria-label="Paginasi permohonan">
                                {{ $applications->links() }}
                            </nav>
                        @endif
                            @if (method_exists($applications, 'links'))
                                <nav class="mt-3" aria-label="Paginasi permohonan">
                                    {{ $applications->links() }}
                                </nav>
                            @endif
                        </div>
                    </div>

                    @if(!empty($q))
                        <div class="card mt-4">
                            <div class="card-body">
                                <h3 class="h5">Keputusan carian untuk "{{ e($q) }}" — Inventori</h3>
                                @if($inventories->isEmpty())
                                    <p class="text-muted">Tiada inventori dijumpai.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nama</th>
                                                    <th>Dibuat</th>
                                                    <th>Tindakan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($inventories as $inv)
                                                    <tr>
                                                        <td>{{ $inv->id }}</td>
                                                        <td>{{ $inv->name }}</td>
                                                        <td>{{ $inv->created_at?->format('Y-m-d') ?? '—' }}</td>
                                                        <td class="text-nowrap">
                                                            <x-action-buttons
                                                                :model="$inv"
                                                                :showRoute="route('inventories.show', $inv->id)"
                                                                :editRoute="route('inventories.edit', $inv->id)"
                                                                :destroyRoute="route('inventories.destroy', $inv->id)"
                                                                :label="$inv->name"
                                                                :id="$inv->id"
                                                                :extraItems="[
                                                                    ['label' => 'Cari Permohonan berkaitan', 'route' => route('applications.index', ['q' => $inv->name])]
                                                                ]"
                                                            />
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if(method_exists($inventories, 'links'))
                                        <nav class="mt-3" aria-label="Paginasi inventori">
                                            {{ $inventories->links() }}
                                        </nav>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
</main>
@endsection
