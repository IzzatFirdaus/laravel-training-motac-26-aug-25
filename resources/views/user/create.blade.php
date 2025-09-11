@extends('layouts.app')

@section('title', __('ui.users.create') . ' — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="page-heading">

    {{-- Breadcrumb Navigation (MyGOVEA clear navigation principle) --}}
    <nav aria-label="Breadcrumb" class="mb-4">
        <ol class="d-flex list-unstyled myds-text--muted myds-body-sm m-0 p-0">
            <li><a href="{{ route('users.index') }}" class="text-primary text-decoration-none" data-nav="users">{{ __('ui.users.breadcrumb_label') }}</a></li>
            <li class="mx-2" aria-hidden="true">/</li>
            <li aria-current="page" class="myds-text--muted">{{ __('ui.users.create') }}</li>
        </ol>
    </nav>

    {{-- MYDS Grid Layout --}}
    <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
        <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-8">

            <header class="mb-6" role="banner">
                <h1 id="page-heading" class="myds-heading-md font-heading font-semibold mb-2">{{ __('ui.users.create') }}</h1>
                <p class="myds-body-sm myds-text--muted mb-0">{{ __('ui.users.description') }}</p>
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

            {{-- Main Form Card --}}
            <section aria-labelledby="create-user-form" data-myds-card="user-create">
                <h2 id="create-user-form" class="sr-only">Borang cipta pengguna</h2>

                <div class="bg-surface border rounded-m p-4 shadow-sm">
                    <form method="POST" action="{{ route('users.store') }}" novalidate aria-label="Borang cipta pengguna" data-myds-form="user-create">
                        @csrf

                        @include('user._form', ['user' => null])

                        <div class="d-flex flex-column flex-sm-row gap-3 mt-6 justify-content-end" data-myds-actions>
                            <a href="{{ route('users.index') }}" class="myds-btn myds-btn--secondary myds-tap-target" aria-label="{{ __('ui.button.cancel_and_back') }}" data-nav="cancel">
                                <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
                                {{ __('ui.cancel') }}
                            </a>
                            <button type="submit" class="myds-btn myds-btn--primary myds-tap-target" aria-label="{{ __('ui.save') }}" data-action="save">
                                <i class="bi bi-save me-2" aria-hidden="true"></i>
                                {{ __('ui.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>

        {{-- Help Sidebar (MyGOVEA user assistance principle) --}}
        <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-4 mt-4 desktop:mt-0">
            <div class="bg-muted border rounded-m p-4" data-myds-sidebar="help">
                <h3 class="myds-heading-xs font-heading font-medium mb-3">{{ __('ui.help.heading') }}</h3>
                <div class="space-y-3">
                    <div>
                        <h4 class="myds-body-sm font-medium mb-1">{{ __('ui.help.tips_heading') }}</h4>
                        <ul class="myds-body-xs myds-text--muted space-y-1">
                            <li>{{ __('ui.users.help.tip_1') }}</li>
                            <li>{{ __('ui.users.help.tip_2') }}</li>
                            <li>{{ __('ui.users.help.tip_3') }}</li>
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
