@extends('layouts.app')

@section('title', 'Kenderaan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="vehicles-heading">
    {{-- MYDS Breadcrumb Navigation --}}
    <nav aria-label="Breadcrumb" class="mb-4">
        <ol class="d-flex list-unstyled myds-text--muted myds-body-sm m-0 p-0">
            <li><a href="{{ route('home') }}" class="text-primary text-decoration-none hover:text-decoration-underline">Papan Pemuka</a></li>
            <li class="mx-2" aria-hidden="true">/</li>
            <li aria-current="page" class="myds-text--muted">Kenderaan</li>
        </ol>
    </nav>

    <header class="mb-6 d-flex flex-column flex-md-row align-items-md-start justify-content-md-between gap-4">
        <div>
            <h1 id="vehicles-heading" class="myds-heading-lg font-heading font-semibold mb-2">Kenderaan</h1>
            <p class="myds-body-md myds-text--muted mb-0" data-myds-desc="Rekod kenderaan yang diselenggara oleh sistem inventori kerajaan.">Rekod kenderaan yang diselenggara oleh sistem inventori kerajaan.</p>
        </div>

        @if($canCreateVehicle)
            <div class="d-flex gap-2">
                <a href="{{ route('vehicles.create') }}" class="myds-btn myds-btn--primary myds-tap-target" aria-label="Cipta kenderaan baharu" data-myds-create-button>
                    <i class="bi bi-plus-lg me-2" aria-hidden="true"></i>
                    Cipta Kenderaan
                </a>
            </div>
        @endif
    </header>

    <section aria-labelledby="vehicles-stats-heading" class="mb-4">
        <h2 id="vehicles-stats-heading" class="sr-only">Statistik Kenderaan</h2>

        @php
            $vehiclesCount = method_exists($vehicles, 'total') ? $vehicles->total() : (is_countable($vehicles) ? count($vehicles) : 0);
        @endphp

        <div id="vehicles-count" class="myds-body-md mb-4" role="status" aria-live="polite" data-count="{{ $vehiclesCount }}">
            <strong>{{ number_format($vehiclesCount) }}</strong> kenderaan dalam sistem
        </div>
    </section>

    <section aria-labelledby="vehicles-filter-heading" class="mb-4">
        <h2 id="vehicles-filter-heading" class="sr-only">Penapis dan Carian</h2>

        {{-- Filters --}}
        <form method="GET" class="myds-card" aria-label="Borang penapis kenderaan" novalidate data-myds-form>
            <div class="myds-card__body">
                <h3 class="myds-heading-sm font-heading font-medium mb-3">Penapis dan Carian</h3>

                <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile gap-3">
                    <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-4">
                        <label for="search" class="myds-label">Cari Kenderaan</label>
                        <input id="search"
                               name="search"
                               class="myds-input"
                               value="{{ request('search','') }}"
                               placeholder="Nama atau keterangan"
                               aria-describedby="search-help"
                               data-search-placeholder="Nama atau keterangan" />
                        <div id="search-help" class="myds-body-xs myds-text--muted mt-1">Cari mengikut nama atau keterangan kenderaan.</div>
                    </div>

                    <div class="mobile:col-span-2 tablet:col-span-2 desktop:col-span-3">
                        <label for="owner_id" class="myds-label">Pemilik</label>
                        <select id="owner_id" name="owner_id" class="myds-input" aria-describedby="owner-help">
                            <option value="" data-all-owners>Semua Pemilik</option>
                            @isset($users)
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ (string) request('owner_id') === (string) $u->id ? 'selected' : '' }}>{{ e($u->name) }}</option>
                                @endforeach
                            @endisset
                        </select>
                        <div id="owner-help" class="myds-body-xs myds-text--muted mt-1">Tapis mengikut pemilik kenderaan.</div>
                    </div>

                    <div class="mobile:col-span-2 tablet:col-span-2 desktop:col-span-3">
                        <label for="per_page" class="myds-label">Item per Halaman</label>
                        <select id="per_page" name="per_page" class="myds-input" aria-describedby="per-page-help">
                            @foreach([5,10,15,25,50,100] as $n)
                                <option value="{{ $n }}" {{ (int) request('per_page', 10) === $n ? 'selected' : '' }}>{{ $n }} item</option>
                            @endforeach
                        </select>
                        <div id="per-page-help" class="myds-body-xs myds-text--muted mt-1">Bilangan kenderaan dipaparkan setiap halaman.</div>
                    </div>

                    <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-2 d-flex align-items-end gap-2">
                        <button type="submit" class="myds-btn myds-btn--primary myds-tap-target flex-grow-1" aria-label="Tapis carian kenderaan" data-search-button>
                            <i class="bi bi-search me-2" aria-hidden="true"></i>Tapis
                        </button>
                        @if(request()->hasAny(['search','owner_id']) && (request('search') || request('owner_id')))
                            <a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--tertiary myds-tap-target" aria-label="Reset carian" data-reset-filters>Reset</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </section>

    <section aria-labelledby="vehicles-table-heading">
        <h2 id="vehicles-table-heading" class="sr-only">Jadual Kenderaan</h2>

        {{-- Data Table with MYDS styling --}}
        <div class="bg-surface border rounded overflow-hidden">
            <div class="myds-table-responsive" role="region" aria-live="polite" aria-atomic="true">
                <table class="myds-table" aria-describedby="vehicles-count">
                    <caption class="sr-only">Senarai kenderaan dalam sistem; gunakan tindakan untuk melihat atau mengurus.</caption>

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
                            <tr data-vehicle-id="{{ $vehicle->id }}">
                                <td class="myds-body-sm myds-text--muted">#{{ $vehicle->id }}</td>
                                <td class="myds-body-sm font-medium">{{ e($vehicle->name) }}</td>
                                <td class="myds-body-sm">
                                    <span class="myds-badge myds-badge--primary rounded-pill">{{ number_format($vehicle->qty) }}</span>
                                </td>
                                <td class="myds-body-sm">
                                    @if($vehicle->price !== null)
                                        RM {{ number_format($vehicle->price, 2) }}
                                    @else
                                        <span class="myds-text--muted">—</span>
                                    @endif
                                </td>
                                <td class="myds-body-sm myds-text--muted">
                                    <span title="{{ e($vehicle->description) }}">{{ \Illuminate\Support\Str::limit($vehicle->description, 60) }}</span>
                                </td>
                                <td class="myds-body-sm">{{ e($vehicle->owner?->name ?? '—') }}</td>
                                <td class="myds-body-sm myds-text--muted">
                                    <time datetime="{{ isset($vehicle->created_at) ? \Illuminate\Support\Carbon::parse($vehicle->created_at)->toIso8601String() : now()->toIso8601String() }}">
                                        {{ isset($vehicle->created_at) ? \Illuminate\Support\Carbon::parse($vehicle->created_at)->format('d/m/Y') : '—' }}
                                    </time>
                                </td>
                                <td class="text-nowrap">
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('vehicles.show', $vehicle->id) }}"
                                           class="myds-btn myds-btn--secondary myds-btn--sm myds-tap-target"
                                           aria-label="Lihat kenderaan {{ e($vehicle->name) }}"
                                           data-vehicle-view>
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </a>

                                        @can('update', $vehicle)
                                            <a href="{{ route('vehicles.edit', $vehicle->id) }}"
                                               class="myds-btn myds-btn--secondary myds-btn--sm myds-tap-target"
                                               aria-label="Ubah kenderaan {{ e($vehicle->name) }}"
                                               data-vehicle-edit>
                                                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            </a>
                                        @endcan

                                        @can('delete', $vehicle)
                                            <form method="POST"
                                                  action="{{ route('vehicles.destroy', $vehicle->id) }}"
                                                  class="d-inline"
                                                  data-myds-form
                                                  aria-label="Padam kenderaan {{ e($vehicle->name) }}"
                                                  data-confirm-message="Anda pasti mahu memadam kenderaan '{{ e($vehicle->name) }}'?"
                                                  data-confirm-title="Padam Kenderaan">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="myds-btn myds-btn--danger myds-btn--sm myds-tap-target"
                                                        aria-label="Padam kenderaan {{ e($vehicle->name) }}"
                                                        data-vehicle-delete>
                                                    <i class="bi bi-trash3" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-6">
                                    <div role="status" class="p-4" data-empty-state>
                                        <i class="bi bi-truck fs-1 mb-3 myds-text--muted" aria-hidden="true"></i>
                                        <h3 class="myds-heading-sm font-heading font-medium mb-2" data-empty-heading="Tiada Kenderaan Dijumpai">Tiada Kenderaan Dijumpai</h3>
                                        <p class="myds-body-sm myds-text--muted mb-3" data-empty-description="Belum ada kenderaan didaftarkan atau tiada keputusan yang sepadan.">
                                            Belum ada kenderaan didaftarkan atau tiada keputusan yang sepadan.
                                        </p>
                                        @if($canCreateVehicle)
                                            <a href="{{ route('vehicles.create') }}"
                                               class="myds-btn myds-btn--primary myds-tap-target"
                                               aria-label="Cipta kenderaan pertama"
                                               data-create-first>
                                                <i class="bi bi-plus-lg me-2" aria-hidden="true"></i>Cipta Kenderaan Pertama
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if (method_exists($vehicles, 'links'))
            <nav aria-label="Navigasi halaman kenderaan" class="mt-4 d-flex justify-content-center">
                {{ $vehicles->withQueryString()->links() }}
            </nav>
        @endif
    </section>
</main>

@push('scripts')
    @vite('resources/js/pages/vehicles-index.js')
@endpush
@endsection
