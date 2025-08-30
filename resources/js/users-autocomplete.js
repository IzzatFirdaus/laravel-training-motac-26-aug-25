/**
 * users-autocomplete.js
 * Progressive enhancement for owner/user autocomplete on forms.
 * - Accessible: combobox pattern (aria-controls, aria-expanded, live region)
 * - Idempotent: safe to call multiple times
 */
/**
 * users-autocomplete.js
 * Progressive enhancement for owner/user autocomplete on forms.
 * - Accessible: combobox pattern (aria-controls, aria-expanded, live region)
 * - Idempotent: safe to call multiple times
 */
export default function enhanceUsersAutocomplete(containerSelector = '#users-autocomplete') {
  const container = document.querySelector(containerSelector);
  if (!container) return;

  // Prevent double-initialisation
  if (container.dataset.mydsInit === '1') return;
  container.dataset.mydsInit = '1';

  const form = container.closest('form') || document.querySelector('form');
  const input = form ? form.querySelector('#name') : null;
  const list = container.querySelector('#users-list');
  const live = container.querySelector('#users-list-live') || null;

  if (!input || !list) return;

  // ensure list has an id for aria-controls
  if (!list.id) list.id = `users-list-${Math.random().toString(36).slice(2, 8)}`;

  let items = [];
  let focused = -1;

  // ARIA setup
  input.setAttribute('role', 'combobox');
  input.setAttribute('aria-autocomplete', 'list');
  input.setAttribute('aria-controls', list.id);
  input.setAttribute('aria-expanded', 'false');
  list.setAttribute('role', 'listbox');
  list.hidden = true;

  function announce(text) {
    if (!live) return;
    live.textContent = text;
  }

  function clearList() {
    list.innerHTML = '';
    list.hidden = true;
    input.setAttribute('aria-expanded', 'false');
    items = [];
    focused = -1;
  }

  function setActive(idx) {
    const children = Array.from(list.children);
    children.forEach((c, i) => {
      c.classList.toggle('active', i === idx);
      c.setAttribute('aria-selected', i === idx ? 'true' : 'false');
    });
    focused = idx;
    if (focused >= 0 && children[focused]) children[focused].scrollIntoView({ block: 'nearest' });
  }

  function applySelection(u) {
    if (!u) return;
    const ownerSelect = document.querySelector('#user_id');
    if (ownerSelect) {
      const opt = Array.from(ownerSelect.options).find(o => o.value === String(u.id));
      if (opt) ownerSelect.value = u.id;
      else {
        const newOpt = document.createElement('option');
        newOpt.value = u.id;
        newOpt.text = u.name;
        newOpt.selected = true;
        ownerSelect.appendChild(newOpt);
      }
    } else if (form) {
      let hidden = form.querySelector('input[name="user_id"]');
      if (!hidden) {
        hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'user_id';
        form.appendChild(hidden);
      }
      hidden.value = u.id;
    }

    // annotate name input
    input.value = (input.value || '').replace(/\s*\u2014\s*.*$/, '').trim() + ' — ' + u.name;
    clearList();
    input.focus();
  }

  async function fetchUsers(q = '') {
    try {
      const searchUrl = container.getAttribute('data-search-url');
      if (!searchUrl) return [];
      const url = new URL(searchUrl, window.location.origin);
      if (q) url.searchParams.set('q', q);
      const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      if (!res.ok) return [];
      return await res.json();
    } catch (e) {
      return [];
    }
  }

  function renderUsers(listData) {
    list.innerHTML = '';
    items = Array.isArray(listData) ? listData : [];
    if (!items.length) {
      clearList();
      announce('Tiada keputusan ditemui');
      return;
    }
    items.forEach((u, idx) => {
      const li = document.createElement('li');
      li.className = 'myds-autocomplete-item';
      li.setAttribute('role', 'option');
      li.setAttribute('tabindex', '-1');
      li.dataset.index = idx;
      li.textContent = u.name;
      li.addEventListener('click', () => applySelection(u));
      li.addEventListener('keydown', (ev) => { if (ev.key === 'Enter' || ev.key === ' ') { ev.preventDefault(); applySelection(u); } });
      list.appendChild(li);
    });
    list.hidden = false;
    input.setAttribute('aria-expanded', 'true');
    announce(`${items.length} keputusan ditemui`);
  }

  // keyboard navigation
  input.addEventListener('keydown', async (ev) => {
    const key = ev.key;
    const children = Array.from(list.children);
    if (key === 'ArrowDown') {
      ev.preventDefault();
      if (!children.length) { const users = await fetchUsers(input.value.trim()); renderUsers(users); return; }
      const next = Math.min(focused + 1, children.length - 1);
      setActive(next);
    } else if (key === 'ArrowUp') {
      ev.preventDefault();
      const prev = Math.max(focused - 1, 0);
      setActive(prev);
    } else if (key === 'Enter') {
      if (focused >= 0) { ev.preventDefault(); const u = items[focused]; if (u) applySelection(u); }
    } else if (key === 'Escape') { clearList(); input.blur(); }
  });

  input.addEventListener('input', async () => {
    const q = input.value.trim();
    if (!q) { clearList(); return; }
    const users = await fetchUsers(q);
    renderUsers(users);
  });

  input.addEventListener('focus', async () => {
    const q = input.value.trim();
    const users = await fetchUsers(q);
    if (users && users.length) renderUsers(users);
  });

  document.addEventListener('click', (ev) => { if (!container.contains(ev.target) && ev.target !== input) clearList(); });
}
  // internal state
