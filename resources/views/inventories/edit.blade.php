@extends('admin.layouts.main')

@section('title', 'Ubah Inventori — ' . config('app.name', 'second-crud'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Inventori</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success myds-alert myds-alert--success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('inventories.update', $inventory->id) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input id="name" name="name" type="text" class="form-control myds-input" value="{{ old('name', $inventory->name) }}">
                            @error('name') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                                <div id="users-autocomplete" class="position-relative mt-2" style="max-width:420px;" data-search-url="{{ route('users.search') }}">
                                <ul id="users-list" class="list-group" role="listbox" aria-label="Cadangan pengguna" style="display:none; position:absolute; z-index:2000; width:100%;"></ul>
                                <div id="users-list-live" class="visually-hidden" aria-live="polite" aria-atomic="true"></div>
                                </div>
                        </div>

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Pemilik (pilihan)</label>
                            @if(auth()->check() && auth()->user()->hasRole('admin'))
                                <select id="user_id" name="user_id" class="form-control myds-select">
                                    <option value="">(tiada pemilik)</option>
                                    @foreach(($users ?? collect()) as $user)
                                        <option value="{{ $user->id }}" {{ (string) old('user_id', $inventory->user_id) === (string) $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                            @else
                                <input type="hidden" name="user_id" value="{{ $inventory->user_id ?? '' }}">
                                <div class="form-control-plaintext myds-form-plaintext">{{ $inventory->user?->name ?? '(tiada pemilik)' }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="qty" class="form-label">Kuantiti</label>
                            <input id="qty" name="qty" type="number" min="0" class="form-control myds-input" value="{{ old('qty', $inventory->qty ?? 0) }}">
                            @error('qty') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Harga</label>
                            <input id="price" name="price" type="number" step="0.01" min="0" class="form-control myds-input" value="{{ old('price', $inventory->price ?? '') }}">
                            @error('price') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Keterangan</label>
                            <textarea id="description" name="description" class="form-control myds-textarea">{{ old('description', $inventory->description) }}</textarea>
                            @error('description') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="vehicle_ids" class="form-label">Pilih Kenderaan (pilihan)</label>
                            <select id="vehicle_ids" name="vehicle_ids[]" class="form-control myds-select" multiple size="5">
                                @foreach($inventory->vehicles ?? collect() as $v)
                                    <option value="{{ $v->id }}" selected>{{ $v->name }}</option>
                                @endforeach
                            </select>
                            @error('vehicle_ids') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="warehouse_id" class="form-label">Gudang</label>
                            <select id="warehouse_id" name="warehouse_id" class="form-control myds-select">
                                <option value="">(Pilih gudang)</option>
                                {{-- Populated by JS on load --}}
                            </select>
                            @error('warehouse_id') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="shelf_id" class="form-label">Rak</label>
                            <select id="shelf_id" name="shelf_id" class="form-control myds-select">
                                <option value="">(Pilih rak)</option>
                                {{-- Populated when warehouse selected --}}
                            </select>
                            @error('shelf_id') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--secondary me-2">Batal</a>
                            <button type="submit" class="myds-btn myds-btn--primary">Kemaskini</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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

                nameInput.value = nameInput.value + ' — ' + u.name;
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
    // Populate vehicles multiselect on load if edit context has inventory id
    (async function initVehicleMulti() {
        const vehicleSelect = document.querySelector('#vehicle_ids');
        if (! vehicleSelect) return;
        // inventory id embedded in URL or via hidden field
        const match = window.location.pathname.match(/\/inventories\/(\d+)/);
        if (! match) return;
        const invId = match[1];
        try {
            const res = await fetch('/inventories/' + invId + '/vehicles', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (! res.ok) return;
            const items = await res.json();
            // merge existing selected options without duplicating
            const existing = Array.from(vehicleSelect.options).map(o => String(o.value));
            items.forEach(v => {
                if (! existing.includes(String(v.id))) {
                    const opt = document.createElement('option');
                    opt.value = v.id; opt.text = v.name; vehicleSelect.appendChild(opt);
                }
            });
        } catch (e) {
            // ignore
        }
    })();
    // Warehouse -> shelf dynamic selects for edit
    (function warehouseShelfInit() {
        const warehouseSelect = document.querySelector('#warehouse_id');
        const shelfSelect = document.querySelector('#shelf_id');

        if (! warehouseSelect || ! shelfSelect) return;

        async function fetchWarehouses() {
            const res = await fetch('{{ url('/warehouses') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (! res.ok) return [];
            return await res.json();
        }

        async function fetchShelves(warehouseId) {
            if (! warehouseId) return [];
            const res = await fetch('/warehouses/' + warehouseId + '/shelves', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (! res.ok) return [];
            return await res.json();
        }

        function populateSelect(select, items, selectedValue = null) {
            select.innerHTML = '';
            const empty = document.createElement('option');
            empty.value = '';
            empty.textContent = '(Pilih)';
            select.appendChild(empty);
            items.forEach(it => {
                const opt = document.createElement('option');
                opt.value = it.id;
                opt.textContent = it.name || it.shelf_number || it.id;
                if (selectedValue && String(selectedValue) === String(it.id)) opt.selected = true;
                select.appendChild(opt);
            });
        }

        (async function init() {
            const warehouses = await fetchWarehouses();
            const currentWarehouse = '{{ old('warehouse_id', $inventory->warehouse_id ?? '') }}';
            populateSelect(warehouseSelect, warehouses, currentWarehouse || null);

            // If an existing inventory has a warehouse, load its shelves and select the current shelf
            const warehouseToLoad = currentWarehouse || '';
            if (warehouseToLoad) {
                const shelves = await fetchShelves(warehouseToLoad);
                const currentShelf = '{{ old('shelf_id', $inventory->shelf_id ?? '') }}';
                populateSelect(shelfSelect, shelves, currentShelf || null);
            }
        })();

        warehouseSelect.addEventListener('change', async function () {
            const wid = this.value;
            const shelves = await fetchShelves(wid);
            populateSelect(shelfSelect, shelves, null);
        });
    })();
});
</script>
@endpush
