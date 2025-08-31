// Minimal enhancements for inventories index page
// - Submit the filter form when per_page or owner changes
// - Debounce search input submit when user stops typing

function debounce(fn, wait = 250) {
  let t;
  return function (...args) {
    clearTimeout(t);
    t = setTimeout(() => fn.apply(this, args), wait);
  };
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('inventories-filter-form');
  if (!form) return;

  const perPage = document.getElementById('per_page');
  if (perPage) {
    if (!perPage.dataset.mydsInit) {
      perPage.dataset.mydsInit = '1';
      perPage.addEventListener('change', () => form.submit());
    }
  }

  const owner = document.getElementById('owner_id');
  if (owner) {
    if (!owner.dataset.mydsInit) {
      owner.dataset.mydsInit = '1';
      owner.addEventListener('change', () => form.submit());
    }
  }

  const search = document.getElementById('search');
  if (search) {
    // Mark input for aria-describedby / live announcements
    if (!search.getAttribute('aria-label') && search.dataset.mydsLabel) search.setAttribute('aria-label', search.dataset.mydsLabel);
    const submitSearch = debounce(() => form.submit(), Number(search.dataset.debounce) || 400);
    if (!search.dataset.mydsInit) {
      search.dataset.mydsInit = '1';
      search.addEventListener('input', submitSearch);
    }
  }
});

export default {};
