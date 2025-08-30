@extends('layouts.app')

@section('title', 'Ubah Inventori - ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="inventory-edit-heading">

{{-- Breadcrumb --}}
<nav aria-label="Breadcrumb" class="mb-4">
    <ol class="d-flex list-unstyled myds-text--muted myds-body-sm">
        <li><a href="{{ route('inventories.index') }}" class="text-primary text-decoration-none hover:text-decoration-underline">Inventori</a></li>
        <li class="mx-2" aria-hidden="true">/</li>
        <li aria-current="page" class="myds-text--muted">Ubah</li>
    </ol>
</nav>

{{-- Page Header --}}
<header class="mb-6">
    <h1 id="inventory-edit-heading" class="myds-heading-md font-heading font-semibold mb-3">Ubah Inventori</h1>
    <div class="myds-body-md myds-text--muted">
        <p class="mb-2">Kemas kini maklumat item inventori yang sedia ada. Medan bertanda bintang (*) adalah wajib diisi.</p>
    </div>
</header>

<div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
    <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-8">

        {{-- Status Message --}}
        @if (session('status'))
            <div class="myds-alert myds-alert--success d-flex align-items-start mb-4" role="status" aria-live="polite">
                <i class="bi bi-check-circle me-2 flex-shrink-0" aria-hidden="true"></i>
                <div>
                    <h4 class="myds-body-md font-medium">Berjaya</h4>
                    <p class="mb-0 myds-body-sm">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        <div class="myds-card">
            <div class="myds-card__body">
                <form method="POST" action="{{ route('inventories.update', $inventory->id) }}" novalidate aria-labelledby="form-title">
                    @csrf
                    @method('PUT')

                    <h2 id="form-title" class="sr-only">Borang Ubah Inventori</h2>

                    <div class="myds-alert myds-alert--info mb-4">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-info-circle me-2 mt-1 flex-shrink-0" aria-hidden="true"></i>
                            <div>
                                <p class="myds-body-sm font-medium mb-1">Panduan Pengisian Borang</p>
                                <p class="myds-body-sm mb-0">Pastikan semua medan wajib diisi dan semak semula data sebelum kemas kini.</p>
                            </div>
                        </div>
                    </div>                    {{-- Name with autocomplete --}}
                    <div class="myds-form-group mb-4">
                        <label for="name" class="myds-label">Nama Item <span class="myds-text--danger ms-1" aria-hidden="true">*</span></label>
                        <input id="name" name="name" type="text" class="myds-input @error('name') myds-input--error @enderror" value="{{ old('name', $inventory->name) }}" aria-describedby="name-help @error('name') name-error @enderror" aria-required="true" required>
                        <div id="name-help" class="myds-help-text">Nama yang jelas dan mudah dicari</div>
                        @error('name') <div id="name-error" class="myds-text--danger myds-body-xs mt-1" role="alert">{{ $message }}</div> @enderror

                        <div id="users-autocomplete" class="position-relative mt-2 autocomplete-wrapper" data-search-url="{{ route('users.search') }}">
                            <ul id="users-list" class="myds-list myds-list--stacked autocomplete-list" role="listbox" aria-label="Cadangan pengguna"></ul>
                            <div id="users-list-live" class="visually-hidden" aria-live="polite" aria-atomic="true"></div>
                        </div>
                    </div>

                    {{-- Owner select or plaintext view --}}
                    <div class="myds-form-group mb-3">
                        <label for="user_id" class="myds-label">Pemilik (pilihan)</label>
                        @if(auth()->check() && auth()->user()->hasRole('admin'))
                            <select id="user_id" name="user_id" class="myds-select @error('user_id') myds-select--error @enderror">
                                <option value="">(tiada pemilik)</option>
                                @foreach(($users ?? collect()) as $user)
                                    <option value="{{ $user->id }}" {{ (string) old('user_id', $inventory->user_id) === (string) $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id') <div class="myds-text--danger myds-body-xs mt-1" role="alert">{{ $message }}</div> @enderror
                        @else
                            <input type="hidden" name="user_id" value="{{ $inventory->user_id ?? '' }}">
                            <div class="myds-form-plaintext">{{ $inventory->user?->name ?? '(tiada pemilik)' }}</div>
                        @endif
                    </div>

                    {{-- Quantity --}}
                    <div class="myds-form-group mb-3">
                        <label for="qty" class="myds-label">Kuantiti <span class="myds-text--danger" aria-hidden="true">*</span></label>
                        <input id="qty" name="qty" type="number" min="0" class="myds-input @error('qty') myds-input--error @enderror" value="{{ old('qty', $inventory->qty ?? 0) }}" required aria-describedby="qty-help @error('qty') qty-error @enderror" aria-required="true">
                        <div id="qty-help" class="myds-help-text">Bilangan unit item</div>
                        @error('qty') <div id="qty-error" class="myds-text--danger myds-body-xs mt-1" role="alert">{{ $message }}</div> @enderror
                    </div>

                    {{-- Price --}}
                    <div class="myds-form-group mb-3">
                        <label for="price" class="myds-label">Harga (RM)</label>
                        <div class="myds-input-group">
                            <span class="myds-input-group__addon">RM</span>
                            <input id="price" name="price" type="number" step="0.01" min="0" class="myds-input @error('price') myds-input--error @enderror" value="{{ old('price', $inventory->price ?? '') }}" aria-describedby="price-help @error('price') price-error @enderror">
                        </div>
                        <div id="price-help" class="myds-help-text">Masukkan harga per unit dalam Ringgit Malaysia</div>
                        @error('price') <div id="price-error" class="myds-text--danger myds-body-xs mt-1" role="alert">{{ $message }}</div> @enderror
                    </div>

                    {{-- Description --}}
                    <div class="myds-form-group mb-4">
                        <label for="description" class="myds-label">Keterangan</label>
                        <textarea id="description" name="description" class="myds-textarea @error('description') myds-textarea--error @enderror" rows="4" aria-describedby="description-help @error('description') description-error @enderror">{{ old('description', $inventory->description) }}</textarea>
                        <div id="description-help" class="myds-help-text">Maklumat tambahan seperti model, nombor siri atau nota</div>
                        @error('description') <div id="description-error" class="myds-text--danger myds-body-xs mt-1" role="alert">{{ $message }}</div> @enderror
                    </div>

                    {{-- Vehicles multi-select --}}
                    <div class="myds-form-group mb-3">
                        <label for="vehicle_ids" class="myds-label">Pilih Kenderaan (pilihan)</label>
                        <select id="vehicle_ids" name="vehicle_ids[]" class="myds-select @error('vehicle_ids') myds-select--error @enderror" multiple size="5">
                            @foreach($inventory->vehicles ?? collect() as $v)
                                <option value="{{ $v->id }}" selected>{{ $v->name }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_ids') <div class="myds-text--danger myds-body-xs mt-1" role="alert">{{ $message }}</div> @enderror
                    </div>

                    {{-- Warehouse & Shelf progressive selects --}}
                    <div class="myds-form-group mb-3">
                        <label for="warehouse_id" class="myds-label">Gudang</label>
                        <select id="warehouse_id" name="warehouse_id" class="myds-select @error('warehouse_id') myds-select--error @enderror"
                                data-warehouses-url="{{ url('/warehouses') }}"
                                data-initial-warehouse="{{ old('warehouse_id', $inventory->warehouse_id ?? '') }}">
                            <option value="">(Pilih gudang)</option>
                            {{-- Populated by JS --}}
                        </select>
                        @error('warehouse_id') <div class="myds-text--danger myds-body-xs mt-1" role="alert">{{ $message }}</div> @enderror
                    </div>

                    <div class="myds-form-group mb-4">
                        <label for="shelf_id" class="myds-label">Rak</label>
                        <select id="shelf_id" name="shelf_id" class="myds-select @error('shelf_id') myds-select--error @enderror" data-initial-shelf="{{ old('shelf_id', $inventory->shelf_id ?? '') }}">
                            <option value="">(Pilih rak)</option>
                            {{-- Populated by JS --}}
                        </select>
                        @error('shelf_id') <div class="myds-text--danger myds-body-xs mt-1" role="alert">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--secondary" aria-label="Batal dan kembali ke senarai inventori">Batal</a>
                        <button type="submit" class="myds-btn myds-btn--primary" aria-label="Kemas kini inventori">
                            <i class="bi bi-save me-1" aria-hidden="true"></i>
                            Kemaskini
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Help panel --}}
    <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-4">
        <div class="myds-card">
            <div class="myds-card__body">
                <h2 class="myds-heading-xs font-heading font-medium mb-3">Panduan Pengisian</h2>
                <div class="myds-body-sm">
                    <div class="mb-3">
                        <strong class="myds-text--primary">Nama Item</strong>
                        <p class="mb-1">Gunakan nama yang jelas dan deskriptif untuk memudahkan pencarian.</p>
                    </div>
                    <div class="mb-3">
                        <strong class="myds-text--primary">Kuantiti</strong>
                        <p class="mb-1">Masukkan bilangan unit sebenar dalam stok.</p>
                    </div>
                    <div class="mb-3">
                        <strong class="myds-text--primary">Harga</strong>
                        <p class="mb-1">Harga per unit untuk tujuan inventori dan pelaporan.</p>
                    </div>
                    <div>
                        <strong class="myds-text--primary">Keterangan</strong>
                        <p class="mb-0">Maklumat tambahan seperti nombor siri, model atau nota khas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/pages/inventories-edit.js')
@endpush
