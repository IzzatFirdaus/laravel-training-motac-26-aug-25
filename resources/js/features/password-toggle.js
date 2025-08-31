// Password visibility toggle (idempotent)
// Usage: add attribute data-toggle="password" and optionally data-target="#inputId"
(function () {
  'use strict';

  function setIcon(btn, visible) {
    if (!btn) return;
  // Use aria-hidden on decorative icons; button should keep an accessible name via aria-label or dataset
  btn.innerHTML = visible ? '<i class="bi bi-eye-slash" aria-hidden="true"></i>' : '<i class="bi bi-eye" aria-hidden="true"></i>';
  }

  function normalizeTargetId(v) {
    if (!v) return null;
    return v.charAt(0) === '#' ? v.slice(1) : v;
  }

  function wirePasswordToggle(root = document) {
    const buttons = root.querySelectorAll ? root.querySelectorAll('[data-toggle="password"]') : [];
    if (!buttons || !buttons.length) return;

    buttons.forEach(function (btn) {
  if (btn._pwToggleAttached) return;
  btn._pwToggleAttached = true;

  // Mark as button for assistive tech and ensure keyboard focus
  if (!btn.hasAttribute('role')) btn.setAttribute('role', 'button');
  if (!btn.hasAttribute('tabindex')) btn.setAttribute('tabindex', '0');

  const rawTarget = btn.getAttribute('data-target') || btn.getAttribute('aria-controls');
      const targetId = normalizeTargetId(rawTarget);
      let input = targetId ? document.getElementById(targetId) : null;

      if (!input) {
        const parent = btn.parentElement || document;
        input = parent.querySelector('input[type="password"], input[data-password-target], input[type="text"][data-password-target]');
      }
      if (!input) return;

      // initialize icon based on current visibility and set accessible label
      const isPwd = input.getAttribute('type') === 'password';
      setIcon(btn, isPwd);
      if (!btn.getAttribute('aria-label')) {
        btn.setAttribute('aria-label', btn.dataset.ariaLabel || (isPwd ? 'Tunjukkan kata laluan' : 'Sembunyikan kata laluan'));
      }

      // Toggle on click & keyboard
      function toggleVisibility(e) {
        if (e) e.preventDefault();
        const currentlyPwd = input.getAttribute('type') === 'password';
        try { input.setAttribute('type', currentlyPwd ? 'text' : 'password'); } catch (err) { /* ignore */ }
        setIcon(btn, !currentlyPwd);
        try { input.focus(); } catch (e) { /* ignore */ }
        // update aria-label after toggle
        try { btn.setAttribute('aria-label', btn.dataset.ariaLabel || (!currentlyPwd ? 'Tunjukkan kata laluan' : 'Sembunyikan kata laluan')); } catch (e) {}
      }

      btn.addEventListener('click', toggleVisibility);
      btn.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') { toggleVisibility(e); }
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () { wirePasswordToggle(document); });
  window.addEventListener('load', function () { wirePasswordToggle(document); });

  window.MYDS = window.MYDS || {};
  window.MYDS.wirePasswordToggle = wirePasswordToggle;
})();
