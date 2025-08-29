@extends('layouts.app')

@section('title', 'Halaman Tidak Dijumpai (404) — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-8" role="main">
    {{-- MYDS Error Page following MyGOVEA citizen-centric principles --}}
    <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
        <div class="mobile:col-span-4 tablet:col-span-6 tablet:col-start-2 desktop:col-span-8 desktop:col-start-3">

            {{-- Error Icon and Status --}}
            <div class="text-center mb-6">
                <div class="mb-4">
                    <svg width="80" height="80" class="mx-auto text-muted" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <line x1="12" y1="9" x2="12" y2="13" stroke="currentColor" stroke-width="2"/>
                        <circle cx="12" cy="17" r="1" fill="currentColor"/>
                    </svg>
                </div>

                {{-- Error Code and Message (Bilingual per MyGOVEA) --}}
                <h1 class="myds-heading-md font-heading font-semibold text-primary mb-2">404</h1>
                <h2 class="myds-heading-sm font-heading mb-4">Halaman Tidak Dijumpai</h2>
                <p class="myds-body-md text-muted mb-6">
                    Maaf, halaman yang anda cari tidak wujud atau telah dipindahkan.
                    Sila semak URL atau gunakan navigasi untuk kembali ke halaman utama.
                </p>

                {{-- English translation for accessibility --}}
                <p class="myds-body-sm text-muted mb-4" lang="en">
                    <em>Sorry, the page you are looking for does not exist or has been moved.</em>
                </p>
            </div>

            {{-- Recovery Actions (MyGOVEA citizen-centric approach) --}}
            <div class="bg-surface border rounded p-4 mb-6">
                <h3 class="myds-heading-xs font-heading font-medium mb-3">Apa yang boleh anda lakukan:</h3>
                <ul class="list-unstyled space-y-2">
                    <li class="d-flex align-items-start">
                        <svg width="16" height="16" class="me-2 mt-1 text-primary flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <polyline points="9,11 12,14 22,4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="myds-body-sm">Semak ejaan URL dan cuba lagi</span>
                    </li>
                    <li class="d-flex align-items-start">
                        <svg width="16" height="16" class="me-2 mt-1 text-primary flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <polyline points="9,11 12,14 22,4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="myds-body-sm">Gunakan menu navigasi di atas</span>
                    </li>
                    <li class="d-flex align-items-start">
                        <svg width="16" height="16" class="me-2 mt-1 text-primary flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <polyline points="9,11 12,14 22,4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="myds-body-sm">Kembali ke halaman sebelumnya menggunakan butang browser</span>
                    </li>
                </ul>
            </div>

            {{-- Action Buttons (MYDS button components) --}}
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="{{ url('/') }}" class="myds-btn myds-btn--primary">
                    <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <polyline points="9,22 9,12 15,12 15,22" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    Laman Utama
                </a>

                <button onclick="history.back()" class="myds-btn myds-btn--secondary">
                    <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <polyline points="15,18 9,12 15,6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kembali
                </button>

                @auth
                <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--secondary">
                    <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                        <line x1="9" y1="12" x2="15" y2="12" stroke="currentColor" stroke-width="2"/>
                        <line x1="12" y1="9" x2="12" y2="15" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    Inventori
                </a>
                @endauth
            </div>

            {{-- Contact Information (MyGOVEA transparency principle) --}}
            <div class="mt-8 p-4 bg-muted rounded">
                <h4 class="myds-heading-xs font-heading font-medium mb-2">Perlu bantuan?</h4>
                <p class="myds-body-sm text-muted mb-2">
                    Jika masalah berterusan, sila hubungi pasukan sokongan teknikal kami.
                </p>
                <div class="d-flex flex-wrap gap-4 text-sm">
                    <span class="text-muted">
                        <svg width="14" height="14" class="me-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Kod Ralat: 404
                    </span>
                    <span class="text-muted">
                        <svg width="14" height="14" class="me-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                            <polyline points="12,6 12,12 16,14" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        {{ now()->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
