@extends('layouts.app')

@section('title', 'Daftar Akaun — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container myds-container--page-centered py-6" role="main" tabindex="-1" aria-labelledby="register-heading">
    <div class="myds-auth-card" role="region" aria-describedby="register-subtitle">
        <header class="myds-auth-card__header">
            <h1 id="register-heading" class="myds-auth-card__title">Daftar Akaun Baru</h1>
            <p id="register-subtitle" class="myds-auth-card__subtitle myds-text--muted">Sila lengkapkan maklumat di bawah untuk mendaftar.</p>
        </header>

        <div class="myds-auth-card__body">
            <form method="POST" action="{{ route('register') }}" class="myds-form" novalidate aria-label="Borang daftar">
                @csrf

                {{-- Name --}}
                <div class="myds-form__group">
                    <label for="name" class="myds-form__label">Nama Penuh</label>
                    <input id="name" name="name" type="text" class="myds-form__input @error('name') is-invalid @enderror" value="{{ old('name') }}" required autocomplete="name" aria-describedby="name-help @error('name') name-error @enderror" autofocus>
                    <div id="name-help" class="myds-form__hint myds-body-xs myds-text--muted">Sila masukkan nama seperti dalam kad pengenalan.</div>
                    @error('name')
                        <div id="name-error" class="myds-form__error-message" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="myds-form__group">
                    <label for="email" class="myds-form__label">Alamat E-mel</label>
                    <input id="email" name="email" type="email" class="myds-form__input @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" aria-describedby="email-help @error('email') email-error @enderror">
                    <div id="email-help" class="myds-form__hint myds-body-xs myds-text--muted">E-mel ini akan digunakan untuk pengesahan.</div>
                    @error('email')
                        <div id="email-error" class="myds-form__error-message" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="myds-form__group">
                    <label for="password" class="myds-form__label">Kata Laluan</label>
                    <div class="d-flex align-items-center gap-2">
                        <input id="password" name="password" type="password" class="myds-form__input @error('password') is-invalid @enderror" required autocomplete="new-password" aria-describedby="password-help @error('password') password-error @enderror">
                        <button type="button" data-toggle="password" data-target="password" class="myds-btn myds-btn--tertiary myds-btn--icon" aria-label="Tunjuk atau sembunyikan kata laluan">
                            <i class="bi bi-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div id="password-help" class="myds-form__hint myds-body-xs myds-text--muted">Minimum 8 aksara dengan gabungan huruf dan nombor.</div>
                    @error('password')
                        <div id="password-error" class="myds-form__error-message" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="myds-form__group">
                    <label for="password-confirm" class="myds-form__label">Sahkan Kata Laluan</label>
                    <div class="d-flex align-items-center gap-2">
                        <input id="password-confirm" name="password_confirmation" type="password" class="myds-form__input" required autocomplete="new-password" aria-describedby="password-confirm-help">
                        <button type="button" data-toggle="password" data-target="password-confirm" class="myds-btn myds-btn--tertiary myds-btn--icon" aria-label="Tunjuk atau sembunyikan pengesahan kata laluan">
                            <i class="bi bi-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div id="password-confirm-help" class="myds-form__hint myds-body-xs myds-text--muted">Masukkan sekali lagi kata laluan anda untuk pengesahan.</div>
                </div>

                {{-- Submit --}}
                <div class="myds-form__group mt-3">
                    <button type="submit" class="myds-btn myds-btn--primary myds-btn--full-width" aria-label="Daftar">Daftar</button>
                </div>

                {{-- Link to login --}}
                <div class="myds-body-xs myds-text--muted text-center mt-2">
                    Sudah mempunyai akaun? <a href="{{ route('login') }}" class="myds-link">Log masuk di sini</a>
                </div>
            </form>
        </div>
    </div>
</main>

@push('scripts')
<!-- Password toggle handled by resources/js/features/password-toggle.js -->
@endpush
@endsection
