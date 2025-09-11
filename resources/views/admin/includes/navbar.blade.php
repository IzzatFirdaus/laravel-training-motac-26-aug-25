@php
    use Illuminate\Support\Facades\Auth;
@endphp

{{-- MYDS & MyGOVEA compliant navigation --}}
<nav class="navbar navbar-expand-lg myds-nav" role="navigation" aria-label="Navigasi utama">
  <div class="container-fluid myds-container">
    <a class="navbar-brand myds-brand d-flex align-items-center gap-2" href="{{ url('/') }}" aria-label="Laman utama {{ config('app.name') }}">
      <img src="{{ asset('images/gov-logo.png') }}" alt="{{ config('app.name') }} logo" width="32" height="32" />
      <span class="font-heading font-semibold">{{ config('app.name', 'Sistem Kerajaan') }}</span>
    </a>

    {{-- Skip link for keyboard users (MYDS) --}}
    <a class="myds-skip-link" href="#main-content">Langkau ke kandungan</a>

    <button id="navToggle" data-test="nav-toggle" class="navbar-toggler myds-btn myds-btn--tertiary" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Togol menu navigasi">
      <i class="bi bi-list" aria-hidden="true"></i>
    </button>

    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 myds-nav-list" role="menubar" aria-label="Menu utama">
        {{-- Inventori --}}
        <li class="nav-item dropdown myds-nav-item" role="none">
          <a id="navInventories" class="nav-link dropdown-toggle myds-nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('nav.inventory') ?? 'Inventori' }} menu" data-test="nav-inventories-toggle">
            {{ __('nav.inventory') ?? 'Inventori' }}
          </a>
          <ul class="dropdown-menu myds-dropdown" aria-labelledby="navInventories" role="menu">
            @if(Route::has('inventories.index'))
              <li><a data-test="nav-inventories-browse" class="dropdown-item myds-dropdown-item {{ request()->routeIs('inventories.index') ? 'active' : '' }}" href="{{ route('inventories.index') }}" role="menuitem" @if(request()->routeIs('inventories.index')) aria-current="page" @endif>{{ __('nav.inventory_browse') ?? 'Semua Inventori' }}</a></li>
            @endif
            @if(Route::has('inventories.create'))
              <li><a data-test="nav-inventories-add" class="dropdown-item myds-dropdown-item" href="{{ route('inventories.create') }}" role="menuitem">{{ __('nav.inventory_add') ?? 'Cipta Inventori' }}</a></li>
            @endif
            @if(Route::has('excel.inventory.form'))
              <li><a data-test="nav-inventories-import" class="dropdown-item myds-dropdown-item" href="{{ route('excel.inventory.form') }}" role="menuitem">Import Inventori</a></li>
            @endif
            @if(Route::has('excel.inventory.export'))
              <li><a data-test="nav-inventories-export" class="dropdown-item myds-dropdown-item" href="{{ route('excel.inventory.export') }}" role="menuitem">Eksport Inventori</a></li>
            @endif
            @can('view_deleted_inventories')
              <li><hr class="dropdown-divider myds-dropdown-divider"></li>
              @if(Route::has('inventories.deleted.index'))
                <li><a data-test="nav-inventories-deleted" href="{{ route('inventories.deleted.index') }}" class="dropdown-item myds-dropdown-item" role="menuitem">Inventori Dipadam</a></li>
              @endif
            @endcan
          </ul>
        </li>
        {{-- Vehicles --}}
        <li class="nav-item dropdown myds-nav-item" role="none">
          <a id="navVehicles" class="nav-link dropdown-toggle myds-nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('nav.vehicles') ?? 'Kenderaan' }} menu" data-test="nav-vehicles-toggle">
            {{ __('nav.vehicles') ?? 'Kenderaan' }}
          </a>
          <ul class="dropdown-menu myds-dropdown" aria-labelledby="navVehicles" role="menu">
            @if(Route::has('vehicles.index'))
              <li><a data-test="nav-vehicles-browse" class="dropdown-item myds-dropdown-item {{ request()->routeIs('vehicles.index') ? 'active' : '' }}" href="{{ route('vehicles.index') }}" role="menuitem" @if(request()->routeIs('vehicles.index')) aria-current="page" @endif>{{ __('nav.vehicles_browse') ?? 'Semua Kenderaan' }}</a></li>
            @endif
            @if(Route::has('vehicles.create'))
              <li><a data-test="nav-vehicles-add" class="dropdown-item myds-dropdown-item" href="{{ route('vehicles.create') }}" role="menuitem">{{ __('nav.vehicles_add') ?? 'Tambah Kenderaan' }}</a></li>
            @endif
          </ul>
        </li>
        {{-- Users --}}
        @can('view_users')
        <li class="nav-item dropdown myds-nav-item" role="none">
          <a id="navUsers" class="nav-link dropdown-toggle myds-nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('nav.users') ?? 'Pengguna' }} menu" data-test="nav-users-toggle">
            {{ __('nav.users') ?? 'Pengguna' }}
          </a>
          <ul class="dropdown-menu myds-dropdown" aria-labelledby="navUsers" role="menu">
            @if(Route::has('users.index'))
              <li><a data-test="nav-users-browse" class="dropdown-item myds-dropdown-item {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}" role="menuitem" @if(request()->routeIs('users.index')) aria-current="page" @endif>{{ __('nav.users_browse') ?? 'Semua Pengguna' }}</a></li>
            @endif
            @can('create_users')
              @if(Route::has('users.create'))
                <li><a data-test="nav-users-add" class="dropdown-item myds-dropdown-item" href="{{ route('users.create') }}" role="menuitem">{{ __('nav.users_add') ?? 'Tambah Pengguna' }}</a></li>
              @endif
            @endcan
          </ul>
        </li>
        @endcan
        {{-- Applications --}}
        <li class="nav-item dropdown myds-nav-item" role="none">
          <a id="navApplications" class="nav-link dropdown-toggle myds-nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Permohonan menu" data-test="nav-applications-toggle">
            Permohonan
          </a>
          <ul class="dropdown-menu myds-dropdown" aria-labelledby="navApplications" role="menu">
            @if(Route::has('applications.index'))
              <li><a data-test="nav-applications-browse" class="dropdown-item myds-dropdown-item {{ request()->routeIs('applications.index') ? 'active' : '' }}" href="{{ route('applications.index') }}" role="menuitem" @if(request()->routeIs('applications.index')) aria-current="page" @endif>Semak Permohonan</a></li>
            @endif
            @if(Route::has('applications.create'))
              <li><a data-test="nav-applications-add" class="dropdown-item myds-dropdown-item" href="{{ route('applications.create') }}" role="menuitem">Cipta Permohonan</a></li>
            @endif
          </ul>
        </li>
      </ul>

      <ul class="navbar-nav ms-auto myds-nav-list d-flex align-items-center flex-row" role="menubar">
        @guest
          @if (Route::has('login'))
            <li class="nav-item" role="none"><a class="nav-link myds-nav-link" href="{{ route('login') }}">{{ __('nav.login') ?? 'Log Masuk' }}</a></li>
          @endif
          @if (Route::has('register'))
            <li class="nav-item" role="none"><a class="nav-link myds-nav-link" href="{{ route('register') }}">{{ __('nav.register') ?? 'Daftar' }}</a></li>
          @endif
        @else
          {{-- Search form (MYDS) --}}
          <li class="nav-item me-lg-2" role="none">
            <form role="search" method="GET" action="{{ url()->current() }}" class="d-flex myds-search-form" aria-label="Carian laman">
              <label for="navbar-search" class="visually-hidden">Cari laman</label>
              <input id="navbar-search" data-test="nav-search" name="search" type="search" class="form-control myds-input myds-input--sm" placeholder="Cari..." value="{{ request('search') }}" aria-describedby="navbar-search-help" />
              <button type="submit" class="btn myds-btn myds-btn--secondary myds-btn--sm ms-1" aria-label="Hantar carian">
                <i class="bi bi-search" aria-hidden="true"></i>
              </button>
            </form>
          </li>

          {{-- Language toggle --}}
          <li class="nav-item me-lg-2" role="none">
            <div class="btn-group myds-btn-group" role="group" aria-label="Pilih bahasa">
              <a href="{{ route('locale.switch', ['locale' => 'ms']) }}" class="btn myds-btn myds-btn--sm @if(app()->getLocale() === 'ms') myds-btn--primary @else myds-btn--tertiary @endif" aria-pressed="{{ app()->getLocale() === 'ms' ? 'true' : 'false' }}">MS</a>
              <a href="{{ route('locale.switch', ['locale' => 'en']) }}" class="btn myds-btn myds-btn--sm @if(app()->getLocale() === 'en') myds-btn--primary @else myds-btn--tertiary @endif" aria-pressed="{{ app()->getLocale() === 'en' ? 'true' : 'false' }}">EN</a>
            </div>
          </li>

          {{-- Theme toggle --}}
          <li class="nav-item me-lg-2" role="none">
            <button id="theme-toggle" data-test="theme-toggle" class="btn myds-btn myds-btn--secondary myds-btn--sm" type="button" aria-label="Tukar kepada tema gelap" title="Tukar tema" aria-pressed="false">
              <span id="theme-toggle-icon"><i class="bi bi-sun-fill"></i></span>
              <span class="visually-hidden" id="theme-toggle-label">Tukar kepada tema gelap</span>
            </button>
          </li>

          {{-- Notifications --}}
          <li class="nav-item dropdown" role="none">
            @php
              $unread = Auth::user()->unreadNotifications;
              $unreadCount = $unread->count();
            @endphp
            <a id="navNotificationsBtn" data-test="nav-notifications-toggle" class="nav-link dropdown-toggle myds-nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Pemberitahuan">
              <i class="bi bi-bell-fill" aria-hidden="true"></i>
              @if($unreadCount > 0)
                <span class="badge myds-badge myds-badge--danger position-absolute top-0 start-100 translate-middle rounded-pill" aria-hidden="true">{{ $unreadCount }}</span>
                <span class="visually-hidden">{{ $unreadCount }} pemberitahuan belum dibaca</span>
              @endif
            </a>
            <ul id="navNotificationsMenu" class="dropdown-menu dropdown-menu-end myds-dropdown" aria-labelledby="navNotificationsBtn" role="menu">
              @forelse($unread->take(5) as $note)
                <li role="presentation">
                  <a href="{{ url('/notifications/'.$note->id) }}" class="dropdown-item myds-dropdown-item d-flex align-items-start" role="menuitem">
                    <span class="me-2 pt-1" aria-hidden="true"><i class="bi bi-bell"></i></span>
                    <span class="flex-fill">
                      <span class="small fw-semibold d-block">{{ $note->data['message'] ?? 'Pemberitahuan' }}</span>
                      <span class="small myds-text--muted d-block">{{ optional($note->created_at)->diffForHumans() }}</span>
                    </span>
                  </a>
                </li>
              @empty
                <li role="presentation"><p class="dropdown-item-text myds-text--muted text-center p-3">Tiada pemberitahuan belum dibaca.</p></li>
              @endforelse
              @if($unreadCount > 0)
                <li><hr class="dropdown-divider myds-dropdown-divider"></li>
                <li role="presentation"><a data-test="nav-notifications-view-all" class="dropdown-item myds-dropdown-item text-center" href="{{ url('/notifications') }}" role="menuitem">Lihat semua pemberitahuan</a></li>
              @endif
            </ul>
          </li>

          {{-- User menu --}}
          <li class="nav-item dropdown" role="none">
            <a id="userMenuBtn" data-test="nav-user-toggle" class="nav-link dropdown-toggle myds-nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu pengguna" v-pre>
              {{ Auth::user()->name }}
            </a>
            <ul id="userMenu" class="dropdown-menu dropdown-menu-end myds-dropdown" aria-labelledby="userMenuBtn" role="menu">
              <li role="presentation">
                <a class="dropdown-item myds-dropdown-item" href="#" role="menuitem">Profil</a>
              </li>
              <li role="presentation"><hr class="dropdown-divider myds-dropdown-divider"></li>
              <li role="presentation">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline" role="menuitem">
                  @csrf
                  <button data-test="nav-logout" type="submit" class="dropdown-item myds-dropdown-item">{{ __('nav.logout') ?? 'Log Keluar' }}</button>
                </form>
              </li>
            </ul>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
