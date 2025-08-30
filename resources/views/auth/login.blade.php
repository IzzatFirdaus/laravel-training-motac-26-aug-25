@extends('layouts.app')

@section('title', 'Log Masuk — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container myds-container--page-centered py-6" role="main" tabindex="-1" aria-labelledby="login-heading">
    <div class="myds-auth-card" role="region" aria-describedby="login-subtitle">
        <header class="myds-auth-card__header">
            <h1 id="login-heading" class="myds-auth-card__title">Log Masuk</h1>
            <p id="login-subtitle" class="myds-auth-card__subtitle myds-text--muted">
                Selamat kembali! Sila masukkan butiran anda.
            </p>
        </header>

        <div class="myds-auth-card__body">
            @if(session('status'))
                <div class="myds-alert myds-alert--success mb-3" role="status" aria-live="polite">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="myds-form" novalidate aria-label="Borang log masuk">
                @csrf

                {{-- Email --}}
                <div class="myds-form__group">
                    <label for="email" class="myds-form__label">Alamat E-mel</label>
                    <input id="email"
                           name="email"
                           type="email"
                           class="myds-form__input @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           autofocus
                           aria-describedby="email-help @error('email') email-error @enderror">
                    <div id="email-help" class="myds-form__hint myds-body-xs myds-text--muted">Contoh: nama@domain.gov.my</div>

                    @error('email')
                        <div id="email-error" class="myds-form__error-message" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="myds-form__group">
                    <label for="password" class="myds-form__label">Kata Laluan</label>
                    <div class="d-flex align-items-center gap-2">
                        <input id="password"
                               name="password"
                               type="password"
                               class="myds-form__input @error('password') is-invalid @enderror"
                               required
                               autocomplete="current-password"
                               aria-describedby="@error('password') password-error @enderror">
                        <button type="button" data-toggle="password" data-target="password" class="myds-btn myds-btn--tertiary myds-btn--icon" aria-label="Tunjuk atau sembunyikan kata laluan" title="Tunjuk/Sembunyi">
                            <i class="bi bi-eye" aria-hidden="true"></i>
                        </button>
                    </div>

                    @error('password')
                        <div id="password-error" class="myds-form__error-message" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember & Forgot --}}
                <div class="myds-form__group d-flex justify-content-between align-items-center">
                    <div class="myds-form__check">
                        <input id="remember" name="remember" type="checkbox" class="myds-form__check-input" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="myds-form__check-label">Ingat Saya</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a class="myds-link myds-link--subtle" href="{{ route('password.request') }}">Lupa Kata Laluan?</a>
                    @endif
                </div>

                {{-- Submit --}}
                <div class="myds-form__group">
                    <button type="submit" class="myds-btn myds-btn--primary myds-btn--full-width" aria-label="Log masuk">Log Masuk</button>
                </div>

                {{-- Register link --}}
                <div class="myds-body-xs myds-text--muted text-center mt-2">
                    Belum mempunyai akaun?
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="myds-link">Daftar di sini</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</main>

@push('scripts')
<!-- Password toggle handled by resources/js/features/password-toggle.js -->
@endpush
@endsection
