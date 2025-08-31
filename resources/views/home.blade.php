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

        <p class="myds-body-lg myds-text--muted mt-2">
            Selamat kembali,
            <span class="font-medium">{{ e(Auth::user()?->name ?? __('Pengguna')) }}</span>.
            <span class="sr-only">Anda telah berjaya log masuk.</span>
        </p>
    </header>

    @php
        // Counts are provided by the controller: $inventoriesCount, $vehiclesCount, $usersCount, $applicationsCount
        $inventoriesCount = $inventoriesCount ?? 0;
        $vehiclesCount = $vehiclesCount ?? 0;
        $usersCount = $usersCount ?? 0;
        $applicationsCount = $applicationsCount ?? 0;
    @endphp

    <section aria-labelledby="dashboard-cards-heading" class="mb-6">
        <h2 id="dashboard-cards-heading" class="sr-only">Akses Pantas</h2>

        <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile gap-4">
            {{-- Inventori --}}
            <article class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6 myds-card myds-card--clickable" data-href="{{ route('inventories.index') }}" role="group" aria-labelledby="card-inventori-title">
                <div class="myds-card__body">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" aria-hidden="true" style="width:48px;height:48px;border-radius:8px;">
                            <i class="bi bi-box-seam" aria-hidden="true" style="font-size:20px"></i>
                        </div>

                        <div class="flex-grow-1">
                            <h3 id="card-inventori-title" class="myds-card__title myds-body-md mb-1">Inventori</h3>
                            <p class="myds-card__description myds-body-sm myds-text--muted mb-2">Urus dan selia semua item inventori dalam sistem.</p>

                            <div class="myds-card__stat" aria-hidden="false">
                                <span class="myds-card__stat-number" aria-live="polite">{{ number_format($inventoriesCount) }}</span>
                                <span class="myds-card__stat-label myds-body-xs myds-text--muted">Jumlah Item</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="myds-card__footer d-flex gap-2">
                    <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--primary myds-btn--sm" aria-label="Lihat senarai inventori">Lihat Senarai</a>
                        @if($canCreateInventory)
                        <a href="{{ route('inventories.create') }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Cipta inventori baru">Cipta Baharu</a>
                        @endif
                </div>
            </article>

            {{-- Kenderaan --}}
            <article class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6 myds-card myds-card--clickable" data-href="{{ route('vehicles.index') }}" role="group" aria-labelledby="card-kenderaan-title">
                <div class="myds-card__body">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-circle bg-success text-white d-inline-flex align-items-center justify-content-center" aria-hidden="true" style="width:48px;height:48px;border-radius:8px;">
                            <i class="bi bi-truck" aria-hidden="true" style="font-size:20px"></i>
                        </div>

                        <div class="flex-grow-1">
                            <h3 id="card-kenderaan-title" class="myds-card__title myds-body-md mb-1">Kenderaan</h3>
                            <p class="myds-card__description myds-body-sm myds-text--muted mb-2">Urus dan selia semua rekod kenderaan dalam sistem.</p>

                            <div class="myds-card__stat">
                                <span class="myds-card__stat-number" aria-live="polite">{{ number_format($vehiclesCount) }}</span>
                                <span class="myds-card__stat-label myds-body-xs myds-text--muted">Jumlah Kenderaan</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="myds-card__footer d-flex gap-2">
                    <a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--primary myds-btn--sm" aria-label="Lihat senarai kenderaan">Lihat Senarai</a>
                        @if($canCreateVehicle)
                        <a href="{{ route('vehicles.create') }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Daftar kenderaan baru">Daftar Baharu</a>
                        @endif
                </div>
            </article>

            {{-- Pengguna --}}
            <article class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6 myds-card myds-card--clickable" data-href="{{ route('users.index') }}" role="group" aria-labelledby="card-pengguna-title">
                <div class="myds-card__body">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-circle bg-warning text-white d-inline-flex align-items-center justify-content-center" aria-hidden="true" style="width:48px;height:48px;border-radius:8px;">
                            <i class="bi bi-people" aria-hidden="true" style="font-size:20px"></i>
                        </div>

                        <div class="flex-grow-1">
                            <h3 id="card-pengguna-title" class="myds-card__title myds-body-md mb-1">Pengguna</h3>
                            <p class="myds-card__description myds-body-sm myds-text--muted mb-2">Urus akaun pengguna dan akses sistem dengan selamat.</p>

                            <div class="myds-card__stat">
                                <span class="myds-card__stat-number" aria-live="polite">{{ number_format($usersCount) }}</span>
                                <span class="myds-card__stat-label myds-body-xs myds-text--muted">Jumlah Pengguna</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="myds-card__footer d-flex gap-2">
                    <a href="{{ route('users.index') }}" class="myds-btn myds-btn--primary myds-btn--sm" aria-label="Lihat pengguna">Lihat Pengguna</a>
                        @if($canCreateUser)
                        <a href="{{ route('users.create') }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Daftar pengguna baru">Daftar Baharu</a>
                        @endif
                </div>
            </article>

            {{-- Permohonan --}}
            <article class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6 myds-card myds-card--clickable" data-href="{{ route('applications.index') }}" role="group" aria-labelledby="card-permohonan-title">
                <div class="myds-card__body">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-circle bg-danger text-white d-inline-flex align-items-center justify-content-center" aria-hidden="true" style="width:48px;height:48px;border-radius:8px;">
                            <i class="bi bi-journal-text" aria-hidden="true" style="font-size:20px"></i>
                        </div>

                        <div class="flex-grow-1">
                            <h3 id="card-permohonan-title" class="myds-card__title myds-body-md mb-1">Permohonan</h3>
                            <p class="myds-card__description myds-body-sm myds-text--muted mb-2">Proses dan urus permohonan rasmi dengan sistematik.</p>

                            <div class="myds-card__stat">
                                <span class="myds-card__stat-number" aria-live="polite">{{ number_format($applicationsCount) }}</span>
                                <span class="myds-card__stat-label myds-body-xs myds-text--muted">Jumlah Permohonan</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="myds-card__footer d-flex gap-2">
                    <a href="{{ route('applications.index') }}" class="myds-btn myds-btn--primary myds-btn--sm" aria-label="Lihat permohonan">Lihat Permohonan</a>
                        @if($canCreateApplication)
                        <a href="{{ route('applications.create') }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Cipta permohonan baru">Cipta Baharu</a>
                        @endif
                </div>
            </article>
        </div>
    </section>

    {{-- MyGOVEA Principles --}}
    <section aria-labelledby="principles-heading" class="mb-5">
        <div class="text-center mb-4">
            <h2 id="principles-heading" class="myds-heading-md font-heading font-semibold mb-2">Prinsip MyGOVEA</h2>
            <p class="myds-body-sm myds-text--muted mb-0">Sistem ini dibangunkan berdasarkan prinsip reka bentuk MyGOVEA.</p>
        </div>

        <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile gap-3">
            <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-4 text-center p-3">
                <div class="mx-auto mb-2 icon-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" aria-hidden="true" style="width:56px;height:56px;border-radius:9999px;">
                    <i class="bi bi-people" aria-hidden="true" style="font-size:24px"></i>
                </div>
                <h3 class="font-heading font-semibold h6 mb-1">Berpaksikan Rakyat</h3>
                <p class="myds-text--muted small mb-0">Mengutamakan keperluan pengguna sebagai fokus utama.</p>
            </div>

            <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-4 text-center p-3">
                <div class="mx-auto mb-2 icon-circle bg-success text-white d-inline-flex align-items-center justify-content-center" aria-hidden="true" style="width:56px;height:56px;border-radius:9999px;">
                    <i class="bi bi-graph-up" aria-hidden="true" style="font-size:24px"></i>
                </div>
                <h3 class="font-heading font-semibold h6 mb-1">Berpacukan Data</h3>
                <p class="myds-text--muted small mb-0">Menggunakan data dengan efisien untuk keputusan yang tepat.</p>
            </div>

            <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-4 text-center p-3">
                <div class="mx-auto mb-2 icon-circle bg-warning text-white d-inline-flex align-items-center justify-content-center" aria-hidden="true" style="width:56px;height:56px;border-radius:9999px;">
                    <i class="bi bi-sliders" aria-hidden="true" style="font-size:24px"></i>
                </div>
                <h3 class="font-heading font-semibold h6 mb-1">Antara Muka Minimalis</h3>
                <p class="myds-text--muted small mb-0">Reka bentuk yang mudah difahami dan digunakan.</p>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
@vite('resources/js/pages/home.js')
@endpush
