@extends('layouts.app')

@section('title', 'Kenderaan — ' . config('app.name', 'Sistem Kerajaan'))
@section('title', __('ui.vehicles.title', ['app' => config('app.name', 'Sistem Kerajaan')]))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="vehicles-heading">
    <header class="mb-6 d-flex flex-column flex-md-row align-items-md-start justify-content-md-between gap-4">
        <div>
            <h1 id="vehicles-heading" class="myds-heading-md font-heading font-semibold mb-2">Kenderaan</h1>
            <p class="myds-body-md myds-text--muted mb-0">Rekod kenderaan yang diselenggara oleh sistem inventori kerajaan.</p>
        </div>
            <div>
                <h1 id="vehicles-heading" class="myds-heading-md font-heading font-semibold mb-2">{{ __('ui.vehicles.heading') }}</h1>
                <p class="myds-body-md myds-text--muted mb-0">{{ __('ui.vehicles.description') }}</p>
            </div>

        @can('create', App\Models\Vehicle::class)
            <div>
                <a href="{{ route('vehicles.create') }}" class="myds-btn myds-btn--primary" aria-label="Cipta Kenderaan">
                    <i class="bi bi-plus-lg me-2" aria-hidden="true"></i>
                    Cipta Kenderaan
                </a>
            </div>
                <div>
                    <a href="{{ route('vehicles.create') }}" class="myds-btn myds-btn--primary" aria-label="{{ __('ui.vehicles.create_button') }}">
                        <i class="bi bi-plus-lg me-2" aria-hidden="true"></i> {{ __('ui.vehicles.create_button') }}
                    </a>
                </div>
        @endcan
    </header>

    <section aria-labelledby="vehicles-table-heading">
        <h2 id="vehicles-table-heading" class="sr-only">Jadual Kenderaan</h2>

        @php
            $vehiclesCount = method_exists($vehicles, 'total') ? $vehicles->total() : (is_countable($vehicles) ? count($vehicles) : 0);
        @endphp
        <div id="vehicles-count" class="myds-body-sm myds-text--muted mb-4" role="status">Memaparkan {{ $vehiclesCount }} kenderaan</div>
        <div id="vehicles-count" class="myds-body-sm myds-text--muted mb-4" role="status" aria-live="polite">{{ __('ui.vehicles.displaying_count', ['count' => $vehiclesCount]) }}</div>

        {{-- Filters --}}
        <form method="GET" class="myds-card mb-4" aria-labelledby="filters-heading" novalidate>
            <div class="myds-card__body">
                <h3 id="filters-heading" class="myds-heading-xs font-heading font-medium mb-3">Penapis dan Carian</h3>
                    <h3 id="filters-heading" class="myds-heading-xs font-heading font-medium mb-3">{{ __('ui.vehicles.filters.heading') }}</h3>
                <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile gap-3">
                    <div class="mobile:col-span-4 tablet:col-span-3 desktop:col-span-3">
                        <label for="search" class="myds-label d-block mb-2">Cari Kenderaan</label>
                        <input id="search" name="search" class="myds-input" value="{{ request('search','') }}" placeholder="Nama atau keterangan" aria-describedby="search-help" />
                        <div id="search-help" class="myds-body-xs myds-text--muted mt-1">Cari mengikut nama atau keterangan.</div>
                            <label for="search" class="myds-label">{{ __('ui.vehicles.filters.search_label') }}</label>
                            <input id="search" name="search" class="myds-input" value="{{ request('search','') }}" placeholder="{{ __('ui.vehicles.filters.search_placeholder') }}" aria-describedby="search-help" />
                            <div id="search-help" class="myds-body-xs myds-text--muted mt-1">{{ __('ui.vehicles.filters.search_help') }}</div>
                    </div>

                    <div class="mobile:col-span-4 tablet:col-span-2 desktop:col-span-3">
                        <label for="owner_id" class="myds-label d-block mb-2">Pemilik</label>
                            <label for="owner_id" class="myds-label">{{ __('ui.vehicles.filters.owner_label') }}</label>
                        <select id="owner_id" name="owner_id" class="myds-input" aria-describedby="owner-help">
                            <option value="">(Semua Pemilik)</option>
                                <option value="">{{ __('ui.vehicles.filters.owner_all') }}</option>
                            @isset($users)
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ (string) request('owner_id') === (string) $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                        <div id="owner-help" class="myds-body-xs myds-text--muted mt-1">Tapis mengikut pemilik kenderaan.</div>
                            <div id="owner-help" class="myds-body-xs myds-text--muted mt-1">{{ __('ui.vehicles.filters.owner_help') }}</div>
                    </div>

                    <div class="mobile:col-span-4 tablet:col-span-2 desktop:col-span-2">
                        <label for="per_page" class="myds-label d-block mb-2">Item per Halaman</label>
                            <label for="per_page" class="myds-label">{{ __('ui.vehicles.filters.per_page_label') }}</label>
                        <select id="per_page" name="per_page" class="myds-input" aria-describedby="per-page-help">
                            @foreach([5,10,15,25,50,100] as $n)
                                <option value="{{ $n }}" {{ (int) request('per_page', 10) === $n ? 'selected' : '' }}>{{ $n }}</option>
                            @endforeach
                        </select>
                        <div id="per-page-help" class="myds-body-xs myds-text--muted mt-1">Bilangan item dipaparkan setiap halaman.</div>
                            <div id="per-page-help" class="myds-body-xs myds-text--muted mt-1">{{ __('ui.vehicles.filters.per_page_help') }}</div>
                    </div>

                    <div class="mobile:col-span-4 tablet:col-span-1 desktop:col-span-4 d-flex align-items-end">
                        <button type="submit" class="myds-btn myds-btn--primary w-100" aria-label="Tapis carian">
                            <i class="bi bi-search me-2" aria-hidden="true"></i>Tapis
                        </button>
                                        <button type="submit" class="myds-btn myds-btn--primary w-100" aria-label="{{ __('ui.vehicles.filters.submit') }}">
                                            <i class="bi bi-search me-2" aria-hidden="true"></i> {{ __('ui.vehicles.filters.submit') }}
                                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Ensure page script is loaded --}}
        @push('scripts')
            @vite('resources/js/pages/vehicles-index.js')
        @endpush

        {{-- Table --}}
        <div class="bg-surface border rounded overflow-hidden">
            <div class="myds-table-responsive">
                <table class="myds-table" aria-describedby="vehicles-count">
                    <caption class="sr-only">Senarai kenderaan; gunakan tindakan untuk melihat atau mengurus.</caption>
                      <caption class="sr-only">{{ __('ui.vehicles.table.caption') }}</caption>
                    <thead>
                        <tr>
                            <th scope="col" class="myds-body-sm font-medium">ID</th>
                            <th scope="col" class="myds-body-sm font-medium">Nama</th>
                            <th scope="col" class="myds-body-sm font-medium">Kuantiti</th>
                            <th scope="col" class="myds-body-sm font-medium">Harga (RM)</th>
                            <th scope="col" class="myds-body-sm font-medium">Keterangan</th>
                            <th scope="col" class="myds-body-sm font-medium">Pemilik</th>
                            <th scope="col" class="myds-body-sm font-medium">Dicipta</th>
                            <th scope="col" class="myds-body-sm font-medium">Tindakan</th>
                              <th scope="col">{{ __('ui.vehicles.table.id') }}</th>
                              <th scope="col">{{ __('ui.vehicles.table.name') }}</th>
                              <th scope="col">{{ __('ui.vehicles.table.qty') }}</th>
                              <th scope="col">{{ __('ui.vehicles.table.price') }}</th>
                              <th scope="col">{{ __('ui.vehicles.table.description') }}</th>
                              <th scope="col">{{ __('ui.vehicles.table.owner') }}</th>
                              <th scope="col">{{ __('ui.vehicles.table.created') }}</th>
                              <th scope="col">{{ __('ui.vehicles.table.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vehicles as $vehicle)
                            <tr>
                                <td class="myds-body-sm myds-text--muted">{{ $vehicle->id }}</td>
                                    <td class="myds-body-sm myds-text--muted">{{ $vehicle->id }}</td>
                                <td class="myds-body-sm font-medium">{{ $vehicle->name }}</td>
                                    <td class="myds-body-sm font-medium">{{ e($vehicle->name) }}</td>
                                <td class="myds-body-sm">{{ $vehicle->qty }}</td>
                                <td class="myds-body-sm">{{ $vehicle->price !== null ? number_format($vehicle->price, 2) : '—' }}</td>
                                <td class="myds-body-sm myds-text--muted">{{ \Illuminate\Support\Str::limit($vehicle->description, 60) }}</td>
                                    <td class="myds-body-sm myds-text--muted">{{ \Illuminate\Support\Str::limit($vehicle->description, 60) }}</td>
                                <td class="myds-body-sm">{{ $vehicle->owner?->name ?? '—' }}</td>
                                <td class="myds-body-sm myds-text--muted">
                                    <time datetime="{{ isset($vehicle->created_at) ? \Illuminate\Support\Carbon::parse($vehicle->created_at)->toIso8601String() : now()->toIso8601String() }}">
                                        {{ isset($vehicle->created_at) ? \Illuminate\Support\Carbon::parse($vehicle->created_at)->format('d/m/Y') : '—' }}
                                    </time>
                                </td>
                                <td class="text-nowrap">
                                    <td class="text-nowrap">
                                    <x-action-buttons
                                        :model="$vehicle"
                                        :showRoute="route('vehicles.show', $vehicle->id)"
                                        :editRoute="route('vehicles.edit', $vehicle->id)"
                                        :destroyRoute="route('vehicles.destroy', $vehicle->id)"
                                        :label="$vehicle->name"
                                        :id="$vehicle->id"
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-6">
                                    <div role="status" class="p-4">
                                        <i class="bi bi-inboxes fs-1 mx-auto d-block mb-3 myds-text--muted" aria-hidden="true"></i>
                                        <h3 class="myds-heading-xs font-heading font-medium mb-2">Tiada Kenderaan Dijumpai</h3>
                                        <p class="myds-body-sm myds-text--muted mb-3">Belum ada kenderaan didaftarkan atau tiada keputusan yang sepadan.</p>
                                        @can('create', App\Models\Vehicle::class)
                                            <a href="{{ route('vehicles.create') }}" class="myds-btn myds-btn--primary" aria-label="Cipta Kenderaan">
                                                <i class="bi bi-plus-lg me-2" aria-hidden="true"></i>Cipta Kenderaan Pertama
                                            </a>
                                        @endcan
                                    </div>
                                        <div role="status" class="p-4">
                                            <i class="bi bi-inboxes fs-1 mb-3 myds-text--muted" aria-hidden="true"></i>
                                            <h3 class="myds-heading-xs font-heading font-medium mb-2">{{ __('ui.vehicles.empty.heading') }}</h3>
                                            <p class="myds-body-sm myds-text--muted mb-3">{{ __('ui.vehicles.empty.description') }}</p>
                                            @can('create', App\Models\Vehicle::class)
                                                <a href="{{ route('vehicles.create') }}" class="myds-btn myds-btn--primary" aria-label="{{ __('ui.vehicles.create_first') }}">
                                                    <i class="bi bi-plus-lg me-2" aria-hidden="true"></i> {{ __('ui.vehicles.create_first') }}
                                                </a>
                                            @endcan
                                        </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if (method_exists($vehicles, 'links'))
            <nav aria-label="Navigasi halaman kenderaan" class="mt-4">
                {{ $vehicles->withQueryString()->links() }}
            </nav>
        @endif
    </section>
</main>
@endsection
