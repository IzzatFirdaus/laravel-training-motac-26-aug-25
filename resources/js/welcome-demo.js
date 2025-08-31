/**
 * welcome-demo.js
 *
 * Small helper to present demo toasts and modals on the welcome/demo pages.
 * - Uses SweetAlert2 if available for nicer UI, otherwise falls back to a minimal toast.
 * - Idempotent and non-blocking.
 *
 * This file is intentionally lightweight and safe when Swal is not present.
 */

(function () {
    'use strict';

    /**
     * Show a transient toast. Tries SweetAlert2 first.
     * @param {string} message
     * @param {string} icon - 'info'|'success'|'warning'|'error'
     */
    function showToast(message, icon = 'info') {
        if (window.Swal && typeof window.Swal.fire === 'function') {
            window.Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon,
                title: message,
            });
            return;
        }

        // Minimal fallback
        let container = document.getElementById('simple-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'simple-toast-container';
            container.style.position = 'fixed';
            container.style.top = '1rem';
            container.style.right = '1rem';
            container.style.zIndex = 99999;
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.textContent = message;
        toast.style.background = 'rgba(0,0,0,0.8)';
        toast.style.color = '#fff';
        toast.style.padding = '0.5rem 0.75rem';
        toast.style.marginTop = '0.5rem';
        toast.style.borderRadius = '0.375rem';
        toast.style.boxShadow = '0 2px 6px rgba(0,0,0,0.2)';
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.transition = 'opacity 300ms';
            toast.style.opacity = '0';
            setTimeout(() => container.removeChild(toast), 300);
        }, 3000);
    }

    // Attach data-demo handlers
    function attachDemoLinks() {
        const demoLinks = document.querySelectorAll('[data-demo]');
        demoLinks.forEach((el) => {
            if (el._demoAttached) return;
            el._demoAttached = true;
            el.addEventListener('click', (e) => {
                e.preventDefault();
                const action = el.getAttribute('data-demo') || 'demo';
                const model = el.getAttribute('data-model') || '';
                const prefix = el.dataset.demoPrefix || '';
                const message = model ? `${model} — ${action} demo` : `${action} demo`;

                // Use dataset-provided localized messages when present
                if (action === 'store') {
                    showToast(el.dataset.demoStoreMessage || `${prefix} ${message} (simulasi: gunakan borang Cipta)`, 'success');
                    return;
                }

                if (action === 'update') {
                    showToast(el.dataset.demoUpdateMessage || `${prefix} ${message} (simulasi: gunakan borang Ubah)`, 'success');
                    return;
                }

                showToast(el.dataset.demoMessage || message, 'info');
            });
        });
    }

    // Demo destroy button handler (non-blocking)
    function attachDemoDestroy() {
        const destroyBtn = document.getElementById('demo-destroy');
        if (!destroyBtn || destroyBtn._demoDestroyAttached) return;
        destroyBtn._demoDestroyAttached = true;
        destroyBtn.addEventListener('click', () => {
            if (window.Swal && typeof window.Swal.fire === 'function') {
                window.Swal.fire({
                    title: 'Demo: Padam',
                    text: 'Ini hanya demo — tiada data akan dipadam. Teruskan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, tunjukkan demo',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) showToast('Tindakan padam disimulasikan — tiada data diubah.', 'info');
                });
                return;
            }

            if (confirm('Demo: Ini hanya demo — tiada data akan dipadam. Teruskan?')) {
                showToast('Tindakan padam disimulasikan — tiada data diubah.', 'info');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        attachDemoLinks();
        attachDemoDestroy();
    });
})();
