@extends('layouts.app')

@section('title', 'Sahkan Kata Laluan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container myds-container--page-centered" role="main">
    <div class="myds-auth-card">
        <div class="myds-auth-card__header">
            <h1 class="myds-auth-card__title">
                {{ __('Sahkan Kata Laluan') }}
            </h1>
            <p class="myds-auth-card__subtitle">
                {{ __('Sila sahkan kata laluan anda sebelum meneruskan.') }}
            </p>
        </div>

        <div class="myds-auth-card__body">
            <form method="POST" action="{{ route('password.confirm') }}" class="myds-form">
                @csrf

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

                {{-- Submit Button & Forgot Password Link --}}
                <div class="myds-form__group">
                    <button type="submit" class="myds-btn myds-btn--primary myds-btn--full-width">
                        {{ __('Sahkan Kata Laluan') }}
                    </button>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-center mt-4">
                        <a class="myds-link" href="{{ route('password.request') }}">
                            {{ __('Lupa Kata Laluan Anda?') }}
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</main>
@endsection
