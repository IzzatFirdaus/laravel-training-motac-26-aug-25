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

    if (!fileSelectLabel._clickAttached) {
      fileSelectLabel._clickAttached = true;
      fileSelectLabel.addEventListener('click', () => fileInput.click());
    }

    if (!fileInput._changeAttached) {
      fileInput._changeAttached = true;
      fileInput.addEventListener('change', () => {
        const f = fileInput.files && fileInput.files[0];
        if (f) {
          fileNameEl.textContent = `${f.name} (${bytesToKB(f.size)} KB)`;
        } else {
          fileNameEl.textContent = 'Tiada fail dipilih';
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

    if (!reuploadLabel._clickAttached) {
      reuploadLabel._clickAttached = true;
      reuploadLabel.addEventListener('click', () => reuploadFile.click());
    }

    if (!reuploadFile._changeAttached) {
      reuploadFile._changeAttached = true;
      reuploadFile.addEventListener('change', () => {
        const f = reuploadFile.files && reuploadFile.files[0];
        if (f) {
          reuploadFilename.textContent = `${f.name} (${bytesToKB(f.size)} KB)`;
          reuploadFlag.value = '1';
        } else {
          reuploadFilename.textContent = 'Tiada fail baru dipilih';
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
