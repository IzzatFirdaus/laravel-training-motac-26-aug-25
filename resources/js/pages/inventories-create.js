// resources/js/pages/inventories-create.js
// Progressive enhancement for warehouse -> shelf dependent selects:
// - Reads URLs and initial values from data-* attributes
// - Populates selects via XHR, non-blocking
// - Defensive and idempotent

document.addEventListener('DOMContentLoaded', function () {
  const warehouseSelect = document.getElementById('warehouse_id');
  const shelfSelect = document.getElementById('shelf_id');

  if (!warehouseSelect || !shelfSelect) return;

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
    select.innerHTML = '';
    const empty = document.createElement('option');
    empty.value = '';
    empty.textContent = '(Pilih)';
    select.appendChild(empty);

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
