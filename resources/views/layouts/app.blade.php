
<!--
  MYDS & MyGOVEA compliant layout shell
  - Ensures grid, typography, ARIA, skip links, theme toggle, semantic tokens, accessibility
  - Follows all government design system and citizen-centric principles
-->
<!doctype html>
<html lang="ms-MY" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <link rel="icon" href="{{ asset('images/gov-logo.png') }}" type="image/png">
    <title>@yield('title', config('app.name', 'Sistem Kerajaan Malaysia'))</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Poppins:400,500,600|Inter:400,500" rel="stylesheet">
    {{-- Load compiled assets via Vite (CSS, SASS and JS). Keep ordering consistent with other layouts. --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="theme-transition" data-page-shell>
    <div id="app">
        <a href="#main-content" class="skip-link myds-skip-link">Langkau ke kandungan utama</a>
        <div id="global-flash" aria-live="polite" aria-atomic="true" class="visually-hidden">
            @if(session('status')){{ session('status') }}@endif
        </div>
        <div id="global-toast" aria-live="polite" aria-atomic="true" class="visually-hidden">
            @if(session('toast')){{ session('toast') }}@endif
        </div>
        <header role="banner" class="myds-header">
            @include('admin.includes.navbar')
        </header>
        <main id="main-content" class="py-4" role="main" tabindex="-1" aria-labelledby="page-heading">
            <div class="myds-container">
                @yield('content')
            </div>
        </main>
    {{-- Reuse admin footer include to keep layout fragments consistent across the app. --}}
    @include('admin.includes.footer')
    </div>
    <div id="myds-confirm" aria-hidden="true" class="visually-hidden">
        <div role="dialog" aria-modal="true" aria-labelledby="myds-confirm-title" id="myds-confirm-dialog">
            <h2 id="myds-confirm-title" class="visually-hidden">Sahkan tindakan</h2>
            <p id="myds-confirm-message" class="visually-hidden">Anda pasti mahu meneruskan tindakan ini?</p>
            <div class="myds-confirm-actions visually-hidden">
                <button type="button" id="myds-confirm-yes">Ya</button>
                <button type="button" id="myds-confirm-no">Batal</button>
            </div>
        </div>
    </div>
    <script>
    (function () {
        'use strict';
        const root = document.documentElement;
        const toggleBtn = document.getElementById('theme-toggle');
        const toggleIcon = document.getElementById('theme-toggle-icon');
        function setTheme(theme) {
            if (!theme) return;
            root.setAttribute('data-theme', theme);
            document.documentElement.dataset.theme = theme;
            if (toggleBtn) {
                const pressed = theme === 'dark';
                toggleBtn.setAttribute('aria-pressed', pressed ? 'true' : 'false');
                if (toggleIcon) {
                    toggleIcon.innerHTML = pressed ? '<i class="bi bi-moon-fill" aria-hidden="true"></i>' : '<i class="bi bi-sun-fill" aria-hidden="true"></i>';
                }
            }
        }
        try {
            const saved = localStorage.getItem('myds_theme');
            if (saved) {
                setTheme(saved);
            } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                setTheme('dark');
            } else {
                setTheme('light');
            }
        } catch (e) {
            setTheme('light');
        }
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                const current = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
                const next = current === 'dark' ? 'light' : 'dark';
                setTheme(next);
                try { localStorage.setItem('myds_theme', next); } catch (e) {}
            });
        }
        async function loadWarehouses() {
            const container = document.getElementById('warehouses-list');
            if (!container) return;
            try {
                const res = await fetch('{{ url('/api/warehouses') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) {
                    container.innerHTML = '<div class="myds-text--muted small px-2">Tidak dapat memuatkan.</div>';
                    return;
                }
                const items = await res.json();
                if (!items || !items.length) {
                    container.innerHTML = '<div class="myds-text--muted small px-2">Tiada gudang didaftarkan.</div>';
                    return;
                }
                container.innerHTML = '';
                items.forEach(w => {
                    const a = document.createElement('a');
                    a.className = 'dropdown-item';
                    a.href = '/warehouses/' + encodeURIComponent(w.id);
                    a.setAttribute('role', 'menuitem');
                    a.textContent = w.name || ('Gudang ' + w.id);
                    container.appendChild(a);
                });
            } catch (e) {
                container.innerHTML = '<div class="myds-text--muted small px-2">Ralat memuatkan gudang.</div>';
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', loadWarehouses);
        } else {
            loadWarehouses();
        }
        const skipLink = document.querySelector('.skip-link');
        if (skipLink) {
            skipLink.addEventListener('click', function (e) {
                const target = document.getElementById('main-content');
                if (target) {
                    target.setAttribute('tabindex', '-1');
                    target.focus({ preventScroll: true });
                }
            });
        }
    })();
    </script>
    @stack('scripts')
</body>
</html>
