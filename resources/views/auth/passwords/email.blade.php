@extends('layouts.app')

@section('title', 'Tetapan Semula Kata Laluan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container myds-container--page-centered py-6" role="main" tabindex="-1" aria-labelledby="reset-email-heading">
    <div class="myds-auth-card" role="region" aria-describedby="reset-email-description">
        <header class="myds-auth-card__header">
            <h1 id="reset-email-heading" class="myds-auth-card__title">Tetapan Semula Kata Laluan</h1>
            <p id="reset-email-description" class="myds-auth-card__subtitle myds-text--muted">
                Masukkan alamat e-mel anda untuk menerima pautan tetapan semula kata laluan.
            </p>
        </header>

        <div class="myds-auth-card__body">
            @if (session('status'))
                <div class="myds-alert myds-alert--success" role="status" aria-live="polite">
                    <div class="myds-alert__body">{{ session('status') }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="myds-form" novalidate aria-label="Borang pautan tetapan semula">
                @csrf

                {{-- Email Address --}}
                <div class="myds-form__group">
                    <label for="email" class="myds-form__label">Alamat E-mel</label>
                    <input id="email"
                           type="email"
                           name="email"
                           class="myds-form__input @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           required
                           aria-required="true"
                           autocomplete="email"
                           aria-describedby="@error('email') email-error @enderror">
                    @error('email')
                        <div id="email-error" class="myds-form__error-message" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="myds-form__group mt-4">
                    <button type="submit" class="myds-btn myds-btn--primary myds-btn--full-width" aria-label="Hantar pautan tetapan semula">
                        Hantar Pautan Tetapan Semula Kata Laluan
                    </button>
                </div>

                {{-- Back to Login --}}
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="myds-link">Kembali ke Log Masuk</a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
