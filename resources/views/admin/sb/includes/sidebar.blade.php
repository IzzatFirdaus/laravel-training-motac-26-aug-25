<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
      <div class="nav">
        <div class="sb-sidenav-menu-heading">Core</div>
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
          Dashboard
        </a>
        <div class="sb-sidenav-menu-heading">Interface</div>
        <a class="nav-link" href="{{ route('inventories.index') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div>
          Inventories
        </a>
        <a class="nav-link" href="{{ route('vehicles.index') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-car"></i></div>
          Vehicles
        </a>
        <a class="nav-link" href="{{ route('users.index') }}">
          <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
          Users
        </a>
      </div>
    </div>
    <div class="sb-sidenav-footer">
      <div class="small">Logged in as:</div>
      {{ auth()->user()->name ?? 'Guest' }}
    </div>
  </nav>
</div>
