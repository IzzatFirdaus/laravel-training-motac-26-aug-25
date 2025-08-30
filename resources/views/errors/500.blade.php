@extends('layouts.app')

@section('title', 'Ralat Pelayan (500) — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-8" role="main" tabindex="-1" aria-labelledby="error-title">
  <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
    <div class="mobile:col-span-4 tablet:col-span-6 tablet:col-start-2 desktop:col-span-8 desktop:col-start-3">

      {{-- Error summary --}}
      <div class="text-center mb-6" role="region" aria-describedby="error-description">
        <div class="mb-4" aria-hidden="true">
          <i class="bi bi-x-octagon text-danger" style="font-size:80px;"></i>
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
            <i class="bi bi-check2-circle me-2 mt-1 text-primary flex-shrink-0" aria-hidden="true"></i>
            <span class="myds-body-sm">Tunggu beberapa minit dan cuba semula.</span>
          </li>

          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-check2-circle me-2 mt-1 text-primary flex-shrink-0" aria-hidden="true"></i>
            <span class="myds-body-sm">Tekan butang <strong>Cuba Semula</strong> untuk memuat semula halaman.</span>
          </li>

          <li class="d-flex align-items-start">
            <i class="bi bi-check2-circle me-2 mt-1 text-primary flex-shrink-0" aria-hidden="true"></i>
            <span class="myds-body-sm">Kembali ke halaman utama dan cuba akses semula.</span>
          </li>
        </ul>
      </div>

      {{-- System status (visible to users) --}}
      <div class="myds-alert myds-alert--warning mb-6" role="status" aria-live="polite">
        <div class="d-flex align-items-start">
          <i class="bi bi-exclamation-triangle me-2 flex-shrink-0" aria-hidden="true"></i>

          <div>
            <h4 class="myds-body-md font-medium mb-1">Status Sistem</h4>
            <p class="myds-body-sm mb-0 text-muted">Sistem sedang mengalami gangguan sementara. Kami mohon maaf atas kesulitan ini.</p>
          </div>
        </div>
      </div>

      {{-- Primary actions --}}
      <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mb-6" role="group" aria-label="Tindakan">
        <a href="{{ url('/') }}" class="myds-btn myds-btn--primary" aria-label="Pergi ke Laman Utama">
          <i class="bi bi-house-door me-2" aria-hidden="true"></i>
          Laman Utama
        </a>

  <button type="button" data-action="reload" class="myds-btn myds-btn--secondary" aria-label="Muat semula halaman">
          <i class="bi bi-arrow-repeat me-2" aria-hidden="true"></i>
          Cuba Semula
        </button>

  <button type="button" data-action="history-back" class="myds-btn myds-btn--tertiary" aria-label="Kembali">
          <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
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
