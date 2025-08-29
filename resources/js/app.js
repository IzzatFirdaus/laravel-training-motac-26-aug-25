import './bootstrap';
import Swal from 'sweetalert2';
import enhanceUsersAutocomplete from './users-autocomplete';

// Namespace under window.MYDS to avoid polluting global scope
window.MYDS = window.MYDS || {};
window.MYDS.Swal = Swal;

// Small inline SVGs used for the theme toggle. Kept minimal and decorative (aria-hidden).
const __MYDS_SVG_SUN = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="12" cy="12" r="4" fill="currentColor"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
const __MYDS_SVG_MOON = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" fill="currentColor"/></svg>';

window.MYDS.handleDestroy = function (btn) {
	const title = 'Amaran';
	const text = 'Ini hanya contoh: tindakan ini akan memadam item jika dihantar. Teruskan?';
	const confirmText = 'Ya, padam';
	const cancelText = 'Batal';

	if (window.MYDS.Swal) {
		// derive button colours from CSS custom properties so themes are respected
		const cs = getComputedStyle(document.documentElement);
		const confirmColor = (cs.getPropertyValue('--danger') || '#d33').trim();
		const cancelColor = (cs.getPropertyValue('--primary') || '#3085d6').trim();
		window.MYDS.Swal.fire({
			title,
			text,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: confirmColor,
			cancelButtonColor: cancelColor,
			confirmButtonText: confirmText,
			cancelButtonText: cancelText,
		}).then((result) => {
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

// Theme toggle: persist preference in localStorage and apply data-theme attribute
function applyTheme(theme) {
	const icon = document.getElementById('theme-toggle-icon');
	const btn = document.getElementById('theme-toggle');

	if (theme === 'dark') {
		document.documentElement.setAttribute('data-theme', 'dark');
		if (icon) icon.innerHTML = __MYDS_SVG_MOON;
		if (btn) btn.setAttribute('aria-pressed', 'true');
	} else {
		// explicitly set light to make checks deterministic
		document.documentElement.setAttribute('data-theme', 'light');
		if (icon) icon.innerHTML = __MYDS_SVG_SUN;
		if (btn) btn.setAttribute('aria-pressed', 'false');
	}
}

// expose theme helper on the MYDS namespace
window.MYDS.applyTheme = applyTheme;

// Single DOMContentLoaded handler: initialize theme, toast, and event delegation
document.addEventListener('DOMContentLoaded', () => {
	// --- Toast handling ---
	const toast = document.getElementById('global-toast');
	if (toast) {
		const msg = (toast.getAttribute('data-toast') || '').trim();
		if (msg && window.MYDS.Swal && typeof window.MYDS.Swal.fire === 'function') {
			window.MYDS.Swal.fire({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 4000,
				icon: 'success',
				title: msg,
			});
		}
	}

	// --- Theme initialization ---
	let stored = null;
	try { stored = localStorage.getItem('myds_theme'); } catch (e) { /* ignore */ }
	const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
	const theme = stored || (prefersDark ? 'dark' : 'light');
	applyTheme(theme === 'dark' ? 'dark' : 'light');

	const toggleBtn = document.getElementById('theme-toggle');
	if (toggleBtn) {
		toggleBtn.addEventListener('click', (e) => {
			const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
			const next = isDark ? 'light' : 'dark';
			try { localStorage.setItem('myds_theme', next); } catch (err) {}
			applyTheme(next);
		});
	}

	// --- Delegated handlers for MYDS form actions and links ---
	document.body.addEventListener('click', (e) => {
		const el = e.target;
		if (!el) return;
		const btn = el.closest && el.closest('button, a');
		if (!btn) return;
		const form = btn.closest && btn.closest('form');
		if (!form || !form.hasAttribute('data-myds-form')) return;

		// Only intercept non-submit buttons intended for destructive actions.
		const isButton = btn.tagName.toLowerCase() === 'button';
		const aria = (btn.getAttribute('aria-label') || '').toLowerCase();
		const isDanger = btn.classList.contains('myds-btn--danger') || btn.classList.contains('text-danger') || aria.includes('padam');

		if (isButton && isDanger) {
			e.preventDefault();
			if (window.MYDS && typeof window.MYDS.handleDestroy === 'function') {
				window.MYDS.handleDestroy(btn);
			} else if (confirm('Ini akan memadam item. Teruskan?')) {
				form.submit();
			}
		}
	}, true);

	// Intercept submit events on forms marked with data-myds-form so that
	// submit buttons (type=submit) still show the confirmation dialog.
	document.body.addEventListener('submit', (e) => {
		const form = e.target;
		if (!form || !form.hasAttribute || !form.hasAttribute('data-myds-form')) return;

		// find the submitter button (modern browsers support e.submitter)
		let submitter = e.submitter;
		if (!submitter) {
			// fallback: try to find a button with type=submit that has focus or is inside the form
			submitter = form.querySelector('button[type="submit"], input[type="submit"]');
		}

		const aria = (submitter && submitter.getAttribute('aria-label') || '').toLowerCase();
		const isDanger = submitter && (submitter.classList.contains('myds-btn--danger') || aria.includes('padam'));
		if (!isDanger) return; // allow normal submits for non-destructive forms

		e.preventDefault();
		if (window.MYDS && typeof window.MYDS.handleDestroy === 'function') {
			window.MYDS.handleDestroy(submitter);
		} else if (confirm('Ini akan memadam item. Teruskan?')) {
			form.submit();
		}
	}, true);

	// Reserved hook for links with data-myds="link" (no-op for now)
	document.body.addEventListener('click', (e) => {
		const link = e.target.closest && e.target.closest('[data-myds="link"]');
		if (!link) return;
		// allow normal navigation; central hook for future enhancements
	}, true);

	// Search keyboard shortcuts intentionally disabled (no search bar rendered)

	// Initialize users autocomplete enhancement if present
	try {
		enhanceUsersAutocomplete('#users-autocomplete');
	} catch (err) {
		// non-fatal
	}
});
