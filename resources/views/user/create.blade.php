@extends('layouts.app')

@section('title', __('ui.users.create') . ' — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-6" role="main" tabindex="-1" aria-labelledby="page-heading">
    <header class="mb-4">
    <h1 id="page-heading" class="myds-heading-md font-heading font-semibold">{{ __('ui.users.create') }}</h1>
    <p class="myds-body-sm myds-text--muted mb-0">{{ __('ui.users.description') }}</p>
    </header>

    <section aria-labelledby="create-user-form">
        <h2 id="create-user-form" class="sr-only">Borang cipta pengguna</h2>

        <div class="myds-card">
            <div class="myds-card__body">
                <form method="POST" action="{{ route('users.store') }}" novalidate aria-label="Borang cipta pengguna" data-myds-form>
                    @csrf

                    @include('user._form', ['user' => null])

                    <div class="d-flex gap-2 mt-4 justify-content-end">
                        <a href="{{ route('users.index') }}" class="myds-btn myds-btn--tertiary" aria-label="{{ __('ui.button.cancel_and_back') }}">{{ __('ui.cancel') }}</a>
                        <button type="submit" class="myds-btn myds-btn--primary" aria-label="{{ __('ui.save') }}">{{ __('ui.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection
