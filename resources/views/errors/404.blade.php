@extends('layouts.app')

@section('title', 'Halaman Tidak Dijumpai (404) — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-8" role="main" tabindex="-1" aria-labelledby="error-title">
  <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
    <div class="mobile:col-span-4 tablet:col-span-6 tablet:col-start-2 desktop:col-span-8 desktop:col-start-3">

      {{-- Error summary --}}
      <div class="text-center mb-6" role="region" aria-describedby="error-description">
        <div class="mb-4" aria-hidden="true">
          <i class="bi bi-exclamation-triangle myds-text--muted" style="font-size:80px;"></i>
        </div>

        <h1 id="error-title" class="myds-heading-md font-heading font-semibold text-primary mb-2">404</h1>
        <h2 class="myds-heading-sm font-heading mb-2">Halaman Tidak Dijumpai</h2>

        <p id="error-description" class="myds-body-md myds-text--muted mb-4">
          Maaf, halaman yang anda cari tidak wujud atau telah dipindahkan.
          Sila semak alamat (URL) atau gunakan navigasi di laman untuk mencari maklumat yang anda perlukan.
        </p>

        <p class="myds-body-sm myds-text--muted mb-4" lang="en">
          <em>Sorry, the page you are looking for does not exist or has been moved.</em>
        </p>
      </div>

      {{-- Recovery suggestions --}}
      <div class="myds-card bg-surface border rounded p-4 mb-6" role="region" aria-labelledby="help-title">
        <h3 id="help-title" class="myds-heading-xs font-heading font-medium mb-3">Apa yang boleh anda lakukan</h3>

        <ul class="myds-list myds-list--bare" style="margin:0; padding-left:0; list-style:none;">
          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-check2-circle me-2 mt-1 text-primary flex-shrink-0" aria-hidden="true"></i>
            <span class="myds-body-sm">Semak ejaan alamat dan cuba lagi.</span>
          </li>

          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-check2-circle me-2 mt-1 text-primary flex-shrink-0" aria-hidden="true"></i>
            <span class="myds-body-sm">Gunakan menu navigasi atau fungsi carian di laman.</span>
          </li>

          <li class="d-flex align-items-start">
            <i class="bi bi-check2-circle me-2 mt-1 text-primary flex-shrink-0" aria-hidden="true"></i>
            <span class="myds-body-sm">Kembali ke halaman sebelumnya menggunakan butang <strong>Kembali</strong> pada pelayar anda.</span>
          </li>
        </ul>
      </div>

      {{-- Primary actions --}}
      <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mb-6" role="group" aria-label="Tindakan pemulihan">
        <a href="{{ url('/') }}" class="myds-btn myds-btn--primary" aria-label="Pergi ke Laman Utama">
          <i class="bi bi-house-door me-2" aria-hidden="true"></i>
          Laman Utama
        </a>

        <button type="button" data-action="history-back" class="myds-btn myds-btn--secondary" aria-label="Kembali ke halaman sebelumnya">
          <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
          Kembali
        </button>

        @auth
          <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--tertiary" aria-label="Pergi ke Inventori">
            <i class="bi bi-box-seam me-2" aria-hidden="true"></i>
            Inventori
          </a>
        @endauth
      </div>

      {{-- Contact & diagnostics --}}
      <div class="mt-6 p-4 bg-muted rounded" role="contentinfo" aria-label="Maklumat sokongan">
        <h4 class="myds-heading-xs font-heading font-medium mb-2">Perlu bantuan?</h4>
        <p class="myds-body-sm myds-text--muted mb-2">
          Jika masalah berterusan, sila hubungi pasukan sokongan teknikal kami. Sertakan maklumat berikut untuk membantu kami mengenal pasti isu.
        </p>

        <div class="d-flex flex-wrap gap-3">
          <div class="myds-text--muted">
            <strong>Kod Ralat:</strong> <span class="myds-body-sm">404</span>
          </div>

          <div class="myds-text--muted">
            <strong>Tarikh & Masa:</strong>
            <span class="myds-body-sm">
              <time datetime="{{ now()->toIso8601String() }}">{{ now()->format('d/m/Y H:i:s') }}</time>
            </span>
          </div>

          <div class="myds-text--muted">
            <strong>URL:</strong>
            <span class="myds-body-sm">{{ e(request()->fullUrl()) }}</span>
          </div>
        </div>

        <div class="mt-3 d-flex gap-2 flex-wrap">
          @if(config('app.support_email'))
            <a href="mailto:{{ config('app.support_email') }}?subject={{ urlencode('Support request (404): ' . request()->path()) }}" class="myds-btn myds-btn--secondary" aria-label="Hubungi sokongan">
              Hubungi Sokongan
            </a>
          @endif
          <a href="{{ url('/feedback') }}" class="myds-btn myds-btn--tertiary" aria-label="Hantar maklum balas mengenai halaman ini">Hantar Maklum Balas</a>
        </div>
      </div>

    </div>
  </div>
</main>

@push('scripts')
<!-- Error page actions (history-back / reload) are handled globally by resources/js/components/action-buttons.js and resources/js/app.js -->
@endpush

@endsection
