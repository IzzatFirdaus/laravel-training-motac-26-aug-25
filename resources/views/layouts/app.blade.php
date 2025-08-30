<!doctype html>
<!--
  Application shell updated for MyGOVEA & MYDS:
  - Bahasa Melayu (ms-MY) as primary language/content
  - Improved accessibility (landmarks, aria, keyboard support, live regions)
  - MYDS utility classes and semantic structure
  - Progressive enhancement for theme toggle and dynamic warehouse menu
-->
<html lang="ms-MY" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Security Headers for government compliance -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">

    <!-- Favicon: Government standard (use local asset) -->
    <link rel="icon" href="{{ asset('images/gov-logo.png') }}" type="image/png">

    {{-- Page title: individual views can set a `title` section. Default to app name. --}}
    <title>@yield('title', config('app.name', 'Sistem Kerajaan Malaysia'))</title>

    <!-- Fonts: MYDS Typography System -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Poppins:400,500,600|Inter:400,500" rel="stylesheet">

    <!-- Styles: MYDS + project CSS -->
    @vite(['resources/css/app.css', 'resources/sass/app.scss'])
</head>
<body class="theme-transition" data-page-shell>
    <div id="app">
        {{-- Skip link for keyboard users (visible on focus via CSS) --}}
        <a href="#main-content" class="skip-link myds-skip-link">Langkau ke kandungan utama</a>

        {{-- Global live regions for screen readers --}}
        <div id="global-flash" aria-live="polite" aria-atomic="true" class="visually-hidden">
            @if(session('status')){{ session('status') }}@endif
        </div>
        <div id="global-toast" aria-live="polite" aria-atomic="true" class="visually-hidden">
            @if(session('toast')){{ session('toast') }}@endif
        </div>

        {{-- Primary header / navigation --}}
        <header role="banner" class="myds-header">
            <nav class="navbar navbar-expand-md myds-nav" role="navigation" aria-label="Navigasi utama">
                <div class="myds-container d-flex align-items-center">
                    <a class="navbar-brand myds-brand d-flex align-items-center" href="{{ url('/') }}" aria-label="Laman utama {{ config('app.name') }}">
                        <img src="{{ asset('images/gov-logo.png') }}"
                             alt="{{ config('app.name') }} logo"
                             width="32"
                             height="32"
                             class="me-2" />
                        <span class="font-heading font-semibold">{{ config('app.name', 'Sistem Kerajaan') }}</span>
                    </a>

                    <button class="navbar-toggler" type="button"
                            data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" aria-expanded="false"
                            aria-label="Togol menu navigasi">
                        <span class="navbar-toggler-icon" aria-hidden="true"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        {{-- Left side primary navigation (citizen-centric) --}}
                        <ul class="navbar-nav me-auto" role="menubar" aria-label="Menu utama">
                            {{-- Inventori --}}
                            <li class="nav-item dropdown" role="none">
                                <a id="navInventories" class="nav-link dropdown-toggle" href="#" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true"
                                   aria-label="{{ __('nav.inventory') }} menu">
                                    {{ __('nav.inventory') }}
                                </a>

                                <div class="dropdown-menu myds-dropdown" aria-labelledby="navInventories" role="menu">
                                    <a class="dropdown-item {{ request()->routeIs('inventories.index') ? 'active' : '' }}"
                                       href="{{ route('inventories.index') }}" role="menuitem"
                                       @if(request()->routeIs('inventories.index')) aria-current="page" @endif>
                                        {{ __('nav.inventory_browse') }}
                                    </a>

                                    @auth
                                        @if(auth()->user()->hasRole('admin'))
                                            <a class="dropdown-item" href="{{ route('inventories.create') }}" role="menuitem">
                                                {{ __('nav.inventory_add') }}
                                            </a>
                                        @endif
                                    @endauth

                                    <a class="dropdown-item" href="{{ route('inventories.show', 1) }}" role="menuitem">
                                        {{ __('nav.inventory_read') }}
                                    </a>

                                    @auth
                                        @if(auth()->user()->hasRole('admin'))
                                            <a class="dropdown-item" href="{{ route('inventories.edit', 1) }}" role="menuitem">
                                                {{ __('nav.inventory_edit') }}
                                            </a>
                                        @endif
                                    @endauth

                                    <div class="dropdown-divider"></div>
                                    <div class="px-3 py-2 myds-text--muted small">Excel</div>
                                    <a class="dropdown-item" href="{{ route('excel.inventory.form') }}" role="menuitem">Import Inventori</a>
                                    <a class="dropdown-item" href="{{ route('excel.inventory.export') }}" role="menuitem">Muat Turun Templat</a>

                                    <div class="dropdown-divider"></div>

                                    @auth
                                        @if(auth()->user()->hasRole('admin'))
                                            <a class="dropdown-item" href="{{ route('inventories.deleted.index') }}" role="menuitem">Inventori Dipadam</a>
                                            <form method="POST" action="{{ route('inventories.destroy', 1) }}" class="px-3 myds-destroy-form" data-model="{{ __('nav.inventory') }}" data-myds-form>
                                                @csrf
                                                <button type="submit" class="dropdown-item myds-btn myds-btn--danger" role="menuitem" aria-label="{{ __('nav.inventory_delete') }}">{{ __('nav.inventory_delete') }}</button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </li>

                            {{-- Vehicles --}}
                            <li class="nav-item dropdown" role="none">
                                <a id="navVehicles" class="nav-link dropdown-toggle" href="#" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true"
                                   aria-label="{{ __('nav.vehicles') }} menu">
                                    {{ __('nav.vehicles') }}
                                </a>

                                <div class="dropdown-menu myds-dropdown" aria-labelledby="navVehicles" role="menu">
                                    <a class="dropdown-item {{ request()->routeIs('vehicles.index') ? 'active' : '' }}" href="{{ route('vehicles.index') }}" role="menuitem" @if(request()->routeIs('vehicles.index')) aria-current="page" @endif>
                                        {{ __('nav.vehicles_browse') }}
                                    </a>

                                    @auth
                                        @if(auth()->user()->hasRole('admin'))
                                            <a class="dropdown-item" href="{{ route('vehicles.create') }}" role="menuitem">{{ __('nav.vehicles_add') }}</a>
                                        @endif
                                    @endauth

                                    <a class="dropdown-item" href="{{ route('vehicles.show', 1) }}" role="menuitem">{{ __('nav.vehicles_read') }}</a>

                                    @auth
                                        @if(auth()->user()->hasRole('admin'))
                                            <a class="dropdown-item" href="{{ route('vehicles.edit', 1) }}" role="menuitem">{{ __('nav.vehicles_edit') }}</a>
                                            <form method="POST" action="{{ route('vehicles.destroy', 1) }}" class="px-3 myds-destroy-form" data-model="{{ __('nav.vehicles') }}" data-myds-form>
                                                @csrf
                                                <button type="submit" class="dropdown-item myds-btn myds-btn--danger" role="menuitem" aria-label="{{ __('nav.vehicles_delete') }}">{{ __('nav.vehicles_delete') }}</button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </li>

                            {{-- Users --}}
                            <li class="nav-item dropdown" role="none">
                                <a id="navUsers" class="nav-link dropdown-toggle" href="#" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true"
                                   aria-label="{{ __('nav.users') }} menu">
                                    {{ __('nav.users') }}
                                </a>

                                <div class="dropdown-menu myds-dropdown" aria-labelledby="navUsers" role="menu">
                                    <a class="dropdown-item {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}" role="menuitem" @if(request()->routeIs('users.index')) aria-current="page" @endif>
                                        {{ __('nav.users_browse') }}
                                    </a>

                                    @auth
                                        @if(auth()->user()->hasRole('admin'))
                                            @can('create', App\Models\User::class)
                                                <a class="dropdown-item" href="{{ route('users.create') }}" role="menuitem">{{ __('nav.users_add') }}</a>
                                            @endcan
                                            <a class="dropdown-item" href="{{ route('users.edit', 1) }}" role="menuitem">{{ __('nav.users_edit') }}</a>
                                            <form method="POST" action="{{ route('users.destroy', 1) }}" class="px-3 myds-destroy-form" data-model="{{ __('nav.users') }}" data-myds-form>
                                                @csrf
                                                <button type="submit" class="dropdown-item myds-btn myds-btn--danger" role="menuitem" aria-label="{{ __('nav.users_delete') }}">{{ __('nav.users_delete') }}</button>
                                            </form>
                                        @endif
                                    @endauth

                                    <a class="dropdown-item" href="{{ route('users.show', 1) }}" role="menuitem">{{ __('nav.users_read') }}</a>
                                </div>
                            </li>

                            {{-- Applications --}}
                            <li class="nav-item dropdown" role="none">
                                <a id="navApplications" class="nav-link dropdown-toggle" href="#" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-label="Permohonan menu">
                                    Permohonan
                                </a>

                                <div class="dropdown-menu myds-dropdown" aria-labelledby="navApplications" role="menu">
                                    <a class="dropdown-item {{ request()->routeIs('applications.index') ? 'active' : '' }}" href="{{ route('applications.index') }}" role="menuitem" @if(request()->routeIs('applications.index')) aria-current="page" @endif>Semak Permohonan</a>

                                    @auth
                                        @if(auth()->user()->hasRole('admin'))
                                            <a class="dropdown-item" href="{{ route('applications.create') }}" role="menuitem">Cipta Permohonan</a>
                                            <a class="dropdown-item" href="{{ route('applications.edit', 1) }}" role="menuitem">Edit Permohonan</a>
                                            <form method="POST" action="{{ route('applications.destroy', 1) }}" class="px-3 myds-destroy-form" data-model="Permohonan" data-myds-form>
                                                @csrf
                                                <button type="submit" class="dropdown-item myds-btn myds-btn--danger" role="menuitem" aria-label="Padam Permohonan">Padam Permohonan</button>
                                            </form>
                                        @endif
                                    @endauth

                                    <a class="dropdown-item" href="{{ route('applications.show', 1) }}" role="menuitem">Lihat Permohonan</a>
                                </div>
                            </li>

                            {{-- Warehouses dynamic list --}}
                            <li class="nav-item dropdown" role="none">
                                <a id="navWarehouses" class="nav-link dropdown-toggle" href="#" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true"
                                   aria-label="Gudang menu">
                                    Gudang
                                </a>

                                <div id="warehousesMenu" class="dropdown-menu myds-dropdown" aria-labelledby="navWarehouses" role="menu">
                                    <div class="px-3 py-2 myds-text--muted small">Gudang & Rak</div>
                                    <div id="warehouses-list" class="px-2" role="presentation">
                                        {{-- JS progressive enhancement will populate list items here --}}
                                        <div class="myds-text--muted small px-2">Memuatkan...</div>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('inventories.create') }}">Cipta Inventori</a>
                                </div>
                            </li>
                        </ul>

                        {{-- Right side: auth and utilities --}}
                        <ul class="navbar-nav ms-auto align-items-center">
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('nav.login') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('nav.register') }}</a>
                                    </li>
                                @endif
                            @else
                                {{-- Theme toggle --}}
                                <li class="nav-item d-flex align-items-center me-2">
                                    <button id="theme-toggle" class="myds-btn myds-btn--secondary" type="button"
                                            aria-pressed="false" aria-label="{{ __('nav.theme_toggle') }}" title="{{ __('nav.theme_toggle') }}">
                                        <span id="theme-toggle-icon" class="me-1" aria-hidden="true"><i class="bi"></i></span>
                                        <span class="visually-hidden">{{ __('nav.theme_toggle') }}</span>
                                    </button>
                                </li>

                                {{-- Notifications --}}
                                <li class="nav-item dropdown me-2">
                                    @php
                                        $unread = auth()->user()->unreadNotifications ?? collect();
                                        $unreadCount = $unread->count();
                                    @endphp

                                    <a id="navNotifications" class="nav-link position-relative" href="#" role="button"
                                       data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-label="Pemberitahuan">
                                        <i class="bi bi-bell" aria-hidden="true"></i>
                                        @if($unreadCount)
                                            <span class="myds-badge myds-badge--danger rounded-pill position-absolute notification-count-badge" aria-hidden="true">{{ $unreadCount }}</span>
                                            <span class="visually-hidden">{{ $unreadCount }} pemberitahuan belum dibaca</span>
                                        @endif
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end myds-dropdown py-0 notifications-menu" aria-labelledby="navNotifications" role="menu">
                                        <div class="px-3 py-2 border-bottom">
                                            <strong>Pemberitahuan</strong>
                                            <span class="myds-text--muted small d-block">{{ $unreadCount }} belum dibaca</span>
                                        </div>

                                        @if($unreadCount === 0)
                                            <div class="px-3 py-3 myds-text--muted">Tiada pemberitahuan</div>
                                        @else
                                            <div class="list-group list-group-flush" role="list">
                                                @foreach($unread->take(10) as $note)
                                                    <a href="{{ url('/notifications/'.$note->id) }}" class="list-group-item list-group-item-action d-flex align-items-start" role="listitem">
                                                        <div class="me-2" aria-hidden="true"><i class="bi bi-bell"></i></div>
                                                        <div class="flex-fill">
                                                            <div class="small fw-semibold">{{ $note->data['message'] ?? '—' }}</div>
                                                            <div class="small myds-text--muted">{{ optional($note->created_at)->format('d/m/Y H:i') }}</div>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="dropdown-divider m-0"></div>
                                        <a class="dropdown-item text-center py-2" href="{{ url('/notifications') }}">Lihat semua pemberitahuan</a>
                                    </div>
                                </li>

                                {{-- User menu --}}
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre aria-label="Menu pengguna">
                                        {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" role="menu">
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="px-3" role="menuitem">
                                            @csrf
                                            <button type="submit" class="dropdown-item myds-btn myds-btn--tertiary" aria-label="{{ __('nav.logout') }}">{{ __('nav.logout') }}</button>
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        {{-- Main content area --}}
        <main id="main-content" class="py-4" role="main" tabindex="-1" aria-labelledby="page-heading">
            <div class="myds-container">
                @yield('content')
            </div>
        </main>

        {{-- Footer --}}
        <footer class="bg-surface border-top py-4 mt-5" role="contentinfo">
            <div class="myds-container">
                <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile gap-3">
                    <div class="mobile:col-span-4 tablet:col-span-6 desktop:col-span-8">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('images/gov-logo.png') }}" alt="{{ config('app.name') }} logo" width="24" height="24" class="me-2" />
                            <span class="font-heading font-semibold">{{ config('app.name', 'Sistem Kerajaan') }}</span>
                        </div>

                        <p class="myds-text--muted small mb-3">
                            Sistem pengurusan inventori dan kenderaan Kerajaan Malaysia.
                            Dibangunkan mengikut prinsip MyGOVEA dan Malaysia Government Design System (MYDS).
                        </p>

                        <div class="d-flex flex-wrap gap-3 mb-3">
                            <a href="#" class="myds-text--muted small">Dasar Privasi</a>
                            <a href="#" class="myds-text--muted small">Terma Penggunaan</a>
                            <a href="#" class="myds-text--muted small">Hubungi Kami</a>
                            <a href="#" class="myds-text--muted small">Bantuan</a>
                        </div>

                        <p class="myds-text--muted small mb-0">© {{ date('Y') }} Kerajaan Malaysia. Hak cipta terpelihara.</p>
                    </div>

                    <div class="mobile:col-span-4 tablet:col-span-2 desktop:col-span-4">
                        <h6 class="font-heading font-semibold mb-2">Hubungi Kami</h6>
                        <address class="myds-text--muted small mb-0">
                            <div>Jabatan Digital Negara</div>
                            <div>Putrajaya, Malaysia</div>
                            <div>Tel: 03-8000 8000</div>
                            <div>E-mel: <a href="mailto:info@jdn.gov.my">info@jdn.gov.my</a></div>
                        </address>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    {{-- Accessible confirmation fallback (progressive enhancement) --}}
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

    {{-- Inline progressive enhancement scripts:
         - Theme toggle with persistence (localStorage)
         - Populate warehouses list (AJAX) for the header menu (graceful degradation)
         These are small helpers; main app JS is loaded via Vite below.
    --}}
    <script>
    (function () {
        'use strict';

        // Theme toggle: reflect saved preference or system preference
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
                // update icon (using Bootstrap icons or similar)
                if (toggleIcon) {
                    toggleIcon.innerHTML = pressed ? '<i class="bi bi-moon-fill" aria-hidden="true"></i>' : '<i class="bi bi-sun-fill" aria-hidden="true"></i>';
                }
            }
        }

        // initialize theme from localStorage or prefers-color-scheme
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
            // ignore storage errors
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

        // Populate warehouses list via AJAX (progressive enhancement)
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

        // Load warehouses on DOM ready (non-blocking)
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', loadWarehouses);
        } else {
            loadWarehouses();
        }

        // Make skip-link focus the main content for screen readers & keyboard users
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

    <!-- Main app JS (bundled via Vite) -->
    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>