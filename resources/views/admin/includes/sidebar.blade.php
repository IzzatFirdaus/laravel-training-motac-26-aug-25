<aside role="navigation" aria-label="Admin navigation" class="d-flex flex-column p-3 myds-sidebar">
  <h5 class="mb-3 font-heading">Pentadbiran</h5>

  <ul class="nav nav-pills flex-column" role="menu">
    <li class="nav-item" role="none">
      <a role="menuitem" href="{{ route('inventories.index') }}" class="nav-link {{ request()->routeIs('inventories.*') ? 'active' : '' }}">Inventori</a>
    </li>
    <li class="nav-item" role="none">
      <a role="menuitem" href="{{ route('vehicles.index') }}" class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Kenderaan</a>
    </li>
    <li class="nav-item" role="none">
      <a role="menuitem" href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">Pengguna</a>
    </li>
    <li class="nav-item" role="none">
      <a role="menuitem" href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">Notifikasi</a>
    </li>
  </ul>
</aside>
