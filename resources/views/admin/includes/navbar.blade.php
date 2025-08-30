<nav class="myds-nav navbar navbar-expand-lg navbar-light bg-white border-bottom" role="navigation" aria-label="Main navigation">
  <!-- Skip link is provided in main layout; masthead area -->
  <div class="myds-container flex items-center justify-between py-2">
    <div class="flex items-center gap-3">
      <a class="myds-brand flex items-center gap-2" href="{{ url('/') }}">
        {{-- optional logo --}}
        <img src="{{ asset('images/gov-logo.png') }}" alt="{{ config('app.name') }} logo" width="36" height="36" aria-hidden="true" />
        <span class="font-heading">{{ config('app.name') }}</span>
      </a>

      {{-- optional small site tagline --}}
      <span class="text-muted hidden md:inline-block">Perkhidmatan Digital Kerajaan</span>
    </div>

    <div class="flex items-center gap-2">
      {{-- Language switcher placeholder (implement as needed) --}}
      <button type="button" class="myds-btn myds-btn--tertiary" aria-label="Tukar bahasa" id="lang-toggle" title="Tukar bahasa">
        BM
      </button>

      {{-- Theme toggle used by resources/js/app.js --}}
      <button id="theme-toggle" class="myds-btn myds-btn--tertiary" aria-pressed="false" aria-label="Toggle theme" title="Toggle theme">
        <span id="theme-toggle-icon" aria-hidden="true">
          <!-- Icon will be set by app.js on DOMContentLoaded -->
          ☀️
        </span>
        <span class="sr-only">Toggle theme</span>
      </button>

      {{-- Mobile menu toggler (Bootstrap behaviour kept) --}}
      <button class="navbar-toggler myds-btn myds-btn--tertiary" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </div>

  <div class="collapse navbar-collapse" id="navMain">
    <div class="myds-container flex items-center justify-between py-2">
      <ul class="navbar-nav ms-auto d-flex align-items-center gap-2">
        @guest
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Log Masuk</a></li>
          @if (Route::has('register'))
            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Daftar</a></li>
          @endif
        @else
          <li class="nav-item dropdown">
            <a id="userDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              {{ auth()->user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              @if (Route::has('profile'))
                <li><a class="dropdown-item" href="{{ route('profile') }}">Profil</a></li>
              @endif
              @if (Route::has('settings'))
                <li><a class="dropdown-item" href="{{ route('settings') }}">Tetapan</a></li>
              @endif
              <li><hr class="dropdown-divider"></li>
              {{-- logout handled via POST form for safety --}}
              <li>
                <a href="{{ route('logout') }}" class="dropdown-item" id="logout-link" role="button">Log Keluar</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none" aria-hidden="true">
                  @csrf
                </form>
              </li>
            </ul>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
