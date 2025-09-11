/*
 * Main application entrypoint (MYDS/MyGOVEA)
 * - Bundles feature modules
 * - Initializes global behaviors with accessibility in mind
 *
 * Exports minimal global namespace window.MYDS for safe integration.
 */

/* polyfills / bootstrap */
import './bootstrap';

/* third-party libraries */
import 'bootstrap-icons/font/bootstrap-icons.css';
import Swal from 'sweetalert2';

/* feature modules (idempotent where appropriate) */
import enhanceUsersAutocomplete from './users-autocomplete';
import initWarehousesMenu from './features/warehouses-menu';
import './features/navbar';
import './features/admin-layout';
import './features/site-init';
import './features/password-toggle';
import './components/action-buttons';
import './features/clipboard';

/* expose MYDS namespace */
window.MYDS = window.MYDS || {};
window.MYDS.Swal = Swal;

/**
 * Set the theme toggle icon element classes (uses Bootstrap Icons).
 * Accepts 'dark' or 'light'.
 */
function setThemeToggleIcon(theme) {
  const iconEl = document.getElementById('theme-toggle-icon');
  if (!iconEl) return;
  // replace classes safely
  iconEl.className = '';
  iconEl.classList.add('bi', theme === 'dark' ? 'bi-moon-fill' : 'bi-sun-fill');
}

/**
 * Confirm destructive actions (used by data-myds-form flow)
 * If SweetAlert2 present use it; otherwise fallback to native confirm()
 *
 * Accepts the button element that triggered the action (submitter).
 */
window.MYDS.handleDestroy = function (btn, options = {}) {
  const title = options.title || 'Amaran';
  const text = options.text || 'Tindakan ini akan memadam item. Teruskan?';
  const confirmText = options.confirmText || 'Ya, padam';
  const cancelText = options.cancelText || 'Batal';

  if (window.MYDS.Swal && typeof window.MYDS.Swal.fire === 'function') {
    // Use CSS variables if present to style buttons
    const cs = getComputedStyle(document.documentElement);
    const confirmColor = (cs.getPropertyValue('--myds-danger') || cs.getPropertyValue('--danger') || '#dc3545').trim();
    const cancelColor = (cs.getPropertyValue('--myds-primary') || cs.getPropertyValue('--primary') || '#0d6efd').trim();

    return window.MYDS.Swal.fire({
      title,
      text,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: confirmColor,
      cancelButtonColor: cancelColor,
      confirmButtonText: options.confirmText || confirmText,
      cancelButtonText: options.cancelText || cancelText,
    }).then((result) => {
      if (result.isConfirmed) {
        const form = btn.closest('form');
        if (form) form.submit();
      }
    });
  }

  // fallback
  if (confirm(text)) {
    const form = btn.closest('form');
    if (form) form.submit();
  }
};

/**
 * Apply theme globally - DEPRECATED: Use site-init.js implementation
 * This is kept for backward compatibility but site-init.js handles theme logic
 */
function applyTheme(theme) {
  // Delegate to site-init.js implementation if available
  if (window.MYDS && window.MYDS.setTheme) {
    window.MYDS.setTheme(theme);
    return;
  }

  // Fallback implementation
  const btn = document.getElementById('theme-toggle');
  if (theme === 'dark') {
    document.documentElement.setAttribute('data-theme', 'dark');
    setThemeToggleIcon('dark');
    if (btn) btn.setAttribute('aria-pressed', 'true');
  } else {
    document.documentElement.setAttribute('data-theme', 'light');
    setThemeToggleIcon('light');
    if (btn) btn.setAttribute('aria-pressed', 'false');
  }
}
window.MYDS.applyTheme = applyTheme;

/**
 * Wire logout anchor to actual logout POST form
 */
function wireLogout() {
  const logoutLink = document.getElementById('logout-link');
  if (!logoutLink) return;
  logoutLink.addEventListener('click', (e) => {
    e.preventDefault();
    const form = document.getElementById('logout-form');
    if (form) form.submit();
  });
}

