{{-- Welcome page following MYDS and MyGOVEA principles --}}
@extends('layouts.app')

@section('title', 'Selamat Datang — ' . config('app.name', 'Sistem Kerajaan Malaysia'))

@section('content')
{{-- Hero Section following MYDS Design --}}
<section class="bg-primary text-white py-5 mb-5 rounded" aria-labelledby="hero-heading">
    <div class="text-center">
        <div class="mb-3">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/66/Coat_of_arms_of_Malaysia.svg/80px-Coat_of_arms_of_Malaysia.svg.png"
                 alt="Jata Negara Malaysia"
                 width="60"
                 height="60"
                 class="mb-3">
        </div>
        <h1 id="hero-heading" class="font-heading font-semibold mb-3" style="font-size: 2.5rem;">
            Selamat Datang ke {{ config('app.name', 'Sistem Kerajaan') }}
        </h1>
        <p class="lead mb-4 mx-auto" style="max-width: 600px;">
            Sistem pengurusan inventori dan kenderaan yang dibangunkan mengikut
            prinsip MyGOVEA dan Malaysia Government Design System (MYDS).
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--secondary bg-white text-primary border-white">
                <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM4 9h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Mula Guna Sistem
            </a>
            @guest
                <a href="{{ route('login') }}" class="myds-btn myds-btn--tertiary text-white border-white">
                    Log Masuk
                </a>
            @endguest
        </div>
    </div>
</section>

{{-- Features Section --}}
<section aria-labelledby="features-heading" class="mb-5">
    <div class="text-center mb-4">
        <h2 id="features-heading" class="font-heading font-semibold h3 mb-2">
            Ciri-ciri Utama Sistem
        </h2>
        <p class="text-muted">
            Sistem ini dibangunkan mengikut prinsip berpaksikan rakyat dan standard MYDS
        </p>
    </div>

    <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
        {{-- Inventory Management --}}
        <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6">
            <div class="bg-surface p-4 rounded border h-100">
                <div class="d-flex align-items-start mb-3">
                    <div class="me-3 p-2 bg-primary text-white rounded">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM4 9h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-heading font-semibold h6 mb-2">Pengurusan Inventori</h3>
                        <p class="text-muted small mb-3">
                            Urus inventori kerajaan dengan sistem yang mudah dan sistematik
                        </p>
                        <a href="{{ route('inventories.index') }}"
                           class="myds-btn myds-btn--secondary myds-btn--sm"
                           aria-label="Lihat senarai inventori">
                            Lihat Inventori
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Vehicle Management --}}
        <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6">
            <div class="bg-surface p-4 rounded border h-100">
                <div class="d-flex align-items-start mb-3">
                    <div class="me-3 p-2 bg-success text-white rounded">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" stroke="currentColor" stroke-width="2"/>
                            <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" stroke="currentColor" stroke-width="2"/>
                            <path d="M5 17h-2v-6l2-5h9l4 5h1a2 2 0 0 1 2 2v4h-2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-heading font-semibold h6 mb-2">Pengurusan Kenderaan</h3>
                        <p class="text-muted small mb-3">
                            Pantau dan urus kenderaan kerajaan dengan komprehensif
                        </p>
                        <a href="{{ route('vehicles.index') }}"
                           class="myds-btn myds-btn--secondary myds-btn--sm"
                           aria-label="Lihat senarai kenderaan">
                            Lihat Kenderaan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- User Management --}}
        <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6">
            <div class="bg-surface p-4 rounded border h-100">
                <div class="d-flex align-items-start mb-3">
                    <div class="me-3 p-2 bg-warning text-white rounded">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-heading font-semibold h6 mb-2">Pengurusan Pengguna</h3>
                        <p class="text-muted small mb-3">
                            Urus akaun pengguna dan akses sistem dengan selamat
                        </p>
                        <a href="{{ route('users.index') }}"
                           class="myds-btn myds-btn--secondary myds-btn--sm"
                           aria-label="Lihat senarai pengguna">
                            Lihat Pengguna
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Applications --}}
        <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6">
            <div class="bg-surface p-4 rounded border h-100">
                <div class="d-flex align-items-start mb-3">
                    <div class="me-3 p-2 bg-danger text-white rounded">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <polyline points="14,2 14,8 20,8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <line x1="16" y1="13" x2="8" y2="13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <line x1="16" y1="17" x2="8" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-heading font-semibold h6 mb-2">Permohonan</h3>
                        <p class="text-muted small mb-3">
                            Proses dan urus permohonan rasmi dengan sistematik
                        </p>
                        <a href="{{ route('applications.index') }}"
                           class="myds-btn myds-btn--secondary myds-btn--sm"
                           aria-label="Lihat senarai permohonan">
                            Lihat Permohonan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- MyGOVEA Principles Section --}}
