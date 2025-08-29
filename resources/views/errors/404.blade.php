@extends('layouts.app')

@section('title', 'Halaman Tidak Dijumpai (404) — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-8" role="main" tabindex="-1" aria-labelledby="error-title">
  <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
    <div class="mobile:col-span-4 tablet:col-span-6 tablet:col-start-2 desktop:col-span-8 desktop:col-start-3">

      {{-- Error summary --}}
      <div class="text-center mb-6" role="region" aria-describedby="error-description">
        <div class="mb-4" aria-hidden="true">
          <svg width="80" height="80" class="mx-auto text-muted" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <line x1="12" y1="9" x2="12" y2="13" stroke="currentColor" stroke-width="2"/>
            <circle cx="12" cy="17" r="1" fill="currentColor"/>
          </svg>
        </div>

        <h1 id="error-title" class="myds-heading-md font-heading font-semibold text-primary mb-2">404</h1>
        <h2 class="myds-heading-sm font-heading mb-2">Halaman Tidak Dijumpai</h2>

        <p id="error-description" class="myds-body-md text-muted mb-4">
          Maaf, halaman yang anda cari tidak wujud atau telah dipindahkan.
          Sila semak alamat (URL) atau gunakan navigasi di laman untuk mencari maklumat yang anda perlukan.
        </p>

        <p class="myds-body-sm text-muted mb-4" lang="en">
          <em>Sorry, the page you are looking for does not exist or has been moved.</em>
        </p>
      </div>

      {{-- Recovery suggestions --}}
      <div class="bg-surface border rounded p-4 mb-6" role="region" aria-labelledby="help-title">
        <h3 id="help-title" class="myds-heading-xs font-heading font-medium mb-3">Apa yang boleh anda lakukan</h3>

        <ul class="list-unstyled" style="margin:0; padding-left:0; list-style:none;">
          <li class="d-flex align-items-start mb-2">
            <svg width="16" height="16" class="me-2 mt-1 text-primary flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <polyline points="9,11 12,14 22,4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="myds-body-sm">Semak ejaan alamat dan cuba lagi.</span>
          </li>

          <li class="d-flex align-items-start mb-2">
            <svg width="16" height="16" class="me-2 mt-1 text-primary flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <polyline points="9,11 12,14 22,4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="myds-body-sm">Gunakan menu navigasi atau fungsi carian di laman.</span>
          </li>

          <li class="d-flex align-items-start">
            <svg width="16" height="16" class="me-2 mt-1 text-primary flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <polyline points="9,11 12,14 22,4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="myds-body-sm">Kembali ke halaman sebelumnya menggunakan butang <strong>Kembali</strong> pada pelayar anda.</span>
          </li>
        </ul>
      </div>

      {{-- Primary actions --}}
      <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mb-6" role="group" aria-label="Tindakan pemulihan">
        <a href="{{ url('/') }}" class="myds-btn myds-btn--primary" aria-label="Pergi ke Laman Utama">
          <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <polyline points="9,22 9,12 15,12 15,22" stroke="currentColor" stroke-width="2"/>
          </svg>
          Laman Utama
        </a>

        <button type="button" onclick="history.back()" class="myds-btn myds-btn--secondary" aria-label="Kembali ke halaman sebelumnya">
          <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <polyline points="15,18 9,12 15,6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Kembali
        </button>

        @auth
          <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--tertiary" aria-label="Pergi ke Inventori">
            <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
              <line x1="9" y1="12" x2="15" y2="12" stroke="currentColor" stroke-width="2"/>
              <line x1="12" y1="9" x2="12" y2="15" stroke="currentColor" stroke-width="2"/>
            </svg>
            Inventori
          </a>
        @endauth
      </div>

      {{-- Contact & diagnostics --}}
      <div class="mt-6 p-4 bg-muted rounded" role="contentinfo" aria-label="Maklumat sokongan">
        <h4 class="myds-heading-xs font-heading font-medium mb-2">Perlu bantuan?</h4>
        <p class="myds-body-sm text-muted mb-2">
          Jika masalah berterusan, sila hubungi pasukan sokongan teknikal kami. Sertakan maklumat berikut untuk membantu kami mengenal pasti isu.
        </p>

        <div class="d-flex flex-wrap gap-3">
          <div class="text-muted">
            <strong>Kod Ralat:</strong> <span class="myds-body-sm">404</span>
          </div>

          <div class="text-muted">
            <strong>Tarikh & Masa:</strong>
            <span class="myds-body-sm">{{ now()->format('d/m/Y H:i:s') }}</span>
          </div>

          <div class="text-muted">
            <strong>URL:</strong>
            <span class="myds-body-sm">{{ request()->fullUrl() }}</span>
          </div>
        </div>

        <div class="mt-3">
          @if(config('app.support_email'))
            <a href="mailto:{{ config('app.support_email') }}?subject=Support%20request%20(404)%20{{ urlencode(request()->path()) }}" class="myds-btn myds-btn--secondary" aria-label="Hubungi sokongan">
              Hubungi Sokongan
            </a>
          @endif
          <a href="{{ url('/feedback') }}" class="myds-btn myds-btn--tertiary" aria-label="Hantar maklum balas mengenai halaman ini">Hantar Maklum Balas</a>
        </div>
      </div>

    </div>
  </div>
</main>
@endsection
