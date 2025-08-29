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
    perPage.addEventListener('change', () => form.submit());
  }

  const owner = document.getElementById('owner_id');
  if (owner) {
    owner.addEventListener('change', () => form.submit());
  }

  const search = document.getElementById('search');
  if (search) {
    const submitSearch = debounce(() => form.submit(), 400);
    search.addEventListener('input', submitSearch);
  }
});

export default {};
