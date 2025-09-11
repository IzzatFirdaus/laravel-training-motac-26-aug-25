@extends('layouts.app')

@section('title', 'Inventori Dipadam — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="page-heading">
    <div class="mx-auto content-maxwidth-xl">
        <header id="page-heading" class="mb-4 d-flex flex-column flex-md-row align-items-start justify-content-between" role="banner">
            <div>
                <h1 class="myds-heading-md font-heading mb-1">Inventori Dipadam</h1>
                <p class="myds-body-sm myds-text--muted mb-0">
                    Senarai item inventori yang telah dipadamkan. Anda boleh memulihkan atau memadam secara kekal mengikut kebenaran.
                </p>
            </div>

            <div class="mt-3 mt-md-0">
                <a data-test="header-back-inventories" href="{{ route('inventories.index') }}"
                   class="myds-btn myds-btn--secondary myds-tap-target"
                   data-action="navigate" data-destination="inventory-list"
                   aria-label="Kembali ke senarai inventori utama">
                    <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                    Kembali
                </a>
            </div>
        </header>

        {{-- Flash messages --}}
        @if (session('status'))
            <div class="myds-alert myds-alert--success mb-3" role="status" aria-live="polite">{{ session('status') }}</div>
        @endif

        @if (session('toast'))
            <div class="myds-alert myds-alert--info mb-3" role="status" aria-live="polite">{{ session('toast') }}</div>
        @endif

        @if ($deletedInventories->count() > 0)
            {{-- Filters / Search --}}
            <section aria-labelledby="filters-heading" class="myds-card mb-3" role="region">
                <div class="myds-card__body">
                    <h2 id="filters-heading" class="sr-only">Carian dan Penapis</h2>

                    <form method="GET" action="{{ route('inventories.deleted.index') }}" class="d-flex flex-column flex-md-row gap-3 align-items-end" role="search" aria-label="Carian inventori dipadam">
                        <div class="flex-grow-1">
                            <label for="search" class="myds-label myds-body-sm">Cari</label>
                            <input id="search" name="search" type="search" value="{{ request('search') }}" class="myds-input" placeholder="{{ __('ui.search_by_name_or_code') }}" aria-label="Carian inventori" />
                        </div>

                        <div class="filter-owner-column">
                            <label for="owner_id" class="myds-label myds-body-sm">Pemilik</label>
                            <select id="owner_id" name="owner_id" class="myds-input" aria-label="Penapis pemilik">
                                <option value="">{{ __('ui.all') }}</option>
                                @isset($users)
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ (string) request('owner_id') === (string) $u->id ? 'selected' : '' }}>
                                            {{ $u->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit"
                                    class="myds-btn myds-btn--primary myds-tap-target"
                                    data-action="search" data-search-type="deleted-inventory"
                                    aria-label="Tapis carian inventori dipadam">
                                <i class="bi bi-search me-1" aria-hidden="true"></i>
                                Tapis
                            </button>
                            @if(request('search') || request('owner_id'))
                                <a href="{{ route('inventories.deleted.index') }}"
                                   class="myds-btn myds-btn--tertiary myds-tap-target"
                                   data-action="clear-filters" data-filter-type="deleted-inventory"
                                   aria-label="Kosongkan penapis carian">
                                    <i class="bi bi-x me-1" aria-hidden="true"></i>
                                    Kosongkan
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </section>

            {{-- Results count --}}
            <div id="inventories-count" class="myds-body-sm myds-text--muted mb-3" role="status" aria-live="polite">
                Memaparkan {{ $deletedInventories->count() }} daripada {{ method_exists($deletedInventories, 'total') ? $deletedInventories->total() : $deletedInventories->count() }} inventori dipadam.
            </div>

            {{-- Table --}}
            <section aria-labelledby="table-heading" class="bg-surface border rounded p-3" role="region">
                <h2 id="table-heading" class="sr-only">Jadual inventori dipadam</h2>

                <div class="myds-table-responsive" role="table" aria-describedby="inventories-count">
                    <table class="myds-table">
                        <caption class="sr-only">Senarai inventori yang dipadam; tindakan tersedia: pulihkan, padam kekal.</caption>
                        <thead>
                            <tr>
                                <th scope="col" class="myds-body-sm myds-text--muted">ID</th>
                                <th scope="col" class="myds-body-sm myds-text--muted">Nama</th>
                                <th scope="col" class="myds-body-sm myds-text--muted">Kuantiti</th>
                                <th scope="col" class="myds-body-sm myds-text--muted">Harga (RM)</th>
                                <th scope="col" class="myds-body-sm myds-text--muted">Pemilik</th>
                                <th scope="col" class="myds-body-sm myds-text--muted">Dipadam Pada</th>
                                <th scope="col" class="myds-body-sm myds-text--muted">Tindakan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($deletedInventories as $inventory)
                                <tr>
                                    <td class="myds-body-sm myds-text--muted">{{ $inventory->id }}</td>
                                    <td class="myds-body-md">{{ $inventory->name ? e($inventory->name) : '—' }}</td>
                                    <td class="myds-body-sm">{{ isset($inventory->qty) ? e($inventory->qty) : '—' }}</td>
                                    <td class="myds-body-sm">
                                        @if(isset($inventory->price) && is_numeric($inventory->price))
                                            {{ 'RM ' . number_format($inventory->price, 2) }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="myds-body-sm myds-text--muted">{{ optional($inventory->user)->name ? e($inventory->user->name) : 'Tidak diketahui' }}</td>
                                    <td class="myds-body-sm myds-text--muted">
                                        @if(isset($inventory->deleted_at))
                                            <time datetime="{{ \Illuminate\Support\Carbon::parse($inventory->deleted_at)->toIso8601String() }}">
                                                {{ \Illuminate\Support\Carbon::parse($inventory->deleted_at)->format('d/m/Y H:i') }}
                                            </time>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            @can('restore', $inventory)
                                                <form method="POST" action="{{ route('inventories.restore', $inventory) }}" class="d-inline"
                                                      data-myds-form data-model="Inventori {{ $inventory->id }}"
                                                      data-action-type="restore" data-inventory-id="{{ $inventory->id }}"
                                                      aria-label="Pulihkan Inventori {{ $inventory->id }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="myds-btn myds-btn--primary myds-btn--sm myds-tap-target"
                                                            data-action="restore" data-item-id="{{ $inventory->id }}"
                                                            aria-label="Pulihkan inventori {{ $inventory->name ?? $inventory->id }}">
                                                        <i class="bi bi-arrow-clockwise me-1" aria-hidden="true"></i>
                                                        Pulihkan
                                                    </button>
                                                </form>
                                            @endcan

                                            @can('forceDelete', $inventory)
                                                <form method="POST" action="{{ route('inventories.force-delete', $inventory) }}" class="d-inline"
                                                      data-myds-form data-model="Inventori {{ $inventory->id }}"
                                                      data-action-type="force-delete" data-inventory-id="{{ $inventory->id }}"
                                                      aria-label="Padam kekal Inventori {{ $inventory->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="myds-btn myds-btn--danger myds-btn--sm myds-tap-target"
                                                            data-action="force-delete" data-item-id="{{ $inventory->id }}"
                                                            data-confirm="true"
                                                            aria-label="Padam kekal inventori {{ $inventory->name ?? $inventory->id }}">
                                                        <i class="bi bi-trash me-1" aria-hidden="true"></i>
                                                        Padam Kekal
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(method_exists($deletedInventories, 'links'))
                    <nav class="mt-4 d-flex justify-content-center" role="navigation" aria-label="Paginasi inventori dipadam">
                        {{ $deletedInventories->withQueryString()->links() }}
                    </nav>
                @endif
            </section>
        @else
            <div class="myds-card">
                <div class="myds-card__body">
                    <div class="myds-alert myds-alert--info" role="status" aria-live="polite">
                        Tiada inventori yang dipadam dijumpai.
                    </div>
                    <div class="mt-3">
                            <a id="back-to-inventories" href="{{ route('inventories.index') }}" class="myds-btn myds-btn--primary">Kembali ke Inventori</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</main>
@endsection
