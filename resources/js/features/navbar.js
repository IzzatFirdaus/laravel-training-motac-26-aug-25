/**
 * navbar.js
 *
 * Initializes navbar behaviors, relying on Bootstrap for core functionality.
 * Ensures accessibility and MYDS compliance.
 *
 * Idempotent and defensive.
 */
(function () {
  'use strict';

  /**
   * Closes all open dropdown menus in the navbar, except for an optional element to ignore.
   * @param {Element} [ignoreEl=null] - The dropdown element to ignore, if any.
   */
  function closeAllDropdowns(ignoreEl = null) {
    const openDropdowns = document.querySelectorAll('.navbar .dropdown-menu.show');
    openDropdowns.forEach(dropdown => {
      const toggle = document.querySelector(`[aria-controls="${dropdown.id}"], [data-bs-target="#${dropdown.id}"]`);
      if (toggle && toggle !== ignoreEl && bootstrap.Dropdown.getInstance(toggle)) {
        bootstrap.Dropdown.getInstance(toggle).hide();
      }
    });
  }

  /**
   * Attaches event listeners for enhanced navbar behavior.
   */
  function attachEventListeners() {
    // Use a flag to prevent attaching listeners multiple times
    if (document.body.dataset.navbarListenersAttached === 'true') return;
    document.body.dataset.navbarListenersAttached = 'true';

    // Close dropdowns when clicking outside
    document.addEventListener('click', (event) => {
      if (!event.target.closest('.navbar')) {
        closeAllDropdowns();
      }
    });

    // Close dropdowns with the Escape key
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        closeAllDropdowns();
        // Also ensure the main nav collapse is closable with Escape
        const navMain = document.getElementById('navMain');
        const navToggle = document.getElementById('navToggle');
        if (navMain && navMain.classList.contains('show') && bootstrap.Collapse.getInstance(navMain)) {
          bootstrap.Collapse.getInstance(navMain).hide();
          navToggle?.focus();
        }
      }
    });
  }

  /**
   * Initializes all navbar functionalities.
   */
  function init() {
    // This function can be expanded if more navbar-specific JS is needed later.
    attachEventListeners();
  }

  // Expose for manual calls if needed, following the project's pattern
  window.MYDS = window.MYDS || {};
  window.MYDS.initNavbar = init;

  // Run initialization on DOMContentLoaded
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    // DOM is already ready
    init();
  }
})();
