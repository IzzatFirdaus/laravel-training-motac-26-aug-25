/*
 * Footer progressive enhancement (MYDS/MyGOVEA compliant)
 * - Back to top button (smooth scroll, accessible focus management)
 * - Language selector auto-submit / navigate
 *
 * Idempotent: safe to import multiple times or when elements are missing.
 */
(function () {
  'use strict';

  if (typeof window === 'undefined') return;
  // prevent wiring twice
  if (window.__mydsFooterWired) return;
  window.__mydsFooterWired = true;

  // Back to top handler (event delegation with accessibility)
  document.addEventListener('click', function (e) {
    var closest = e && e.target && e.target.closest ? e.target.closest('[data-action="scroll-top"]') : null;
    if (!closest) return;
    e.preventDefault();

    // Announce the action for screen readers
    try {
      var liveRegion = document.createElement('div');
      liveRegion.setAttribute('aria-live', 'polite');
      liveRegion.className = 'visually-hidden';
      liveRegion.textContent = 'Menggulung ke bahagian atas halaman';
      document.body.appendChild(liveRegion);
      setTimeout(function() { document.body.removeChild(liveRegion); }, 2000);
    } catch (err) { /* ignore */ }

    try {
      var main = document.getElementById('main-content') || document.body;
      main.scrollIntoView({ behavior: 'smooth' });
      setTimeout(function () {
        try {
          main.focus({ preventScroll: true });
        } catch (err) {
          main.setAttribute('tabindex', '-1');
          main.focus();
        }
      }, 300);
    } catch (err) {
      try { window.scrollTo({ top: 0, behavior: 'smooth' }); } catch (e) { window.scrollTo(0, 0); }
    }
  }, false);

  // Language selector wiring (accessible navigation)
  (function wireLang() {
    var sel = document.getElementById('footer-lang');
    if (!sel) return;
    if (sel._langWired) return;
    sel._langWired = true;

    // Add accessibility attributes
    sel.setAttribute('aria-label', 'Tukar bahasa halaman');

    sel.addEventListener('change', function () {
      // Announce language change
      try {
        var liveRegion = document.createElement('div');
        liveRegion.setAttribute('aria-live', 'polite');
        liveRegion.className = 'visually-hidden';
        liveRegion.textContent = 'Menukar bahasa ke ' + (this.options[this.selectedIndex]?.text || this.value);
        document.body.appendChild(liveRegion);
        setTimeout(function() { document.body.removeChild(liveRegion); }, 2000);
      } catch (err) { /* ignore */ }

      try {
        var url = new URL(window.location.href);
        url.searchParams.set('lang', this.value);
        window.location.href = url.toString();
      } catch (err) {
        var form = document.getElementById('footer-language-form');
        if (form) form.submit();
      }
    });
  })();
})();
