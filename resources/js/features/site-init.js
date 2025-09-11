/**
 * site-init.js
 * Site-wide initialisation:
 * - Theme handling (reads/writes myds-theme)
 * - Warehouses menu loader (non-blocking)
 * - Skip-link focus wiring
 *
 * Idempotent and defensive.
 */
(function () {
  'use strict';

  const root = document.documentElement;
  const toggleBtn = document.getElementById('theme-toggle');
  const toggleIcon = document.getElementById('theme-toggle-icon');

  function setTheme(theme) {
    if (!theme) return;
    root.setAttribute('data-theme', theme);
    try { root.dataset.theme = theme; } catch (e) { /* ignore */ }

    if (toggleBtn) {
      toggleBtn.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
      // Update aria-label for better accessibility
      const isDark = theme === 'dark';
      toggleBtn.setAttribute('aria-label', isDark ? 'Tukar kepada tema terang' : 'Tukar kepada tema gelap');
    }

    if (toggleIcon) {
      // Safely replace icon classes without affecting other classes
      toggleIcon.className = '';
      toggleIcon.classList.add('bi', theme === 'dark' ? 'bi-moon-fill' : 'bi-sun-fill');
    }

    // Update the theme toggle label text
    const toggleLabel = document.getElementById('theme-toggle-label');
    if (toggleLabel) {
      toggleLabel.textContent = theme === 'dark' ? 'Tukar kepada tema terang' : 'Tukar kepada tema gelap';
    }
  }

  function getPreferredTheme() {
    try {
      const stored = localStorage.getItem('myds-theme');
      if (stored === 'dark' || stored === 'light') return stored;
    } catch (e) { /* ignore */ }
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) return 'dark';
    return 'light';
  }

  function initTheme() {
    const initial = getPreferredTheme();
    setTheme(initial);

    if (toggleBtn && !toggleBtn._siteInitAttached) {
      toggleBtn._siteInitAttached = true;
      toggleBtn.addEventListener('click', function () {
        const current = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
        const next = current === 'dark' ? 'light' : 'dark';
        setTheme(next);
  try { localStorage.setItem('myds-theme', next); } catch (e) { /* ignore */ }
      });
    }
  }

  async function loadWarehouses() {
    const container = document.getElementById('warehouses-list');
    if (!container) return;
    const url = container.getAttribute('data-fetch-url') || '/warehouses';
    try {
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      if (!res.ok) {
        container.innerHTML = '<div class="myds-text--muted small px-2">Tidak dapat memuatkan.</div>';
        return;
      }
      const items = await res.json();
      container.innerHTML = '';
      items.forEach(w => {
        const header = document.createElement('h6');
        header.className = 'dropdown-header';
        header.textContent = w.name;
        container.appendChild(header);
      });
    } catch (e) {
      container.innerHTML = '<div class="myds-text--muted small px-2">Ralat memuatkan gudang.</div>';
    }
  }

  function wireSkipLink() {
    const skipLink = document.querySelector('.myds-skip-link, .skip-link');
    if (!skipLink || skipLink._wired) return;
    skipLink._wired = true;
    skipLink.addEventListener('click', function () {
      const target = document.getElementById('main-content');
      if (!target) return;
      target.setAttribute('tabindex', '-1');
      try { target.focus({ preventScroll: true }); } catch (e) { target.focus(); }
    });
  }

  function init(root = document) {
    if (init._attached) return;
    init._attached = true;
    initTheme();
    // non-blocking warehouse loader
    setTimeout(loadWarehouses, 0);
    wireSkipLink();
  }

  // Expose for programmatic init and theme setting
  window.MYDS = window.MYDS || {};
  window.MYDS.initSite = init;
  window.MYDS.setTheme = setTheme;

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () { init(document); });
  } else {
    init(document);
  }
})();
