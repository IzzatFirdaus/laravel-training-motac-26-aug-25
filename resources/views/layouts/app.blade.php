<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Page title: individual views can set a `title` section. Default to app name. --}}
    <title>@yield('title', config('app.name', 'second-crud'))</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    @vite('resources/sass/app.scss')
</head>
<body>
    <div id="app">
    {{-- Skip to content for keyboard users --}}
    <a href="#main-content" class="visually-hidden-focusable">Langkau ke kandungan</a>

    {{-- Global flash region for status messages (aria-live) --}}
    <div id="global-flash" aria-live="polite" aria-atomic="true" class="sr-only">@if(session('status')){{ session('status') }}@endif</div>

    <nav class="navbar navbar-expand-md bg-surface border-bottom" role="navigation" aria-label="Navigasi utama">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar: grouped dropdowns for Inventori and Kenderaan -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item dropdown">
                            <a id="navInventories" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-label="Inventori menu">
                                Inventori
                            </a>
                            <div class="dropdown-menu myds-dropdown" aria-labelledby="navInventories" role="menu">
                                <a class="dropdown-item {{ request()->routeIs('inventories.index') ? 'active' : '' }}" href="{{ route('inventories.index') }}" role="menuitem" @if(request()->routeIs('inventories.index')) aria-current="page" @endif>Senarai (Browse)</a>
                                <a class="dropdown-item" href="{{ route('inventories.create') }}" role="menuitem">Tambah (Add)</a>
                                <a class="dropdown-item" href="{{ route('inventories.show', 1) }}" role="menuitem">Baca (Read)</a>
                                <a class="dropdown-item" href="{{ route('inventories.edit', 1) }}" role="menuitem">Ubah (Edit)</a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('inventories.destroy', 1) }}" class="px-3 myds-destroy-form" data-model="Inventori">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger" role="menuitem" aria-label="Padam contoh inventori">Padam (Delete)</button>
                                </form>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a id="navVehicles" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-label="Kenderaan menu">
                                Kenderaan
                            </a>
                            <div class="dropdown-menu myds-dropdown" aria-labelledby="navVehicles" role="menu">
                                <a class="dropdown-item {{ request()->routeIs('vehicles.index') ? 'active' : '' }}" href="{{ route('vehicles.index') }}" role="menuitem" @if(request()->routeIs('vehicles.index')) aria-current="page" @endif>Senarai (Browse)</a>
                                <a class="dropdown-item" href="{{ route('vehicles.create') }}" role="menuitem">Tambah (Add)</a>
                                <a class="dropdown-item" href="{{ route('vehicles.show', 1) }}" role="menuitem">Papar (Read)</a>
                                <a class="dropdown-item" href="{{ route('vehicles.edit', 1) }}" role="menuitem">Ubah (Edit)</a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('vehicles.destroy', 1) }}" class="px-3 myds-destroy-form" data-model="Kenderaan">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger" role="menuitem" aria-label="Padam contoh kenderaan">Padam (Delete)</button>
                                </form>
                            </div>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Log Masuk</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Daftar</a>
                                </li>
                            @endif
                        @else
                            {{-- Theme toggle placed to the left of the profile dropdown for RTL safety and alignment --}}
                            <li class="nav-item d-flex align-items-center me-2">
                                <button id="theme-toggle" class="myds-btn myds-btn--secondary" type="button" aria-pressed="false" aria-label="Togol mod terang/gelap" title="Togol mod terang/gelap">
                                    <span id="theme-toggle-icon" class="me-1" aria-hidden="true"><!-- svg icon inserted by JS --></span>
                                    <span class="visually-hidden">Togol mod terang/gelap</span>
                                </button>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre aria-label="Menu pengguna">
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" role="menu">
                                    <a class="dropdown-item" href="{{ route('logout') }}" role="menuitem"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Log Keluar
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main id="main-content" class="py-4">
            {{-- Paparkan mesej status sesi dalam kawasan yang boleh dilihat dan aria-live untuk AT --}}
            @if(session('status'))
                <div class="container">
                    <div class="alert alert-success" role="status">{{ session('status') }}</div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    <!-- Scripts (separate include to avoid bundling into one head entry) -->
    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>
