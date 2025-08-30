<footer role="contentinfo" class="border-top mt-4 p-3 text-center small myds-footer bg-surface">
  <div class="myds-container flex flex-col md:flex-row items-center justify-between gap-3">
    <div class="myds-text--muted">
      &copy; {{ date('Y') }} <span class="font-heading">{{ config('app.name') }}</span>
    </div>

    <nav aria-label="Footer" class="space-x-3">
      <a href="{{ url('/') }}" class="text-primary underline-offset-2">Home</a>
      <a href="{{ url('/disclaimer') }}" class="myds-text--muted">Disclaimer</a>
      <a href="{{ url('/privacy') }}" class="myds-text--muted">Privacy</a>
      <a href="{{ url('/contact') }}" class="myds-text--muted">Contact</a>
    </nav>
  </div>
</footer>
