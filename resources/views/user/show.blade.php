@extends('layouts.app')

@section('title', e($user->name) . ' — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="page-heading">

    {{-- Breadcrumb Navigation (MyGOVEA clear navigation principle) --}}
    <nav aria-label="Breadcrumb" class="mb-4">
        <ol class="d-flex list-unstyled myds-text--muted myds-body-sm m-0 p-0">
            <li><a href="{{ route('users.index') }}" class="text-primary text-decoration-none" data-nav="users">{{ __('ui.users.breadcrumb_label') }}</a></li>
            <li class="mx-2" aria-hidden="true">/</li>
            <li aria-current="page" class="myds-text--muted">{{ e($user->name) }}</li>
        </ol>
    </nav>

    {{-- MYDS Grid Layout --}}
    <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
        <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-8">

            <header class="mb-6 d-flex flex-column flex-sm-row justify-content-between align-items-start gap-3" role="banner">
                <div>
                    <h1 id="page-heading" class="myds-heading-md font-heading font-semibold mb-2">{{ e($user->name) }}</h1>
                    <p class="myds-body-sm myds-text--muted mb-0">{{ __('ui.user.details') }}</p>
                </div>

                <div class="d-flex gap-2" data-myds-actions>
                    @can('update', $user)
                        <a href="{{ route('users.edit', $user->id) }}" class="myds-btn myds-btn--primary myds-btn--outline myds-tap-target" aria-label="{{ __('ui.edit') }}" data-action="edit">
                            <i class="bi bi-pencil me-2" aria-hidden="true"></i>
                            {{ __('ui.edit') }}
                        </a>
                    @endcan
                </div>
            </header>

            {{-- Status Messages --}}
            @if(session('status'))
                <div class="myds-alert myds-alert--success d-flex align-items-start mb-4" role="alert" aria-live="polite">
                    <i class="bi bi-check-circle me-2 flex-shrink-0" aria-hidden="true"></i>
                    <div>
                        <h4 class="myds-body-md font-medium">{{ __('ui.form.success_heading') }}</h4>
                        <p class="mb-0 myds-body-sm">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            {{-- User Details Card --}}
            <section aria-labelledby="user-details-heading" data-myds-card="user-details">
                <div class="bg-surface border rounded-m p-4 shadow-sm mb-4">
                    <h2 id="user-details-heading" class="myds-heading-sm font-heading font-medium mb-4">{{ __('ui.user.details_heading') }}</h2>

                    <dl class="row g-3" role="list">
                        <div class="col-12 col-md-6" role="listitem">
                            <dt class="myds-body-sm font-medium myds-text--muted mb-1">{{ __('ui.user.id') }}</dt>
                            <dd class="myds-body-md mb-0" data-field="id">{{ $user->id }}</dd>
                        </div>

                        <div class="col-12 col-md-6" role="listitem">
                            <dt class="myds-body-sm font-medium myds-text--muted mb-1">{{ __('ui.user.name') }}</dt>
                            <dd class="myds-body-md mb-0" data-field="name">{{ e($user->name) }}</dd>
                        </div>

                        <div class="col-12" role="listitem">
                            <dt class="myds-body-sm font-medium myds-text--muted mb-1">{{ __('ui.user.email') }}</dt>
                            <dd class="myds-body-md mb-0" data-field="email">
                                <a href="mailto:{{ e($user->email) }}" class="text-primary text-decoration-none myds-tap-target" rel="noopener" aria-label="{{ __('ui.user.email_link', ['email' => $user->email]) }}">
                                    <i class="bi bi-envelope me-1" aria-hidden="true"></i>
                                    {{ e($user->email) }}
                                </a>
                            </dd>
                        </div>

                        <div class="col-12 col-md-6" role="listitem">
                            <dt class="myds-body-sm font-medium myds-text--muted mb-1">{{ __('ui.user.created') }}</dt>
                            <dd class="myds-body-md mb-0" data-field="created_at">
                                <time datetime="{{ $user->created_at?->toIso8601String() ?? now()->toIso8601String() }}">
                                    {{ $user->created_at?->format('d/m/Y H:i') ?? '—' }}
                                </time>
                            </dd>
                        </div>

                        <div class="col-12 col-md-6" role="listitem">
                            <dt class="myds-body-sm font-medium myds-text--muted mb-1">{{ __('ui.user.updated') }}</dt>
                            <dd class="myds-body-md mb-0" data-field="updated_at">
                                <time datetime="{{ $user->updated_at?->toIso8601String() ?? now()->toIso8601String() }}">
                                    {{ $user->updated_at?->format('d/m/Y H:i') ?? '—' }}
                                </time>
                            </dd>
                        </div>
                    </dl>
                </div>
            </section>

            {{-- Related Data (if any) --}}
            @if(isset($user->vehicles) && $user->vehicles->count())
                <section aria-labelledby="user-vehicles-heading" data-myds-card="user-vehicles">
                    <div class="bg-surface border rounded-m p-4 shadow-sm mb-4">
                        <h3 id="user-vehicles-heading" class="myds-heading-sm font-heading font-medium mb-3">{{ __('ui.user.related_vehicles') }}</h3>
                        <ul class="list-unstyled space-y-2" role="list">
                            @foreach($user->vehicles as $vehicle)
                                <li class="d-flex align-items-center" role="listitem">
                                    <i class="bi bi-truck me-2 myds-text--muted" aria-hidden="true"></i>
                                    <a href="{{ route('vehicles.show', $vehicle->id) }}" class="text-primary text-decoration-none myds-tap-target" data-nav="vehicle-{{ $vehicle->id }}">
                                        {{ e($vehicle->name) ?? '—' }}
                                    </a>
                                    <span class="ms-2 myds-text--muted myds-body-xs">(ID: {{ $vehicle->id }})</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            @endif

            {{-- Action Buttons --}}
            <div class="d-flex flex-column flex-sm-row gap-3" data-myds-actions>
                <a href="{{ route('users.index') }}" class="myds-btn myds-btn--secondary myds-tap-target" aria-label="{{ __('ui.back') }}" data-nav="back">
                    <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
                    {{ __('ui.back') }}
                </a>
                @can('update', $user)
                    <a href="{{ route('users.edit', $user->id) }}" class="myds-btn myds-btn--primary myds-btn--outline myds-tap-target" aria-label="{{ __('ui.edit') }}" data-action="edit">
                        <i class="bi bi-pencil me-2" aria-hidden="true"></i>
                        {{ __('ui.edit') }}
                    </a>
                @endcan
            </div>
        </div>

        {{-- Help Sidebar (MyGOVEA user assistance principle) --}}
        <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-4 mt-4 desktop:mt-0">
            <div class="bg-muted border rounded-m p-4" data-myds-sidebar="help">
                <h3 class="myds-heading-xs font-heading font-medium mb-3">{{ __('ui.help.user_actions') }}</h3>
                <div class="space-y-3">
                    <div>
                        <h4 class="myds-body-sm font-medium mb-1">{{ __('ui.help.available_actions') }}</h4>
                        <ul class="myds-body-xs myds-text--muted space-y-1">
                            <li>{{ __('ui.users.help.view_tip_1') }}</li>
                            <li>{{ __('ui.users.help.view_tip_2') }}</li>
                            <li>{{ __('ui.users.help.view_tip_3') }}</li>
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
