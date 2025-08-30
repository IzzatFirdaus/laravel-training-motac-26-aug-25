// MYDS/MyGOVEA: page-specific JS for inventories edit
// - Progressive enhancement of form interactions
// - Ensure ARIA roles for dynamic lists and selects
document.addEventListener('DOMContentLoaded', function () {
  // Simple user autocomplete (click to select)
  const nameInput = document.getElementById('name');
  const usersList = document.getElementById('users-list');
  const usersWrapper = document.getElementById('users-autocomplete');
  const ownerSelect = document.getElementById('user_id');

  async function fetchUsers(q = '') {
    const urlStr = usersWrapper?.dataset?.searchUrl || '';
    if (!urlStr) return [];
    try {
      const url = new URL(urlStr, window.location.origin);
      if (q) url.searchParams.set('q', q);
      const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      if (!res.ok) return [];
      return await res.json();
    } catch (e) { return []; }
  }

  function renderUsers(items) {
    if (!usersList) return;
    usersList.innerHTML = '';
    if (!items || !items.length) { usersList.style.display = 'none'; return; }
    items.forEach(u => {
      const li = document.createElement('li');
      li.className = 'list-group-item list-group-item-action';
      li.textContent = u.name;
      li.setAttribute('role', 'option');
      li.addEventListener('click', () => {
        if (ownerSelect) {
          const existing = Array.from(ownerSelect.options).find(o => o.value === String(u.id));
          if (existing) existing.selected = true;
          else {
            const opt = document.createElement('option');
            opt.value = u.id; opt.text = u.name; opt.selected = true;
            ownerSelect.appendChild(opt);
          }
        }
        if (nameInput) {
          nameInput.value = (nameInput.value || '').replace(/\s*—\s*.*$/, '') + ' — ' + u.name;
        }
        usersList.style.display = 'none';
      });
      usersList.appendChild(li);
    });
    usersList.style.display = 'block';
  }

  if (nameInput && usersList && usersWrapper) {
    nameInput.addEventListener('focus', async () => renderUsers(await fetchUsers(nameInput.value.trim())));
    nameInput.addEventListener('input', async () => renderUsers(await fetchUsers(nameInput.value.trim())));
    document.addEventListener('click', (ev) => {
      if (!usersWrapper.contains(ev.target)) usersList.style.display = 'none';
    });
  }

  // Vehicles: augment list with any available vehicles
  (async function initVehicleMulti() {
    const vehicleSelect = document.getElementById('vehicle_ids');
    if (!vehicleSelect) return;
    const match = window.location.pathname.match(/\/inventories\/(\d+)/);
    const invId = match ? match[1] : null;
    if (!invId) return;
    try {
      const res = await fetch('/inventories/' + invId + '/vehicles', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      if (!res.ok) return;
      const items = await res.json();
      const existing = new Set(Array.from(vehicleSelect.options).map(o => String(o.value)));
      (items || []).forEach(v => {
        if (!existing.has(String(v.id))) {
          const opt = document.createElement('option');
          opt.value = v.id; opt.text = v.name;
          vehicleSelect.appendChild(opt);
        }
      });
    } catch (e) { /* ignore */ }
  })();

  // Warehouse -> Shelf progressive enhancement
  const warehouseSelect = document.getElementById('warehouse_id');
  const shelfSelect = document.getElementById('shelf_id');
  async function fetchJSON(url) {
    try {
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      if (!res.ok) return [];
      return await res.json();
    } catch (e) { return []; }
  }
  function populateSelect(select, items, selectedValue) {
    if (!select) return;
    select.innerHTML = '';
    const empty = document.createElement('option'); empty.value = ''; empty.textContent = '(Pilih)'; select.appendChild(empty);
    (items || []).forEach(it => {
      const opt = document.createElement('option');
      opt.value = it.id; opt.textContent = it.name || it.shelf_number || it.id;
      if (selectedValue && String(selectedValue) === String(it.id)) opt.selected = true;
      select.appendChild(opt);
    });
  }

  (async function initStorage() {
    if (!warehouseSelect || !shelfSelect) return;
    const warehousesUrl = warehouseSelect.dataset.warehousesUrl || '/warehouses';
    const initialWarehouse = warehouseSelect.dataset.initialWarehouse || '';
    const initialShelf = shelfSelect.dataset.initialShelf || '';

    const warehouses = await fetchJSON(warehousesUrl);
    populateSelect(warehouseSelect, warehouses, initialWarehouse || null);

    if (initialWarehouse) {
      const base = warehousesUrl.replace(/\/$/, '');
      const shelves = await fetchJSON(base + '/' + initialWarehouse + '/shelves');
      populateSelect(shelfSelect, shelves, initialShelf || null);
    }

    warehouseSelect.addEventListener('change', async function () {
      const base = (warehouseSelect.dataset.warehousesUrl || '/warehouses').replace(/\/$/, '');
      const shelves = this.value ? await fetchJSON(base + '/' + this.value + '/shelves') : [];
      populateSelect(shelfSelect, shelves, null);
    });
  })();
});
