// resources/js/pages/excel-inventory-import.js
// MYDS-enhanced file input UX for Inventory Import pages
// - Mirrors selected filename and size
// - Toggles hidden _reupload flag when a new file is chosen during import
// Accessible: uses aria-live regions to announce updates

(function () {
    function setupInitialUpload() {
        var fileInput = document.getElementById('file');
        var fileNameEl = document.getElementById('file-name');
        var fileSelectLabel = document.getElementById('file-select-label');

        if (!fileInput || !fileNameEl || !fileSelectLabel) {
            return;
        }

        fileSelectLabel.addEventListener('click', function () {
            fileInput.click();
        });

        fileInput.addEventListener('change', function () {
            var f = fileInput.files && fileInput.files[0];
            if (f) {
                fileNameEl.textContent = f.name + ' (' + Math.round(f.size / 1024) + ' KB)';
            } else {
                fileNameEl.textContent = 'Tiada fail dipilih';
            }
        });
    }

    function setupReupload() {
        var reuploadFile = document.getElementById('reupload-file');
        var reuploadFlag = document.getElementById('reupload-flag');
        var reuploadFilename = document.getElementById('reupload-filename');
        var reuploadLabel = document.getElementById('reupload-label');

        if (!reuploadFile || !reuploadFlag || !reuploadFilename || !reuploadLabel) {
            return;
        }

        reuploadLabel.addEventListener('click', function () {
            reuploadFile.click();
        });

        reuploadFile.addEventListener('change', function () {
            var f = reuploadFile.files && reuploadFile.files[0];
            if (f) {
                reuploadFilename.textContent = f.name + ' (' + Math.round(f.size / 1024) + ' KB)';
                reuploadFlag.value = '1';
            } else {
                reuploadFilename.textContent = 'Tiada fail baru dipilih';
                reuploadFlag.value = '0';
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            setupInitialUpload();
            setupReupload();
        });
    } else {
        setupInitialUpload();
        setupReupload();
    }
})();
