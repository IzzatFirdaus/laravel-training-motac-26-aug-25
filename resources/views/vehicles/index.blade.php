@extends('layouts.app')

@section('title', 'Kenderaan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main">
    {{-- MYDS Page Header with MyGOVEA principles --}}
    <header class="mb-6">
        <div class="d-flex flex-column flex-md-row align-items-md-start justify-content-md-between gap-4">
            <div>
                <h1 class="myds-heading-md font-heading font-semibold mb-2">Kenderaan</h1>
                <p class="myds-body-md text-muted mb-0">
                    Rekod kenderaan yang diselenggara oleh sistem inventori kerajaan.
                </p>
                <p class="myds-body-sm text-muted mt-1" lang="en">
                    <em>Vehicle records maintained by the government inventory system.</em>
                </p>
            </div>
            @can('create', App\Models\Vehicle::class)
                <div>
                    <a href="{{ route('vehicles.create') }}" class="myds-btn myds-btn--primary">
                        <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <line x1="12" y1="5" x2="12" y2="19" stroke="currentColor" stroke-width="2"/>
                            <line x1="5" y1="12" x2="19" y2="12" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        Cipta Kenderaan
                    </a>
                </div>
            @endcan
        </div>
    </header>

    {{-- MYDS Data Table Section --}}
    <section aria-labelledby="vehicles-table-heading">
        <h2 id="vehicles-table-heading" class="sr-only">Jadual Kenderaan</h2>

        {{-- Results Count --}}
        @php
            $vehiclesCount = method_exists($vehicles, 'total') ? $vehicles->total() : $vehicles->count();
        @endphp
        <div class="myds-body-sm text-muted mb-4" id="vehicles-count" role="status">
            Memaparkan {{ $vehiclesCount }} kenderaan{{ $vehiclesCount !== 1 ? '' : '' }}
        </div>

        {{-- MYDS Filters Form --}}
        <form method="GET" class="bg-surface border rounded-m p-4 mb-4" aria-labelledby="filters-heading">
            <h3 id="filters-heading" class="myds-heading-xs font-heading font-medium mb-3">Penapis dan Carian</h3>

            <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
                {{-- Search Field --}}
                <div class="mobile:col-span-4 tablet:col-span-3 desktop:col-span-3">
                    <label for="search" class="form-label myds-body-sm font-medium d-block mb-2">Cari Kenderaan</label>
                    <input id="search"
                           name="search"
                           class="myds-input"
                           value="{{ request('search','') }}"
                           placeholder="Nama atau keterangan"
                           aria-describedby="search-help" />
                    <div id="search-help" class="myds-body-xs text-muted mt-1">Cari mengikut nama atau keterangan kenderaan</div>
                </div>

                {{-- Owner Filter --}}
                <div class="mobile:col-span-4 tablet:col-span-2 desktop:col-span-3">
                    <label for="owner_id" class="form-label myds-body-sm font-medium d-block mb-2">Pemilik</label>
                    <select id="owner_id" name="owner_id" class="myds-input" aria-describedby="owner-help">
                        <option value="">(Semua Pemilik)</option>
                        @isset($users)
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ (string) request('owner_id') === (string) $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                    <div id="owner-help" class="myds-body-xs text-muted mt-1">Tapis mengikut pemilik kenderaan</div>
                </div>

                {{-- Per Page Filter --}}
                <div class="mobile:col-span-4 tablet:col-span-2 desktop:col-span-2">
                    <label for="per_page" class="form-label myds-body-sm font-medium d-block mb-2">Item per Halaman</label>
                    <select id="per_page" name="per_page" class="myds-input" onchange="this.form.submit()" aria-describedby="per-page-help">
                        @foreach([5,10,15,25,50,100] as $n)
                            <option value="{{ $n }}" {{ (int) request('per_page', 10) === $n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                    <div id="per-page-help" class="myds-body-xs text-muted mt-1">Bilangan item yang dipaparkan</div>
                </div>

                {{-- Submit Button --}}
                <div class="mobile:col-span-4 tablet:col-span-1 desktop:col-span-4 d-flex align-items-end">
                    <button type="submit" class="myds-btn myds-btn--primary w-100">
                        <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
                            <path d="m21 21-4.35-4.35" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        Tapis
                    </button>
                </div>
            </div>
        </form>

        {{-- MYDS Data Table --}}
        <div class="bg-surface border rounded-m overflow-hidden">
            <div class="table-responsive">
                <table class="myds-table" aria-describedby="vehicles-count">
                    <caption class="sr-only">Senarai kenderaan dengan tindakan pantas untuk lihat butiran.</caption>
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vehicles as $vehicle)
                            <tr>
                                <td class="myds-body-sm text-muted">{{ $vehicle->id }}</td>
                                <td class="myds-body-sm font-medium">{{ $vehicle->name }}</td>
                                <td class="myds-body-sm">{{ $vehicle->qty }}</td>
                                <td class="myds-body-sm">{{ $vehicle->price !== null ? number_format($vehicle->price, 2) : '—' }}</td>
                                <td class="myds-body-sm text-muted">
                                    {{ \Illuminate\Support\Str::limit($vehicle->description, 60) }}
                                </td>
                                <td class="myds-body-sm">{{ $vehicle->owner?->name ?? '—' }}</td>
                                <td class="myds-body-sm text-muted">
                                    {{ isset($vehicle->created_at) ? \Illuminate\Support\Carbon::parse($vehicle->created_at)->format('d/m/Y') : '—' }}
                                </td>
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
                                        <svg width="48" height="48" class="mx-auto mb-3 text-muted" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path d="M3 3h18v18H3V3zm16 16V5H5v14h14z" stroke="currentColor" stroke-width="2"/>
                                            <path d="M8 8h8v8H8V8z" stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                        <h3 class="myds-heading-xs font-heading font-medium mb-2">Tiada Kenderaan Dijumpai</h3>
                                        <p class="myds-body-sm text-muted mb-3">
                                            Belum ada kenderaan yang didaftarkan dalam sistem atau tiada hasil yang sepadan dengan carian anda.
                                        </p>
                                        @can('create', App\Models\Vehicle::class)
                                            <a href="{{ route('vehicles.create') }}" class="myds-btn myds-btn--primary">
                                                <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                    <line x1="12" y1="5" x2="12" y2="19" stroke="currentColor" stroke-width="2"/>
                                                    <line x1="5" y1="12" x2="19" y2="12" stroke="currentColor" stroke-width="2"/>
                                                </svg>
                                                Cipta Kenderaan Pertama
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

        {{-- MYDS Pagination --}}
        @if (method_exists($vehicles, 'links'))
            <nav aria-label="Navigasi halaman kenderaan" class="mt-4">
                {{ $vehicles->withQueryString()->links() }}
            </nav>
        @endif
    </section>
</main>
@endsection
