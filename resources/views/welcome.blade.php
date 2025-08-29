{{-- Welcome page following MYDS and MyGOVEA principles --}}
@extends('layouts.app')

@section('title', 'Selamat Datang — ' . config('app.name', 'Sistem Kerajaan Malaysia'))

@section('content')
<main id="main-content" class="myds-container py-6" role="main" tabindex="-1" aria-labelledby="welcome-heading">
    {{-- Hero Section --}}
    <section class="bg-primary text-white py-6 rounded mb-5" aria-labelledby="hero-heading">
        <div class="text-center myds-container">
            <div class="mb-3">
                @if(file_exists(public_path('images/gov-logo.png')))
                    <img src="{{ asset('images/gov-logo.png') }}" alt="{{ config('app.name') }} logo" width="72" height="72" class="mx-auto d-block" />
                @endif
            </div>

            <h1 id="hero-heading" class="font-heading font-semibold myds-heading-lg mb-3">
                Selamat Datang ke {{ config('app.name', 'Sistem Kerajaan') }}
            </h1>

            <p class="myds-body-md mb-4 mx-auto" style="max-width:720px;">
                Sistem pengurusan inventori dan kenderaan yang dibangunkan mengikut prinsip MyGOVEA dan Malaysia Government Design System (MYDS).
            </p>

            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--secondary" aria-label="Mula gunakan sistem">
                    <svg width="16" height="16" class="me-2" aria-hidden="true" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM4 9h16" stroke="currentColor" stroke-width="1.6" fill="none"/></svg>
                    Mula Guna Sistem
                </a>

                @guest
                    <a href="{{ route('login') }}" class="myds-btn myds-btn--tertiary" aria-label="Log masuk">
                        Log Masuk
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section aria-labelledby="features-heading" class="mb-5">
        <div class="text-center mb-4">
            <h2 id="features-heading" class="font-heading font-semibold myds-heading-md mb-2">Ciri-ciri Utama Sistem</h2>
            <p class="myds-body-sm text-muted mb-0">Sistem ini dibangunkan mengikut prinsip berpaksikan rakyat dan standard MYDS.</p>
        </div>

        <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile gap-4">
            {{-- Inventory Management --}}
            <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6">
                <div class="bg-surface p-4 rounded border h-100">
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3 p-2 bg-primary text-white rounded">
                            <svg width="24" height="24" aria-hidden="true" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
                        </div>
                        <div>
                            <h3 class="font-heading font-semibold h6 mb-1">Pengurusan Inventori</h3>
                            <p class="text-muted small mb-2">Urus inventori kerajaan dengan sistem yang mudah dan sistematik.</p>
                            <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Lihat inventori">Lihat Inventori</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Vehicle Management --}}
            <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6">
                <div class="bg-surface p-4 rounded border h-100">
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3 p-2 bg-success text-white rounded">
                            <svg width="24" height="24" aria-hidden="true" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 17h14" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
                        </div>
                        <div>
                            <h3 class="font-heading font-semibold h6 mb-1">Pengurusan Kenderaan</h3>
                            <p class="text-muted small mb-2">Pantau dan urus kenderaan kerajaan dengan komprehensif.</p>
                            <a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Lihat kenderaan">Lihat Kenderaan</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Users --}}
            <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6">
                <div class="bg-surface p-4 rounded border h-100">
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3 p-2 bg-warning text-white rounded">
                            <svg width="24" height="24" aria-hidden="true" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
                        </div>
                        <div>
                            <h3 class="font-heading font-semibold h6 mb-1">Pengurusan Pengguna</h3>
                            <p class="text-muted small mb-2">Urus akaun pengguna dan akses sistem dengan selamat.</p>
                            <a href="{{ route('users.index') }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Lihat pengguna">Lihat Pengguna</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Applications --}}
            <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6">
                <div class="bg-surface p-4 rounded border h-100">
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3 p-2 bg-danger text-white rounded">
                            <svg width="24" height="24" aria-hidden="true" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
                        </div>
                        <div>
                            <h3 class="font-heading font-semibold h6 mb-1">Permohonan</h3>
                            <p class="text-muted small mb-2">Proses dan urus permohonan rasmi dengan sistematik.</p>
                            <a href="{{ route('applications.index') }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Lihat permohonan">Lihat Permohonan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MyGOVEA Principles --}}
    <section aria-labelledby="principles-heading" class="mb-5">
        <div class="text-center mb-4">
            <h2 id="principles-heading" class="font-heading font-semibold myds-heading-md mb-2">Prinsip MyGOVEA</h2>
            <p class="myds-body-sm text-muted mb-0">Sistem ini dibangunkan berdasarkan prinsip reka bentuk MyGOVEA.</p>
        </div>

        <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile gap-3">
            <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-4 text-center p-3">
                <div class="mx-auto mb-2 icon-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width:56px;height:56px;border-radius:9999px;">
                    <svg width="24" height="24" aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.5"/></svg>
                </div>
                <h3 class="font-heading font-semibold h6 mb-1">Berpaksikan Rakyat</h3>
                <p class="text-muted small mb-0">Mengutamakan keperluan pengguna sebagai fokus utama.</p>
            </div>

            <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-4 text-center p-3">
                <div class="mx-auto mb-2 icon-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width:56px;height:56px;border-radius:9999px;">
                    <svg width="24" height="24" aria-hidden="true" viewBox="0 0 24 24" fill="none"><polyline points="22,12 18,12 15,21 9,3 6,12 2,12" stroke="currentColor" stroke-width="1.5"/></svg>
                </div>
                <h3 class="font-heading font-semibold h6 mb-1">Berpacukan Data</h3>
                <p class="text-muted small mb-0">Menggunakan data dengan efisien untuk keputusan yang tepat.</p>
            </div>

            <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-4 text-center p-3">
                <div class="mx-auto mb-2 icon-circle bg-warning text-white d-inline-flex align-items-center justify-content-center" style="width:56px;height:56px;border-radius:9999px;">
                    <svg width="24" height="24" aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M7 2H5a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h2" stroke="currentColor" stroke-width="1.5"/></svg>
                </div>
                <h3 class="font-heading font-semibold h6 mb-1">Antara Muka Minimalis</h3>
                <p class="text-muted small mb-0">Reka bentuk yang mudah difahami dan digunakan.</p>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
@vite('resources/js/pages/welcome.js')
@endpush
