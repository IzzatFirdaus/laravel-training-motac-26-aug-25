// resources/js/pages/excel-inventory-import.js
// MYDS-enhanced file input UX for Inventory Import pages
// - Mirrors selected filename and size
// - Toggles hidden _reupload flag when a new file is chosen during import
// Accessible: uses aria-live regions to announce updates

(function () {
  'use strict';

  function bytesToKB(n) {
    return Math.round(n / 1024);
  }

  function setupInitialUpload() {
    const fileInput = document.getElementById('file');
    const fileNameEl = document.getElementById('file-name');
    const fileSelectLabel = document.getElementById('file-select-label');

    if (!fileInput || !fileNameEl || !fileSelectLabel) return;

    // idempotent click wiring
    if (!fileSelectLabel.dataset.mydsClick) {
      fileSelectLabel.dataset.mydsClick = '1';
      fileSelectLabel.addEventListener('click', () => fileInput.click());
    }

    // ensure fileNameEl has an aria-live region for announcements
    if (!fileNameEl.getAttribute('aria-live')) fileNameEl.setAttribute('aria-live', 'polite');

    if (!fileInput.dataset.mydsChange) {
      fileInput.dataset.mydsChange = '1';
      fileInput.addEventListener('change', () => {
        const f = fileInput.files && fileInput.files[0];
        if (f) {
          fileNameEl.textContent = `${f.name} (${bytesToKB(f.size)} KB)`;
        } else {
          fileNameEl.textContent = fileNameEl.dataset.emptyText || 'Tiada fail dipilih';
        }
      });
    }
  }

  function setupReupload() {
    const reuploadFile = document.getElementById('reupload-file');
    const reuploadFlag = document.getElementById('reupload-flag');
    const reuploadFilename = document.getElementById('reupload-filename');
    const reuploadLabel = document.getElementById('reupload-label');

    if (!reuploadFile || !reuploadFlag || !reuploadFilename || !reuploadLabel) return;

    if (!reuploadLabel.dataset.mydsClick) {
      reuploadLabel.dataset.mydsClick = '1';
      reuploadLabel.addEventListener('click', () => reuploadFile.click());
    }

    if (!reuploadFilename.getAttribute('aria-live')) reuploadFilename.setAttribute('aria-live', 'polite');

    if (!reuploadFile.dataset.mydsChange) {
      reuploadFile.dataset.mydsChange = '1';
      reuploadFile.addEventListener('change', () => {
        const f = reuploadFile.files && reuploadFile.files[0];
        if (f) {
          reuploadFilename.textContent = `${f.name} (${bytesToKB(f.size)} KB)`;
          reuploadFlag.value = '1';
        } else {
          reuploadFilename.textContent = reuploadFilename.dataset.emptyText || 'Tiada fail baru dipilih';
          reuploadFlag.value = '0';
        }
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      setupInitialUpload();
      setupReupload();
    });
  } else {
    setupInitialUpload();
    setupReupload();
  }
})();
