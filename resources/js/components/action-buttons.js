// Accessible action buttons and small UX helpers for MYDS
// - Adds keyboard interaction for dropdown toggles
// - Hooks data-myds-form confirmation flows via data-confirm or data-confirm-message
(function () {
  'use strict';

  function getMenuForButton(btn) {
    if (!btn) return null;
    const controls = btn.getAttribute && btn.getAttribute('aria-controls');
    if (controls) return document.getElementById(controls);
    const next = btn.nextElementSibling;
    if (next && next.matches && next.matches('[role="menu"], .dropdown-menu, .myds-dropdown')) return next;
    return null;
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
    document.querySelectorAll('.myds-nav-link--toggle[aria-expanded="true"]').forEach(function (other) {
      if (other !== btn) closeMenu(other);
    });
    const menu = getMenuForButton(btn);
    btn.setAttribute('aria-expanded', 'true');
    if (menu) menu.removeAttribute('hidden');
  }

  function attachDropdownKeyboard(root = document) {
    const selectors = '[data-myds-toggle="dropdown"], .myds-nav-link--toggle, [data-toggle="dropdown"]';
    const buttons = root.querySelectorAll ? root.querySelectorAll(selectors) : [];
    if (!buttons || !buttons.length) return;

    buttons.forEach(function (btn) {
      if (btn._mydsDropdownAttached) return;
      btn._mydsDropdownAttached = true;

      btn.setAttribute('role', 'button');
      if (!btn.hasAttribute('tabindex')) btn.setAttribute('tabindex', '0');
      if (!btn.hasAttribute('aria-expanded')) btn.setAttribute('aria-expanded', 'false');

      btn.addEventListener('click', function (e) {
        const expanded = btn.getAttribute('aria-expanded') === 'true';
        if (expanded) closeMenu(btn); else openMenu(btn);
      });

      btn.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          btn.click();
        } else if (e.key === 'Escape') {
          closeMenu(btn);
          btn.focus();
        }
      });
    });
  }

  // Confirmation flows: delegated and idempotent
  function attachConfirmations() {
    if (attachConfirmations._attached) return;
    attachConfirmations._attached = true;

    // Delegated click for elements with data-confirm (buttons/links)
    document.body.addEventListener('click', function (e) {
      const el = e.target.closest && e.target.closest('[data-confirm]');
      if (!el) return;
      const message = el.getAttribute('data-confirm') || el.getAttribute('data-confirm-message') || 'Are you sure?';
      // If element is inside a form and is a submitter, let the submit handler handle confirmation.
      const form = el.closest('form');
      if (form && el.type === 'submit') return;
      if (!window.confirm(message)) {
        e.preventDefault();
        e.stopPropagation();
      }
    }, true);

    // Intercept form submit when the submitter has data-confirm (modern browsers expose e.submitter)
    document.body.addEventListener('submit', function (e) {
      try {
        const submitter = e.submitter || null;
        if (!submitter) return;
        const message = submitter.getAttribute && (submitter.getAttribute('data-confirm') || submitter.getAttribute('data-confirm-message'));
        if (message && !window.confirm(message)) {
          e.preventDefault();
          e.stopPropagation();
        }
      } catch (err) {
        // ignore
      }
    }, true);
  }

  function init(root = document) {
    if (init._attached) return; // idempotent
    init._attached = true;
    attachDropdownKeyboard(root);
    attachConfirmations();
  }

  // Expose for manual calls
  window.MYDS = window.MYDS || {};
  window.MYDS.initActionButtons = init;

  // Auto-run on ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () { init(document); });
  } else {
    init(document);
  }
})();
