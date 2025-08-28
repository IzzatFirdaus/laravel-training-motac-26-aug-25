<div class="row g-3">
    <div class="col-12">
        <label for="name" class="form-label">Nama</label>
        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $application->name ?? '') }}" required maxlength="255">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div id="users-autocomplete" class="position-relative mt-2" style="max-width:420px;">
            <ul id="users-list" class="list-group" style="display:none; position:absolute; z-index:2000; width:100%;"></ul>
        </div>
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Keterangan</label>
        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $application->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

        <div class="col-12">
            <label for="inventory_id" class="form-label">Inventori</label>
            <select id="inventory_id" name="inventory_id" class="form-control @error('inventory_id') is-invalid @enderror">
                <option value="">(tiada)</option>
                @foreach(($inventories ?? collect()) as $inv)
                    <option value="{{ $inv->id }}" {{ (string) old('inventory_id', $application->inventory_id ?? '') === (string) $inv->id ? 'selected' : '' }}>{{ $inv->name }}</option>
                @endforeach
            </select>
            @error('inventory_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <label for="vehicle_id" class="form-label">Kenderaan</label>
            <select id="vehicle_id" name="vehicle_id" class="form-control @error('vehicle_id') is-invalid @enderror">
                <option value="">(tiada)</option>
                {{-- Options will be populated dynamically based on selected inventory --}}
                @if(isset($application) && $application->vehicle_id && $application->inventory)
                    @foreach($application->inventory->vehicles ?? collect() as $v)
                        <option value="{{ $v->id }}" {{ (string) old('vehicle_id', $application->vehicle_id ?? '') === (string) $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                    @endforeach
                @endif
            </select>
            @error('vehicle_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        {{-- Pemilik (owner) field: show a select for admins, otherwise set current user as hidden owner --}}
        <!--
            <div class="col-12">
                <label for="user_id" class="form-label">Pemilik</label>

                {{-- If the user is authenticated and has the "admin" role, show a dropdown to choose the owner --}}
                //@if(auth()->check() && auth()->user()->hasRole('admin'))
                <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid ///@enderror">
                {{-- Allow no selection --}}
                <option value="">(tiada)</option>

                {{-- List available users as options; preserve old input or model value for selection --}}
                @foreach(($users ?? collect()) as $u)
                <option value="{{ $u->id }}" {{ (string) old('user_id', $application->user_id ?? '') === (string) $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
                </select>

                {{-- Validation feedback for user_id --}}
                @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @else
                {{-- For non-admins: set the current authenticated user as the owner using a hidden input --}}
                <input type="hidden" name="user_id" value="{{ auth()->id() ?? '' }}">
                <div class="form-text">Anda akan menjadi pemilik permohonan ini.</div>
                @endif
            </div>
        -->
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.querySelector('#name');
    const usersList = document.querySelector('#users-list');
    const usersWrapper = document.querySelector('#users-autocomplete');
    const ownerSelect = document.querySelector('#user_id');

    if (! nameInput || ! usersList) return;

    async function fetchUsers(q = '') {
        const url = new URL('{{ route('users.search') }}', window.location.origin);
        if (q) url.searchParams.set('q', q);
        const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (! res.ok) return [];
        return await res.json();
    }

    function renderUsers(items) {
        usersList.innerHTML = '';
        if (! items.length) { usersList.style.display = 'none'; return; }
        items.forEach(u => {
            const li = document.createElement('li');
            li.className = 'list-group-item list-group-item-action';
            li.textContent = u.name;
            li.dataset.userId = u.id;
            li.addEventListener('click', function () {
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

                // Indicate owner in the Nama input without replacing it
                nameInput.value = (nameInput.value || '') + ' — ' + u.name;
                usersList.style.display = 'none';
            });
            usersList.appendChild(li);
        });
        usersList.style.display = 'block';
    }

    nameInput.addEventListener('focus', async function () {
        const q = nameInput.value.trim();
        const users = await fetchUsers(q);
        renderUsers(users);
    });

    nameInput.addEventListener('input', async function () {
        const q = nameInput.value.trim();
        const users = await fetchUsers(q);
        renderUsers(users);
    });

    document.addEventListener('click', function (ev) {
        if (! usersWrapper.contains(ev.target) && ev.target !== nameInput) {
            usersList.style.display = 'none';
        }
    });

    // Vehicle select: fetch vehicles for selected inventory and populate the select
    const inventorySelect = document.querySelector('#inventory_id');
    const vehicleSelect = document.querySelector('#vehicle_id');

    async function fetchVehiclesForInventory(inventoryId) {
        if (! inventoryId) return [];
        const url = new URL('{{ url('/') }}');
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
        // clear existing non-empty options
        const selectedVal = vehicleSelect.value || '';
        vehicleSelect.innerHTML = '';
        const empty = document.createElement('option');
        empty.value = '';
        empty.text = '(tiada)';
        vehicleSelect.appendChild(empty);

        items.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v.id;
            opt.text = v.name;
            if (String(selectedVal) === String(v.id) || String('{{ old('vehicle_id', $application->vehicle_id ?? '') }}') === String(v.id)) {
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
