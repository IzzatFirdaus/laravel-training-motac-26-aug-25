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
      if (other === btn) return;
      closeMenu(other);
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
  document.addEventListener('DOMContentLoaded', () => {
    const navToggle = document.getElementById('navToggle');
    const navMain = document.getElementById('navMain');
    if (navToggle && navMain) {
      if (!navToggle._navToggleAttached) {
        navToggle._navToggleAttached = true;
        navToggle.addEventListener('click', () => {
          const expanded = navToggle.getAttribute('aria-expanded') === 'true';
          navToggle.setAttribute('aria-expanded', String(!expanded));
          navMain.hidden = expanded;
        });
      }
    }

    attachMenuButtons();

    // close menus on outside click
    document.addEventListener('click', (e) => {
      const target = e.target;
      if (target.closest && (target.closest('.myds-dropdown') || isMenuButton(target) || target.closest('.myds-nav-link--toggle'))) {
        // clicked inside a menu area: ignore
        return;
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
  });

  // Theme toggle wiring (useful if theme button exists)
  document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-toggle-icon');
    if (!themeToggle) return;

    function applyTheme(theme) {
      if (theme === 'dark') {
        document.documentElement.classList.add('dark');
        document.documentElement.setAttribute('data-theme', 'dark');
        if (themeIcon) themeIcon.className = 'bi bi-moon-fill';
        themeToggle.setAttribute('aria-pressed', 'true');
      } else {
        document.documentElement.classList.remove('dark');
        document.documentElement.setAttribute('data-theme', 'light');
        if (themeIcon) themeIcon.className = 'bi bi-sun-fill';
        themeToggle.setAttribute('aria-pressed', 'false');
      }
      try { localStorage.setItem('myds-theme', theme); } catch (e) { /* ignore */ }
    }

    // init
    (function initTheme() {
      try {
        const stored = localStorage.getItem('myds-theme');
        const pref = stored || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        applyTheme(pref);
      } catch (e) {
        applyTheme('light');
      }
    }());

    if (!themeToggle._themeAttached) {
      themeToggle._themeAttached = true;
      themeToggle.addEventListener('click', () => {
        const current = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        applyTheme(current === 'dark' ? 'light' : 'dark');
      });
    }
  });
})();
