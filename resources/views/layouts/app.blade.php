<!--
  MYDS & MyGOVEA compliant layout shell
  - Ensures grid, typography, ARIA, skip links, theme toggle, semantic tokens, accessibility
-->
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'ms') }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <link rel="icon" href="{{ asset('images/gov-logo.png') }}" type="image/png">
    <title>@yield('title', config('app.name', 'Sistem Kerajaan Malaysia'))</title>

    {{-- Fonts and compiled assets via Vite --}}
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Poppins:400,500,600|Inter:400,500" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="theme-transition" data-page-shell>
    <div id="app">
        {{-- Accessible skip link --}}
        <a href="#main-content" class="myds-skip-link visually-hidden-focusable">Langkau ke kandungan utama</a>

        {{-- Global live regions: content mirrored via data attributes for JS to read safely --}}
        <div id="global-flash" aria-live="polite" aria-atomic="true" class="visually-hidden" data-status="{{ session('status') ?? '' }}"></div>
        <div id="global-toast" aria-live="polite" aria-atomic="true" class="visually-hidden" data-toast="{{ session('toast') ?? '' }}"></div>

        {{-- Header / navigation --}}
        <header role="banner" class="myds-header" aria-label="Navigasi Laman">
            @include('admin.includes.navbar')
        </header>

        {{-- Main content --}}
        <main id="main-content" class="py-4" role="main" tabindex="-1" aria-labelledby="@yield('main-heading-id', 'page-heading')">
            <div class="myds-container">
                @yield('content')
            </div>
        </main>

        {{-- Footer --}}
        @include('admin.includes.footer')
    </div>

    {{-- Accessible confirm dialog template (hidden until used). Scripts should populate text & show it. --}}
    <div id="myds-confirm" aria-hidden="true" class="visually-hidden" aria-live="polite">
        <div role="dialog" aria-modal="true" aria-labelledby="myds-confirm-title" id="myds-confirm-dialog">
            <h2 id="myds-confirm-title" class="visually-hidden">Sahkan tindakan</h2>
            <p id="myds-confirm-message" class="visually-hidden">Anda pasti mahu meneruskan tindakan ini?</p>
            <div class="myds-confirm-actions visually-hidden">
                <button type="button" id="myds-confirm-yes">Ya</button>
                <button type="button" id="myds-confirm-no">Batal</button>
            </div>
        </div>
    </div>

    {{-- Page-specific scripts --}}
    @stack('scripts')
</body>
</html>
