import './bootstrap';
import Swal from 'sweetalert2';
import enhanceUsersAutocomplete from './users-autocomplete';

// Expose MYDS namespace (keeps global pollution minimal)
window.MYDS = window.MYDS || {};
window.MYDS.Swal = Swal;

/* Inline icons for theme toggle (kept minimal and accessible). */
const __MYDS_SVG_SUN = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="12" cy="12" r="4" fill="currentColor"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
const __MYDS_SVG_MOON = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" fill="currentColor"/></svg>';

/**
 * Show confirm dialog for destructive actions (delete)
 * Accepts a button element contained in a form. Uses SweetAlert2 if available,
 * otherwise falls back to native confirm(). If confirmed, submits the ancestor form.
 *
 * @param {HTMLElement} btn - The button or element that triggered the action.
 */
window.MYDS.handleDestroy = function (btn) {
  const title = 'Amaran';
  const text = 'Ini hanya contoh: tindakan ini akan memadam item jika dihantar. Teruskan?';
  const confirmText = 'Ya, padam';
  const cancelText = 'Batal';

  if (window.MYDS.Swal && typeof window.MYDS.Swal.fire === 'function') {
    const cs = getComputedStyle(document.documentElement);
    const confirmColor = (cs.getPropertyValue('--danger') || '#d33').trim();
    const cancelColor = (cs.getPropertyValue('--primary') || '#3085d6').trim();

    window.MYDS.Swal.fire({
      title,
      text,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: confirmColor,
      cancelButtonColor: cancelColor,
      confirmButtonText: confirmText,
      cancelButtonText: cancelText,
    }).then((result) => {
      if (result.isConfirmed) {
        const form = btn.closest('form');
        if (form) form.submit();
      }
    });
  } else {
    if (confirm(text)) {
      const form = btn.closest('form');
      if (form) form.submit();
    }
  }
};

/**
 * Apply theme: sets data-theme attribute and updates toggle icon & aria-pressed.
 * @param {string} theme - 'light' | 'dark'
 */
function applyTheme(theme) {
  const icon = document.getElementById('theme-toggle-icon');
  const btn = document.getElementById('theme-toggle');

  if (theme === 'dark') {
    document.documentElement.setAttribute('data-theme', 'dark');
    if (icon) icon.innerHTML = __MYDS_SVG_MOON;
    if (btn) btn.setAttribute('aria-pressed', 'true');
  } else {
    document.documentElement.setAttribute('data-theme', 'light');
    if (icon) icon.innerHTML = __MYDS_SVG_SUN;
    if (btn) btn.setAttribute('aria-pressed', 'false');
  }
}
window.MYDS.applyTheme = applyTheme;

/**
 * Enhance logout links: find an anchor with id 'logout-link' and submit the nearby logout form.
 * This keeps logout as POST while allowing users to click a visible link.
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
 * Ensure skip-link moves focus to main content and that main receives focus for accessibility.
 */
function wireSkipLink() {
  const skip = document.querySelector('.skip-link');
  if (!skip) return;

  skip.addEventListener('click', (e) => {
    // normal anchor behaviour will scroll; we also focus the main region
    setTimeout(() => {
      const main = document.getElementById('main-content');
      if (main) main.focus();
    }, 0);
  });
}

/**
 * DOM ready initialization
 */
document.addEventListener('DOMContentLoaded', () => {
  // Show flash toast if present
  const toast = document.getElementById('global-toast');
  if (toast) {
    const msg = (toast.getAttribute('data-toast') || '').trim();
    if (msg && window.MYDS.Swal && typeof window.MYDS.Swal.fire === 'function') {
      window.MYDS.Swal.fire({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        icon: 'success',
        title: msg,
      });
    }
  }

  // Theme initialization
  let stored = null;
  try { stored = localStorage.getItem('myds_theme'); } catch (e) { /* ignore */ }
  const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  const theme = stored || (prefersDark ? 'dark' : 'light');
  applyTheme(theme === 'dark' ? 'dark' : 'light');

  const toggleBtn = document.getElementById('theme-toggle');
  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
      const next = isDark ? 'light' : 'dark';
      try { localStorage.setItem('myds_theme', next); } catch (err) { /* ignore */ }
      applyTheme(next);
    });
  }

  // Wire logout and skip link
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

  // Intercept submit for destructive submitters (modern browsers expose e.submitter)
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

  // Initialize any optional enhancements (non-fatal)
  try {
    enhanceUsersAutocomplete('#users-autocomplete');
  } catch (err) {
    // non-fatal
    // console.debug('users-autocomplete not present or failed to initialize', err);
  }
});
