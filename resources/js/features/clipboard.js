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

      const originalText = (btn.textContent || '').trim();
      const live = document.createElement('span');
      live.setAttribute('aria-live', 'polite');
      live.className = 'visually-hidden';
      btn.appendChild(live);

      btn.addEventListener('click', function () {
        const url = btn.getAttribute('data-copy-url') || '';
        copyToClipboard(url).then(function () {
          // Temporary UI feedback
          try { btn.innerText = 'Disalin'; } catch (e) { /* ignore */ }
          try { live.textContent = 'Pautan disalin ke papan klip'; } catch (e) {}
          setTimeout(function () {
            try { btn.innerText = originalText || 'Salin pautan'; } catch (e) {}
            try { live.textContent = ''; } catch (e) {}
          }, 1500);
        }).catch(function () {
          try { alert('Gagal menyalin pautan'); } catch (e) {}
        });
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () { wireClipboard(document); });
  window.MYDS = window.MYDS || {};
  window.MYDS.wireClipboard = wireClipboard;
})();
