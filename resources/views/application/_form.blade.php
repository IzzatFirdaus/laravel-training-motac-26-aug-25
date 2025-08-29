<div class="row g-3">
    <div class="col-12">
        <label for="name" class="form-label font-heading">Nama <span class="text-danger" aria-hidden="true">*</span></label>
        <input
            id="name"
            name="name"
            type="text"
            class="form-control myds-input @error('name') is-invalid @enderror"
            value="{{ old('name', $application->name ?? '') }}"
            required
            maxlength="255"
            aria-required="true"
            aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
            @if($errors->has('name')) aria-describedby="name-error" @endif
        >
        @error('name')
            <div id="name-error" class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror

        <div id="users-autocomplete" class="position-relative mt-2 autocomplete-wrapper" aria-hidden="false">
            <ul id="users-list" class="list-group autocomplete-list" role="listbox" aria-label="Cadangan pengguna" style="display:none"></ul>
        </div>
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Keterangan</label>
        <textarea
            id="description"
            name="description"
            class="form-control myds-input @error('description') is-invalid @enderror"
            aria-invalid="{{ $errors->has('description') ? 'true' : 'false' }}"
            @if($errors->has('description')) aria-describedby="description-error" @endif
        >{{ old('description', $application->description ?? '') }}</textarea>
        @error('description')
            <div id="description-error" class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="inventory_id" class="form-label">Inventori</label>
        <select
            id="inventory_id"
            name="inventory_id"
            class="form-control myds-input @error('inventory_id') is-invalid @enderror"
            aria-invalid="{{ $errors->has('inventory_id') ? 'true' : 'false' }}"
            @if($errors->has('inventory_id')) aria-describedby="inventory-error" @endif
        >
            <option value="">{{ __('(tiada)') }}</option>
            @foreach(($inventories ?? collect()) as $inv)
                <option value="{{ $inv->id }}" {{ (string) old('inventory_id', $application->inventory_id ?? '') === (string) $inv->id ? 'selected' : '' }}>
                    {{ $inv->name }}
                </option>
            @endforeach
        </select>
        @error('inventory_id')
            <div id="inventory-error" class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="vehicle_id" class="form-label">Kenderaan</label>
        <select
            id="vehicle_id"
            name="vehicle_id"
            class="form-control myds-input @error('vehicle_id') is-invalid @enderror"
            aria-invalid="{{ $errors->has('vehicle_id') ? 'true' : 'false' }}"
            @if($errors->has('vehicle_id')) aria-describedby="vehicle-error" @endif
        >
            <option value="">{{ __('(tiada)') }}</option>
            {{-- Options will be populated dynamically based on selected inventory --}}
            @if(isset($application) && $application->vehicle_id && $application->inventory)
                @foreach($application->inventory->vehicles ?? collect() as $v)
                    <option value="{{ $v->id }}" {{ (string) old('vehicle_id', $application->vehicle_id ?? '') === (string) $v->id ? 'selected' : '' }}>
                        {{ $v->name }}
                    </option>
                @endforeach
            @endif
        </select>
        @error('vehicle_id')
            <div id="vehicle-error" class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>

    {{-- Pemilik (owner) field: show a select for admins, otherwise set current user as hidden owner --}}
    {{-- Kept commented but cleaned and accessible if enabled --}}
    {{--
    <div class="col-12">
        <label for="user_id" class="form-label">Pemilik</label>

        @if(auth()->check() && auth()->user()->hasRole('admin'))
            <select id="user_id" name="user_id" class="form-control myds-input @error('user_id') is-invalid @enderror" aria-describedby="{{ $errors->has('user_id') ? 'user-error' : '' }}">
                <option value="">{{ __('(tiada)') }}</option>
                @foreach(($users ?? collect()) as $u)
                    <option value="{{ $u->id }}" {{ (string) old('user_id', $application->user_id ?? '') === (string) $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
            @error('user_id') <div id="user-error" class="invalid-feedback" role="alert">{{ $message }}</div> @enderror
        @else
            <input type="hidden" name="user_id" value="{{ auth()->id() ?? '' }}">
            <div class="form-text">Anda akan menjadi pemilik permohonan ini.</div>
        @endif
    </div>
    --}}
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.querySelector('#name');
    const usersList = document.querySelector('#users-list');
    const usersWrapper = document.querySelector('#users-autocomplete');
    const ownerSelect = document.querySelector('#user_id');

    if (! nameInput || ! usersList) return;

    /**
     * Fetch matching users from server (Ajax)
     * Returns array of { id, name }
     */
    async function fetchUsers(q = '') {
        const url = new URL('{{ route('users.search') }}', window.location.origin);
        if (q) url.searchParams.set('q', q);
        try {
            const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (! res.ok) return [];
            return await res.json();
        } catch (err) {
            return [];
        }
    }

    /**
     * Render list of user suggestions as listbox options
     * Ensures keyboard accessibility (Enter selects, ArrowUp/Down moves)
     */
    function renderUsers(items) {
        usersList.innerHTML = '';
        usersList.style.display = 'none';
        if (! Array.isArray(items) || items.length === 0) return;

        items.forEach((u, idx) => {
            const li = document.createElement('li');
            li.className = 'list-group-item list-group-item-action';
            li.setAttribute('role', 'option');
            li.setAttribute('tabindex', '0');
            li.textContent = u.name;
            li.dataset.userId = u.id;
            // click handler selects user
            li.addEventListener('click', function () {
                selectUser(u);
            });
            // keyboard handler (Enter or Space)
            li.addEventListener('keydown', function (ev) {
                if (ev.key === 'Enter' || ev.key === ' ') {
                    ev.preventDefault();
                    selectUser(u);
                }
                // handle arrow navigation
                if (ev.key === 'ArrowDown') {
                    ev.preventDefault();
                    const next = li.nextElementSibling;
                    if (next) next.focus();
                }
                if (ev.key === 'ArrowUp') {
                    ev.preventDefault();
                    const prev = li.previousElementSibling;
                    if (prev) prev.focus();
                }
            });
            usersList.appendChild(li);
        });

        // mark container visible to assistive tech
        usersList.style.display = 'block';
        usersList.setAttribute('aria-hidden', 'false');
        usersList.firstElementChild && usersList.firstElementChild.focus();
    }

    /**
     * When a user suggestion is selected: set hidden owner or select option,
     * annotate name field for clarity, and hide the suggestion list.
     */
    function selectUser(u) {
        if (! u) return;

        if (ownerSelect) {
            const opt = Array.from(ownerSelect.options).find(o => o.value === String(u.id));
            if (opt) {
                ownerSelect.value = u.id;
            } else {
                const newOpt = document.createElement('option');
                newOpt.value = u.id;
                newOpt.text = u.name;
                newOpt.selected = true;
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

        // Append owner hint to the name input (keeping original user text)
        nameInput.value = (nameInput.value || '').replace(/\s*—\s*.*$/, '') + ' — ' + u.name;
        usersList.style.display = 'none';
        usersList.setAttribute('aria-hidden', 'true');
    }

    nameInput.addEventListener('focus', async function () {
        const q = nameInput.value.trim();
        const users = await fetchUsers(q);
        renderUsers(users);
    });

    nameInput.addEventListener('input', async function () {
        const q = nameInput.value.trim();
        if (! q) {
            usersList.style.display = 'none';
            return;
        }
        const users = await fetchUsers(q);
        renderUsers(users);
    });

    // close suggestions when clicking outside
    document.addEventListener('click', function (ev) {
        if (! usersWrapper.contains(ev.target) && ev.target !== nameInput) {
            usersList.style.display = 'none';
            usersList.setAttribute('aria-hidden', 'true');
        }
    });

    // close on escape
    document.addEventListener('keydown', function (ev) {
        if (ev.key === 'Escape') {
            usersList.style.display = 'none';
            usersList.setAttribute('aria-hidden', 'true');
            nameInput.focus();
        }
    });

    // Vehicle select: fetch vehicles for selected inventory and populate the select
    const inventorySelect = document.querySelector('#inventory_id');
    const vehicleSelect = document.querySelector('#vehicle_id');

    async function fetchVehiclesForInventory(inventoryId) {
        if (! inventoryId) return [];
        const url = new URL('{{ url('/') }}', window.location.origin);
        url.pathname = '/inventories/' + inventoryId + '/vehicles';
        try {
            const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (! res.ok) return [];
            return await res.json();
        } catch (e) {
            return [];
        }
    }

    function populateVehicleSelect(items) {
        if (! vehicleSelect) return;
        const previousVal = vehicleSelect.value || '';
        vehicleSelect.innerHTML = '';
        const empty = document.createElement('option');
        empty.value = '';
        empty.text = '{{ __('(tiada)') }}';
        vehicleSelect.appendChild(empty);

        (items || []).forEach(v => {
            const opt = document.createElement('option');
            opt.value = v.id;
            opt.text = v.name;
            if (String(previousVal) === String(v.id) || String('{{ old('vehicle_id', $application->vehicle_id ?? '') }}') === String(v.id)) {
                opt.selected = true;
            }
            vehicleSelect.appendChild(opt);
        });
    }

    if (inventorySelect && vehicleSelect) {
        inventorySelect.addEventListener('change', async function () {
            const invId = inventorySelect.value;
            const vehicles = await fetchVehiclesForInventory(invId);
            populateVehicleSelect(vehicles || []);
        });

        // Populate on initial load if an inventory is already selected
        (async function initVehicles() {
            const invId = inventorySelect.value;
            if (invId) {
                const vehicles = await fetchVehiclesForInventory(invId);
                populateVehicleSelect(vehicles || []);
            }
        })();
    }
});
</script>
@endpush
