<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    {{-- Use Vite to load compiled CSS/JS (handles dev server and production manifest) --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css'])
    @stack('styles')
</head>
<body class="theme-transition" data-page="{{ optional(Route::current())->getName() }}">
    {{-- Skip link (required by accessibility guidelines) --}}
    <a class="skip-link" href="#main-content">Langkau ke kandungan utama</a>

    {{-- Phase banner / announce bar (MyGOVEA / MYDS style) --}}
    <div id="phase-banner" class="phase-banner bg-surface border-bottom" role="status" aria-live="polite">
      <div class="myds-container py-2 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <span class="myds-badge myds-badge--primary">Beta</span>
          <div class="text-muted">Perkhidmatan ini berada dalam fasa Beta. <a href="/feedback" class="text-primary underline">Beri maklum balas</a></div>
        </div>
        <div class="text-muted small">Terkini: {{ \Carbon\Carbon::now()->format('j M Y') }}</div>
      </div>
    </div>

    @include('admin.includes.navbar')

    <div class="container-fluid myds-container">
        <div class="row">
            <aside class="col-md-2 bg-light vh-100 p-3 d-none d-md-block">
                @include('admin.includes.sidebar')
            </aside>

            <main id="main-content" class="col-12 col-md-10 p-4" role="main" tabindex="-1">
                @yield('content')
            </main>
        </div>
    </div>

    @include('admin.includes.footer')

    {{-- Hidden global toast placeholder (app.js picks up element with data-toast) --}}
    @if(session('status'))
      <div id="global-toast" data-toast="{{ session('status') }}" class="d-none" aria-hidden="true"></div>
    @endif

    {{-- Hidden logout form already included in navbar; add progressive enhancement JS --}}
    @stack('scripts')
</body>
</html>
