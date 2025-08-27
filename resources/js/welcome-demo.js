// welcome-demo.js
// Handles demo toasts on the welcome page. Kept separate from app.js so it's only loaded on the welcome view.
document.addEventListener('DOMContentLoaded', function () {
    // small helper to show a toast via SweetAlert2 if available, otherwise use a minimal fallback
    function showToast(message, icon) {
        icon = icon || 'info';
        if (window.Swal && typeof window.Swal.fire === 'function') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: icon,
                title: message
            });
            return;
        }

        // fallback toast (minimal, no CSS dependency)
        var container = document.getElementById('simple-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'simple-toast-container';
            container.style.position = 'fixed';
            container.style.top = '1rem';
            container.style.right = '1rem';
            container.style.zIndex = 99999;
            document.body.appendChild(container);
        }

        var toast = document.createElement('div');
        toast.textContent = message;
        toast.style.background = 'rgba(0,0,0,0.8)';
        toast.style.color = 'white';
        toast.style.padding = '0.5rem 0.75rem';
        toast.style.marginTop = '0.5rem';
        toast.style.borderRadius = '0.375rem';
        toast.style.boxShadow = '0 2px 6px rgba(0,0,0,0.2)';
        container.appendChild(toast);

        setTimeout(function () {
            toast.style.transition = 'opacity 300ms';
            toast.style.opacity = '0';
            setTimeout(function () { container.removeChild(toast); }, 300);
        }, 3000);
    }

    // attach click handlers to demo links (data-demo attribute)
    var demoLinks = document.querySelectorAll('[data-demo]');
    demoLinks.forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var action = el.getAttribute('data-demo') || 'demo';
            var model = el.getAttribute('data-model') || '';
            var title = model ? model + ' — ' + action + ' demo' : action + ' demo';

            if (action === 'store') {
                showToast(title + ' (simulasi: gunakan borang Cipta)', 'success');
                return;
            }

            if (action === 'update') {
                showToast(title + ' (simulasi: gunakan borang Sunting)', 'success');
                return;
            }

            // generic demo
            showToast(title, 'info');
        });
    });

    // preserve destroy demo behaviour if the button exists
    var destroyBtn = document.getElementById('demo-destroy');
    if (destroyBtn) {
        destroyBtn.addEventListener('click', function () {
            if (window.Swal && typeof window.Swal.fire === 'function') {
                Swal.fire({
                    title: 'Demo: Destroy',
                    text: 'Ini hanya demo — tiada data akan dipadam. Teruskan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, show demo',
                    cancelButtonText: 'Cancel'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        showToast('Destroy action simulated — no data changed.', 'info');
                    }
                });
                return;
            }

            if (confirm('Demo: Ini hanya demo — tiada data akan dipadam. Teruskan?')) {
                showToast('Destroy action simulated — no data changed.', 'info');
            }
        });
    }
});
