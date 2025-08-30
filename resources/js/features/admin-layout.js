/**
 * admin-layout.js
 *
 * Admin layout helpers:
 * - Sidebar toggle for mobile
 * - Skip-link focus handling
 * - Idempotent wiring; safe to include multiple times
 */

(function () {
  'use strict';

  function wireSidebarToggle() {
    const toggle = document.getElementById('admin-sidebar-toggle');
    const sidebar = document.getElementById('admin-sidebar');
    if (!toggle || !sidebar) return;

    if (toggle._adminToggleAttached) return;
    toggle._adminToggleAttached = true;

    toggle.addEventListener('click', function () {
      const expanded = this.getAttribute('aria-expanded') === 'true';
      this.setAttribute('aria-expanded', String(!expanded));
      if (sidebar.hasAttribute('hidden')) {
        sidebar.removeAttribute('hidden');
        sidebar.classList.remove('d-none');
      } else {
        sidebar.setAttribute('hidden', '');
        sidebar.classList.add('d-none');
      }

      if (!expanded) {
        setTimeout(() => {
          const firstLink = sidebar.querySelector('a, button, [tabindex]:not([tabindex="-1"])');
          if (firstLink) firstLink.focus();
        }, 100);
      } else {
        toggle.focus();
      }
    });
  }

  function wireSkipLink() {
    const skip = document.querySelector('.myds-skip-link, .skip-link');
    if (!skip || skip._wired) return;
    skip._wired = true;

    skip.addEventListener('click', (e) => {
      // allow default jump then focus main
      setTimeout(() => {
        const main = document.getElementById('main-content');
        if (main) {
          main.setAttribute('tabindex', '-1');
          try { main.focus({ preventScroll: true }); } catch (err) { main.focus(); }
        }
      }, 0);
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    wireSidebarToggle();
    wireSkipLink();
  });

  window.addEventListener('load', () => {
    wireSidebarToggle();
    wireSkipLink();
  });

})();
