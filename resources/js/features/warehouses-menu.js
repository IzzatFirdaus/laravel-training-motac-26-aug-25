// resources/js/features/warehouses-menu.js
// Dynamically populates the warehouses dropdown menu (MYDS + MyGOVEA: clear navigation, data-driven)
export default function initWarehousesMenu() {
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

    for (const w of warehouses) {
      const header = document.createElement('h6');
      header.className = 'dropdown-header';
      header.textContent = w.name || `Gudang ${w.id}`;
      container.appendChild(header);

      // fetch shelves for warehouse
      const shelves = await fetchJSON(`/warehouses/${encodeURIComponent(w.id)}/shelves`);
      if (!shelves.length) {
        const none = document.createElement('div');
        none.className = 'dropdown-item text-muted small';
        none.textContent = '(Tiada rak)';
        container.appendChild(none);
      } else {
        for (const s of shelves) {
          const a = document.createElement('a');
          a.className = 'dropdown-item';
          a.href = `${(base ? base.replace(/\/$/, '') : '')}/inventories/create?warehouse_id=${encodeURIComponent(w.id)}&shelf_id=${encodeURIComponent(s.id)}`;
          a.setAttribute('role', 'menuitem');
          a.textContent = s.shelf_number || s.name || `Rak ${s.id}`;
          container.appendChild(a);
        }
      }

      const divider = document.createElement('div');
      divider.className = 'dropdown-divider';
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
