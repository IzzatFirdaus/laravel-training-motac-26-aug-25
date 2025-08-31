/**
 * navbar.js
 *
 * Navbar behaviour:
 * - Toggle mobile nav visibility
 * - Accessible dropdown/menu toggles with keyboard support
 * - Theme toggle wiring (if theme toggle element exists)
 *
 * Idempotent and defensive.
 */

(function () {
  'use strict';

  function isMenuButton(el) {
    return el && el.classList && el.classList.contains('myds-nav-link--toggle');
  }

  function getMenuForButton(btn) {
    const controls = btn.getAttribute('aria-controls');
    return controls ? document.getElementById(controls) : null;
  }

  function closeMenu(btn) {
    if (!btn) return;
    const menu = getMenuForButton(btn);
    btn.setAttribute('aria-expanded', 'false');
    if (menu) menu.setAttribute('hidden', '');
  }

  function openMenu(btn) {
    if (!btn) return;
    // close other menus
    document.querySelectorAll('.myds-nav-link--toggle[aria-expanded="true"]').forEach((other) => {
      if (other !== btn) closeMenu(other);
    });
    const menu = getMenuForButton(btn);
    btn.setAttribute('aria-expanded', 'true');
    if (menu) menu.removeAttribute('hidden');
  }

  // Attach menu button behaviours
  function attachMenuButtons() {
    document.querySelectorAll('.myds-nav-link--toggle').forEach((btn) => {
      if (!btn || btn._navAttached) return;
      btn._navAttached = true;

      btn.addEventListener('click', function () {
        const expanded = this.getAttribute('aria-expanded') === 'true';
        if (expanded) closeMenu(this); else openMenu(this);
      });

      btn.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          this.click();
          return;
        }
        if (e.key === 'Escape') {
          closeMenu(this);
          this.focus();
        }
      });
    });
  }

  // Mobile nav toggle
  function initNavToggle() {
    const navToggle = document.getElementById('navToggle');
    const navMain = document.getElementById('navMain');
    if (navToggle && navMain && !navToggle._navToggleAttached) {
      navToggle._navToggleAttached = true;
      navToggle.addEventListener('click', () => {
        const expanded = navToggle.getAttribute('aria-expanded') === 'true';
        navToggle.setAttribute('aria-expanded', String(!expanded));
        navMain.hidden = expanded;
      });
    }
  }

  function attachEventListeners() {
    if (attachEventListeners._attached) return;
    attachEventListeners._attached = true;

    // close menus on outside click
    document.addEventListener('click', (e) => {
      const target = e.target;
      if (target.closest && (target.closest('.myds-dropdown') || isMenuButton(target) || target.closest('.myds-nav-link--toggle'))) {
        return; // clicked inside a menu area: ignore
      }
      document.querySelectorAll('.myds-nav-link--toggle[aria-expanded="true"]').forEach((btn) => closeMenu(btn));
    });

    // Escape key closes menus and mobile nav
    document.addEventListener('keydown', (e) => {
      if (e.key !== 'Escape') return;
      document.querySelectorAll('.myds-nav-link--toggle[aria-expanded="true"]').forEach((btn) => closeMenu(btn));
      const navToggle = document.getElementById('navToggle');
      const navMain = document.getElementById('navMain');
      if (navToggle && navToggle.getAttribute('aria-expanded') === 'true') {
        navToggle.setAttribute('aria-expanded', 'false');
        if (navMain) navMain.hidden = true;
      }
    });
  }

  function initThemeToggle() {
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-toggle-icon');
    if (!themeToggle || themeToggle._themeAttached) return;
    themeToggle._themeAttached = true;

    function applyTheme(theme) {
      document.documentElement.setAttribute('data-theme', theme);
      try { document.documentElement.dataset.theme = theme; } catch (e) { /* ignore */ }
      themeToggle.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
      if (themeIcon) themeIcon.className = 'bi ' + (theme === 'dark' ? 'bi-moon-fill' : 'bi-sun-fill');
      try { localStorage.setItem('myds-theme', theme); } catch (e) { /* ignore */ }
    }

    // init theme
    try {
      const stored = localStorage.getItem('myds-theme');
      const pref = stored || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
      applyTheme(pref);
    } catch (e) {
      applyTheme('light');
    }

    themeToggle.addEventListener('click', () => {
      const current = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
      applyTheme(current === 'dark' ? 'light' : 'dark');
    });
  }

  function init() {
    if (init._attached) return;
    init._attached = true;
    initNavToggle();
    attachMenuButtons();
    attachEventListeners();
    initThemeToggle();
  }

  // Expose for manual calls
  window.MYDS = window.MYDS || {};
  window.MYDS.initNavbar = init;

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
