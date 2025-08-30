{{--
  Footer: updated to MyGOVEA & MYDS conventions
  - Bahasa Melayu labels
  - Accessible landmark and nav
  - Uses myds-container / myds-footer classes
  - Links use route/url helpers; external links include rel attributes if needed
--}}
<footer role="contentinfo" class="myds-footer border-top mt-4 p-3 bg-surface">
  <div class="myds-container d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
    <div class="myds-text--muted small">
      &copy; {{ date('Y') }} <span class="font-heading">{{ config('app.name') }}</span>. Hak cipta terpelihara.
    </div>

    <nav aria-label="Pautan Kaki Laman" class="d-flex gap-3">
      <a href="{{ url('/') }}" class="text-primary text-decoration-none" aria-label="Laman Utama">Laman Utama</a>
      <a href="{{ url('/disclaimer') }}" class="myds-text--muted text-decoration-none" aria-label="Penafian">Penafian</a>
      <a href="{{ url('/privacy') }}" class="myds-text--muted text-decoration-none" aria-label="Dasar Privasi">Dasar Privasi</a>
      <a href="{{ url('/contact') }}" class="myds-text--muted text-decoration-none" aria-label="Hubungi Kami">Hubungi Kami</a>
    </nav>
  </div>
</footer>

@push('scripts')
@vite('resources/js/features/footer.js')
@endpush
