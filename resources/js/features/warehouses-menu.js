// resources/js/features/warehouses-menu.js
// Dynamically populates the warehouses dropdown menu (MYDS + MyGOVEA: clear navigation, data-driven, accessible)
export default function initWarehousesMenu() {
  if (initWarehousesMenu._initialized) return;
  initWarehousesMenu._initialized = true;

  const container = document.querySelector('#warehouses-list');
  if (!container) return;

  async function fetchJSON(url) {
    try {
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      if (!res.ok) return [];
      return await res.json();
    } catch (_) { return []; }
  }

  async function run() {
    const base = document.querySelector('base')?.href || '';
    const warehousesUrl = (container.getAttribute('data-fetch-url') || '/warehouses');
    const warehouses = await fetchJSON(new URL(warehousesUrl, window.location.origin).toString());
    container.innerHTML = '';
    container.setAttribute('role', 'menu');
    container.setAttribute('aria-label', 'Senarai gudang dan rak');

    for (const w of warehouses) {
      const header = document.createElement('h6');
      header.className = 'dropdown-header';
      header.textContent = w.name || `Gudang ${w.id}`;
      header.setAttribute('role', 'presentation');
      container.appendChild(header);

      // fetch shelves for warehouse
      const shelves = await fetchJSON(`/warehouses/${encodeURIComponent(w.id)}/shelves`);
      if (!shelves.length) {
        const none = document.createElement('div');
        none.className = 'dropdown-item text-muted small';
        none.textContent = '(Tiada rak)';
        none.setAttribute('role', 'presentation');
        container.appendChild(none);
      } else {
        for (const s of shelves) {
          const a = document.createElement('a');
          a.className = 'dropdown-item';
          a.href = `${(base ? base.replace(/\/$/, '') : '')}/inventories/create?warehouse_id=${encodeURIComponent(w.id)}&shelf_id=${encodeURIComponent(s.id)}`;
          a.setAttribute('role', 'menuitem');
          a.setAttribute('aria-describedby', `shelf-${s.id}-desc`);
          a.textContent = s.shelf_number || s.name || `Rak ${s.id}`;
          container.appendChild(a);
        }
      }

      const divider = document.createElement('div');
      divider.className = 'dropdown-divider';
      divider.setAttribute('role', 'separator');
      container.appendChild(divider);
    }
  }

  // Auto-run non-blocking
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', run);
  } else {
    run();
  }
}

// auto-init for convenience when module loaded directly
if (typeof document !== 'undefined' && document.readyState !== 'loading') {
  initWarehousesMenu();
}

// Expose for manual initialization
window.MYDS = window.MYDS || {};
window.MYDS.initWarehousesMenu = initWarehousesMenu;
