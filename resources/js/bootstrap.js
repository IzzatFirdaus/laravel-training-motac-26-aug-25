/**
 * bootstrap.js
 *
 * Minimal, robust bootstrap for project JavaScript:
 * - Loads Bootstrap JS (if available)
 * - Configures axios with X-Requested-With header and CSRF if present
 * - Keeps echo/broadcasting commented out for optional usage
 *
 * This file is safe to import multiple times and avoids throwing when optional libs are absent.
 */

// Import Bootstrap's JS bundle
import 'bootstrap';

/* Axios setup */
import axios from 'axios';

window.axios = window.axios || axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// If Laravel sets XSRF cookie, axios will send it automatically. Optionally read CSRF token meta and set header for non-cookie setups.
if (!window.axios._mydsCsrfSetup) {
	window.axios._mydsCsrfSetup = true;
	const meta = document.querySelector('meta[name="csrf-token"]');
	if (meta && meta.content) {
		window.axios.defaults.headers.common['X-CSRF-TOKEN'] = meta.content;
	}
}
