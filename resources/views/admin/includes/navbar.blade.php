
{{-- MYDS & MyGOVEA compliant navigation --}}
<nav class="myds-nav navbar navbar-expand-lg bg-surface border-bottom" role="navigation" aria-label="Navigasi utama">
  <div class="myds-container d-flex align-items-center justify-content-between py-2">
    <a class="navbar-brand myds-brand d-flex align-items-center" href="{{ url('/') }}" aria-label="Laman utama {{ config('app.name') }}">
      <img src="{{ asset('images/gov-logo.png') }}" alt="{{ config('app.name') }} logo" width="32" height="32" class="me-2" />
      <span class="font-heading font-semibold">{{ config('app.name', 'Sistem Kerajaan') }}</span>
    </a>
    <button class="navbar-toggler myds-btn myds-btn--tertiary" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Togol menu navigasi">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
  <div class="collapse navbar-collapse" id="navMain">
    <div class="myds-container d-flex align-items-center justify-content-between py-2">
      <ul class="navbar-nav me-auto" role="menubar" aria-label="Menu utama">
        {{-- Inventori --}}
        <li class="nav-item dropdown" role="none">
          <a id="navInventories" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-label="{{ __('nav.inventory') }} menu">
            {{ __('nav.inventory') }}
          </a>
          <div class="dropdown-menu myds-dropdown" aria-labelledby="navInventories" role="menu">
            <a class="dropdown-item {{ request()->routeIs('inventories.index') ? 'active' : '' }}" href="{{ route('inventories.index') }}" role="menuitem" @if(request()->routeIs('inventories.index')) aria-current="page" @endif>{{ __('nav.inventory_browse') }}</a>
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
          <a id="navApplications" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-label="Permohonan menu">
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
          <a id="navWarehouses" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-label="Gudang menu">
            Gudang
          </a>
          <div id="warehousesMenu" class="dropdown-menu myds-dropdown" aria-labelledby="navWarehouses" role="menu">
            <div class="px-3 py-2 myds-text--muted small">Gudang & Rak</div>
            <div id="warehouses-list" class="px-2" role="presentation">
              <div class="myds-text--muted small px-2">Memuatkan...</div>
            </div>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('inventories.create') }}">Cipta Inventori</a>
          </div>
        </li>
      </ul>
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
          <li class="nav-item d-flex align-items-center me-2">
            <button id="theme-toggle" class="myds-btn myds-btn--secondary" type="button" aria-pressed="false" aria-label="{{ __('nav.theme_toggle') }}" title="{{ __('nav.theme_toggle') }}">
              <span id="theme-toggle-icon" class="me-1" aria-hidden="true"><i class="bi"></i></span>
              <span class="visually-hidden">{{ __('nav.theme_toggle') }}</span>
            </button>
          </li>
          <li class="nav-item dropdown me-2">
            @php
              $unread = auth()->user()->unreadNotifications ?? collect();
              $unreadCount = $unread->count();
            @endphp
            <a id="navNotifications" class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-label="Pemberitahuan">
              <i class="bi bi-bell" aria-hidden="true"></i>
              @if($unreadCount)
                <span class="myds-badge myds-badge--danger rounded-pill position-absolute top-0 start-100 translate-middle" aria-hidden="true">{{ $unreadCount }}</span>
                <span class="visually-hidden">{{ $unreadCount }} pemberitahuan belum dibaca</span>
                <ul class="p-0 m-0" role="list" style="list-style:none; max-height: 320px; overflow:auto;">
                  @foreach($unread->take(10) as $note)
                    <li role="listitem" class="border-bottom">
                      <a href="{{ url('/notifications/'.$note->id) }}" class="d-flex align-items-start px-3 py-2 text-decoration-none">
                        <span class="me-2" aria-hidden="true"><i class="bi bi-bell"></i></span>
                        <span class="flex-fill">
                          <span class="small fw-semibold d-block">{{ $note->data['message'] ?? 'Pemberitahuan' }}</span>
                          <span class="small myds-text--muted d-block">{{ optional($note->created_at)->format('d/m/Y H:i') }}</span>
                        </span>
                      </a>
                    </li>
                  @endforeach
                </ul>
                  @foreach($unread->take(10) as $note)
                    <a href="{{ url('/notifications/'.$note->id) }}" class="list-group-item list-group-item-action d-flex align-items-start" role="listitem">
                      <div class="me-2" aria-hidden="true"><i class="bi bi-bell"></i></div>
                      <div class="flex-fill">
                        <div class="small fw-semibold">{{ $note->data['message'] ?? '' }}</div>
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
