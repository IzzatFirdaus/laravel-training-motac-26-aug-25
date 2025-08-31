/**
 * application-form.js
 * Enhancements for Application forms:
 * - users autocomplete (re-usable, ARIA-compliant)
 * - inventory -> vehicle dependent select population
 *
 * MYDS/MyGOVEA: Idempotent, accessible, and citizen-centric form enhancements.
 */
export default function enhanceApplicationForm(root = document) {
  if (!root) root = document;
  if (root._applicationFormEnhanced) return;
  root._applicationFormEnhanced = true;

  const nameInput = root.querySelector('#name');
  const usersList = root.querySelector('#users-list');
  const usersWrapper = root.querySelector('#users-autocomplete');
  const ownerSelect = root.querySelector('#user_id');

  // users autocomplete logic
  if (nameInput && usersList && usersWrapper) {
    const searchUrl = usersWrapper.dataset.searchUrl || '/users/search';

    async function fetchUsers(q = '') {
      try {
        const url = new URL(searchUrl, window.location.origin);
        if (q) url.searchParams.set('q', q);
        const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!res.ok) return [];
        return await res.json();
      } catch (err) { return []; }
    }

    function selectUser(u) {
      if (!u) return;
      if (ownerSelect) {
        const opt = Array.from(ownerSelect.options).find(o => o.value === String(u.id));
        if (opt) ownerSelect.value = u.id;
        else {
          const newOpt = document.createElement('option');
          newOpt.value = u.id; newOpt.text = u.name; newOpt.selected = true;
          ownerSelect.appendChild(newOpt);
        }
      } else {
        let hidden = root.querySelector('input[name="user_id"]');
        if (!hidden) {
          hidden = document.createElement('input');
          hidden.type = 'hidden';
          hidden.name = 'user_id';
          root.querySelector('form')?.appendChild(hidden);
        }
        hidden.value = u.id;
      }

      nameInput.value = (nameInput.value || '').replace(/\s*\u2014\s*.*$/, '').trim() + ' — ' + u.name;
      usersList.classList.add('visually-hidden');
      usersList.setAttribute('aria-hidden', 'true');
      nameInput.setAttribute('aria-expanded', 'false');
      nameInput.focus();
    }

    async function renderUsers(q) {
      const arr = await fetchUsers(q);
      usersList.innerHTML = '';
      if (!arr || !arr.length) {
        usersList.classList.add('visually-hidden');
        usersList.setAttribute('aria-hidden', 'true');
        return;
      }
      usersList.setAttribute('role', 'listbox');
      usersList.setAttribute('aria-label', 'Senarai pengguna berkaitan');
      arr.forEach((u) => {
        const li = document.createElement('li');
        li.className = 'myds-autocomplete-item';
        li.setAttribute('role', 'option');
        li.setAttribute('tabindex', '0');
        li.setAttribute('aria-describedby', `user-${u.id}-desc`);
        li.textContent = u.name;
        li.addEventListener('click', () => selectUser(u));
        li.addEventListener('keydown', (ev) => {
          if (ev.key === 'Enter' || ev.key === ' ') { ev.preventDefault(); selectUser(u); }
          else if (ev.key === 'ArrowDown') {
            ev.preventDefault();
            const next = li.nextElementSibling;
            if (next) next.focus();
          }
          else if (ev.key === 'ArrowUp') {
            ev.preventDefault();
            const prev = li.previousElementSibling;
            if (prev) prev.focus();
            else nameInput.focus();
          }
        });
        usersList.appendChild(li);
      });
      usersList.classList.remove('visually-hidden');
      usersList.setAttribute('aria-hidden', 'false');
    }

    if (!nameInput._appFormHandlersAttached) {
      nameInput.setAttribute('aria-autocomplete', 'list');
      nameInput.setAttribute('aria-expanded', 'false');
      if (usersList) nameInput.setAttribute('aria-controls', usersList.id || 'users-list');

      nameInput.addEventListener('input', (ev) => {
        const q = nameInput.value.trim();
        if (!q) {
          usersList.innerHTML = '';
          usersList.classList.add('visually-hidden');
          usersList.setAttribute('aria-hidden', 'true');
          nameInput.setAttribute('aria-expanded', 'false');
          return;
        }
        nameInput.setAttribute('aria-expanded', 'true');
        renderUsers(q);
      });

      nameInput.addEventListener('focus', () => {
        const q = nameInput.value.trim();
        if (q) {
          nameInput.setAttribute('aria-expanded', 'true');
          renderUsers(q);
        }
      });

      nameInput.addEventListener('keydown', (ev) => {
        if (ev.key === 'ArrowDown') {
          ev.preventDefault();
          const firstItem = usersList.querySelector('[role="option"]');
          if (firstItem) firstItem.focus();
        } else if (ev.key === 'Escape') {
          usersList.classList.add('visually-hidden');
          usersList.setAttribute('aria-hidden', 'true');
          nameInput.setAttribute('aria-expanded', 'false');
        }
      });

      document.addEventListener('click', (ev) => {
        try {
          if (!usersWrapper.contains(ev.target) && ev.target !== nameInput) {
            usersList.classList.add('visually-hidden');
            usersList.setAttribute('aria-hidden', 'true');
            nameInput.setAttribute('aria-expanded', 'false');
          }
        } catch (e) { /* ignore */ }
      });

      nameInput._appFormHandlersAttached = true;
    }
  }

  // inventory -> vehicle dependency
  const inventorySelect = root.querySelector('#inventory_id');
  const vehicleSelect = root.querySelector('#vehicle_id');
  if (inventorySelect && vehicleSelect) {
    const vehiclesUrlTemplate = inventorySelect.dataset.vehiclesUrl || inventorySelect.dataset.fetchUrl || '/inventories/{id}/vehicles';

    async function fetchVehiclesForInventory(inventoryId) {
      if (!inventoryId) return [];
      try {
        let urlStr = vehiclesUrlTemplate;
        if (urlStr.indexOf('{id}') !== -1) {
          urlStr = urlStr.replace('{id}', encodeURIComponent(inventoryId));
        } else {
          urlStr = `/inventories/${encodeURIComponent(inventoryId)}/vehicles`;
        }
        const url = new URL(urlStr, window.location.origin);
        const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!res.ok) return [];
        return await res.json();
      } catch (err) { return []; }
    }

    function populateVehicleSelect(items, selectedId = null) {
      const prev = vehicleSelect.value || '';
      vehicleSelect.innerHTML = '';
      const empty = document.createElement('option'); empty.value = ''; empty.text = '(tiada)'; vehicleSelect.appendChild(empty);
      (items || []).forEach((v) => {
        const opt = document.createElement('option');
        opt.value = v.id; opt.text = v.name;
        if (String(prev) === String(v.id) || (selectedId && String(selectedId) === String(v.id))) opt.selected = true;
        vehicleSelect.appendChild(opt);
      });
    }

    if (!inventorySelect._appFormChangeAttached) {
      inventorySelect.addEventListener('change', async () => {
        const items = await fetchVehiclesForInventory(inventorySelect.value);
        populateVehicleSelect(items);
      });
      inventorySelect._appFormChangeAttached = true;
    }

    // initial populate if value exists
    (async function init() {
      const invId = inventorySelect.value;
      if (invId) {
        const items = await fetchVehiclesForInventory(invId);
        populateVehicleSelect(items, vehicleSelect.value);
      }
    })();
  }
}

/* auto-init when loaded as module in the page */
document.addEventListener('DOMContentLoaded', () => {
  try {
    const root = document.getElementById('main-content') || document;
    enhanceApplicationForm(root);
  } catch (err) { /* non-fatal */ }
});

// Expose for manual initialization
window.MYDS = window.MYDS || {};
window.MYDS.enhanceApplicationForm = enhanceApplicationForm;
