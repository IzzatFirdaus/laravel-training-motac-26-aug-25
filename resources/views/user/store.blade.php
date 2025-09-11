@extends('layouts.app')

@section('title', __('ui.user.create_heading') . ' — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="page-heading">

    {{-- Breadcrumb Navigation (MyGOVEA clear navigation principle) --}}
    <nav aria-label="Breadcrumb" class="mb-4">
        <ol class="d-flex list-unstyled myds-text--muted myds-body-sm m-0 p-0">
            <li><a href="{{ route('users.index') }}" class="text-primary text-decoration-none" data-nav="users">{{ __('ui.users.breadcrumb_label') }}</a></li>
            <li class="mx-2" aria-hidden="true">/</li>
            <li aria-current="page" class="myds-text--muted">{{ __('ui.user.create_heading') }}</li>
        </ol>
    </nav>

    {{-- MYDS Grid Layout --}}
    <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
        <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-8 desktop:col-start-3">

            <header class="mb-6" role="banner">
                <h1 id="page-heading" class="myds-heading-md font-heading font-semibold mb-2">{{ __('ui.user.create_heading') }}</h1>
            </header>

            {{-- Success Message Card --}}
            <section data-myds-card="success">
                <div class="bg-surface border rounded-m p-4 shadow-sm">
                    <div class="myds-alert myds-alert--success d-flex align-items-start" role="alert" aria-live="polite">
                        <i class="bi bi-check-circle me-3 flex-shrink-0" aria-hidden="true"></i>
                        <div>
                            <h4 class="myds-body-md font-medium mb-2">{{ __('ui.form.success_heading') }}</h4>
                            <p class="myds-body-md mb-3">{{ __('ui.user.create_body') }}</p>
                            <p class="myds-body-sm myds-text--muted mb-0">
                                {{ __('ui.user.auto_redirect_notice') }}
                                <a href="{{ route('users.index') }}" class="text-primary myds-tap-target" data-nav="users">{{ __('ui.back') }}</a>.
                            </p>
                        </div>
                    </div>

                    {{-- Quick Action --}}
                    <div class="mt-4 pt-3 border-top">
                        <a href="{{ route('users.index') }}" class="myds-btn myds-btn--primary myds-tap-target" aria-label="{{ __('ui.back') }}" data-nav="back">
                            <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
                            {{ __('ui.back') }}
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

@push('scripts')
    <meta id="users-redirect-url" content="{{ route('users.index') }}" data-myds-redirect>
    <meta id="users-redirect-delay" content="1200" data-myds-redirect-delay>
    @vite('resources/js/pages/users-redirect.js')
@endpush
@endsection
