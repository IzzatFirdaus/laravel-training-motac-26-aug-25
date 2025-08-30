@extends('layouts.app')

@section('title', $title ?? config('app.name'))

@section('content')
{{-- Skip link for keyboard users --}}
<a class="myds-skip-link sr-only focus:not-sr-only" href="#main-content">Langkau ke kandungan utama</a>

{{-- Phase banner / announce bar (MyGOVEA / MYDS style) --}}
<div id="phase-banner" class="phase-banner bg-surface border-bottom" role="status" aria-live="polite">
  <div class="myds-container py-2 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-3">
      <span class="myds-badge myds-badge--primary" aria-hidden="true">Beta</span>
      <div class="myds-body-sm myds-text--muted">
        Perkhidmatan ini berada dalam fasa Beta.
        <a href="{{ url('/feedback') }}" class="text-primary text-decoration-underline ms-1">Beri maklum balas</a>
      </div>
    </div>

    <div class="myds-body-xs myds-text--muted" aria-hidden="false">
      Terkini: {{ \Carbon\Carbon::now()->format('j M Y') }}
    </div>
  </div>
</div>

{{-- Page layout: responsive two-column with accessible sidebar --}}
<div class="myds-container py-4">
  <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile gap-4">

    {{-- Mobile: sidebar toggle button (visible on small screens) --}}
    <div class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-0 d-md-none mb-2">
      <button id="admin-sidebar-toggle" class="myds-btn myds-btn--tertiary" type="button"
              aria-controls="admin-sidebar" aria-expanded="false" aria-label="Togol navigasi pentadbiran">
        <i class="bi bi-list" aria-hidden="true"></i>
        <span class="visually-hidden">Togol navigasi pentadbiran</span>
      </button>
    </div>

    {{-- Sidebar: hidden on small screens, visible on md+ --}}
    <aside id="admin-sidebar" role="navigation" aria-label="Navigasi Pentadbiran"
           class="mobile:col-span-4 tablet:col-span-4 desktop:col-span-2 myds-sidebar p-3 bg-surface border rounded d-none d-md-block">
      @include('admin.includes.sidebar')
    </aside>

    {{-- Main content --}}
    <main id="main-content" class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-10" role="main" tabindex="-1" aria-labelledby="admin-main-heading">
      {{-- Page heading for assistive tech; individual pages can override via section 'admin.header' --}}
      <header class="mb-4">
        @hasSection('admin.header')
          @yield('admin.header')
        @else
          <h1 id="admin-main-heading" class="myds-heading-md font-heading font-semibold">{{ $title ?? 'Pentadbiran' }}</h1>
        @endif
      </header>

      <section aria-label="Kandungan pentadbiran">
        @yield('admin.content')
      </section>
    </main>
  </div>
</div>

{{-- Footer is provided by layouts.app via includes/footer --}}
@push('scripts')
{{-- Admin layout JS moved to resources/js/features/admin-layout.js and is bundled into app.js via @vite in layouts.app --}}
@endpush

@stack('scripts')
@endsection
