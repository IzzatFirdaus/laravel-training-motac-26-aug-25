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

try {
	// Import Bootstrap's JS bundle; will be tree-shaken by bundlers if unused.
	// Ensure bootstrap is installed in your dependencies.
	// eslint-disable-next-line import/no-extraneous-dependencies
	import('bootstrap').catch(() => { /* bootstrap not available; continue gracefully */ });
} catch (e) {
	// Older bundlers may not support dynamic import in this context; fall back to static import if needed.
	// import 'bootstrap';
}

/* Axios setup */
import axios from 'axios';

window.axios = window.axios || axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// If Laravel sets XSRF cookie, axios will send it automatically. Ensure backend cookie name matches axios defaults if customised.
// No throw; axios not required for all pages.
