@extends('layouts.app')

@section('title', 'Permohonan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="applications-heading">
    <div class="mx-auto content-maxwidth-xl">
        <header class="d-flex align-items-start justify-content-between mb-3">
            <div>
                <h1 id="applications-heading" class="myds-heading-md font-heading">Permohonan</h1>
                <p class="myds-body-sm myds-text--muted mb-0">Senarai permohonan dalam sistem.</p>
            </div>
            <div class="text-end">
                @if($canCreateApplication)
                    <a href="{{ route('applications.create') }}" class="myds-btn myds-btn--primary">Cipta Permohonan</a>
                @endif
            </div>
        </header>

        {{-- Search --}}
        <div class="mb-3">
            <form method="GET" action="{{ route('applications.index') }}" class="d-flex gap-2 align-items-center" role="search" aria-label="Carian permohonan">
                <label for="q" class="sr-only">Cari permohonan</label>
                <input id="q" name="q" class="myds-input" placeholder="{{ __('placeholders.application_search') }}" value="{{ old('q', $q ?? '') }}" aria-label="{{ __('placeholders.application_search') }}">
                <button class="myds-btn myds-btn--secondary" type="submit" aria-label="Cari">Cari</button>
            </form>
        </div>

        <section aria-labelledby="applications-table">
            <h2 id="applications-table" class="sr-only">Jadual permohonan</h2>

            <div class="myds-card">
                <div class="myds-card__body">
                    <div id="applications-count" class="myds-body-sm myds-text--muted mb-3" role="status">
                        Memaparkan {{ method_exists($applications, 'total') ? $applications->total() : (is_countable($applications) ? count($applications) : 0) }} permohonan
                    </div>

                    <div class="myds-table-responsive" role="region" aria-live="polite" aria-atomic="true">
                        <table class="myds-table" aria-describedby="applications-count">
                            <caption class="sr-only">Senarai permohonan; gunakan tindakan untuk lihat atau edit permohonan.</caption>
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
                                        <td class="myds-body-sm myds-text--muted">{{ $application->id }}</td>
                                        <td class="myds-body-sm">{{ $application->name ?? '—' }}</td>
                                        <td class="myds-body-sm myds-text--muted">
                                            <time datetime="{{ $application->created_at?->toIso8601String() ?? now()->toIso8601String() }}">
                                                {{ $application->created_at?->format('Y-m-d') ?? '—' }}
                                            </time>
                                        </td>
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
                                                @if($canCreateApplication)
                                                    <a href="{{ route('applications.create') }}" class="myds-btn myds-btn--primary">Cipta permohonan pertama</a>
                                                @endif
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
                </div>
            </div>

            {{-- Optional inventories result when search query present --}}
            @if(!empty($q))
                <div class="myds-card mt-4">
                    <div class="myds-card__body">
                        <h3 class="myds-heading-sm">Keputusan carian untuk "{{ e($q) }}" — Inventori</h3>
                        @if(isset($inventories) && $inventories->isEmpty())
                            <p class="myds-body-sm myds-text--muted">Tiada inventori dijumpai.</p>
                        @else
                            <div class="myds-table-responsive">
                                <table class="myds-table" aria-describedby="inventories-count">
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
                                                <td class="myds-body-sm myds-text--muted">{{ $inv->id }}</td>
                                                <td class="myds-body-sm">{{ $inv->name }}</td>
                                                <td class="myds-body-sm myds-text--muted">
                                                    <time datetime="{{ $inv->created_at?->toIso8601String() ?? now()->toIso8601String() }}">
                                                        {{ $inv->created_at?->format('Y-m-d') ?? '—' }}
                                                    </time>
                                                </td>
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
        </section>
    </div>
</main>
@endsection
