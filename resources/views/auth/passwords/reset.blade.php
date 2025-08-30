@extends('layouts.app')

@section('title', 'Tetapan Semula Kata Laluan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container myds-container--page-centered py-6" role="main" tabindex="-1" aria-labelledby="reset-heading">
    <div class="myds-auth-card" role="region" aria-describedby="reset-description">
        <header class="myds-auth-card__header">
            <h1 id="reset-heading" class="myds-auth-card__title">Tetapkan Semula Kata Laluan</h1>
            <p id="reset-description" class="myds-auth-card__subtitle myds-text--muted">
                Sila lengkapkan borang di bawah untuk menetapkan semula kata laluan anda.
            </p>
        </header>

        <div class="myds-auth-card__body">
            <form method="POST" action="{{ route('password.update') }}" class="myds-form" novalidate aria-label="Borang tetapan semula kata laluan">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email Address --}}
                <div class="myds-form__group">
                    <label for="email" class="myds-form__label">Alamat E-mel</label>
                    <input id="email"
                           type="email"
                           name="email"
                           class="myds-form__input @error('email') is-invalid @enderror"
                           value="{{ $email ?? old('email') }}"
                           required
                           aria-required="true"
                           autocomplete="email"
                           aria-describedby="@error('email') email-error @enderror">
                    @error('email')
                        <div id="email-error" class="myds-form__error-message" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="myds-form__group">
                    <label for="password" class="myds-form__label">Kata Laluan Baharu</label>
                    <div class="d-flex gap-2 align-items-center">
                        <input id="password" type="password" name="password" class="myds-form__input @error('password') is-invalid @enderror" required autocomplete="new-password" aria-describedby="password-help @error('password') password-error @enderror">
                        <button type="button" class="myds-btn myds-btn--tertiary myds-btn--icon" data-toggle="password" data-target="password" aria-label="Tunjukkan atau sembunyikan kata laluan baru">
                            <i class="bi bi-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div id="password-help" class="myds-form__hint myds-body-xs myds-text--muted mt-2">
                        Kata laluan mesti sekurang-kurangnya 8 aksara.
                    </div>
                    @error('password')
                        <div id="password-error" class="myds-form__error-message" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="myds-form__group">
                    <label for="password-confirm" class="myds-form__label">Sahkan Kata Laluan Baharu</label>
                    <div class="d-flex gap-2 align-items-center">
                        <input id="password-confirm" type="password" name="password_confirmation" class="myds-form__input" required autocomplete="new-password" aria-describedby="password-confirm-help">
                        <button type="button" class="myds-btn myds-btn--tertiary myds-btn--icon" data-toggle="password" data-target="password-confirm" aria-label="Tunjukkan atau sembunyikan pengesahan kata laluan">
                            <i class="bi bi-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div id="password-confirm-help" class="myds-form__hint myds-body-xs myds-text--muted mt-2">
                        Masukkan sekali lagi kata laluan baru anda untuk pengesahan.
                    </div>
                </div>

                {{-- Submit --}}
                <div class="myds-form__group mt-4">
                    <button type="submit" class="myds-btn myds-btn--primary myds-btn--full-width" aria-label="Tetapkan semula kata laluan">
                        Tetapkan Semula Kata Laluan
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

@push('scripts')
<!-- Password toggle handled by resources/js/features/password-toggle.js -->
@endpush
@endsection
