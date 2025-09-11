@php
use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.app')

@section('title', 'Inventori — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="inventories-heading">
  <header class="mb-4 d-flex flex-column flex-md-row align-items-start justify-content-between gap-3" role="banner">
    <div>
      <h1 id="inventories-heading" class="myds-heading-md font-heading">Inventori</h1>
      <p class="myds-body-sm myds-text--muted mb-0">Urus inventori anda — lihat, kemas kini, import dan eksport data.</p>
    </div>

    <div class="d-flex gap-2">
      @if($canCreateInventory)
        <a href="{{ route('inventories.create') }}" class="myds-btn myds-btn--primary myds-tap-target" aria-label="Cipta inventori baru" data-action="create">
          <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Cipta Inventori
        </a>
      @endif

      @if($canViewAnyInventory)
        <a href="{{ route('excel.inventory.form') }}" class="myds-btn myds-btn--secondary myds-tap-target" aria-label="Import atau eksport inventori" data-action="import-export">Import/Export</a>
      @endif

      @auth
        @if(Auth::user()->hasRole('admin'))
          <button data-test="page-inventories-deleted" type="button" data-href="{{ route('inventories.deleted.index') }}" class="myds-btn myds-btn--tertiary myds-tap-target" aria-label="Inventori dipadam" data-action="deleted" onclick="window.location.href=this.dataset.href">Inventori Dipadam</button>
        @endif
      @endauth
    </div>
  </header>

  <section aria-labelledby="filter-heading" class="mb-4">
    <h2 id="filter-heading" class="sr-only">Penapis dan carian</h2>

    @php
      $inventoryCount = method_exists($inventories, 'total') ? $inventories->total() : $inventories->count();
    @endphp

    <div class="myds-card mb-3">
      <div class="myds-card__body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="myds-body-sm myds-text--muted" id="inventory-count" aria-live="polite"><strong>{{ number_format($inventoryCount) }}</strong> item inventori</div>
        </div>

        <form method="GET" id="inventories-filter-form" class="myds-grid gap-3" aria-label="Penapis carian inventori">
          <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6">
            <label for="search" class="myds-label myds-body-sm">Cari inventori</label>
            <input id="search" name="search" class="myds-input" value="{{ request('search','') }}" placeholder="{{ __('placeholders.inventory_search') }}" aria-describedby="search-help">
            <div id="search-help" class="myds-body-xs myds-text--muted mt-1">Cari mengikut nama atau keterangan.</div>
          </div>

          <div class="mobile:col-span-2 tablet:col-span-2 desktop:col-span-3">
            <label for="owner_id" class="myds-label myds-body-sm">Pemilik</label>
            <select id="owner_id" name="owner_id" class="myds-input">
              <option value="">{{ __('ui.all') }}</option>
              @isset($users)
                @foreach($users as $u)
                  <option value="{{ $u->id }}" {{ (string) request('owner_id') === (string) $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
              @endisset
            </select>
          </div>

          <div class="mobile:col-span-2 tablet:col-span-2 desktop:col-span-3">
            <label for="per_page" class="myds-label myds-body-sm">Item per halaman</label>
            <select id="per_page" name="per_page" class="myds-input">
              @foreach([5,10,15,25,50,100] as $n)
                <option value="{{ $n }}" {{ (int) request('per_page', 10) === $n ? 'selected' : '' }}>{{ $n }} item</option>
              @endforeach
            </select>
          </div>

          <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-12 d-flex gap-2">
            <button type="submit" class="myds-btn myds-btn--primary" aria-label="Cari inventori"><i class="bi bi-search me-1" aria-hidden="true"></i>Cari</button>
            @if(request()->hasAny(['search','owner_id']) && (request('search') || request('owner_id')))
              <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--tertiary" aria-label="Reset carian">Reset Carian</a>
            @endif
          </div>
        </form>
      </div>
    </div>
  </section>

  <section aria-labelledby="inventories-table">
    <h2 id="inventories-table" class="sr-only">Jadual Inventori</h2>

    <div class="bg-surface border rounded">
      <div class="myds-table-responsive" role="region" aria-live="polite" aria-atomic="true">
        <table class="myds-table" aria-describedby="inventory-count">
          <caption class="sr-only">Jadual inventori</caption>
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Nama Item</th>
              <th scope="col">Kuantiti</th>
              <th scope="col">Pemilik</th>
              <th scope="col">Harga (RM)</th>
              <th scope="col">Keterangan</th>
              <th scope="col">Tindakan</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($inventories as $inventory)
              <tr>
                <td class="myds-body-sm myds-text--muted">#{{ $inventory->id }}</td>
                <td class="myds-body-md">{{ e($inventory->name) }}</td>
                <td><span class="myds-badge myds-badge--primary rounded-pill">{{ $inventory->qty }}</span></td>
                <td class="myds-body-sm">{{ e($inventory->user?->name ?? '—') }}</td>
                <td class="myds-body-sm">
                  @if($inventory->price !== null) RM {{ number_format($inventory->price,2) }} @else <span class="myds-text--muted">—</span> @endif
                </td>
                <td class="myds-body-sm"><span title="{{ $inventory->description }}">{{ \Illuminate\Support\Str::limit($inventory->description, 60) }}</span></td>
                <td>
                  <div class="d-flex gap-1">
                    <a href="{{ route('inventories.show', $inventory->id) }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Lihat {{ $inventory->name }}"><i class="bi bi-eye" aria-hidden="true"></i></a>
                    @can('update', $inventory)
                      <a href="{{ route('inventories.edit', $inventory->id) }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Ubah {{ $inventory->name }}"><i class="bi bi-pencil-square" aria-hidden="true"></i></a>
                    @endcan
                    @can('delete', $inventory)
                      <form method="POST" action="{{ route('inventories.destroy', $inventory->id) }}" class="d-inline" data-myds-form aria-label="Padam {{ $inventory->name }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="myds-btn myds-btn--danger myds-btn--sm" aria-label="Padam {{ $inventory->name }}"><i class="bi bi-trash3" aria-hidden="true"></i></button>
                      </form>
                    @endcan
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-6">
                  <div class="myds-body-sm myds-text--muted">
                    <i class="bi bi-inboxes fs-1 mb-2" aria-hidden="true"></i>
                    <p class="mb-2">Tiada inventori dijumpai.</p>
                    @if($canCreateInventory)
                      <a href="{{ route('inventories.create') }}" class="myds-btn myds-btn--primary myds-btn--sm">Cipta Inventori Pertama</a>
                    @endif
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if(method_exists($inventories, 'links'))
        <nav class="mt-3 d-flex justify-content-center" aria-label="Paginasi inventori">
          {{ $inventories->withQueryString()->links() }}
        </nav>
      @endif
    </div>
  </section>

  @push('scripts')
  @vite('resources/js/pages/inventories-index.js')
  @endpush
</main>
@endsection
