@extends('layouts.app')

@section('title', 'Ralat Pelayan (500) — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-8" role="main">
    {{-- MYDS 500 Error Page following MyGOVEA citizen-centric principles --}}
    <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
        <div class="mobile:col-span-4 tablet:col-span-6 tablet:col-start-2 desktop:col-span-8 desktop:col-start-3">

            {{-- Error Icon and Status --}}
            <div class="text-center mb-6">
                <div class="mb-4">
                    <svg width="80" height="80" class="mx-auto text-danger" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                        <line x1="15" y1="9" x2="9" y2="15" stroke="currentColor" stroke-width="2"/>
                        <line x1="9" y1="9" x2="15" y2="15" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>

                {{-- Error Code and Message (Bilingual per MyGOVEA) --}}
                <h1 class="myds-heading-md font-heading font-semibold text-danger mb-2">500</h1>
                <h2 class="myds-heading-sm font-heading mb-4">Ralat Pelayan</h2>
                <p class="myds-body-md text-muted mb-6">
                    Maaf, berlaku masalah teknikal pada pelayan kami.
                    Pasukan teknikal telah dimaklumkan dan sedang menyelesaikan masalah ini.
                </p>

                {{-- English translation for accessibility --}}
                <p class="myds-body-sm text-muted mb-4" lang="en">
                    <em>Sorry, there is a technical issue with our server. Our technical team has been notified.</em>
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
                        <span class="myds-body-sm">Tunggu beberapa minit dan cuba semula</span>
                    </li>
                    <li class="d-flex align-items-start">
                        <svg width="16" height="16" class="me-2 mt-1 text-primary flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <polyline points="9,11 12,14 22,4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="myds-body-sm">Refresh halaman ini</span>
                    </li>
                    <li class="d-flex align-items-start">
                        <svg width="16" height="16" class="me-2 mt-1 text-primary flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <polyline points="9,11 12,14 22,4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="myds-body-sm">Kembali ke halaman utama dan cuba akses semula</span>
                    </li>
                </ul>
            </div>

            {{-- System Status Alert --}}
            <div class="alert alert-warning d-flex align-items-start mb-6" role="alert">
                <svg width="20" height="20" class="me-2 flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="12" y1="9" x2="12" y2="13" stroke="currentColor" stroke-width="2"/>
                    <circle cx="12" cy="17" r="1" fill="currentColor"/>
                </svg>
                <div>
                    <h4 class="alert-heading myds-body-md font-medium">Status Sistem</h4>
                    <p class="mb-0 myds-body-sm">
                        Sistem sedang mengalami gangguan sementara. Kami mohon maaf atas kesulitan ini.
                        Anggaran masa pemulihan: 15-30 minit.
                    </p>
                </div>
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

                <button onclick="location.reload()" class="myds-btn myds-btn--secondary">
                    <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <polyline points="23,4 23,10 17,10" stroke="currentColor" stroke-width="2"/>
                        <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    Cuba Semula
                </button>

                <button onclick="history.back()" class="myds-btn myds-btn--tertiary">
                    <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <polyline points="15,18 9,12 15,6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kembali
                </button>
            </div>

            {{-- Technical Support Information (MyGOVEA transparency principle) --}}
            <div class="mt-8 p-4 bg-muted rounded">
                <h4 class="myds-heading-xs font-heading font-medium mb-2">Bantuan Teknikal</h4>
                <p class="myds-body-sm text-muted mb-3">
                    Jika masalah berterusan, sila hubungi pasukan sokongan teknikal kami dengan maklumat berikut:
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-2">
                            <svg width="14" height="14" class="me-2 text-muted" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="myds-body-sm text-muted">Kod Ralat: 500</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <svg width="14" height="14" class="me-2 text-muted" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                <polyline points="12,6 12,12 16,14" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <span class="myds-body-sm text-muted">{{ now()->format('d/m/Y H:i:s') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-2">
                            <svg width="14" height="14" class="me-2 text-muted" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                                <polyline points="2,6 12,13 22,6" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <span class="myds-body-sm text-muted">Ref: {{ substr(md5(now()->timestamp), 0, 8) }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <svg width="14" height="14" class="me-2 text-muted" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke="currentColor" stroke-width="2"/>
                                <circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <span class="myds-body-sm text-muted">{{ request()->url() }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
