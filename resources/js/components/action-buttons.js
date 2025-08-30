// Accessible action buttons and small UX helpers for MYDS
// - Adds keyboard interaction for dropdown toggles
// - Hooks data-myds-form confirmation flows via data-confirm or data-confirm-message
(function () {
  'use strict';

  function getMenuForButton(btn) {
    if (!btn) return null;
    const controls = btn.getAttribute && btn.getAttribute('aria-controls');
    if (controls) return document.getElementById(controls);
    // fallback: nextElementSibling that appears to be a menu
    const next = btn.nextElementSibling;
    if (next && next.matches && next.matches('[role="menu"], .dropdown-menu, .myds-dropdown')) return next;
    return null;
  }

  function openMenu(btn) {
    const menu = getMenuForButton(btn);
    if (!btn) return;
    // close siblings
    document.querySelectorAll('[aria-expanded="true"]').forEach(function (b) {
      if (b !== btn) {
        b.setAttribute('aria-expanded', 'false');
        const m = getMenuForButton(b);
        if (m) m.setAttribute('hidden', '');
      }
    });
    btn.setAttribute('aria-expanded', 'true');
    if (menu) menu.removeAttribute('hidden');
  }

  function closeMenu(btn) {
    if (!btn) return;
    const menu = getMenuForButton(btn);
    btn.setAttribute('aria-expanded', 'false');
    if (menu) menu.setAttribute('hidden', '');
  }

  function attachDropdownKeyboard(root = document) {
    const selectors = '[data-myds-toggle="dropdown"], .myds-nav-link--toggle, [data-toggle="dropdown"]';
    const buttons = root.querySelectorAll ? root.querySelectorAll(selectors) : [];
    if (!buttons) return;
    buttons.forEach(function (btn) {
      if (btn._mydsDropdownAttached) return;
      btn._mydsDropdownAttached = true;

      btn.addEventListener('keydown', function (e) {
        const key = e.key;
        if (key === 'Enter' || key === ' ') {
          e.preventDefault();
          const expanded = btn.getAttribute('aria-expanded') === 'true';
          if (expanded) closeMenu(btn); else openMenu(btn);
        } else if (key === 'ArrowDown') {
          e.preventDefault();
          openMenu(btn);
          const menu = getMenuForButton(btn);
          if (menu) {
            const first = menu.querySelector('[role="menuitem"], a, button');
            if (first) first.focus();
          }
        } else if (key === 'ArrowUp') {
          e.preventDefault();
          openMenu(btn);
          const menu = getMenuForButton(btn);
          if (menu) {
            const items = menu.querySelectorAll('[role="menuitem"], a, button');
            if (items && items.length) items[items.length - 1].focus();
          }
        } else if (key === 'Escape') {
          closeMenu(btn);
          btn.focus();
        }
      });
    });
  }

  // Confirmation flows: look for forms with data-myds-form and elements with data-confirm
  function attachConfirmations() {
    // delegated click for any element with data-confirm inside a form[data-myds-form]
    document.body.addEventListener('click', function (e) {
      const el = e.target.closest && e.target.closest('[data-confirm], [data-confirm-message]');
      if (!el) return;
      const form = el.closest && el.closest('form');
      if (!form || !form.hasAttribute('data-myds-form')) return;

      const msg = el.getAttribute('data-confirm') || form.getAttribute('data-confirm-message') || el.getAttribute('data-confirm-message');
      if (!msg) return;
      e.preventDefault();

      // Use Swal if available for nicer confirm dialogs
      if (window.MYDS && window.MYDS.Swal && typeof window.MYDS.Swal.fire === 'function') {
        window.MYDS.Swal.fire({
          title: msg,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya',
          cancelButtonText: 'Batal'
        }).then(function (result) {
          if (result.isConfirmed) {
            // if element is a button inside the form, try to submit the form
            try { form.submit(); } catch (err) { }
          }
        });
      } else {
        if (confirm(msg)) {
          try { form.submit(); } catch (err) { }
        }
      }
    }, true);

    // Intercept form submit if submitter has data-confirm (for modern browsers e.submitter)
    document.body.addEventListener('submit', function (e) {
      const form = e.target;
      if (!form || !form.hasAttribute || !form.hasAttribute('data-myds-form')) return;
      const submitter = e.submitter || form.querySelector('[type="submit"]');
      const msg = (submitter && (submitter.getAttribute('data-confirm') || submitter.getAttribute('data-confirm-message'))) || form.getAttribute('data-confirm-message');
      if (!msg) return;
      e.preventDefault();

      if (window.MYDS && window.MYDS.Swal && typeof window.MYDS.Swal.fire === 'function') {
        window.MYDS.Swal.fire({
          title: msg,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya',
          cancelButtonText: 'Batal'
        }).then(function (result) {
          if (result.isConfirmed) {
            try { form.submit(); } catch (err) { }
          }
        });
      } else {
        if (confirm(msg)) {
          try { form.submit(); } catch (err) { }
        }
      }
    }, true);
  }

  function init(root = document) {
    if (init._attached) return; init._attached = true;
    attachDropdownKeyboard(root);
    attachConfirmations();
    // re-run attachDropdownKeyboard on DOM changes (useful after AJAX content)
    document.addEventListener('DOMContentLoaded', function () { attachDropdownKeyboard(document); });
    window.addEventListener('load', function () { attachDropdownKeyboard(document); });
  }

  // Expose for manual calls
  window.MYDS = window.MYDS || {};
  window.MYDS.initActionButtons = init;

  document.addEventListener('DOMContentLoaded', function () { init(document); });
})();
