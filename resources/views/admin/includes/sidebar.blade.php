<aside role="navigation" aria-label="Navigasi Pentadbiran" class="myds-sidebar p-3 border-end bg-surface" style="min-width: 220px;">
  <h5 class="mb-3 font-heading">Pentadbiran</h5>
  <nav role="menu">
    <ul class="p-0 m-0" style="list-style:none;">
      <li role="none" class="mb-1">
        <a role="menuitem" href="{{ route('inventories.index') }}" class="nav-link d-block px-3 py-2 {{ request()->routeIs('inventories.*') ? 'active' : '' }}">Inventori</a>
      </li>
      <li role="none" class="mb-1">
        <a role="menuitem" href="{{ route('vehicles.index') }}" class="nav-link d-block px-3 py-2 {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Kenderaan</a>
      </li>
      <li role="none" class="mb-1">
        <a role="menuitem" href="{{ route('users.index') }}" class="nav-link d-block px-3 py-2 {{ request()->routeIs('users.*') ? 'active' : '' }}">Pengguna</a>
      </li>
      <li role="none" class="mb-1">
        <a role="menuitem" href="{{ route('notifications.index') }}" class="nav-link d-block px-3 py-2 {{ request()->routeIs('notifications.*') ? 'active' : '' }}">Notifikasi</a>
      </li>
    </ul>
  </nav>
</aside>
