@extends('layouts.app')

@section('title', 'Cipta Inventori — ' . config('app.name', 'second-crud'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Cipta Inventori</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success myds-alert myds-alert--success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('inventories.store') }}">
                        @csrf

                        <x-form-field name="name" label="Nama" :value="old('name')" />

                        {{-- Users autocomplete helper: clicking name input will fetch users and allow quick selection --}}
                        <div id="users-autocomplete" class="position-relative mt-2" style="max-width:420px;" data-search-url="{{ route('users.search') }}">
                            {{-- Autocomplete list: role=listbox with aria-controls and aria-expanded on the input --}}
                            <ul id="users-list" class="list-group" role="listbox" aria-label="Cadangan pengguna" style="display:none; position:absolute; z-index:2000; width:100%;"></ul>
                            <div id="users-list-live" class="visually-hidden" aria-live="polite" aria-atomic="true"></div>
                        </div>

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Pemilik (pilihan)</label>
                            @if(auth()->check() && auth()->user()->hasRole('admin'))
                                <select id="user_id" name="user_id" class="form-control myds-select" aria-describedby="user_id-error">
                                    <option value="">(tiada pemilik)</option>
                                    @foreach(($users ?? collect()) as $user)
                                        <option value="{{ $user->id }}" {{ (string) old('user_id') === (string) $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id') <div id="user_id-error" class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                            @else
                                {{-- Non-admins cannot assign owner; set hidden field to current user (if any) --}}
                                <input type="hidden" name="user_id" value="{{ auth()->id() ?? '' }}">
                                <div class="form-text">Anda akan menjadi pemilik item ini.</div>
                            @endif
                        </div>

                        <x-form-field name="qty" type="number" label="Kuantiti" :value="old('qty', 0)" />

                        <x-form-field name="price" type="number" label="Harga" :value="old('price', '')" />

                        <x-form-field name="description" type="textarea" label="Keterangan">{{ old('description') }}</x-form-field>

                        <div class="mb-3">
                            <label for="vehicle_ids" class="form-label">Pilih Kenderaan (pilihan)</label>
                            <select id="vehicle_ids" name="vehicle_ids[]" class="form-control myds-select" multiple size="5">
                                {{-- Options populated dynamically based on selected inventory context --}}
                            </select>
                            @error('vehicle_ids') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="warehouse_id" class="form-label">Gudang</label>
                            <select id="warehouse_id" name="warehouse_id" class="form-control myds-select">
                                <option value="">(Pilih gudang)</option>
                                {{-- Options will be populated by JS on page load --}}
                            </select>
                            @error('warehouse_id') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="shelf_id" class="form-label">Rak</label>
                            <select id="shelf_id" name="shelf_id" class="form-control myds-select">
                                <option value="">(Pilih rak)</option>
                                {{-- Populated when warehouse is selected --}}
                            </select>
                            @error('shelf_id') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--secondary me-2">Batal</a>
                            <button type="submit" class="myds-btn myds-btn--primary">Cipta</button>
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
                // If admin select exists, set it; otherwise set hidden input
                if (ownerSelect) {
                    const opt = Array.from(ownerSelect.options).find(o => o.value === String(u.id));
                    if (opt) {
                        ownerSelect.value = u.id;
                    } else {
                        // Append option and select
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

                // optionally set the name input to include owner hint
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

    // Dynamic warehouse -> shelf selects
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
            // Populate warehouses
            const warehouses = await fetchWarehouses();
            const oldWarehouse = '{{ old('warehouse_id', '') }}';
            populateSelect(warehouseSelect, warehouses, oldWarehouse || null);

            // If there is an old warehouse value (validation) or none, attempt to load shelves
            const initialWarehouse = oldWarehouse || '';
            if (initialWarehouse) {
                const shelves = await fetchShelves(initialWarehouse);
                const oldShelf = '{{ old('shelf_id', '') }}';
                populateSelect(shelfSelect, shelves, oldShelf || null);
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
