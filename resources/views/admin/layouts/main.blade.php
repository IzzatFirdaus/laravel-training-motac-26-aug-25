@extends('layouts.app')

@section('title', isset($title) ? $title : config('app.name'))

@section('content')
    {{-- Phase banner / announce bar (MyGOVEA / MYDS style) --}}
    <div id="phase-banner" class="phase-banner bg-surface border-bottom" role="status" aria-live="polite">
      <div class="myds-container py-2 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <span class="myds-badge myds-badge--primary">Beta</span>
          <div class="myds-text--muted">Perkhidmatan ini berada dalam fasa Beta. <a href="/feedback" class="text-primary underline">Beri maklum balas</a></div>
        </div>
        <div class="myds-text--muted small">Terkini: {{ \Carbon\Carbon::now()->format('j M Y') }}</div>
      </div>
    </div>

    <div class="container-fluid myds-container">
        <div class="row">
            <aside class="col-md-2 bg-light vh-100 p-3 d-none d-md-block">
                @include('admin.includes.sidebar')
            </aside>

            <main id="main-content" class="col-12 col-md-10 p-4" role="main" tabindex="-1">
                @yield('admin.content')
            </main>
        </div>
    </div>

    {{-- Footer is provided by layouts.app via admin.includes.footer --}}
    @stack('scripts')
@endsection
