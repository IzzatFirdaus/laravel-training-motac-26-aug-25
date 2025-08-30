// resources/js/features/warehouses-menu.js
// Populate Warehouses dropdown menu dynamically (MYDS + MyGOVEA: clear navigation, data-driven)
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
    const warehouses = await fetchJSON((base ? base.replace(/\/$/, '') : '') + '/warehouses');
    container.innerHTML = '';

    for (const w of warehouses) {
      // warehouse header
      const header = document.createElement('h6');
      header.className = 'dropdown-header';
      header.textContent = w.name;
      container.appendChild(header);

      const shelves = await fetchJSON(`/warehouses/${encodeURIComponent(w.id)}/shelves`);
      if (shelves.length === 0) {
        const none = document.createElement('div');
        none.className = 'dropdown-item text-muted small';
        none.textContent = '(Tiada rak)';
        container.appendChild(none);
      } else {
        for (const s of shelves) {
          const a = document.createElement('a');
          a.className = 'dropdown-item';
          a.href = `${(base ? base.replace(/\/$/, '') : '') + '/inventories/create'}?warehouse_id=${encodeURIComponent(w.id)}&shelf_id=${encodeURIComponent(s.id)}`;
          a.textContent = s.shelf_number;
          container.appendChild(a);
        }
      }

      const divider = document.createElement('div');
      divider.className = 'dropdown-divider';
      container.appendChild(divider);
    }
  }

  run();
}

// Auto-init on DOM ready for convenience
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initWarehousesMenu);
} else {
  initWarehousesMenu();
}
