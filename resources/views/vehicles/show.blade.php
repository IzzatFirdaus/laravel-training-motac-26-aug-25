@extends('layouts.app')

@section('title', __('ui.vehicles.show_title', ['id' => $vehicle->id, 'app' => config('app.name', 'Sistem Kerajaan')]))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="page-heading">

    {{-- Breadcrumb Navigation (MyGOVEA clear navigation principle) --}}
    <nav aria-label="Breadcrumb" class="mb-4">
        <ol class="d-flex list-unstyled myds-text--muted myds-body-sm m-0 p-0">
            <li><a href="{{ route('vehicles.index') }}" class="text-primary text-decoration-none" data-nav="vehicles">{{ __('ui.vehicles.breadcrumb_label') }}</a></li>
            <li class="mx-2" aria-hidden="true">/</li>
            <li aria-current="page" class="myds-text--muted">{{ e($vehicle->name) }}</li>
        </ol>
    </nav>

    {{-- MYDS Grid Layout --}}
    <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
        <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-8">

            <header class="mb-6" role="banner">
                <h1 id="page-heading" class="myds-heading-md font-heading font-semibold mb-2">{{ __('ui.vehicles.show_heading', ['id' => $vehicle->id]) }}</h1>
                <p class="myds-body-sm myds-text--muted mb-0">{{ __('ui.vehicles.show_subheading') }}</p>
            </header>

            {{-- Status Message --}}
            @if(session('status'))
                <div class="myds-alert myds-alert--success d-flex align-items-start mb-4" role="alert" aria-live="polite">
                    <i class="bi bi-check-circle me-2 flex-shrink-0" aria-hidden="true"></i>
                    <div>
                        <h4 class="myds-body-md font-medium">{{ __('ui.form.success_heading') }}</h4>
                        <p class="mb-0 myds-body-sm">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            {{-- Vehicle Details Card --}}
            <div class="bg-surface border rounded-m p-4 shadow-sm mb-4" data-myds-card="vehicle-details">
                <h2 class="myds-heading-sm font-heading font-medium mb-4">{{ __('ui.vehicles.details_heading') }}</h2>

                <dl class="row g-3" role="list">
                    <div class="col-12 col-md-6" role="listitem">
                        <dt class="myds-body-sm font-medium myds-text--muted mb-1">{{ __('ui.vehicles.table.name') }}</dt>
                        <dd class="myds-body-md mb-0" data-field="name">{{ e($vehicle->name) ?? '—' }}</dd>
                    </div>

                    <div class="col-12 col-md-6" role="listitem">
                        <dt class="myds-body-sm font-medium myds-text--muted mb-1">{{ __('ui.vehicles.table.owner') }}</dt>
                        <dd class="myds-body-md mb-0" data-field="owner">{{ e($vehicle->owner?->name) ?? '—' }}</dd>
                    </div>

                    <div class="col-12 col-md-6" role="listitem">
                        <dt class="myds-body-sm font-medium myds-text--muted mb-1">{{ __('ui.vehicles.table.qty') }}</dt>
                        <dd class="myds-body-md mb-0" data-field="quantity">{{ $vehicle->qty ?? 0 }}</dd>
                    </div>

                    <div class="col-12 col-md-6" role="listitem">
                        <dt class="myds-body-sm font-medium myds-text--muted mb-1">{{ __('ui.vehicles.table.price') }}</dt>
                        <dd class="myds-body-md mb-0" data-field="price">
                            {{ isset($vehicle->price) ? 'RM ' . number_format($vehicle->price, 2) : '—' }}
                        </dd>
                    </div>

                    @if($vehicle->description)
                        <div class="col-12" role="listitem">
                            <dt class="myds-body-sm font-medium myds-text--muted mb-1">{{ __('ui.vehicles.table.description') }}</dt>
                            <dd class="myds-body-md mb-0" data-field="description">{!! nl2br(e($vehicle->description)) !!}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Related Inventories (if any) --}}
            @if(isset($vehicle->inventories) && $vehicle->inventories->count())
                <div class="bg-surface border rounded-m p-4 shadow-sm mb-4" data-myds-card="related-inventories">
                    <h3 class="myds-heading-sm font-heading font-medium mb-3">{{ __('ui.vehicles.related_inventories') }}</h3>
                    <ul class="list-unstyled space-y-2" role="list">
                        @foreach($vehicle->inventories as $inv)
                            <li class="d-flex align-items-center" role="listitem">
                                <i class="bi bi-box me-2 myds-text--muted" aria-hidden="true"></i>
                                <a href="{{ route('inventories.show', $inv->id) }}" class="text-primary text-decoration-none" data-nav="inventory-{{ $inv->id }}">
                                    {{ e($inv->name) ?? '—' }}
                                </a>
                                <span class="ms-2 myds-text--muted myds-body-xs">(ID: {{ $inv->id }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div class="d-flex flex-column flex-sm-row gap-3" data-myds-actions>
                <a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--secondary myds-tap-target" aria-label="{{ __('ui.back') }}" data-nav="back">
                    <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
                    {{ __('ui.back') }}
                </a>
                <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="myds-btn myds-btn--primary myds-btn--outline myds-tap-target" aria-label="{{ __('ui.edit') }}" data-action="edit">
                    <i class="bi bi-pencil me-2" aria-hidden="true"></i>
                    {{ __('ui.edit') }}
                </a>
                <x-destroy :action="route('vehicles.destroy', $vehicle->id)" :label="$vehicle->name ?? __('ui.vehicles.breadcrumb_label')" />
            </div>
        </div>

        {{-- Help Sidebar (MyGOVEA user assistance principle) --}}
        <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-4 mt-4 desktop:mt-0">
            <div class="bg-muted border rounded-m p-4" data-myds-sidebar="help">
                <h3 class="myds-heading-xs font-heading font-medium mb-3">{{ __('ui.help.vehicle_actions') }}</h3>
                <div class="space-y-3">
                    <div>
                        <h4 class="myds-body-sm font-medium mb-1">{{ __('ui.help.available_actions') }}</h4>
                        <ul class="myds-body-xs myds-text--muted space-y-1">
                            <li>{{ __('ui.vehicles.help.view_tip_1') }}</li>
                            <li>{{ __('ui.vehicles.help.view_tip_2') }}</li>
                            <li>{{ __('ui.vehicles.help.view_tip_3') }}</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="myds-body-sm font-medium mb-1">{{ __('ui.help.support_heading') }}</h4>
                        <p class="myds-body-xs myds-text--muted">{!! __('ui.help.support_contact', ['contact' => '<a href="mailto:support@jdn.gov.my" class="text-primary">support@jdn.gov.my</a>']) !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
