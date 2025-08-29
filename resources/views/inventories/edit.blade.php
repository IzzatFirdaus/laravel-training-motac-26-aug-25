@extends('admin.layouts.main')

@section('title', 'Ubah Inventori — ' . config('app.name', 'second-crud'))

@section('content')
<div class="myds-container">
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
                                <div id="users-autocomplete" class="position-relative mt-2 autocomplete-wrapper" data-search-url="{{ route('users.search') }}">
                                <ul id="users-list" class="list-group autocomplete-list" role="listbox" aria-label="Cadangan pengguna"></ul>
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
                            <select id="warehouse_id" name="warehouse_id" class="form-control myds-select"
                                    data-warehouses-url="{{ url('/warehouses') }}"
                                    data-initial-warehouse="{{ old('warehouse_id', $inventory->warehouse_id ?? '') }}">
                                <option value="">(Pilih gudang)</option>
                                {{-- Populated by JS on load --}}
                            </select>
                            @error('warehouse_id') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="shelf_id" class="form-label">Rak</label>
                            <select id="shelf_id" name="shelf_id" class="form-control myds-select"
                                    data-initial-shelf="{{ old('shelf_id', $inventory->shelf_id ?? '') }}">
                                <option value="">(Pilih rak)</option>
                                {{-- Populated when warehouse selected --}}
                            </select>
                            @error('shelf_id') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--secondary mr-2">Batal</a>
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
    @vite('resources/js/pages/inventories-edit.js')
@endpush
