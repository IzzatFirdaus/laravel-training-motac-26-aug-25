/**
 * Vehicles index page enhancements
 * - Submits the per_page select automatically on change (externalized from inline)
 */

document.addEventListener('DOMContentLoaded', () => {
  const perPage = document.getElementById('per_page');
  if (perPage && perPage.dataset.mydsInit !== '1') {
    perPage.dataset.mydsInit = '1';
    perPage.addEventListener('change', () => {
      const form = perPage.form;
      if (form) form.submit();
    });
  }
});
