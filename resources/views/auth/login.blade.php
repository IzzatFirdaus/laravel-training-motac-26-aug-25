@extends('layouts.app')

@section('title', 'Log Masuk — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container myds-container--page-centered" role="main">
    <div class="myds-auth-card">
        <div class="myds-auth-card__header">
            <h1 class="myds-auth-card__title">
                {{ __('Log Masuk') }}
            </h1>
            <p class="myds-auth-card__subtitle">
                {{ __('Selamat kembali! Sila masukkan butiran anda.') }}
            </p>
        </div>

        <div class="myds-auth-card__body">
            <form method="POST" action="{{ route('login') }}" class="myds-form">
                @csrf

                {{-- Email Address --}}
                <div class="myds-form__group">
                    <label for="email" class="myds-form__label">{{ __('Alamat E-mel') }}</label>
                    <input id="email" type="email" class="myds-form__input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                           aria-describedby="email-help">
                    <small id="email-help" class="myds-form__help">Contoh: nama@domain.gov.my</small>
                    @error('email')
                        <span class="myds-form__error-message" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="myds-form__group">
                    <label for="password" class="myds-form__label">{{ __('Kata Laluan') }}</label>
                    <input id="password" type="password" class="myds-form__input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                        <span class="myds-form__error-message" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- Remember Me & Forgot Password --}}
                <div class="myds-form__group d-flex justify-content-between align-items-center">
                    <div class="myds-form__check">
                        <input class="myds-form__check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="myds-form__check-label" for="remember">
                            {{ __('Ingat Saya') }}
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="myds-link myds-link--subtle" href="{{ route('password.request') }}">
                            {{ __('Lupa Kata Laluan?') }}
                        </a>
                    @endif
                </div>

                {{-- Submit Button --}}
                <div class="myds-form__group">
                    <button type="submit" class="myds-btn myds-btn--primary myds-btn--full-width">
                        {{ __('Log Masuk') }}
                    </button>
                </div>

                {{-- Link to Register --}}
                <div class="text-center mt-4">
                    <p class="myds-body-sm text-muted">
                        {{ __('Belum mempunyai akaun?') }}
                        <a href="{{ route('register') }}" class="myds-link">{{ __('Daftar di sini') }}</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
