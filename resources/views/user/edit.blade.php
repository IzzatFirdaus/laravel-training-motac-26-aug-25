@extends('layouts.app')

@section('title', __('ui.users.create') . ' — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-6" role="main" tabindex="-1" aria-labelledby="edit-user-heading">
    <header class="mb-4">
    <h1 id="edit-user-heading" class="myds-heading-md font-heading font-semibold">{{ __('ui.users.create') }}</h1>
    <p class="myds-body-sm myds-text--muted mb-0">{{ __('ui.users.description') }}</p>
    </header>

    <section aria-labelledby="edit-user-form">
        <h2 id="edit-user-form" class="sr-only">Borang ubah pengguna</h2>

        <div class="myds-card">
            <div class="myds-card__body">
                @if (session('status'))
                    <div class="myds-alert myds-alert--success mb-3" role="status" aria-live="polite">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('users.update', $user->id) }}" novalidate aria-label="Borang ubah pengguna" data-myds-form>
                    @csrf
                    @method('PUT')

                    @include('user._form', ['user' => $user])

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('users.index') }}" class="myds-btn myds-btn--tertiary" aria-label="{{ __('ui.button.cancel_and_back') }}">{{ __('ui.cancel') }}</a>
                        <button type="submit" class="myds-btn myds-btn--primary" aria-label="{{ __('ui.button.update_user') }}">{{ __('ui.button.update_user') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection
