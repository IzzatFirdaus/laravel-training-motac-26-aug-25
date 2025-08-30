@extends('layouts.app')

@section('title', 'Daftar Akaun — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container myds-container--page-centered" role="main">
    <div class="myds-auth-card">
        <div class="myds-auth-card__header">
            <h1 class="myds-auth-card__title">
                {{ __('Daftar Akaun Baru') }}
            </h1>
            <p class="myds-auth-card__subtitle">
                {{ __('Sila lengkapkan maklumat di bawah untuk mendaftar.') }}
            </p>
        </div>

        <div class="myds-auth-card__body">
            <form method="POST" action="{{ route('register') }}" class="myds-form">
                @csrf

                {{-- Name --}}
                <div class="myds-form__group">
                    <label for="name" class="myds-form__label">{{ __('Nama Penuh') }}</label>
                    <input id="name" type="text" class="myds-form__input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                           aria-describedby="name-help">
                    <small id="name-help" class="myds-form__help">Sila masukkan nama seperti dalam kad pengenalan.</small>
                    @error('name')
                        <span class="myds-form__error-message" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- Email Address --}}
                <div class="myds-form__group">
                    <label for="email" class="myds-form__label">{{ __('Alamat E-mel') }}</label>
                    <input id="email" type="email" class="myds-form__input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email"
                           aria-describedby="email-help-register">
                    <small id="email-help-register" class="myds-form__help">E-mel ini akan digunakan untuk pengesahan.</small>
                    @error('email')
                        <span class="myds-form__error-message" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="myds-form__group">
                    <label for="password" class="myds-form__label">{{ __('Kata Laluan') }}</label>
                    <input id="password" type="password" class="myds-form__input @error('password') is-invalid @enderror" name="password" required autocomplete="new-password"
                           aria-describedby="password-help">
                    <small id="password-help" class="myds-form__help">Minimum 8 aksara dengan gabungan huruf dan nombor.</small>
                    @error('password')
                        <span class="myds-form__error-message" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="myds-form__group">
                    <label for="password-confirm" class="myds-form__label">{{ __('Sahkan Kata Laluan') }}</label>
                    <input id="password-confirm" type="password" class="myds-form__input" name="password_confirmation" required autocomplete="new-password">
                </div>

                {{-- Submit Button --}}
                <div class="myds-form__group">
                    <button type="submit" class="myds-btn myds-btn--primary myds-btn--full-width">
                        {{ __('Daftar') }}
                    </button>
                </div>

                {{-- Link to Login --}}
                                            <div class="myds-body-xs myds-text--muted text-center">
                                Sudah mempunyai akaun? <a href="{{ route('login') }}" class="text-decoration-none">Log masuk di sini</a>
                            </div>
            </form>
        </div>
    </div>
</main>
@endsection
