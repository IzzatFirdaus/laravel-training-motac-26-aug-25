@extends('layouts.app')

@section('title', 'Ralat Pelayan (500) — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-8" role="main" tabindex="-1" aria-labelledby="error-title">
  <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
    <div class="mobile:col-span-4 tablet:col-span-6 tablet:col-start-2 desktop:col-span-8 desktop:col-start-3">

      {{-- Error summary --}}
      <div class="text-center mb-6" role="region" aria-describedby="error-description">
        <div class="mb-4" aria-hidden="true">
          <svg width="80" height="80" class="mx-auto text-danger" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
            <line x1="15" y1="9" x2="9" y2="15" stroke="currentColor" stroke-width="2"/>
            <line x1="9" y1="9" x2="15" y2="15" stroke="currentColor" stroke-width="2"/>
          </svg>
        </div>

        <h1 id="error-title" class="myds-heading-md font-heading font-semibold text-danger mb-2">500</h1>
        <h2 class="myds-heading-sm font-heading mb-2">Ralat Pelayan</h2>

        <p id="error-description" class="myds-body-md text-muted mb-4">
          Maaf, berlaku masalah teknikal pada pelayan kami. Pasukan teknikal telah dimaklumkan dan sedang menyelesaikan masalah ini.
        </p>

        <p class="myds-body-sm text-muted mb-4" lang="en">
          <em>Sorry, there is a technical issue with our server. Our technical team has been notified.</em>
        </p>
      </div>

      {{-- Suggestions --}}
      <div class="bg-surface border rounded p-4 mb-6" role="region" aria-labelledby="actions-title">
        <h3 id="actions-title" class="myds-heading-xs font-heading font-medium mb-3">Apa yang boleh anda lakukan</h3>

        <ul class="list-unstyled" style="margin:0; padding-left:0; list-style:none;">
          <li class="d-flex align-items-start mb-2">
            <svg width="16" height="16" class="me-2 mt-1 text-primary flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <polyline points="9,11 12,14 22,4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="myds-body-sm">Tunggu beberapa minit dan cuba semula.</span>
          </li>

          <li class="d-flex align-items-start mb-2">
            <svg width="16" height="16" class="me-2 mt-1 text-primary flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <polyline points="9,11 12,14 22,4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="myds-body-sm">Tekan butang <strong>Cuba Semula</strong> untuk memuat semula halaman.</span>
          </li>

          <li class="d-flex align-items-start">
            <svg width="16" height="16" class="me-2 mt-1 text-primary flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <polyline points="9,11 12,14 22,4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="myds-body-sm">Kembali ke halaman utama dan cuba akses semula.</span>
          </li>
        </ul>
      </div>

      {{-- System status (visible to users) --}}
      <div class="myds-alert myds-alert--warning mb-6" role="status" aria-live="polite">
        <div class="d-flex align-items-start">
          <svg width="20" height="20" class="me-2 flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <line x1="12" y1="9" x2="12" y2="13" stroke="currentColor" stroke-width="2"/>
            <circle cx="12" cy="17" r="1" fill="currentColor"/>
          </svg>

          <div>
            <h4 class="myds-body-md font-medium mb-1">Status Sistem</h4>
            <p class="myds-body-sm mb-0 text-muted">Sistem sedang mengalami gangguan sementara. Kami mohon maaf atas kesulitan ini.</p>
          </div>
        </div>
      </div>

      {{-- Primary actions --}}
      <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mb-6" role="group" aria-label="Tindakan">
        <a href="{{ url('/') }}" class="myds-btn myds-btn--primary" aria-label="Pergi ke Laman Utama">
          <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <polyline points="9,22 9,12 15,12 15,22" stroke="currentColor" stroke-width="2"/>
          </svg>
          Laman Utama
        </a>

        <button type="button" onclick="location.reload()" class="myds-btn myds-btn--secondary" aria-label="Muat semula halaman">
          <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <polyline points="23,4 23,10 17,10" stroke="currentColor" stroke-width="2"/>
            <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" stroke="currentColor" stroke-width="2"/>
          </svg>
          Cuba Semula
        </button>

        <button type="button" onclick="history.back()" class="myds-btn myds-btn--tertiary" aria-label="Kembali">
          <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <polyline points="15,18 9,12 15,6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Kembali
        </button>
      </div>

      {{-- Technical info (for transparency) --}}
      <div class="mt-6 p-4 bg-muted rounded" role="contentinfo" aria-label="Maklumat teknikal ringkas">
        <h4 class="myds-heading-xs font-heading font-medium mb-2">Bantuan Teknikal</h4>
        <p class="myds-body-sm text-muted mb-3">Jika masalah berterusan, sila hubungi pasukan sokongan teknikal kami dan sertakan maklumat berikut.</p>

        <div class="d-flex flex-wrap gap-3">
          <div class="text-muted"><strong>Kod Ralat:</strong> <span class="myds-body-sm">500</span></div>
          <div class="text-muted"><strong>Masa:</strong> <span class="myds-body-sm">{{ now()->format('d/m/Y H:i:s') }}</span></div>
          <div class="text-muted"><strong>Rujukan:</strong> <span class="myds-body-sm">{{ substr(md5(now()->timestamp), 0, 8) }}</span></div>
          <div class="text-muted"><strong>URL:</strong> <span class="myds-body-sm">{{ request()->fullUrl() }}</span></div>
        </div>

        <div class="mt-3">
          @if(config('app.support_email'))
            <a href="mailto:{{ config('app.support_email') }}?subject=Support%20request%20(500)%20{{ urlencode(request()->path()) }}" class="myds-btn myds-btn--secondary" aria-label="Hubungi sokongan">Hubungi Sokongan</a>
          @endif
          <a href="{{ url('/status') }}" class="myds-btn myds-btn--tertiary" aria-label="Semak status sistem">Semak Status Sistem</a>
        </div>
      </div>

    </div>
  </div>
</main>
@endsection
