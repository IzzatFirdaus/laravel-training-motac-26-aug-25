// Enhancements for Application form: user autocomplete and vehicles-by-inventory
export default function enhanceApplicationForm(root = document) {
  const nameInput = root.querySelector('#name');
  const usersList = root.querySelector('#users-list');
  const usersWrapper = root.querySelector('#users-autocomplete');
  const ownerSelect = root.querySelector('#user_id');

  if (nameInput && usersList) {
    async function fetchUsers(q = '') {
      try {
        const url = new URL('/users/search', window.location.origin);
        if (q) url.searchParams.set('q', q);
        const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!res.ok) return [];
        return await res.json();
      } catch { return []; }
    }

    function selectUser(u) {
      if (!u) return;
      if (ownerSelect) {
        const opt = Array.from(ownerSelect.options).find(o => o.value === String(u.id));
        if (opt) ownerSelect.value = u.id; else {
          const newOpt = document.createElement('option');
          newOpt.value = u.id; newOpt.text = u.name; newOpt.selected = true; ownerSelect.appendChild(newOpt);
        }
      } else {
        let hidden = root.querySelector('input[name="user_id"]');
        if (!hidden) { hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.name = 'user_id'; root.querySelector('form')?.appendChild(hidden); }
        hidden.value = u.id;
      }
      nameInput.value = (nameInput.value || '').replace(/\s*\u2014\s*.*$/, '') + ' \u2014 ' + u.name;
      usersList.style.display = 'none';
      usersList.setAttribute('aria-hidden', 'true');
    }

    function renderUsers(items) {
      usersList.innerHTML = '';
      usersList.style.display = 'none';
      if (!Array.isArray(items) || items.length === 0) return;
      items.forEach((u) => {
        const li = document.createElement('li');
        li.className = 'list-group-item list-group-item-action';
        li.setAttribute('role', 'option');
        li.setAttribute('tabindex', '0');
        li.textContent = u.name;
        li.dataset.userId = u.id;
        li.addEventListener('click', () => selectUser(u));
        li.addEventListener('keydown', (ev) => {
          if (ev.key === 'Enter' || ev.key === ' ') { ev.preventDefault(); selectUser(u); }
          if (ev.key === 'ArrowDown') { ev.preventDefault(); li.nextElementSibling?.focus(); }
          if (ev.key === 'ArrowUp') { ev.preventDefault(); li.previousElementSibling?.focus(); }
        });
        usersList.appendChild(li);
      });
      usersList.style.display = 'block';
      usersList.setAttribute('aria-hidden', 'false');
    }

    nameInput.addEventListener('focus', async () => { renderUsers(await fetchUsers(nameInput.value.trim())); });
    nameInput.addEventListener('input', async () => {
      const q = nameInput.value.trim();
      if (!q) { usersList.style.display = 'none'; return; }
      renderUsers(await fetchUsers(q));
    });
    document.addEventListener('click', (ev) => {
      if (!usersWrapper.contains(ev.target) && ev.target !== nameInput) {
        usersList.style.display = 'none'; usersList.setAttribute('aria-hidden', 'true');
      }
    });
    document.addEventListener('keydown', (ev) => {
      if (ev.key === 'Escape') { usersList.style.display = 'none'; usersList.setAttribute('aria-hidden', 'true'); nameInput.focus(); }
    });
  }

  const inventorySelect = root.querySelector('#inventory_id');
  const vehicleSelect = root.querySelector('#vehicle_id');
  if (inventorySelect && vehicleSelect) {
    async function fetchVehiclesForInventory(inventoryId) {
      if (!inventoryId) return [];
      try {
        const url = new URL(`/inventories/${inventoryId}/vehicles`, window.location.origin);
        const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!res.ok) return [];
        return await res.json();
      } catch { return []; }
    }
    function populateVehicleSelect(items, selectedId = null) {
      const previousVal = vehicleSelect.value || '';
      vehicleSelect.innerHTML = '';
      const empty = document.createElement('option'); empty.value = ''; empty.text = '(tiada)'; vehicleSelect.appendChild(empty);
      (items || []).forEach((v) => {
        const opt = document.createElement('option'); opt.value = v.id; opt.text = v.name;
        if (String(previousVal) === String(v.id) || (selectedId && String(selectedId) === String(v.id))) opt.selected = true;
        vehicleSelect.appendChild(opt);
      });
    }
    inventorySelect.addEventListener('change', async () => {
      populateVehicleSelect(await fetchVehiclesForInventory(inventorySelect.value));
    });
    (async function init() {
      const invId = inventorySelect.value; if (invId) populateVehicleSelect(await fetchVehiclesForInventory(invId), vehicleSelect.value);
    })();
  }
}

// Auto-initialize if loaded directly via Vite on pages with the form
document.addEventListener('DOMContentLoaded', () => {
  const root = document.getElementById('main-content') || document;
  enhanceApplicationForm(root);
});
