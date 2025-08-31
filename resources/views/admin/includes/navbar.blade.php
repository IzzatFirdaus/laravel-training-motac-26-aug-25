{{-- MYDS & MyGOVEA compliant navigation --}}
<nav class="myds-nav bg-surface border-bottom" role="navigation" aria-label="Navigasi utama">
  <div class="myds-container flex items-center justify-between py-2">
    <a class="myds-brand flex items-center gap-2" href="{{ url('/') }}" aria-label="Laman utama {{ config('app.name') }}">
      <img src="{{ asset('images/gov-logo.png') }}" alt="{{ config('app.name') }} logo" width="32" height="32" />
      <span class="font-heading font-semibold">{{ config('app.name', 'Sistem Kerajaan') }}</span>
    </a>
    {{-- Skip link for keyboard users (MYDS) --}}
    <a class="myds-skip-link sr-only focus:not-sr-only" href="#main-content">Langkau ke kandungan</a>
    <button id="navToggle" class="myds-btn myds-btn--tertiary" type="button" aria-controls="navMain" aria-expanded="false" aria-label="Togol menu navigasi">
      <span class="sr-only">Togol menu navigasi</span>
      <i class="bi bi-list" aria-hidden="true"></i>
    </button>
  </div>

  <div id="navMain" class="myds-container flex items-center justify-between py-2" hidden>
    <ul class="myds-nav-list flex gap-2" role="menubar" aria-label="Menu utama">
        {{-- Inventori --}}
        <li class="myds-nav-item myds-nav-item--hasmenu" role="none">
          <button id="navInventories" class="myds-nav-link myds-nav-link--toggle" type="button" aria-expanded="false" aria-haspopup="true" aria-controls="navInventoriesMenu" aria-label="{{ __('nav.inventory') }} menu">
            {{ __('nav.inventory') }}
          </button>
          <div id="navInventoriesMenu" class="myds-dropdown" aria-labelledby="navInventories" role="menu" hidden>
            @if(Route::has('inventories.index'))
              <a class="myds-dropdown-item {{ request()->routeIs('inventories.index') ? 'active' : '' }}" href="{{ route('inventories.index') }}" role="menuitem" @if(request()->routeIs('inventories.index')) aria-current="page" @endif>{{ __('nav.inventory_browse') }}</a>
            @endif

            @if(Route::has('inventories.create'))
              <a class="myds-dropdown-item" href="{{ route('inventories.create') }}" role="menuitem">{{ __('nav.inventory_add') ?? 'Cipta Inventori' }}</a>
            @endif

            @if(Route::has('inventories.show'))
              <a class="myds-dropdown-item" href="{{ route('inventories.show', 1) }}" role="menuitem">{{ __('nav.inventory_read') ?? 'Lihat Inventori' }}</a>
            @endif

            @if(Route::has('inventories.edit'))
              <a class="myds-dropdown-item" href="{{ route('inventories.edit', 1) }}" role="menuitem">{{ __('nav.inventory_edit') ?? 'Sunting Inventori' }}</a>
            @endif

            @if(Route::has('excel.inventory.form'))
              <a class="myds-dropdown-item" href="{{ route('excel.inventory.form') }}" role="menuitem">Import Inventori</a>
            @endif
            @if(Route::has('excel.inventory.export'))
              <a class="myds-dropdown-item" href="{{ route('excel.inventory.export') }}" role="menuitem">Muat Turun Templat</a>
            @endif

            <div class="myds-dropdown-divider"></div>

            @auth
                @if(auth()->user()->hasRole('admin'))
                @if(Route::has('inventories.deleted.index'))
                  <a class="myds-dropdown-item" href="{{ route('inventories.deleted.index') }}" role="menuitem">Inventori Dipadam</a>
                @endif
                @if(Route::has('inventories.destroy'))
                  <form method="POST" action="{{ route('inventories.destroy', 1) }}" class="px-3 myds-destroy-form" data-model="{{ __('nav.inventory') }}" data-myds-form>
                    @csrf
                    <button type="submit" class="myds-dropdown-item myds-btn myds-btn--danger" role="menuitem" aria-label="{{ __('nav.inventory_delete') }}">{{ __('nav.inventory_delete') }}</button>
                  </form>
                @endif
                @if(Route::has('inventories.restore'))
                  <form method="POST" action="{{ route('inventories.restore', 1) }}" class="px-3" role="menuitem">
                    @csrf
                    <button type="submit" class="myds-dropdown-item">Pulihkan Inventori</button>
                  </form>
                @endif
                @if(Route::has('inventories.force-delete'))
                  <form method="POST" action="{{ route('inventories.force-delete', 1) }}" class="px-3" role="menuitem">
                    @csrf
                    <button type="submit" class="myds-dropdown-item myds-btn myds-btn--danger">Padam Kekal</button>
                  </form>
                @endif
              @endif
            @endauth
          </div>
        </li>
        {{-- Vehicles --}}
        <li class="myds-nav-item myds-nav-item--hasmenu" role="none">
          <button id="navVehicles" class="myds-nav-link myds-nav-link--toggle" type="button" aria-expanded="false" aria-haspopup="true" aria-controls="navVehiclesMenu" aria-label="{{ __('nav.vehicles') }} menu">
            {{ __('nav.vehicles') }}
          </button>
          <div id="navVehiclesMenu" class="myds-dropdown" aria-labelledby="navVehicles" role="menu" hidden>
            @if(Route::has('vehicles.index'))
              <a class="myds-dropdown-item {{ request()->routeIs('vehicles.index') ? 'active' : '' }}" href="{{ route('vehicles.index') }}" role="menuitem" @if(request()->routeIs('vehicles.index')) aria-current="page" @endif>{{ __('nav.vehicles_browse') }}</a>
            @endif

            @if(Route::has('vehicles.create'))
              <a class="myds-dropdown-item" href="{{ route('vehicles.create') }}" role="menuitem">{{ __('nav.vehicles_add') ?? 'Tambah Kenderaan' }}</a>
            @endif

            @if(Route::has('vehicles.show'))
              <a class="myds-dropdown-item" href="{{ route('vehicles.show', 1) }}" role="menuitem">{{ __('nav.vehicles_read') ?? 'Lihat Kenderaan' }}</a>
            @endif

            @if(Route::has('vehicles.edit'))
              <a class="myds-dropdown-item" href="{{ route('vehicles.edit', 1) }}" role="menuitem">{{ __('nav.vehicles_edit') ?? 'Sunting Kenderaan' }}</a>
            @endif

            @auth
              @if(auth()->user()->hasRole('admin') && Route::has('vehicles.destroy'))
                <form method="POST" action="{{ route('vehicles.destroy', 1) }}" class="px-3 myds-destroy-form" data-model="{{ __('nav.vehicles') }}" data-myds-form>
                  @csrf
                  <button type="submit" class="myds-dropdown-item myds-btn myds-btn--danger" role="menuitem" aria-label="{{ __('nav.vehicles_delete') }}">{{ __('nav.vehicles_delete') ?? 'Padam Kenderaan' }}</button>
                </form>
              @endif
            @endauth
          </div>
        </li>
        {{-- Users --}}
        <li class="myds-nav-item myds-nav-item--hasmenu" role="none">
          <button id="navUsers" class="myds-nav-link myds-nav-link--toggle" type="button" aria-expanded="false" aria-haspopup="true" aria-controls="navUsersMenu" aria-label="{{ __('nav.users') }} menu">
            {{ __('nav.users') }}
          </button>
          <div id="navUsersMenu" class="myds-dropdown" aria-labelledby="navUsers" role="menu" hidden>
            @if(Route::has('users.index'))
              <a class="myds-dropdown-item {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}" role="menuitem" @if(request()->routeIs('users.index')) aria-current="page" @endif>{{ __('nav.users_browse') }}</a>
            @endif

            @auth
                @if(auth()->user()->hasRole('admin'))
                @if($canCreateUser)
                  @if(Route::has('users.create'))
                    <a class="myds-dropdown-item" href="{{ route('users.create') }}" role="menuitem">{{ __('nav.users_add') }}</a>
                  @endif
                @endif
                @if(Route::has('users.edit'))
                  <a class="myds-dropdown-item" href="{{ route('users.edit', 1) }}" role="menuitem">{{ __('nav.users_edit') }}</a>
                @endif
                @if(Route::has('users.destroy'))
                  <form method="POST" action="{{ route('users.destroy', 1) }}" class="px-3 myds-destroy-form" data-model="{{ __('nav.users') }}" data-myds-form>
                    @csrf
                    <button type="submit" class="myds-dropdown-item myds-btn myds-btn--danger" role="menuitem" aria-label="{{ __('nav.users_delete') }}">{{ __('nav.users_delete') }}</button>
                  </form>
                @endif
              @endif
            @endauth

            @if(Route::has('users.show'))
              <a class="myds-dropdown-item" href="{{ route('users.show', 1) }}" role="menuitem">{{ __('nav.users_read') }}</a>
            @endif
          </div>
        </li>
        {{-- Applications --}}
        <li class="myds-nav-item myds-nav-item--hasmenu" role="none">
          <button id="navApplications" class="myds-nav-link myds-nav-link--toggle" type="button" aria-expanded="false" aria-haspopup="true" aria-controls="navApplicationsMenu" aria-label="Permohonan menu">
            Permohonan
          </button>
          <div id="navApplicationsMenu" class="myds-dropdown" aria-labelledby="navApplications" role="menu" hidden>
            @if(Route::has('applications.index'))
              <a class="myds-dropdown-item {{ request()->routeIs('applications.index') ? 'active' : '' }}" href="{{ route('applications.index') }}" role="menuitem" @if(request()->routeIs('applications.index')) aria-current="page" @endif>Semak Permohonan</a>
            @endif

            @if(Route::has('applications.create'))
              <a class="myds-dropdown-item" href="{{ route('applications.create') }}" role="menuitem">Cipta Permohonan</a>
            @endif

            @if(Route::has('applications.show'))
              <a class="myds-dropdown-item" href="{{ route('applications.show', 1) }}" role="menuitem">Lihat Permohonan</a>
            @endif

            @if(Route::has('applications.edit'))
              <a class="myds-dropdown-item" href="{{ route('applications.edit', 1) }}" role="menuitem">Edit Permohonan</a>
            @endif

            @auth
              @if(auth()->user()->hasRole('admin') && Route::has('applications.destroy'))
                <form method="POST" action="{{ route('applications.destroy', 1) }}" class="px-3 myds-destroy-form" data-model="Permohonan" data-myds-form>
                  @csrf
                  <button type="submit" class="myds-dropdown-item myds-btn myds-btn--danger" role="menuitem" aria-label="Padam Permohonan">Padam Permohonan</button>
                </form>
              @endif
            @endauth
          </div>
        </li>
        {{-- Warehouses dynamic list --}}
        <li class="myds-nav-item myds-nav-item--hasmenu" role="none">
          <button id="navWarehouses" class="myds-nav-link myds-nav-link--toggle" type="button" aria-expanded="false" aria-haspopup="true" aria-controls="navWarehousesMenu" aria-label="Gudang menu">
            Gudang
          </button>
          <div id="navWarehousesMenu" class="myds-dropdown" aria-labelledby="navWarehouses" role="menu" hidden>
            <div class="px-3 py-2 myds-text--muted small">Gudang & Rak</div>
            <div id="warehouses-list" class="px-2" role="presentation" data-fetch-url="{{ url('/api/warehouses') }}">
              <div class="myds-text--muted small px-2">Memuatkan...</div>
            </div>
            <div class="myds-dropdown-divider"></div>
            @if(Route::has('warehouses.index'))
              <a class="myds-dropdown-item" href="{{ route('warehouses.index') }}">Senarai Gudang</a>
            @endif
            @if(Route::has('warehouses.shelves'))
              <a class="myds-dropdown-item" href="#">Lihat Rak (Dinamik)</a>
            @endif
            @if(Route::has('inventories.create'))
              <a class="myds-dropdown-item" href="{{ route('inventories.create') }}">Cipta Inventori</a>
            @endif
          </div>
        </li>
  </ul>
  <ul class="myds-nav-list flex gap-2 ms-auto items-center">
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
          {{-- Search form (MYDS) - accessible, preserves current URL query when submitting --}}
          <li class="myds-nav-item me-2" role="none">
            <form role="search" method="GET" action="{{ url()->current() }}" class="myds-search-form d-flex align-items-center" aria-label="Carian laman">
              <label for="navbar-search" class="sr-only">Cari laman</label>
              <input id="navbar-search" name="search" type="search" class="myds-input" placeholder="Cari..." value="{{ request('search') }}" aria-describedby="navbar-search-help" />
              <button type="submit" class="myds-btn myds-btn--secondary ms-2" aria-label="Cari">
                <i class="bi bi-search" aria-hidden="true"></i>
              </button>
            </form>
          </li>

          {{-- Language toggle (simple) - preserves current URL and adds lang param --}}
          <li class="myds-nav-item me-2" role="none">
            <div class="myds-btn-group" role="group" aria-label="Pilih bahasa">
              <a href="{{ request()->fullUrlWithQuery(['lang' => 'ms']) }}" class="myds-btn @if(app()->getLocale() === 'ms') myds-btn--primary @else myds-btn--tertiary @endif" aria-pressed="{{ app()->getLocale() === 'ms' ? 'true' : 'false' }}">MS</a>
              <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}" class="myds-btn @if(app()->getLocale() === 'en') myds-btn--primary @else myds-btn--tertiary @endif" aria-pressed="{{ app()->getLocale() === 'en' ? 'true' : 'false' }}">EN</a>
            </div>
          </li>

          <li class="myds-nav-item flex items-center me-2">
            <button id="theme-toggle" class="myds-btn myds-btn--secondary" type="button" aria-pressed="false" aria-label="{{ __('nav.theme_toggle') }}" title="{{ __('nav.theme_toggle') }}">
              <span id="theme-toggle-icon" class="me-1" aria-hidden="true"><i class="bi"></i></span>
              <span class="sr-only">{{ __('nav.theme_toggle') }}</span>
            </button>
          </li>
          <li class="myds-nav-item dropdown me-2">
            @php
              // Prefer controller-provided notification data when available
              if (! isset($unread)) {
                  $unread = auth()->user()->unreadNotifications ?? collect();
              }
              if (! isset($unreadCount)) {
                  $unreadCount = isset($unread) ? $unread->count() : 0;
              }
            @endphp
            <button id="navNotificationsBtn" class="myds-nav-link myds-nav-link--toggle position-relative" type="button" aria-expanded="false" aria-haspopup="true" aria-controls="navNotificationsMenu" aria-label="Pemberitahuan">
              <i class="bi bi-bell" aria-hidden="true"></i>
              @if($unreadCount)
                <span class="myds-badge myds-badge--danger rounded-pill position-absolute top-0 start-100 translate-middle" aria-hidden="true">{{ $unreadCount }}</span>
                <span class="sr-only">{{ $unreadCount }} pemberitahuan belum dibaca</span>
              @endif
            </button>
            <div id="navNotificationsMenu" class="myds-dropdown" aria-labelledby="navNotificationsBtn" role="menu" hidden>
              <ul class="p-0 m-0" role="list" style="list-style:none; max-height: 320px; overflow:auto;">
                @forelse($unread->take(10) as $note)
                  <li role="listitem" class="border-bottom">
                    <a href="{{ url('/notifications/'.$note->id) }}" class="d-flex align-items-start px-3 py-2 text-decoration-none">
                      <span class="me-2" aria-hidden="true"><i class="bi bi-bell"></i></span>
                      <span class="flex-fill">
                        <span class="small fw-semibold d-block">{{ $note->data['message'] ?? 'Pemberitahuan' }}</span>
                        <span class="small myds-text--muted d-block">{{ optional($note->created_at)->format('d/m/Y H:i') }}</span>
                      </span>
                    </a>
                  </li>
                @empty
                  <li class="border-bottom px-3 py-2 myds-text--muted">Tiada pemberitahuan belum dibaca.</li>
                @endforelse
              </ul>
              <div class="myds-dropdown-divider m-0"></div>
              <a class="myds-dropdown-item text-center py-2" href="{{ url('/notifications') }}">Lihat semua pemberitahuan</a>
            </div>
          </li>
          <li class="myds-nav-item dropdown">
            <button id="userMenuBtn" class="myds-nav-link myds-nav-link--toggle dropdown-toggle" type="button" aria-controls="userMenu" aria-expanded="false" aria-haspopup="true" v-pre aria-label="Menu pengguna">
              {{ Auth::user()->name }}
            </button>
            <div id="userMenu" class="myds-dropdown myds-dropdown-end" aria-labelledby="userMenuBtn" role="menu" hidden>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="px-3" role="menuitem">
                @csrf
                <button type="submit" class="myds-dropdown-item myds-btn myds-btn--tertiary" aria-label="{{ __('nav.logout') }}">{{ __('nav.logout') }}</button>
              </form>
            </div>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
