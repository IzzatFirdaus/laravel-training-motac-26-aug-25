@extends('layouts.app')

@section('title', 'Inventori 14 ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="page-heading">
    {{-- Page Header (MYDS) --}}
    <header class="mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <h1 id="page-heading" class="myds-heading-md font-heading font-semibold mb-2">Inventori</h1>
                <p class="text-muted mb-0">Sistem pengurusan inventori kerajaan. Lihat, urus dan cipta rekod inventori dengan mudah.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @can('create', App\Models\Inventory::class)
                    <a href="{{ route('inventories.create') }}" class="myds-btn myds-btn--primary" aria-label="Cipta inventori baharu">
                        <svg width="16" height="16" class="me-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Cipta Inventori
                    </a>
                @endcan

                @can('viewAny', App\Models\Inventory::class)
                    <a href="{{ route('excel.inventory.form') }}" class="myds-btn myds-btn--secondary" aria-label="Import atau export data inventori">Import/Export</a>
                @endcan

                @auth
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('inventories.deleted.index') }}" class="myds-btn myds-btn--tertiary" aria-label="Lihat inventori yang telah dipadam">Inventori Dipadam</a>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    {{-- Search and Filter --}}
    <section aria-labelledby="filter-heading" class="mb-4">
        <h2 id="filter-heading" class="sr-only">Penapis dan carian inventori</h2>

        @php
            $inventoryCount = method_exists($inventories, 'total') ? $inventories->total() : $inventories->count();
        @endphp

        <div class="bg-surface p-3 rounded border mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="text-muted" id="inventory-count" aria-live="polite">
                    <strong>{{ number_format($inventoryCount) }}</strong> item inventori dijumpai
                </div>
            </div>

            <form method="GET" class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile gap-3" aria-label="Penapis carian inventori" id="inventories-filter-form">
                <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6">
                    <label for="search" class="form-label myds-body-sm font-medium">Cari inventori</label>
                    <input id="search" name="search" class="myds-input" value="{{ request('search','') }}" placeholder="{{ __('placeholders.inventory_search') }}" aria-describedby="search-help" />
                    <div id="search-help" class="myds-body-xs text-muted mt-1">Cari berdasarkan nama atau keterangan item.</div>
                </div>

                <div class="mobile:col-span-2 tablet:col-span-2 desktop:col-span-3">
                    <label for="owner_id" class="form-label myds-body-sm font-medium">Pemilik</label>
                    <select id="owner_id" name="owner_id" class="myds-input">
                        <option value="">Semua pemilik</option>
                        @isset($users)
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ (string) request('owner_id') === (string) $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                <div class="mobile:col-span-2 tablet:col-span-2 desktop:col-span-3">
                    <label for="per_page" class="form-label myds-body-sm font-medium">Item per halaman</label>
                    <select id="per_page" name="per_page" class="myds-input">
                        @foreach([5,10,15,25,50,100] as $n)
                            <option value="{{ $n }}" {{ (int) request('per_page', 5) === $n ? 'selected' : '' }}>{{ $n }} item</option>
                        @endforeach
                    </select>
                </div>

                <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-12 d-flex gap-2">
                    <button type="submit" class="myds-btn myds-btn--primary">
                        <svg width="16" height="16" class="me-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
                            <path d="m21 21-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Cari
                    </button>
                    @if(request()->hasAny(['search', 'owner_id']) && (request('search') || request('owner_id')))
                        <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--secondary">Reset Carian</a>
                    @endif
                </div>
            </form>
        </div>
    </section>

    {{-- Data Table --}}
    <section aria-labelledby="inventories-heading">
        <h2 id="inventories-heading" class="sr-only">Senarai data inventori</h2>

        <div class="bg-surface rounded border">
            <div class="myds-table-responsive" role="region" aria-live="polite" aria-atomic="true">
                <table class="myds-table" aria-describedby="inventory-count" role="table">
                    <caption class="sr-only">Jadual inventori dengan {{ $inventoryCount }} item. Gunakan butang tindakan untuk lihat atau edit item.</caption>
                    <thead>
                        <tr role="row">
                            <th scope="col" class="font-heading">ID</th>
                            <th scope="col" class="font-heading">Nama Item</th>
                            <th scope="col" class="font-heading">Kuantiti</th>
                            <th scope="col" class="font-heading">Pemilik</th>
                            <th scope="col" class="font-heading">Harga (RM)</th>
                            <th scope="col" class="font-heading">Keterangan</th>
                            <th scope="col" class="font-heading">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inventories as $inventory)
                            <tr role="row">
                                <td><span class="font-mono font-medium">#{{ $inventory->id }}</span></td>
                                <td><strong>{{ $inventory->name }}</strong></td>
                                <td><span class="badge bg-primary rounded-pill">{{ $inventory->qty }}</span></td>
                                <td>{{ $inventory->user?->name ?? '—' }}</td>
                                <td>
                                    @if($inventory->price !== null)
                                        RM {{ number_format($inventory->price, 2) }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span title="{{ $inventory->description }}">{{ \Illuminate\Support\Str::limit($inventory->description, 60) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('inventories.show', $inventory->id) }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Lihat detail {{ $inventory->name }}" title="Lihat detail">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/>
                                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                                            </svg>
                                            <span class="sr-only">Lihat</span>
                                        </a>
                                        @can('update', $inventory)
                                            <a href="{{ route('inventories.edit', $inventory->id) }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Edit {{ $inventory->name }}" title="Edit item">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <span class="sr-only">Edit</span>
                                            </a>
                                        @endcan
                                        @can('delete', $inventory)
                                            <form method="POST" action="{{ route('inventories.destroy', $inventory->id) }}" class="d-inline" data-myds-form>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="myds-btn myds-btn--danger myds-btn--sm" aria-label="Padam {{ $inventory->name }}" title="Padam item">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                        <polyline points="3,6 5,6 21,6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="m19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                    <span class="sr-only">Padam</span>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr role="row">
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <svg width="48" height="48" class="mb-2 mx-auto d-block" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM4 9h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <p>Tiada inventori dijumpai.</p>
                                        @can('create', App\Models\Inventory::class)
                                            <a href="{{ route('inventories.create') }}" class="myds-btn myds-btn--primary myds-btn--sm">Cipta Inventori Pertama</a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Pagination --}}
    @if(method_exists($inventories, 'links'))
        <nav aria-label="Navigasi halaman inventori" class="mt-4">
            {{ $inventories->withQueryString()->links() }}
        </nav>
    @endif

    {{-- Page specific JS (Vite) --}}
    @vite('resources/js/pages/inventories-index.js')
</main>
@endsection
