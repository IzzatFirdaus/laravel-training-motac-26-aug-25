// Lightweight accessibility enhancement for the users autocomplete used in inventory forms
// Adds keyboard navigation (ArrowUp/ArrowDown/Enter/Escape) and ARIA state management.

export default function enhanceUsersAutocomplete(containerSelector = '#users-autocomplete') {
    const container = document.querySelector(containerSelector);
    if (!container) return;

    const input = container.closest('form')?.querySelector('#name');
    const list = container.querySelector('#users-list');
    if (!input || !list) return;

    let items = [];
    let focused = -1;

    function setActive(index) {
        const children = Array.from(list.children);
        children.forEach((c, i) => {
            c.classList.toggle('active', i === index);
            c.setAttribute('aria-selected', (i === index) ? 'true' : 'false');
        });
        focused = index;
        if (focused >= 0 && children[focused]) {
            children[focused].scrollIntoView({ block: 'nearest' });
        }
    }

    function clearList() {
        list.innerHTML = '';
        list.style.display = 'none';
        input.setAttribute('aria-expanded', 'false');
        items = [];
        focused = -1;
    }

    function renderUsers(arr) {
        list.innerHTML = '';
        items = arr;
    if (! items.length) { clearList(); return; }
        arr.forEach((u, idx) => {
            const li = document.createElement('li');
            li.className = 'list-group-item list-group-item-action';
            li.textContent = u.name;
            li.dataset.userId = u.id;
            li.setAttribute('role', 'option');
            li.setAttribute('tabindex', '-1');
            li.setAttribute('aria-selected', 'false');
            li.addEventListener('click', function () {
                selectUser(idx);
            });
            list.appendChild(li);
        });
        list.style.display = 'block';
        input.setAttribute('aria-expanded', 'true');
        // Announce result count for screen readers
        const live = container.querySelector('#users-list-live');
        if (live) {
            const text = items.length === 1 ? '1 keputusan ditemui' : `${items.length} keputusan ditemui`;
            live.textContent = text;
        }
    }

    function selectUser(index) {
        const u = items[index];
        if (! u) return;
        const ownerSelect = document.querySelector('#user_id');
        if (ownerSelect) {
            const opt = Array.from(ownerSelect.options).find(o => o.value === String(u.id));
            if (opt) {
                ownerSelect.value = u.id;
            } else {
                const newOpt = document.createElement('option');
                newOpt.value = u.id; newOpt.text = u.name; newOpt.selected = true;
                ownerSelect.appendChild(newOpt);
            }
        } else {
            let hidden = document.querySelector('input[name="user_id"]');
            if (! hidden) {
                hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'user_id';
                document.querySelector('form').appendChild(hidden);
            }
            hidden.value = u.id;
        }

        // Optionally annotate the name input so the user knows which owner was chosen
        input.value = input.value.trim() + ' — ' + u.name;
        clearList();
        input.focus();
    }

    async function fetchUsers(q = '') {
        try {
            const searchUrl = container.getAttribute('data-search-url');
            if (! searchUrl) return [];
            const url = new URL(searchUrl, window.location.origin);
            if (q) url.searchParams.set('q', q);
            const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (! res.ok) return [];
            return await res.json();
        } catch (err) {
            return [];
        }
    }

    // keyboard handling
    input.setAttribute('role', 'combobox');
    input.setAttribute('aria-autocomplete', 'list');
    input.setAttribute('aria-controls', 'users-list');
    input.setAttribute('aria-expanded', 'false');

    input.addEventListener('keydown', async function (ev) {
        const key = ev.key;
        const children = Array.from(list.children);
        if (key === 'ArrowDown') {
            ev.preventDefault();
            if (! children.length) {
                const users = await fetchUsers(input.value.trim());
                renderUsers(users);
                return;
            }
            const next = Math.min((focused + 1), children.length - 1);
            setActive(next);
        } else if (key === 'ArrowUp') {
            ev.preventDefault();
            const prev = Math.max((focused - 1), 0);
            setActive(prev);
        } else if (key === 'Enter') {
            if (focused >= 0) {
                ev.preventDefault();
                selectUser(focused);
            }
        } else if (key === 'Escape') {
            clearList();
        }
    });

    input.addEventListener('input', async function () {
        const q = input.value.trim();
        const users = await fetchUsers(q);
        renderUsers(users);
    });

    input.addEventListener('focus', async function () {
        const q = input.value.trim();
        const users = await fetchUsers(q);
        renderUsers(users);
    });

    document.addEventListener('click', function (ev) {
        if (! container.contains(ev.target) && ev.target !== input) {
            clearList();
        }
    });
}
