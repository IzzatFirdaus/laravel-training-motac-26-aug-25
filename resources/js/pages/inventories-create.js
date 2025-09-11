// resources/js/pages/inventories-create.js
// Progressive enhancement for warehouse -> shelf dependent selects:
// - Reads URLs and initial values from data-* attributes
// - Populates selects via XHR, non-blocking
// - Defensive and idempotent

document.addEventListener('DOMContentLoaded', function () {
  // Initialize users autocomplete enhancement if available
  try {
    // dynamic import for progressive enhancement
    import('../users-autocomplete.js').then(mod => { if (mod && typeof mod.default === 'function') mod.default('#users-autocomplete'); }).catch(() => {});
  } catch (e) {
    // ignore if module system not available in this environment
  }
  const warehouseSelect = document.getElementById('warehouse_id');
  const shelfSelect = document.getElementById('shelf_id');

  if (!warehouseSelect || !shelfSelect) return;

  // Prevent double-initialisation when this script is included multiple times
  if (warehouseSelect.dataset.mydsInit === '1') return;
  warehouseSelect.dataset.mydsInit = '1';

  const warehousesUrl = warehouseSelect.dataset.warehousesUrl || '/warehouses';
  const initialWarehouse = warehouseSelect.dataset.initialWarehouse || '';
  const initialShelf = shelfSelect.dataset.initialShelf || '';

  async function fetchJSON(url) {
    try {
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      if (!res.ok) return [];
      return await res.json();
    } catch (e) {
      return [];
    }
  }

  function populateSelect(select, items, selectedValue) {
  if (!select) return;
  select.innerHTML = '';
  const empty = document.createElement('option');
  empty.value = '';
  empty.textContent = select.dataset.emptyText || '(Pilih)';
  select.appendChild(empty);

  // accessibility: allow an explicit aria-label or derive one from dataset/name
  if (!select.getAttribute('aria-label')) select.setAttribute('aria-label', select.dataset.ariaLabel || select.name || 'Pilih');

    (items || []).forEach(it => {
      const opt = document.createElement('option');
      opt.value = it.id;
      opt.textContent = it.name || it.shelf_number || it.id;
      if (selectedValue && String(selectedValue) === String(it.id)) opt.selected = true;
      select.appendChild(opt);
    });
  }

  (async function init() {
    const warehouses = await fetchJSON(warehousesUrl);
    populateSelect(warehouseSelect, warehouses, initialWarehouse);

    if (initialWarehouse) {
      const base = warehousesUrl.replace(/\/$/, '');
      const shelves = await fetchJSON(`${base}/${initialWarehouse}/shelves`);
      populateSelect(shelfSelect, shelves, initialShelf);
    }
  })();

  warehouseSelect.addEventListener('change', async function () {
    const wid = this.value;
    const base = warehousesUrl.replace(/\/$/, '');
    const shelves = wid ? await fetchJSON(`${base}/${wid}/shelves`) : [];
    populateSelect(shelfSelect, shelves, null);
  });
});
