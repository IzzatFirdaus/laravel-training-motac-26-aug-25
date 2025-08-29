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
                {{ __('Sila lengkapkan borang di bawah untuk menetapkan semula kata laluan anda.') }}
            </p>
        </div>

        <div class="myds-auth-card__body">
            <form method="POST" action="{{ route('password.update') }}" class="myds-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email Address --}}
                <div class="myds-form__group">
                    <label for="email" class="myds-form__label">{{ __('Alamat E-mel') }}</label>
                    <input id="email" type="email" class="myds-form__input @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="myds-form__error-message" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="myds-form__group">
                    <label for="password" class="myds-form__label">{{ __('Kata Laluan Baharu') }}</label>
                    <input id="password" type="password" class="myds-form__input @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    @error('password')
                        <span class="myds-form__error-message" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="myds-form__group">
                    <label for="password-confirm" class="myds-form__label">{{ __('Sahkan Kata Laluan Baharu') }}</label>
                    <input id="password-confirm" type="password" class="myds-form__input" name="password_confirmation" required autocomplete="new-password">
                </div>

                {{-- Submit Button --}}
                <div class="myds-form__group">
                    <button type="submit" class="myds-btn myds-btn--primary myds-btn--full-width">
                        {{ __('Tetapkan Semula Kata Laluan') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
