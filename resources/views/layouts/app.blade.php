<!doctype html>
<!-- Application UI content is presented in Bahasa Melayu (ms) per MyGOVEA requirement -->
<html lang="ms" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Security Headers for government compliance -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">

    <!-- Favicon: Malaysia Government standard -->
    <link rel="icon" href="https://laravel.com/img/favicon/favicon.ico" type="image/x-icon">

    {{-- Page title: individual views can set a `title` section. Default to app name. --}}
    <title>@yield('title', config('app.name', 'Sistem Kerajaan Malaysia'))</title>

    <!-- Fonts: MYDS Typography System -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <!-- Use Poppins for headings and Inter for body text per MYDS guidelines -->
    <link href="https://fonts.bunny.net/css?family=Poppins:400,500,600|Inter:400,500" rel="stylesheet">

    <!-- Styles: MYDS + Bootstrap hybrid approach -->
    @vite(['resources/css/app.css', 'resources/sass/app.scss'])
</head>
<body class="theme-transition">
    <div id="app">
    {{-- Accessibility: Skip to content link for keyboard users (MyGOVEA principle) --}}
    <a href="#main-content" class="skip-link">Langkau ke kandungan utama</a>

    {{-- Live region for status messages (ARIA accessibility) --}}
    <div id="global-flash" aria-live="polite" aria-atomic="true" class="sr-only">@if(session('status')){{ session('status') }}@endif</div>
    <div id="global-toast" data-toast="@if(session('toast')){{ session('toast') }}@endif" class="sr-only"></div>

    {{-- MYDS Navigation Header --}}
    <header role="banner">
        <nav class="navbar navbar-expand-md myds-nav" role="navigation" aria-label="Navigasi utama">
            <div class="myds-container">
                <a class="navbar-brand myds-brand d-flex align-items-center" href="{{ url('/') }}" aria-label="Laman utama {{ config('app.name') }}">
                {{-- Malaysian Government Logo: use local asset for offline and compliance --}}
                <img src="{{ asset('images/gov-logo.png') }}"
                    alt="{{ config('app.name') }} logo"
                    width="32"
                    height="32"
                    class="me-2" />
                    <span class="font-heading font-semibold">{{ config('app.name', 'Sistem Kerajaan') }}</span>
                </a>

                <button class="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent"
                        aria-expanded="false"
                        aria-label="Togol menu navigasi">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side: Primary navigation (MyGOVEA citizen-centric approach) -->
                    <ul class="navbar-nav me-auto" role="menubar">
                        <li class="nav-item dropdown">
                            <a id="navInventories" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-label="{{ __('nav.inventory') }} menu">
                                {{ __('nav.inventory') }}
                            </a>
                            <div class="dropdown-menu myds-dropdown" aria-labelledby="navInventories" role="menu">
                                <a class="dropdown-item {{ request()->routeIs('inventories.index') ? 'active' : '' }}" href="{{ route('inventories.index') }}" role="menuitem" @if(request()->routeIs('inventories.index')) aria-current="page" @endif>{{ __('nav.inventory_browse') }}</a>
                                @auth
                                @if(auth()->user()->hasRole('admin'))
                                <a class="dropdown-item" href="{{ route('inventories.create') }}" role="menuitem">{{ __('nav.inventory_add') }}</a>
                                @endif
                                @endauth
                                <a class="dropdown-item" href="{{ route('inventories.show', 1) }}" role="menuitem">{{ __('nav.inventory_read') }}</a>
                                @auth
                                @if(auth()->user()->hasRole('admin'))
                                <a class="dropdown-item" href="{{ route('inventories.edit', 1) }}" role="menuitem">{{ __('nav.inventory_edit') }}</a>
                                @endif
                                @endauth
                                <div class="dropdown-divider"></div>
                                <div class="px-3 py-2 text-muted small">Excel</div>
                                <a class="dropdown-item" href="{{ route('excel.inventory.form') }}" role="menuitem">Import Inventori</a>
                                <a class="dropdown-item" href="{{ route('excel.inventory.export') }}" role="menuitem">Muat Turun Templat</a>
                                <div class="dropdown-divider"></div>
                                @auth
                                    @if(auth()->user()->hasRole('admin'))
                                        <a class="dropdown-item" href="{{ route('inventories.deleted.index') }}" role="menuitem">Inventori Dipadam</a>
                                    @endif
                                @endauth
                                @auth
                                @if(auth()->user()->hasRole('admin'))
                                <form method="POST" action="{{ route('inventories.destroy', 1) }}" class="px-3 myds-destroy-form" data-model="{{ __('nav.inventory') }}" data-myds-form>
                                    @csrf
                                    <button type="submit" class="dropdown-item myds-btn myds-btn--danger" role="menuitem" aria-label="{{ __('nav.inventory_delete') }}">{{ __('nav.inventory_delete') }}</button>
                                </form>
                                @endif
                                @endauth
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a id="navVehicles" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-label="{{ __('nav.vehicles') }} menu">
                                {{ __('nav.vehicles') }}
                            </a>
                            <div class="dropdown-menu myds-dropdown" aria-labelledby="navVehicles" role="menu">
                                <a class="dropdown-item {{ request()->routeIs('vehicles.index') ? 'active' : '' }}" href="{{ route('vehicles.index') }}" role="menuitem" @if(request()->routeIs('vehicles.index')) aria-current="page" @endif>{{ __('nav.vehicles_browse') }}</a>
                                @auth
                                @if(auth()->user()->hasRole('admin'))
                                <a class="dropdown-item" href="{{ route('vehicles.create') }}" role="menuitem">{{ __('nav.vehicles_add') }}</a>
                                @endif
                                @endauth
                                <a class="dropdown-item" href="{{ route('vehicles.show', 1) }}" role="menuitem">{{ __('nav.vehicles_read') }}</a>
                                @auth
                                @if(auth()->user()->hasRole('admin'))
                                <a class="dropdown-item" href="{{ route('vehicles.edit', 1) }}" role="menuitem">{{ __('nav.vehicles_edit') }}</a>
                                @endif
                                @endauth
                                <div class="dropdown-divider"></div>
                                @auth
                                @if(auth()->user()->hasRole('admin'))
                                <form method="POST" action="{{ route('vehicles.destroy', 1) }}" class="px-3 myds-destroy-form" data-model="{{ __('nav.vehicles') }}" data-myds-form>
                                    @csrf
                                    <button type="submit" class="dropdown-item myds-btn myds-btn--danger" role="menuitem" aria-label="{{ __('nav.vehicles_delete') }}">{{ __('nav.vehicles_delete') }}</button>
                                </form>
                                @endif
                                @endauth
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a id="navUsers" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-label="{{ __('nav.users') }} menu">
                                {{ __('nav.users') }}
                            </a>
                            <div class="dropdown-menu myds-dropdown" aria-labelledby="navUsers" role="menu">
                                <a class="dropdown-item {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}" role="menuitem" @if(request()->routeIs('users.index')) aria-current="page" @endif>{{ __('nav.users_browse') }}</a>
                                @auth
                                @if(auth()->user()->hasRole('admin'))
                                @can('create', App\Models\User::class)
                                    <a class="dropdown-item" href="{{ route('users.create') }}" role="menuitem">{{ __('nav.users_add') }}</a>
                                @endcan
                                @endif
                                @endauth
                                <a class="dropdown-item" href="{{ route('users.show', 1) }}" role="menuitem">{{ __('nav.users_read') }}</a>
                                @auth
                                @if(auth()->user()->hasRole('admin'))
                                <a class="dropdown-item" href="{{ route('users.edit', 1) }}" role="menuitem">{{ __('nav.users_edit') }}</a>
                                @endif
                                @endauth
                                <div class="dropdown-divider"></div>
                                @auth
                                @if(auth()->user()->hasRole('admin'))
                                <form method="POST" action="{{ route('users.destroy', 1) }}" class="px-3 myds-destroy-form" data-model="{{ __('nav.users') }}" data-myds-form>
                                    @csrf
                                    <button type="submit" class="dropdown-item myds-btn myds-btn--danger" role="menuitem" aria-label="{{ __('nav.users_delete') }}">{{ __('nav.users_delete') }}</button>
                                </form>
                                @endif
                                @endauth
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a id="navApplications" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-label="Permohonan menu">
                                Permohonan
                            </a>
                            <div class="dropdown-menu myds-dropdown" aria-labelledby="navApplications" role="menu">
                                <a class="dropdown-item {{ request()->routeIs('applications.index') ? 'active' : '' }}" href="{{ route('applications.index') }}" role="menuitem" @if(request()->routeIs('applications.index')) aria-current="page" @endif>Semak Permohonan</a>
                                @auth
                                @if(auth()->user()->hasRole('admin'))
                                    <a class="dropdown-item" href="{{ route('applications.create') }}" role="menuitem">Cipta Permohonan</a>
                                @endif
                                @endauth
                                <a class="dropdown-item" href="{{ route('applications.show', 1) }}" role="menuitem">Lihat Permohonan</a>
                                @auth
                                @if(auth()->user()->hasRole('admin'))
                                    <a class="dropdown-item" href="{{ route('applications.edit', 1) }}" role="menuitem">Edit Permohonan</a>
                                @endif
                                @endauth
                                <div class="dropdown-divider"></div>
                                @auth
                                @if(auth()->user()->hasRole('admin'))
                                <form method="POST" action="{{ route('applications.destroy', 1) }}" class="px-3 myds-destroy-form" data-model="Permohonan" data-myds-form>
                                    @csrf
                                    <button type="submit" class="dropdown-item myds-btn myds-btn--danger" role="menuitem" aria-label="Padam Permohonan">Padam Permohonan</button>
                                </form>
                                @endif
                                @endauth
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a id="navWarehouses" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-label="Gudang menu">
                                Gudang
                            </a>
                            <div id="warehousesMenu" class="dropdown-menu myds-dropdown" aria-labelledby="navWarehouses" role="menu">
                                <div class="px-3 py-2 text-muted small">Gudang & Rak</div>
                                <div id="warehouses-list" class="px-2"></div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('inventories.create') }}">Cipta Inventori</a>
                            </div>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar: search, language toggle, auth -->
                    {{-- Temporarily disabled search bar
                    <form class="d-flex me-3" role="search" action="{{ route('home') }}" method="GET" aria-label="{{ __('nav.search') }}">
                        <label for="site-search" class="visually-hidden">{{ __('nav.search') }}</label>
                        <input class="form-control myds-input me-2" type="search" id="site-search" name="q" placeholder="{{ __('nav.search_placeholder') }}" aria-label="{{ __('nav.search') }}" />
                        <button class="myds-btn myds-btn--primary" type="submit">{{ __('nav.search') }}</button>
                    </form>
                    --}}

                    <ul class="navbar-nav ms-auto align-items-center">
                        {{-- Language toggle hidden per requirement (keep content in BM, code EN)
                        <li class="nav-item me-2">
                            @php $currentLocale = app()->getLocale(); @endphp
                            @if($currentLocale === 'ms')
                                <a class="nav-link" href="{{ route('locale.switch', ['locale' => 'en']) }}" aria-label="Tukar bahasa ke Inggeris">EN</a>
                            @else
                                <a class="nav-link" href="{{ route('locale.switch', ['locale' => 'ms']) }}" aria-label="Switch language to Malay">MS</a>
                            @endif
                        </li>
                        --}}
                        <!-- Authentication Links -->
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
                            {{-- Theme toggle placed to the left of the profile dropdown for RTL safety and alignment --}}
                            <li class="nav-item d-flex align-items-center me-2">
                                <button id="theme-toggle" class="myds-btn myds-btn--secondary" type="button" aria-pressed="false" aria-label="{{ __('nav.theme_toggle') }}" title="{{ __('nav.theme_toggle') }}">
                                    <span id="theme-toggle-icon" class="me-1" aria-hidden="true"><!-- svg icon inserted by JS --></span>
                                    <span class="visually-hidden">{{ __('nav.theme_toggle') }}</span>
                                </button>
                            </li>

                            @auth
                            {{-- Notifications dropdown (MYDS style) --}}
                            <li class="nav-item dropdown me-2">
                                @php
                                    $unread = auth()->user()->unreadNotifications ?? collect();
                                    $unreadCount = $unread->count();
                                @endphp
                                <a id="navNotifications" class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-label="Pemberitahuan">
                                    {{-- Bell icon --}}
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M15 17H9a3 3 0 0 1-3-3V11a6 6 0 1 1 12 0v3a3 3 0 0 1-3 3z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    @if($unreadCount)
                                        <span class="badge bg-danger rounded-pill position-absolute myds-badge myds-badge--danger notification-count-badge">{{ $unreadCount }}</span>
                                    @endif
                                </a>

                                <div class="dropdown-menu dropdown-menu-end myds-dropdown py-0 notifications-menu" aria-labelledby="navNotifications" role="menu">
                                    <div class="px-3 py-2 border-bottom">
                                        <strong>Pemberitahuan</strong>
                                        <span class="text-muted small d-block">{{ $unreadCount }} belum dibaca</span>
                                    </div>

                                    @if($unreadCount === 0)
                                        <div class="px-3 py-3 text-muted">Tiada pemberitahuan</div>
                                    @else
                                        <div class="list-group list-group-flush">
                                            @foreach($unread->take(10) as $note)
                                                <a href="{{ url('/notifications/'.$note->id) }}" class="list-group-item list-group-item-action d-flex align-items-start">
                                                    <div class="me-2">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M12 22s1.5-1 4-1 4-1 4-5V9a8 8 0 1 0-16 0v7c0 4 2.5 5 4 5s4 1 4 1z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                    </div>
                                                    <div class="flex-fill">
                                                        <div class="small fw-semibold">{{ $note->data['message'] ?? '—' }}</div>
                                                        <div class="small text-muted">{{ optional($note->created_at)->format('d/m/Y H:i') }}</div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="dropdown-divider m-0"></div>
                                    <a class="dropdown-item text-center py-2" href="{{ url('/notifications') }}">Lihat semua pemberitahuan</a>
                                </div>
                            </li>
                            @endauth

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
    </header>

    {{-- Main content area with MYDS container --}}
    <main id="main-content" class="py-4" role="main">
        <div class="myds-container">
            @yield('content')
        </div>
    </main>

    {{-- MYDS Footer following MyGOVEA guidelines --}}
    <footer class="bg-surface border-top py-4 mt-5" role="contentinfo">
        <div class="myds-container">
            <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
                {{-- Footer content spans full width on mobile, 6 columns on tablet, 8 columns on desktop --}}
                <div class="mobile:col-span-4 tablet:col-span-6 desktop:col-span-8">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('images/gov-logo.png') }}"
                             alt="{{ config('app.name') }} logo"
                             width="24"
                             height="24"
                             class="me-2" />
                        <span class="font-heading font-semibold">{{ config('app.name', 'Sistem Kerajaan') }}</span>
                    </div>
                    <p class="text-muted small mb-3">
                        Sistem pengurusan inventori dan kenderaan Kerajaan Malaysia.
                        Dibangunkan mengikut prinsip MyGOVEA dan MYDS.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mb-3">
                        <a href="#" class="text-muted small">Dasar Privasi</a>
                        <a href="#" class="text-muted small">Terma Penggunaan</a>
                        <a href="#" class="text-muted small">Hubungi Kami</a>
                        <a href="#" class="text-muted small">Bantuan</a>
                    </div>
                    <p class="text-muted small mb-0">
                        © {{ date('Y') }} Kerajaan Malaysia. Hak cipta terpelihara.
                    </p>
                </div>
                {{-- Contact information on the right --}}
                <div class="mobile:col-span-4 tablet:col-span-2 desktop:col-span-4">
                    <h6 class="font-heading font-semibold mb-2">Hubungi Kami</h6>
                    <div class="text-muted small">
                        <p class="mb-1">Jabatan Digital Negara</p>
                        <p class="mb-1">Putrajaya, Malaysia</p>
                        <p class="mb-1">Tel: 03-8000 8000</p>
                        <p class="mb-0">E-mel: info@jdn.gov.my</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
    {{-- Accessible fallback confirmation region for non-JS or non-SweetAlert environments. JS uses SweetAlert; this container is available if a progressive enhancement is needed. --}}
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
    <!-- Scripts (separate include to avoid bundling into one head entry) -->
    @vite('resources/js/app.js')
    @stack('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.querySelector('#warehouses-list');
        if (! container) return;

        async function fetchWarehouses() {
            const res = await fetch('{{ url('/warehouses') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (! res.ok) return [];
            return await res.json();
        }

        async function fetchShelves(warehouseId) {
            const res = await fetch('/warehouses/' + warehouseId + '/shelves', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (! res.ok) return [];
            return await res.json();
        }

        (async function init() {
            const warehouses = await fetchWarehouses();
            container.innerHTML = '';
            for (const w of warehouses) {
                // warehouse header
                const header = document.createElement('h6');
                header.className = 'dropdown-header';
                header.textContent = w.name;
                container.appendChild(header);

                const shelves = await fetchShelves(w.id);
                if (shelves.length === 0) {
                    const none = document.createElement('div');
                    none.className = 'dropdown-item text-muted small';
                    none.textContent = '(Tiada rak)';
                    container.appendChild(none);
                } else {
                    for (const s of shelves) {
                        const a = document.createElement('a');
                        a.className = 'dropdown-item';
                        a.href = '{{ route('inventories.create') }}' + '?warehouse_id=' + encodeURIComponent(w.id) + '&shelf_id=' + encodeURIComponent(s.id);
                        a.textContent = s.shelf_number;
                        container.appendChild(a);
                    }
                }

                const divider = document.createElement('div');
                divider.className = 'dropdown-divider';
                container.appendChild(divider);
            }
        })();
    });
    </script>
</body>
</html>
