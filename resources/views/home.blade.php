@extends('layouts.app')

@section('title', 'Papan Pemuka — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-8" role="main">
    <header class="mb-6">
        <h1 class="myds-heading-xl font-heading font-semibold">Papan Pemuka</h1>
        @if (session('status'))
            <div class="myds-alert myds-alert--success mt-4" role="status">
                <p class="myds-alert__body">{{ session('status') }}</p>
            </div>
        @endif
        <p class="myds-body-lg text-muted mt-2">Selamat kembali, {{ Auth::user()->name }}. Anda telah berjaya log masuk.</p>
    </header>

    <section aria-labelledby="quick-links-heading">
        <h2 id="quick-links-heading" class="myds-heading-lg font-heading mb-4">Pautan Pantas</h2>

        @php
            // Count quick stats; safe if tables are missing (returns 0)
            try {
                $inventoriesCount = \DB::table('inventories')->count();
            } catch (\Throwable $e) {
                $inventoriesCount = 0;
            }

            try {
                $vehiclesCount = \DB::table('vehicles')->count();
            } catch (\Throwable $e) {
                $vehiclesCount = 0;
            }
        @endphp

        <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
            {{-- Inventori Card --}}
            <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6">
                <div class="myds-card myds-card--clickable-container">
                    <div class="myds-card__body">
                        <h3 class="myds-card__title">Inventori</h3>
                        <p class="myds-card__description">
                            Urus dan selia semua item inventori dalam sistem.
                        </p>
                        <div class="myds-card__stat">
                            <span class="myds-card__stat-number">{{ $inventoriesCount }}</span>
                            <span class="myds-card__stat-label">Jumlah Item</span>
                        </div>
                    </div>
                    <div class="myds-card__footer">
                        <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--primary myds-btn--sm">
                            <span>Lihat Senarai</span>
                            <svg class="myds-icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                        </a>
                        <a href="{{ route('inventories.create') }}" class="myds-btn myds-btn--secondary myds-btn--sm">
                            <span>Cipta Baharu</span>
                            <svg class="myds-icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 5v14m-7-7h14"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Kenderaan Card --}}
            <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-6">
                <div class="myds-card myds-card--clickable-container">
                    <div class="myds-card__body">
                        <h3 class="myds-card__title">Kenderaan</h3>
                        <p class="myds-card__description">
                            Urus dan selia semua rekod kenderaan dalam sistem.
                        </p>
                        <div class="myds-card__stat">
                            <span class="myds-card__stat-number">{{ $vehiclesCount }}</span>
                            <span class="myds-card__stat-label">Jumlah Kenderaan</span>
                        </div>
                    </div>
                    <div class="myds-card__footer">
                        <a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--primary myds-btn--sm">
                            <span>Lihat Senarai</span>
                            <svg class="myds-icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                        </a>
                        <a href="{{ route('vehicles.create') }}" class="myds-btn myds-btn--secondary myds-btn--sm">
                            <span>Cipta Baharu</span>
                            <svg class="myds-icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 5v14m-7-7h14"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Excel Management Card --}}
            @auth
            <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-12">
                <div class="myds-card">
                    <div class="myds-card__body">
                        <h3 class="myds-card__title">Pengurusan Data Excel</h3>
                        <p class="myds-card__description">
                            Import data inventori dari fail Excel atau muat turun templat untuk kegunaan luar talian.
                        </p>
                    </div>
                    <div class="myds-card__footer">
                        <a href="{{ route('excel.inventory.form') }}" class="myds-btn myds-btn--tertiary myds-btn--sm">
                            <svg class="myds-icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M4 14.8V9.2a.8.8 0 01.8-.8h4.4a.8.8 0 01.8.8v5.6a.8.8 0 01-.8.8H4.8a.8.8 0 01-.8-.8zm0 0l5.2-3.2m-5.2 0l5.2 3.2m9.6-3.2v5.6a.8.8 0 01-.8.8H14a.8.8 0 01-.8-.8V9.2c0-.44.36-.8.8-.8h4.4c.44 0 .8.36.8.8zm0 0l-5.2 3.2m5.2 0l-5.2-3.2"/></svg>
                            <span>Import Inventori (Excel)</span>
                        </a>
                        <a href="{{ route('excel.inventory.export') }}" class="myds-btn myds-btn--tertiary myds-btn--sm myds-btn--outline">
                            <svg class="myds-icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 15l-4-4h3V4h2v7h3l-4 4zM4 17v2h16v-2H4z"/></svg>
                            <span>Muat Turun Templat</span>
                        </a>
                    </div>
                </div>
            </div>
            @endauth
        </div>
    </section>
</main>
@endsection