<section aria-labelledby="principles-heading" class="mb-5">
    <div class="text-center mb-4">
        <h2 id="principles-heading" class="font-heading font-semibold h3 mb-2">
            Prinsip MyGOVEA
        </h2>
        <p class="text-muted">
            Sistem ini dibangunkan berdasarkan prinsip reka bentuk MyGOVEA
        </p>
    </div>

    <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
        <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-4">
            <div class="text-center p-3">
                <div class="mb-3">
                    <div class="mx-auto bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </div>
                </div>
                <h3 class="font-heading font-semibold h6 mb-2">Berpaksikan Rakyat</h3>
                <p class="text-muted small">
                    Mengutamakan keperluan dan kehendak pengguna sebagai fokus utama
                </p>
            </div>
        </div>

        <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-4">
            <div class="text-center p-3">
                <div class="mb-3">
                    <div class="mx-auto bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <polyline points="22,12 18,12 15,21 9,3 6,12 2,12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <h3 class="font-heading font-semibold h6 mb-2">Berpacukan Data</h3>
                <p class="text-muted small">
                    Menggunakan data dengan efisien untuk keputusan yang tepat
                </p>
            </div>
        </div>

        <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-4">
            <div class="text-center p-3">
                <div class="mb-3">
                    <div class="mx-auto bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M9 11H5a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M21 11h-4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7 2H5a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M19 2h-2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <h3 class="font-heading font-semibold h6 mb-2">Antara Muka Minimalis</h3>
                <p class="text-muted small">
                    Reka bentuk yang mudah difahami dan digunakan
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Statistics Section --}}
@auth
<section aria-labelledby="stats-heading" class="mb-5">
    <div class="bg-muted p-4 rounded">
        <div class="text-center mb-4">
            <h2 id="stats-heading" class="font-heading font-semibold h4 mb-2">
                Statistik Sistem
            </h2>
            <p class="text-muted">
                Maklumat ringkas tentang data dalam sistem
            </p>
        </div>

        <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
            <div class="mobile:col-span-2 tablet:col-span-2 desktop:col-span-3 text-center">
                <div class="bg-surface p-3 rounded">
                    <div class="h3 font-heading font-semibold text-primary mb-1">
                        {{ \App\Models\Inventory::count() }}
                    </div>
                    <div class="small text-muted">Item Inventori</div>
                </div>
            </div>

            <div class="mobile:col-span-2 tablet:col-span-2 desktop:col-span-3 text-center">
                <div class="bg-surface p-3 rounded">
                    <div class="h3 font-heading font-semibold text-success mb-1">
                        {{ \App\Models\User::count() }}
                    </div>
                    <div class="small text-muted">Pengguna Aktif</div>
                </div>
            </div>

            <div class="mobile:col-span-2 tablet:col-span-2 desktop:col-span-3 text-center">
                <div class="bg-surface p-3 rounded">
                    <div class="h3 font-heading font-semibold text-warning mb-1">
                        {{ \App\Models\Application::count() }}
                    </div>
                    <div class="small text-muted">Permohonan</div>
                </div>
            </div>

            <div class="mobile:col-span-2 tablet:col-span-2 desktop:col-span-3 text-center">
                <div class="bg-surface p-3 rounded">
                    <div class="h3 font-heading font-semibold text-danger mb-1">
                        {{ \App\Models\Vehicle::count() }}
                    </div>
                    <div class="small text-muted">Kenderaan</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endauth

{{-- Quick Actions --}}
@auth
<section aria-labelledby="actions-heading">
    <div class="text-center mb-4">
        <h2 id="actions-heading" class="font-heading font-semibold h4 mb-2">
            Tindakan Pantas
        </h2>
        <p class="text-muted">
            Akses pantas kepada fungsi yang kerap digunakan
        </p>
    </div>

    <div class="d-flex justify-content-center gap-3 flex-wrap">
        @can('create', App\Models\Inventory::class)
            <a href="{{ route('inventories.create') }}"
               class="myds-btn myds-btn--primary"
               aria-label="Cipta inventori baharu">
                <svg width="16" height="16" class="me-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Cipta Inventori
            </a>
        @endcan

        @can('create', App\Models\Vehicle::class)
            <a href="{{ route('vehicles.create') }}"
               class="myds-btn myds-btn--secondary"
               aria-label="Daftar kenderaan baharu">
                <svg width="16" height="16" class="me-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Daftar Kenderaan
            </a>
        @endcan

        <a href="{{ route('excel.inventory.form') }}"
           class="myds-btn myds-btn--tertiary"
           aria-label="Import atau export data">
            <svg width="16" height="16" class="me-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <polyline points="14,2 14,8 20,8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Import/Export
        </a>
    </div>
</section>
@endauth

@endsection

@push('scripts')
<script>
// Enhance accessibility with keyboard navigation for cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('[role="button"], .card[href]');
    cards.forEach(card => {
        if (!card.getAttribute('tabindex')) {
            card.setAttribute('tabindex', '0');
        }

        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                card.click();
            }
        });
    });
});
</script>
@endpush
