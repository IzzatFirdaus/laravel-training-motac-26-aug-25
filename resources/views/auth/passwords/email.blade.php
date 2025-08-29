@extends('layouts.app')

@section('title', 'Tetapan Semula Kata Laluan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container myds-container--page-centered" role="main">
    <div class="myds-auth-card">
        <div class="myds-auth-card__header">
            <h1 class="myds-auth-card__title">
                {{ __('Tetapan Semula Kata Laluan') }}
            </h1>
            <p class="myds-auth-card__subtitle">
                {{ __('Masukkan alamat e-mel anda untuk menerima pautan tetapan semula.') }}
            </p>
        </div>

        <div class="myds-auth-card__body">
            @if (session('status'))
                <div class="myds-alert myds-alert--success" role="alert">
                    <p class="myds-alert__body">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="myds-form">
                @csrf

                {{-- Email Address --}}
                <div class="myds-form__group">
                    <label for="email" class="myds-form__label">{{ __('Alamat E-mel') }}</label>
                    <input id="email" type="email" class="myds-form__input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="myds-form__error-message" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="myds-form__group">
                    <button type="submit" class="myds-btn myds-btn--primary myds-btn--full-width">
                        {{ __('Hantar Pautan Tetapan Semula Kata Laluan') }}
                    </button>
                </div>

                {{-- Back to Login --}}
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="myds-link">{{ __('Kembali ke Log Masuk') }}</a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
