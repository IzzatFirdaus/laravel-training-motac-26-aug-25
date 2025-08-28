<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon: use Laravel's standard favicon -->
    <link rel="icon" href="https://laravel.com/img/favicon/favicon.ico" type="image/x-icon">

    {{-- Page title: individual views can set a `title` section. Default to app name. --}}
    <title>@yield('title', config('app.name', 'second-crud'))</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <!-- Use Poppins for headings and Inter for body (MYDS) -->
    <link href="https://fonts.bunny.net/css?family=Poppins:400,500,600|Inter:400,500" rel="stylesheet">

    <!-- Styles -->
    @vite('resources/sass/app.scss')
</head>
<body>
    <div id="app">
    {{-- Skip to content for keyboard users --}}
    <a href="#main-content" class="visually-hidden-focusable">Langkau ke kandungan</a>

    {{-- Vehicles: live region for status messages (aria-live). Users/Inventories use toast. --}}
    <div id="global-flash" aria-live="polite" aria-atomic="true" class="sr-only">@if(session('status')){{ session('status') }}@endif</div>
    <div id="global-toast" data-toast="@if(session('toast')){{ session('toast') }}@endif" class="sr-only"></div>

    <nav class="navbar navbar-expand-md bg-surface border-bottom myds-nav" role="navigation" aria-label="Navigasi utama">
            <div class="container">
                <a class="navbar-brand myds-brand d-flex align-items-center" href="{{ url('/') }}" aria-label="Laman utama">
                    {{-- Optional: place agency logo here --}}
                    <span class="ms-0">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar: MYDS primary navigation groups -->
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
                                <a class="dropdown-item" href="{{ route('users.create') }}" role="menuitem">{{ __('nav.users_add') }}</a>
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
                    </ul>

                    <!-- Right Side Of Navbar: search, language toggle, auth -->
                    {{-- Temporarily disabled search bar
                    <form class="d-flex me-3" role="search" action="{{ route('home') }}" method="GET" aria-label="{{ __('nav.search') }}">
                        <label for="site-search" class="visually-hidden">{{ __('nav.search') }}</label>
                        <input class="form-control me-2" type="search" id="site-search" name="q" placeholder="{{ __('nav.search_placeholder') }}" aria-label="{{ __('nav.search') }}" />
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

        <main id="main-content" class="py-4">
            @yield('content')
        </main>
    </div>
    <!-- Scripts (separate include to avoid bundling into one head entry) -->
    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>
