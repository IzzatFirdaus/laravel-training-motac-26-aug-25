@extends('layouts.app')

@section('title', 'Butiran Kenderaan — ' . config('app.name', 'Sistem Kerajaan'))
@section('title', __('ui.vehicles.show_title', ['id' => $vehicle->id, 'app' => config('app.name', 'Sistem Kerajaan')]))

@section('content')
<main id="main-content" class="myds-container py-4" role="main">
    <div class="desktop:col-span-8 tablet:col-span-8 mobile:col-span-4 mx-auto content-maxwidth-lg">
        <header class="mb-3">
            <h1 class="myds-heading-md font-heading font-semibold">Kenderaan #{{ $vehicle->id }}</h1>
            <p class="myds-body-sm myds-text--muted">Butiran penuh kenderaan yang direkodkan</p>
        </header>
            <header class="mb-3">
                <h1 class="myds-heading-md font-heading font-semibold">{{ __('ui.vehicles.show_heading', ['id' => $vehicle->id]) }}</h1>
                <p class="myds-body-sm myds-text--muted">{{ __('ui.vehicles.show_subheading') }}</p>
            </header>

        <div class="bg-surface border rounded-m p-4 shadow-sm">
            @if(session('status'))
                <div class="myds-alert myds-alert--success mb-3" role="status">{{ session('status') }}</div>
            @endif
                @if(session('status'))
                    <div class="myds-alert myds-alert--success mb-3" role="status">{{ session('status') }}</div>
                @endif

            <dl class="row g-3">
                <dt class="col-4 myds-body-sm myds-text--muted">Nama</dt>
                <dd class="col-8 myds-body-md">{{ $vehicle->name ?? '—' }}</dd>
                    <dt class="col-4 myds-body-sm myds-text--muted">{{ __('ui.vehicles.table.name') }}</dt>
                    <dd class="col-8 myds-body-md">{{ $vehicle->name ?? '—' }}</dd>

                <dt class="col-4 myds-body-sm myds-text--muted">Pemilik</dt>
                <dd class="col-8 myds-body-md">{{ $vehicle->owner?->name ?? '—' }}</dd>
                    <dt class="col-4 myds-body-sm myds-text--muted">{{ __('ui.vehicles.table.owner') }}</dt>
                    <dd class="col-8 myds-body-md">{{ $vehicle->owner?->name ?? '—' }}</dd>

                <dt class="col-4 myds-body-sm myds-text--muted">Kuantiti</dt>
                <dd class="col-8 myds-body-md">{{ $vehicle->qty ?? 0 }}</dd>
                    <dt class="col-4 myds-body-sm myds-text--muted">{{ __('ui.vehicles.table.qty') }}</dt>
                    <dd class="col-8 myds-body-md">{{ $vehicle->qty ?? 0 }}</dd>

                <dt class="col-4 myds-body-sm myds-text--muted">Harga</dt>
                <dd class="col-8 myds-body-md">{{ isset($vehicle->price) ? 'RM '.number_format($vehicle->price, 2) : '—' }}</dd>
                    <dt class="col-4 myds-body-sm myds-text--muted">{{ __('ui.vehicles.table.price') }}</dt>
                    <dd class="col-8 myds-body-md">{{ isset($vehicle->price) ? 'RM '.number_format($vehicle->price, 2) : '—' }}</dd>

                <dt class="col-12 myds-body-sm myds-text--muted">Keterangan</dt>
                <dd class="col-12 myds-body-md">{!! nl2br(e($vehicle->description)) !!}</dd>
                    <dt class="col-12 myds-body-sm myds-text--muted">{{ __('ui.vehicles.table.description') }}</dt>
                    <dd class="col-12 myds-body-md">{!! nl2br(e($vehicle->description)) !!}</dd>
            </dl>

            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--secondary" aria-label="Kembali ke senarai kenderaan">Kembali</a>
                <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="myds-btn myds-btn--primary myds-btn--outline">Edit</a>
                <x-destroy :action="route('vehicles.destroy', $vehicle->id)" :label="$vehicle->name ?? 'Kenderaan'" />
            </div>
                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--secondary" aria-label="{{ __('ui.back') }}">{{ __('ui.back') }}</a>
                    <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="myds-btn myds-btn--primary myds-btn--outline">{{ __('ui.edit') }}</a>
                    <x-destroy :action="route('vehicles.destroy', $vehicle->id)" :label="$vehicle->name ?? __('ui.vehicles.breadcrumb_label')" />
                </div>

            @if(isset($vehicle->inventories) && $vehicle->inventories->count())
                <hr class="my-3">
                <h3 class="myds-heading-sm">Inventori berkaitan</h3>
                    <h3 class="myds-heading-sm">{{ __('ui.vehicles.related_inventories') }}</h3>
                <ul class="myds-list myds-list--bare">
                    @foreach($vehicle->inventories as $inv)
                        <li><a href="{{ route('inventories.show', $inv->id) }}">{{ $inv->name ?? '—' }}</a> <span class="myds-text--muted">(ID: {{ $inv->id }})</span></li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</main>
@endsection
