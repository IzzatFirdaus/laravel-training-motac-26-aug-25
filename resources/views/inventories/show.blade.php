@extends('layouts.app')

@section('title', 'Butiran Inventori — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="inventory-heading">
  <nav aria-label="Breadcrumb" class="mb-4">
    <ol class="d-flex list-unstyled myds-text--muted myds-body-sm m-0 p-0">
      <li><a href="{{ route('home') }}" class="text-primary text-decoration-none">Laman Utama</a></li>
      <li class="mx-2" aria-hidden="true">/</li>
      <li><a href="{{ route('inventories.index') }}" class="text-primary text-decoration-none">Inventori</a></li>
      <li class="mx-2" aria-hidden="true">/</li>
      <li aria-current="page" class="myds-text--muted">{{ e($inventory->reference ?? ('#'.$inventory->id)) }}</li>
    </ol>
  </nav>

  <section class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile gap-4">
    <div class="desktop:col-span-8 tablet:col-span-8 mobile:col-span-4">
      <header class="mb-3">
        <h1 id="inventory-heading" class="myds-heading-md font-heading font-semibold">Inventori {{ e($inventory->reference ?? '#'.$inventory->id) }}</h1>
        <p class="myds-body-sm myds-text--muted">Butiran penuh item inventori.</p>
      </header>

      <div class="myds-card">
        <div class="myds-card__body">
          @if(session('status'))
            <div class="myds-alert myds-alert--success mb-3" role="status" aria-live="polite">{{ session('status') }}</div>
          @endif

          <dl class="row g-3">
            <dt class="col-4 myds-body-sm myds-text--muted">Nama</dt>
            <dd class="col-8 myds-body-md">{{ e($inventory->name) }}</dd>

            <dt class="col-4 myds-body-sm myds-text--muted">Pemilik</dt>
            <dd class="col-8 myds-body-md">{{ e($inventory->user?->name ?? '—') }}</dd>

            <dt class="col-4 myds-body-sm myds-text--muted">Kuantiti</dt>
            <dd class="col-8 myds-body-md">{{ e($inventory->qty ?? 0) }}</dd>

            <dt class="col-4 myds-body-sm myds-text--muted">Harga</dt>
            <dd class="col-8 myds-body-md">
              @if(isset($inventory->price)) RM {{ number_format($inventory->price,2) }} @else <span class="myds-text--muted">—</span> @endif
            </dd>

            <dt class="col-12 myds-body-sm myds-text--muted">Keterangan</dt>
            <dd class="col-12 myds-body-md">{!! nl2br(e($inventory->description)) !!}</dd>
          </dl>

          <div class="mt-4 d-flex gap-2">
            <a href="{{ route('inventories.index') }}"
               class="myds-btn myds-btn--tertiary myds-tap-target"
               data-action="navigate" data-destination="inventory-list"
               aria-label="Kembali ke senarai inventori">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                Kembali
            </a>
            @can('update', $inventory)
              <a href="{{ route('inventories.edit', $inventory->id) }}"
                 class="myds-btn myds-btn--primary myds-tap-target"
                 data-action="edit" data-inventory-id="{{ $inventory->id }}"
                 aria-label="Kemaskini inventori {{ $inventory->name }}">
                  <i class="bi bi-pencil me-1" aria-hidden="true"></i>
                  Kemaskini
              </a>
            @endcan
            @can('delete', $inventory)
              <x-destroy :action="route('inventories.destroy', $inventory->id)"
                         :label="$inventory->name ?? 'Inventori'"
                         data-item-id="{{ $inventory->id }}"
                         data-item-type="inventory"/>
            @endcan
          </div>

          @if(!empty($inventory->vehicles) && $inventory->vehicles->count())
            <hr class="my-3">
            <h3 class="myds-heading-sm">Kenderaan Berkaitan</h3>
            <ul class="myds-list myds-list--bare">
              @foreach($inventory->vehicles as $v)
                <li><a href="{{ route('vehicles.show', $v->id) }}" class="text-decoration-none">{{ e($v->name) }}</a> <span class="myds-text--muted">(ID: {{ $v->id }})</span></li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
    </div>

    <aside class="desktop:col-span-4 tablet:col-span-8 mobile:col-span-4">
      <div class="myds-card">
        <div class="myds-card__body">
          <h4 class="myds-heading-xs font-heading mb-2">Maklumat Tambahan</h4>
          <p class="myds-body-sm myds-text--muted mb-1">Tarikh dicipta:
            @if(isset($inventory->created_at))
              <time datetime="{{ \Illuminate\Support\Carbon::parse($inventory->created_at)->toIso8601String() }}">{{ \Illuminate\Support\Carbon::parse($inventory->created_at)->format('Y-m-d H:i') }}</time>
            @else
              —
            @endif
          </p>
          @if(isset($inventory->warehouse?->name))
            <p class="myds-body-sm myds-text--muted mb-0">Gudang: {{ e($inventory->warehouse->name) }}</p>
          @endif
        </div>
      </div>
    </aside>
  </section>
</main>
@endsection