/**
 * Ensure skip-link moves focus to main content
 */
function wireSkipLink() {
  const skip = document.querySelector('.myds-skip-link, .skip-link');
  if (!skip) return;
  skip.addEventListener('click', () => {
    setTimeout(() => {
      const main = document.getElementById('main-content');
      if (main) main.focus();
    }, 0);
  });
}

/**
 * Initialize on DOMContentLoaded
 */
document.addEventListener('DOMContentLoaded', () => {
  // Show toast (read from data attributes to avoid exposing content in DOM)
  const toastEl = document.getElementById('global-toast');
  const toastMsg = toastEl ? toastEl.dataset.toast : '';
  if (toastMsg && window.MYDS.Swal && typeof window.MYDS.Swal.fire === 'function') {
    window.MYDS.Swal.fire({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 4000,
      icon: 'success',
      title: toastMsg,
    });
  }

  // Theme init: handled by site-init.js to avoid conflicts
  // The site-init.js module properly handles theme initialization and persistence

  // Toggle button: handled by site-init.js
  // Remove duplicate event listener to avoid conflicts

  wireLogout();
  wireSkipLink();

  // Global delegated handlers for destructive actions within forms that have data-myds-form
  document.body.addEventListener('click', (e) => {
    const el = e.target;
    if (!el) return;
    const btn = el.closest && el.closest('button, a');
    if (!btn) return;
    const form = btn.closest && btn.closest('form');
    if (!form || !form.hasAttribute('data-myds-form')) return;

    const isButton = btn.tagName && btn.tagName.toLowerCase() === 'button';
    const aria = (btn.getAttribute && (btn.getAttribute('aria-label') || '')).toLowerCase();
    const isDanger = (btn.classList && (btn.classList.contains('myds-btn--danger') || btn.classList.contains('text-danger'))) || aria.includes('padam') || aria.includes('delete');

    if (isButton && isDanger) {
      e.preventDefault();
      if (window.MYDS && typeof window.MYDS.handleDestroy === 'function') {
        window.MYDS.handleDestroy(btn);
      } else if (confirm('Ini akan memadam item. Teruskan?')) {
        form.submit();
      }
    }
  }, true);

  // Intercept submit for modern browsers where submitter is provided
  document.body.addEventListener('submit', (e) => {
    const form = e.target;
    if (!form || !form.hasAttribute || !form.hasAttribute('data-myds-form')) return;

    let submitter = e.submitter;
    if (!submitter) submitter = form.querySelector('button[type="submit"], input[type="submit"]');
    const aria = (submitter && (submitter.getAttribute('aria-label') || '')).toLowerCase();
    const isDanger = submitter && (submitter.classList.contains('myds-btn--danger') || aria.includes('padam') || aria.includes('delete'));
    if (!isDanger) return;

    e.preventDefault();
    if (window.MYDS && typeof window.MYDS.handleDestroy === 'function') {
      window.MYDS.handleDestroy(submitter);
    } else if (confirm('Ini akan memadam item. Teruskan?')) {
      form.submit();
    }
  }, true);

  // Global data-action handlers (history-back, reload)
  document.body.addEventListener('click', (e) => {
    const actionEl = e.target.closest('[data-action]');
    if (!actionEl) return;
    const action = actionEl.getAttribute('data-action');
    if (!action) return;
    e.preventDefault();
    if (action === 'history-back') {
      if (window.history.length > 1) history.back();
      else window.location.href = '/';
    } else if (action === 'reload') {
      location.reload();
    }
  });

  // Try to initialize optional enhancements (non-fatal)
  try { enhanceUsersAutocomplete('#users-autocomplete'); } catch (err) { /* non-fatal */ }
  try { initWarehousesMenu(); } catch (err) { /* non-fatal */ }
});
