@extends('layouts.app')

@section('title', 'Sahkan Alamat E-mel — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container myds-container--page-centered py-6" role="main" tabindex="-1" aria-labelledby="verify-heading">
    <div class="myds-auth-card" role="region" aria-describedby="verify-description">
        <header class="myds-auth-card__header">
            <h1 id="verify-heading" class="myds-auth-card__title">Sahkan Alamat E-mel Anda</h1>
        </header>

        <div class="myds-auth-card__body">
            @if (session('resent'))
                <div class="myds-alert myds-alert--success mb-3" role="status" aria-live="polite">
                    Pautan pengesahan baharu telah dihantar ke alamat e-mel anda.
                </div>
            @endif

            <p id="verify-description" class="myds-body-md myds-text--muted mb-3">
                Sebelum meneruskan, sila semak e-mel anda untuk pautan pengesahan.
            </p>

            <p class="myds-body-md mb-0">
                Jika anda tidak menerima e-mel tersebut,
                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="myds-btn myds-btn--link p-0 align-baseline" aria-label="Mohon pautan pengesahan sekali lagi">klik di sini untuk memohon sekali lagi</button>
                    .
                </form>
            </p>
        </div>
    </div>
</main>
@endsection
