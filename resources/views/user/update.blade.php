@extends('layouts.app')

@section('title', __('ui.user.updated_heading') . ' — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-6" role="main" tabindex="-1" aria-labelledby="user-updated-heading">
    <header class="mb-4">
    <h1 id="user-updated-heading" class="myds-heading-md font-heading font-semibold">{{ __('ui.user.updated_heading') }}</h1>
    </header>

    <section>
        <div class="myds-card">
            <div class="myds-card__body">
                <p role="status" class="myds-body-md">{{ __('ui.user.updated_body') }}</p>
                <p class="myds-body-sm myds-text--muted">{{ __('ui.user.updated_body') }} <a href="{{ route('users.index') }}" class="myds-link">{{ __('ui.back') }}</a>.</p>
            </div>
        </div>
    </section>
</main>

@push('scripts')
    <meta id="users-redirect-url" content="{{ route('users.index') }}">
    <meta id="users-redirect-delay" content="1200">
    @vite('resources/js/pages/users-redirect.js')
@endpush
@endsection
