// resources/js/features/clipboard.js
// Idempotent handler for elements with `data-copy-url` attribute.
// Provides clipboard copy with accessible feedback and graceful fallback.
(function () {
  'use strict';

  function copyToClipboard(text) {
    if (!text) return Promise.reject(new Error('empty'));
    if (navigator.clipboard && navigator.clipboard.writeText) {
      return navigator.clipboard.writeText(text);
    }
    // Fallback
    return new Promise(function (resolve, reject) {
      try {
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed'; ta.style.left = '-9999px';
        document.body.appendChild(ta);
        ta.select();
        const ok = document.execCommand('copy');
        document.body.removeChild(ta);
        if (ok) resolve(); else reject(new Error('execCommand failed'));
      } catch (e) { reject(e); }
    });
  }

  function wireClipboard(root = document) {
    const buttons = root.querySelectorAll ? root.querySelectorAll('[data-copy-url]') : [];
    if (!buttons || !buttons.length) return;

    buttons.forEach(function (btn) {
      if (btn._clipboardAttached) return;
      btn._clipboardAttached = true;

      // Ensure the element is presented as a button to assistive tech
      if (!btn.hasAttribute('role')) btn.setAttribute('role', 'button');
      if (!btn.hasAttribute('tabindex')) btn.setAttribute('tabindex', '0');

      const originalText = (btn.textContent || '').trim();

      // Provide a dedicated live region (not visible) for announcements
      let live = btn.querySelector('[aria-live]');
      if (!live) {
        live = document.createElement('span');
        live.setAttribute('aria-live', 'polite');
        live.className = 'visually-hidden';
        btn.appendChild(live);
      }

      // Use dataset fallbacks for copy messages so templates can localize
      const successText = btn.dataset.copySuccess || 'Pautan disalin ke papan klip';
      const buttonSuccessLabel = btn.dataset.copyLabelSuccess || 'Disalin';
      const failureText = btn.dataset.copyFailure || 'Gagal menyalin pautan';

      // Ensure accessible name exists
      if (!btn.getAttribute('aria-label')) {
        btn.setAttribute('aria-label', btn.dataset.copyAriaLabel || originalText || 'Salin pautan');
      }

      btn.addEventListener('click', function (e) {
        e.preventDefault();
        const url = btn.getAttribute('data-copy-url') || '';
        copyToClipboard(url).then(function () {
          // Temporary UI feedback
          try { btn.textContent = buttonSuccessLabel; } catch (e) { /* ignore */ }
          try { live.textContent = successText; } catch (e) {}
          setTimeout(function () {
            try { btn.textContent = originalText || (btn.dataset.copyLabel || 'Salin pautan'); } catch (e) {}
            try { live.textContent = ''; } catch (e) {}
          }, 1500);
        }).catch(function () {
          try { live.textContent = failureText; } catch (e) {}
        });
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () { wireClipboard(document); });
  window.MYDS = window.MYDS || {};
  window.MYDS.wireClipboard = wireClipboard;
})();
