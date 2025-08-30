@extends('layouts.app')

@section('title', 'Sahkan Kata Laluan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container myds-container--page-centered py-6" role="main" tabindex="-1" aria-labelledby="confirm-heading">
    <div class="myds-auth-card" role="region" aria-describedby="confirm-description">
        <header class="myds-auth-card__header">
            <h1 id="confirm-heading" class="myds-auth-card__title">Sahkan Kata Laluan</h1>
            <p id="confirm-description" class="myds-auth-card__subtitle myds-text--muted">
                Sila sahkan kata laluan anda sebelum meneruskan. Ini untuk keselamatan akaun anda.
            </p>
        </header>

        <div class="myds-auth-card__body">
            <form method="POST" action="{{ route('password.confirm') }}" class="myds-form" novalidate aria-label="Borang sahkan kata laluan">
                @csrf

                {{-- Password --}}
                <div class="myds-form__group">
                    <label for="password" class="myds-form__label">Kata Laluan</label>
                    <div class="d-flex align-items-center gap-2">
                        <input id="password"
                               name="password"
                               type="password"
                               class="myds-form__input @error('password') is-invalid @enderror"
                               autocomplete="current-password"
                               required
                               aria-required="true"
                               aria-describedby="password-help @error('password') password-error @enderror"
                               autofocus>
                        <button type="button" class="myds-btn myds-btn--tertiary myds-btn--icon" id="toggle-password" aria-label="Tunjukkan atau sembunyikan kata laluan" title="Tunjuk/Sembunyi">
                            <i class="bi bi-eye" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div id="password-help" class="myds-form__hint myds-body-xs myds-text--muted mt-2">
                        Masukkan kata laluan semasa anda untuk mengesahkan identiti.
                    </div>

                    @error('password')
                        <div id="password-error" class="myds-form__error-message" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="myds-form__group mt-4">
                    <button type="submit" class="myds-btn myds-btn--primary myds-btn--full-width" aria-label="Sahkan kata laluan">
                        Sahkan Kata Laluan
                    </button>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-center mt-3">
                        <a class="myds-link" href="{{ route('password.request') }}">Lupa Kata Laluan?</a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</main>

@push('scripts')
{{-- Password toggle moved to resources/js/features/password-toggle.js and bundled via app.js --}}
@endpush
@endsection
