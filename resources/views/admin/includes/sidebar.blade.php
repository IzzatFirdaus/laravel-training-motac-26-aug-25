{{--
  Sidebar navigation updated for MyGOVEA & MYDS:
  - Role and aria attributes improved
  - Malay link labels
  - Uses myds-sidebar and accessible menu items
--}}
<aside role="navigation" aria-label="Navigasi Pentadbiran" class="myds-sidebar p-3 border-end bg-surface" style="min-width:220px;">
  <h5 class="mb-3 font-heading">Pentadbiran</h5>

  <nav role="menu" aria-label="Menu pentadbiran">
    <ul class="p-0 m-0" style="list-style:none;">
      <li role="none" class="mb-1">
        <a role="menuitem" href="{{ route('inventories.index') }}" class="d-block px-3 py-2 myds-nav-link {{ request()->routeIs('inventories.*') ? 'active' : '' }}" aria-current="{{ request()->routeIs('inventories.*') ? 'page' : 'false' }}">Inventori</a>
      </li>
      <li role="none" class="mb-1">
        <a role="menuitem" href="{{ route('vehicles.index') }}" class="d-block px-3 py-2 myds-nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}" aria-current="{{ request()->routeIs('vehicles.*') ? 'page' : 'false' }}">Kenderaan</a>
      </li>
      <li role="none" class="mb-1">
        <a role="menuitem" href="{{ route('users.index') }}" class="d-block px-3 py-2 myds-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" aria-current="{{ request()->routeIs('users.*') ? 'page' : 'false' }}">Pengguna</a>
      </li>
      <li role="none" class="mb-1">
        <a role="menuitem" href="{{ route('notifications.index') }}" class="d-block px-3 py-2 myds-nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" aria-current="{{ request()->routeIs('notifications.*') ? 'page' : 'false' }}">Pemberitahuan</a>
      </li>
    </ul>
  </nav>
</aside>
