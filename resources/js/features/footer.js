/*
 * Footer progressive enhancement
 * - Back to top button (smooth scroll)
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

  // Back to top handler (event delegation)
  document.addEventListener('click', function (e) {
    var closest = e && e.target && e.target.closest ? e.target.closest('[data-action="scroll-top"]') : null;
    if (!closest) return;
    e.preventDefault();
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

  // Language selector wiring
  (function wireLang() {
    var sel = document.getElementById('footer-lang');
    if (!sel) return;
    if (sel._langWired) return;
    sel._langWired = true;
    sel.addEventListener('change', function () {
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
