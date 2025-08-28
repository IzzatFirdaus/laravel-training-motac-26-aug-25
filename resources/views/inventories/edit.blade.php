@extends('layouts.app')

@section('title', 'Ubah Inventori — ' . config('app.name', 'second-crud'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Inventori</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('inventories.update', $inventory->id) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $inventory->name) }}">
                            @error('name') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                            <div id="users-autocomplete" class="position-relative mt-2" style="max-width:420px;">
                                <ul id="users-list" class="list-group" style="display:none; position:absolute; z-index:2000; width:100%;"></ul>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Pemilik (pilihan)</label>
                            @if(auth()->check() && auth()->user()->hasRole('admin'))
                                <select id="user_id" name="user_id" class="form-control">
                                    <option value="">(tiada pemilik)</option>
                                    @foreach(($users ?? collect()) as $user)
                                        <option value="{{ $user->id }}" {{ (string) old('user_id', $inventory->user_id) === (string) $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                            @else
                                <input type="hidden" name="user_id" value="{{ $inventory->user_id ?? '' }}">
                                <div class="form-control-plaintext">{{ $inventory->user?->name ?? '(tiada pemilik)' }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="qty" class="form-label">Kuantiti</label>
                            <input id="qty" name="qty" type="number" min="0" class="form-control" value="{{ old('qty', $inventory->qty ?? 0) }}">
                            @error('qty') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Harga</label>
                            <input id="price" name="price" type="number" step="0.01" min="0" class="form-control" value="{{ old('price', $inventory->price ?? '') }}">
                            @error('price') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Keterangan</label>
                            <textarea id="description" name="description" class="form-control">{{ old('description', $inventory->description) }}</textarea>
                            @error('description') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
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
});
</script>
@endpush
