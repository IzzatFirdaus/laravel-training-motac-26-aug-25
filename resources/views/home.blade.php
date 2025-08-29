@extends('layouts.app')

@section('title', 'Papan Pemuka — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')

<main id="main-content" class="myds-container py-8" role="main" tabindex="-1" aria-labelledby="dashboard-heading">
    <header class="mb-6">
        <h1 id="dashboard-heading" class="myds-heading-xl font-heading font-semibold">Papan Pemuka</h1>
        @if (session('status'))
            <div class="myds-alert myds-alert--success mt-4" role="status" aria-live="polite">
                <p class="myds-body-md mb-0">{{ session('status') }}</p>
            </div>
        @endif
        <p class="myds-body-lg text-muted mt-2">
            Selamat kembali, <span class="font-medium">{{ Auth::user()?->name ?? __('Pengguna') }}</span>.
            <span class="sr-only">Anda telah berjaya log masuk.</span>
        </p>
    </header>

    <section aria-labelledby="dashboard-links-heading" class="mb-6">
        <h2 id="dashboard-links-heading" class="sr-only">Akses Pantas</h2>
        @php
            try { $inventoriesCount = \App\Models\Inventory::count(); } catch (\Throwable $e) { $inventoriesCount = 0; }
            try { $vehiclesCount = \App\Models\Vehicle::count(); } catch (\Throwable $e) { $vehiclesCount = 0; }
            try { $usersCount = \App\Models\User::count(); } catch (\Throwable $e) { $usersCount = 0; }
            try { $applicationsCount = \App\Models\Application::count(); } catch (\Throwable $e) { $applicationsCount = 0; }
        @endphp
        <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile gap-4">
            {{-- Inventori Card --}}
            <article class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6 myds-card myds-card--clickable" role="link" tabindex="0" aria-label="Inventori" data-href="{{ route('inventories.index') }}">
                <div class="myds-card__body">
                    <div class="me-3 p-2 bg-primary text-white rounded d-inline-block mb-2">
                        <svg width="24" height="24" aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
                    </div>
                    <h3 class="myds-card__title">Inventori</h3>
                    <p class="myds-card__description myds-body-sm text-muted">Urus dan selia semua item inventori dalam sistem.</p>
                    <div class="myds-card__stat">
                        <span class="myds-card__stat-number">{{ number_format($inventoriesCount) }}</span>
                        <span class="myds-card__stat-label myds-body-xs text-muted">Jumlah Item</span>
                    </div>
                </div>
                <div class="myds-card__footer d-flex gap-2">
                    <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--primary myds-btn--sm" aria-label="Lihat senarai inventori">
                        <span>Lihat Senarai</span>
                    </a>
                    @can('create', App\Models\Inventory::class)
                        <a href="{{ route('inventories.create') }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Cipta inventori baru">
                            <span>Cipta Baharu</span>
                        </a>
                    @endcan
                </div>
            </article>
            {{-- Kenderaan Card --}}
            <article class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6 myds-card myds-card--clickable" role="link" tabindex="0" aria-label="Kenderaan" data-href="{{ route('vehicles.index') }}">
                <div class="myds-card__body">
                    <div class="me-3 p-2 bg-success text-white rounded d-inline-block mb-2">
                        <svg width="24" height="24" aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M5 17h14" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
                    </div>
                    <h3 class="myds-card__title">Kenderaan</h3>
                    <p class="myds-card__description myds-body-sm text-muted">Urus dan selia semua rekod kenderaan dalam sistem.</p>
                    <div class="myds-card__stat">
                        <span class="myds-card__stat-number">{{ number_format($vehiclesCount) }}</span>
                        <span class="myds-card__stat-label myds-body-xs text-muted">Jumlah Kenderaan</span>
                    </div>
                </div>
                <div class="myds-card__footer d-flex gap-2">
                    <a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--primary myds-btn--sm" aria-label="Lihat senarai kenderaan">
                        <span>Lihat Senarai</span>
                    </a>
                    @can('create', App\Models\Vehicle::class)
                        <a href="{{ route('vehicles.create') }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Daftar kenderaan baru">
                            <span>Cipta Baharu</span>
                        </a>
                    @endcan
                </div>
            </article>
            {{-- Pengguna Card --}}
            <article class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6 myds-card myds-card--clickable" role="link" tabindex="0" aria-label="Pengguna" data-href="{{ route('users.index') }}">
                <div class="myds-card__body">
                    <div class="me-3 p-2 bg-warning text-white rounded d-inline-block mb-2">
                        <svg width="24" height="24" aria-hidden="true" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
                    </div>
                    <h3 class="myds-card__title">Pengguna</h3>
                    <p class="myds-card__description myds-body-sm text-muted">Urus akaun pengguna dan akses sistem dengan selamat.</p>
                    <div class="myds-card__stat">
                        <span class="myds-card__stat-number">{{ number_format($usersCount) }}</span>
                        <span class="myds-card__stat-label myds-body-xs text-muted">Jumlah Pengguna</span>
                    </div>
                </div>
                <div class="myds-card__footer d-flex gap-2">
                    <a href="{{ route('users.index') }}" class="myds-btn myds-btn--primary myds-btn--sm" aria-label="Lihat pengguna">
                        <span>Lihat Pengguna</span>
                    </a>
                </div>
            </article>
            {{-- Permohonan Card --}}
            <article class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6 myds-card myds-card--clickable" role="link" tabindex="0" aria-label="Permohonan" data-href="{{ route('applications.index') }}">
                <div class="myds-card__body">
                    <div class="me-3 p-2 bg-danger text-white rounded d-inline-block mb-2">
                        <svg width="24" height="24" aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
                    </div>
                    <h3 class="myds-card__title">Permohonan</h3>
                    <p class="myds-card__description myds-body-sm text-muted">Proses dan urus permohonan rasmi dengan sistematik.</p>
                    <div class="myds-card__stat">
                        <span class="myds-card__stat-number">{{ number_format($applicationsCount) }}</span>
                        <span class="myds-card__stat-label myds-body-xs text-muted">Jumlah Permohonan</span>
                    </div>
                </div>
                <div class="myds-card__footer d-flex gap-2">
                    <a href="{{ route('applications.index') }}" class="myds-btn myds-btn--primary myds-btn--sm" aria-label="Lihat permohonan">
                        <span>Lihat Permohonan</span>
                    </a>
                </div>
            </article>
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
@vite('resources/js/pages/home.js')
@endpush
