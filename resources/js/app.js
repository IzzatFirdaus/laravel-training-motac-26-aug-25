import './bootstrap';
import Swal from 'sweetalert2';

// Namespace under window.MYDS to avoid polluting global scope
window.MYDS = window.MYDS || {};
window.MYDS.Swal = Swal;

// Small inline SVGs used for the theme toggle. Kept minimal and decorative (aria-hidden).
var __MYDS_SVG_SUN = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="12" cy="12" r="4" fill="currentColor"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
var __MYDS_SVG_MOON = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" fill="currentColor"/></svg>';

window.MYDS.handleDestroy = function(btn) {
	var title = 'Amaran';
	var text = 'Ini hanya contoh: tindakan ini akan memadam item jika dihantar. Teruskan?';
	var confirmText = 'Ya, padam';
	var cancelText = 'Batal';

	if (window.MYDS.Swal) {
		// derive button colours from CSS custom properties so themes are respected
		var cs = getComputedStyle(document.documentElement);
		var confirmColor = cs.getPropertyValue('--danger').trim() || '#d33';
		var cancelColor = cs.getPropertyValue('--primary').trim() || '#3085d6';
		window.MYDS.Swal.fire({
			title: title,
			text: text,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: confirmColor,
			cancelButtonColor: cancelColor,
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

// Theme toggle: persist preference in localStorage and apply data-theme attribute
function applyTheme(theme) {
	if (theme === 'dark') {
		document.documentElement.setAttribute('data-theme', 'dark');
	var icon = document.getElementById('theme-toggle-icon');
	if (icon) icon.innerHTML = __MYDS_SVG_MOON;
		var btn = document.getElementById('theme-toggle');
		if (btn) btn.setAttribute('aria-pressed', 'true');
	} else {
		// explicitly set light to make checks deterministic
		document.documentElement.setAttribute('data-theme', 'light');
	var icon = document.getElementById('theme-toggle-icon');
	if (icon) icon.innerHTML = __MYDS_SVG_SUN;
		var btn = document.getElementById('theme-toggle');
		if (btn) btn.setAttribute('aria-pressed', 'false');
	}
}

// expose theme helper on the MYDS namespace
window.MYDS.applyTheme = applyTheme;

// initialize theme after DOM is ready so elements exist
document.addEventListener('DOMContentLoaded', function () {
	var stored = null;
	try { stored = localStorage.getItem('myds_theme'); } catch (e) { /* ignore */ }
	var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
	var theme = stored || (prefersDark ? 'dark' : 'light');
	applyTheme(theme === 'dark' ? 'dark' : 'light');

	var toggleBtn = document.getElementById('theme-toggle');
	if (toggleBtn) {
		toggleBtn.addEventListener('click', function (e) {
			var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
			var next = isDark ? 'light' : 'dark';
			try { localStorage.setItem('myds_theme', next); } catch (err) {}
			applyTheme(next);
		});
	} else {
		// fallback: delegated click handler for older browsers or if button not found
		document.addEventListener('click', function (e) {
			var target = e.target;
			if (!target) return;
			var toggle = (target.id === 'theme-toggle') || (target.closest && target.closest('#theme-toggle'));
			if (toggle) {
				var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
				var next = isDark ? 'light' : 'dark';
				try { localStorage.setItem('myds_theme', next); } catch (err) {}
				applyTheme(next);
			}
		});
	}
});

// Delegated handlers for MYDS form actions and links.
document.addEventListener('DOMContentLoaded', function () {
	// Click delegation for destructive actions inside forms marked with data-myds-form
	document.body.addEventListener('click', function (e) {
		var el = e.target;
		if (!el) return;
		var btn = el.closest && el.closest('button, a');
		if (!btn) return;
		var form = btn.closest && btn.closest('form');
		if (!form || !form.hasAttribute('data-myds-form')) return;

		// Only intercept non-submit buttons intended for destructive actions.
		var isButton = btn.tagName.toLowerCase() === 'button';
		var isDanger = btn.classList.contains('myds-btn--danger') || btn.classList.contains('text-danger') || (btn.getAttribute('aria-label') || '').toLowerCase().includes('padam');

		if (isButton && isDanger) {
			e.preventDefault();
			if (window.MYDS && typeof window.MYDS.handleDestroy === 'function') {
				window.MYDS.handleDestroy(btn);
			} else if (confirm('Ini akan memadam item. Teruskan?')) {
				form.submit();
			}
		}
	}, true);

	// Optional: make links marked with data-myds="link" behave as standard navigations (no-op here, but reserved for future enhancements)
	document.body.addEventListener('click', function (e) {
		var link = e.target.closest && e.target.closest('[data-myds="link"]');
		if (!link) return;
		// allow normal navigation; this handler exists so we can later enhance link behavior (analytics, prefetch) centrally
	}, true);

	// Search keyboard shortcuts disabled (no search bar rendered)
});
