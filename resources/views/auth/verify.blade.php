@extends('layouts.app')

@section('title', 'Sahkan Alamat E-mel — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container myds-container--page-centered" role="main">
    <div class="myds-auth-card">
        <div class="myds-auth-card__header">
            <h1 class="myds-auth-card__title">
                {{ __('Sahkan Alamat E-mel Anda') }}
            </h1>
        </div>

        <div class="myds-auth-card__body">
            @if (session('resent'))
                <div class="myds-alert myds-alert--success" role="alert">
                    <p class="myds-alert__body">{{ __('Pautan pengesahan baharu telah dihantar ke alamat e-mel anda.') }}</p>
                </div>
            @endif

            <p class="myds-body-md mb-4">
                {{ __('Sebelum meneruskan, sila semak e-mel anda untuk pautan pengesahan.') }}
            </p>
            <p class="myds-body-md">
                {{ __('Jika anda tidak menerima e-mel tersebut') }},
                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="myds-btn myds-btn--link p-0 m-0 align-baseline">{{ __('klik di sini untuk memohon sekali lagi') }}</button>.
                </form>
            </p>
        </div>
    </div>
</main>
@endsection
