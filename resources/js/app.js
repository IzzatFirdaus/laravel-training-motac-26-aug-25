import './bootstrap';
import Swal from 'sweetalert2';

// Namespace under window.MYDS to avoid polluting global scope
window.MYDS = window.MYDS || {};
window.MYDS.Swal = Swal;

window.MYDS.handleDestroy = function(btn) {
	var title = 'Amaran';
	var text = 'Ini hanya contoh: tindakan ini akan memadam item jika dihantar. Teruskan?';
	var confirmText = 'Ya, padam';
	var cancelText = 'Batal';

	if (window.MYDS.Swal) {
		window.MYDS.Swal.fire({
			title: title,
			text: text,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: confirmText,
			cancelButtonText: cancelText
		}).then(function (result) {
			if (result.isConfirmed) {
				btn.closest('form').submit();
			}
		});
	} else {
		if (confirm(text)) {
			btn.closest('form').submit();
		}
	}
};

// Helper to display a global flash message from a server-side rendered data attribute
document.addEventListener('DOMContentLoaded', function () {
	var flash = document.getElementById('global-flash');
	if (flash && flash.textContent && flash.textContent.trim().length) {
		var msg = flash.textContent.trim();
		if (window.MYDS.Swal && typeof window.MYDS.Swal.fire === 'function') {
			window.MYDS.Swal.fire({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 4000,
				icon: 'success',
				title: msg
			});
		} else {
			// fallback: small alert
			var el = document.createElement('div');
			el.textContent = msg;
			el.style.position = 'fixed';
			el.style.top = '1rem';
			el.style.right = '1rem';
			el.style.background = 'rgba(0,0,0,0.85)';
			el.style.color = 'white';
			el.style.padding = '0.5rem 0.75rem';
			el.style.borderRadius = '0.25rem';
			document.body.appendChild(el);
			setTimeout(function () { el.remove(); }, 4000);
		}
	}
});
