@extends('layouts.app')

@section('title', 'Butiran Inventori — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main">

    <nav aria-label="Breadcrumb" class="mb-4">
        <ol class="d-flex list-unstyled text-muted myds-body-sm">
            <li><a href="{{ route('home') }}" class="text-decoration-none text-primary">Laman Utama</a></li>
            <li class="mx-2" aria-hidden="true">/</li>
            <li><a href="{{ route('inventories.index') }}" class="text-decoration-none text-primary">Inventori</a></li>
            <li class="mx-2" aria-hidden="true">/</li>
            <li aria-current="page" class="text-muted">{{ $inventory->reference ?? ('#'.$inventory->id) }}</li>
        </ol>
    </nav>

    <section class="myds-grid">
        <div class="desktop:col-span-8 tablet:col-span-8 mobile:col-span-4">
            <header class="mb-3">
                <h1 class="myds-heading-md font-heading font-semibold">Inventori #{{ $inventory->id }}</h1>
                <p class="myds-body-sm text-muted mb-0">Butiran penuh item inventori</p>
            </header>

            <div class="bg-surface border rounded-m p-4 shadow-sm">
                @if(session('status'))
                    <div class="myds-alert myds-alert--success mb-3" role="status">{{ session('status') }}</div>
                @endif

                <dl class="row g-3">
                    <dt class="col-4 myds-body-sm text-muted">Nama</dt>
                    <dd class="col-8 myds-body-md">{{ $inventory->name ?? '—' }}</dd>

                    <dt class="col-4 myds-body-sm text-muted">Pemilik</dt>
                    <dd class="col-8 myds-body-md">{{ $inventory->user?->name ?? '—' }}</dd>

                    <dt class="col-4 myds-body-sm text-muted">Kuantiti</dt>
                    <dd class="col-8 myds-body-md">{{ $inventory->qty ?? 0 }}</dd>

                    <dt class="col-4 myds-body-sm text-muted">Harga</dt>
                    <dd class="col-8 myds-body-md">{{ isset($inventory->price) ? 'RM '.number_format($inventory->price, 2) : '—' }}</dd>

                    <dt class="col-12 myds-body-sm text-muted">Keterangan</dt>
                    <dd class="col-12 myds-body-md">{!! nl2br(e($inventory->description)) !!}</dd>
                </dl>

                <div class="mt-4 d-flex gap-2 justify-content-start">
                    <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--secondary" aria-label="Kembali ke senarai inventori">Kembali</a>
                    @can('update', $inventory)
                        <a href="{{ route('inventories.edit', $inventory->id) }}" class="myds-btn myds-btn--primary myds-btn--outline">Edit</a>
                    @endcan
                    @can('delete', $inventory)
                        <x-destroy :action="route('inventories.destroy', $inventory->id)" :label="$inventory->name ?? 'Inventori'" />
                    @endcan
                </div>

                @if(isset($inventory->vehicles) && $inventory->vehicles->count())
                    <hr class="my-3">
                    <h3 class="myds-heading-sm">Kenderaan berkaitan</h3>
                    <ul class="myds-list myds-list--bare">
                        @foreach($inventory->vehicles as $v)
                            <li><a href="{{ route('vehicles.show', $v->id) }}" class="text-decoration-none">{{ $v->name ?? '—' }}</a> <span class="text-muted">(ID: {{ $v->id }})</span></li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <aside class="desktop:col-span-4 tablet:col-span-8 mobile:col-span-4">
            <div class="bg-muted p-4 rounded">
                <h4 class="font-heading font-semibold h6">Maklumat tambahan</h4>
                <p class="small text-muted">Tarikh dicipta: {{ $inventory->created_at?->format('Y-m-d H:i') ?? '—' }}</p>
            </div>
        </aside>
    </section>
</main>
@endsection
