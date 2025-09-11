/**
 * users-index.js
 * Page-specific enhancements for the users index page
 * - Enhances filters and search functionality
 * - Manages table interactions and user management features
 * - Follows MYDS accessibility guidelines
 */

(function () {
  'use strict';

  /**
   * Initialize users index page features
   */
  function init() {
    // Add any specific functionality for users index page
    console.log('Users index page initialized');

    // Example: Enhanced search functionality
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
      // Add debounced search if needed
      searchInput.setAttribute('autocomplete', 'off');
    }

    // Example: Table row click handlers for better UX
    const tableRows = document.querySelectorAll('.myds-table tbody tr');
    tableRows.forEach(row => {
      const viewLink = row.querySelector('a[href*="/users/"]');
      if (viewLink) {
        row.style.cursor = 'pointer';
        row.addEventListener('click', (e) => {
          // Only navigate if not clicking on buttons or links
          if (!e.target.closest('button, a')) {
            window.location = viewLink.href;
          }
        });
      }
    });
  }

  // Expose for manual calls if needed
  window.MYDS = window.MYDS || {};
  window.MYDS.initUsersIndex = init;

  // Auto-initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
