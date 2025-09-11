@extends('layouts.app')

@section('title', 'Cipta Inventori — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main">

{{-- MYDS Breadcrumb Navigation --}}
<nav aria-label="Breadcrumb" class="mb-4">
    <ol class="d-flex list-unstyled myds-text--muted myds-body-sm">
        <li><a href="{{ route('inventories.index') }}" class="text-primary text-decoration-none hover:text-decoration-underline">Inventori</a></li>
        <li class="mx-2" aria-hidden="true">/</li>
        <li aria-current="page" class="myds-text--muted">Cipta Baharu</li>
    </ol>
</nav>

{{-- Page Header (MyGOVEA clear display principles) --}}
<header class="mb-6">
    <h1 class="myds-heading-md font-heading font-semibold mb-3">Cipta Inventori Baharu</h1>
    <div class="myds-body-md myds-text--muted">
        <p class="mb-2">
            Isi maklumat di bawah untuk menambah item inventori baharu ke dalam sistem.
            Medan bertanda bintang (*) adalah <strong>wajib diisi</strong>.
        </p>
        <p class="myds-body-sm myds-text--muted mb-0">
            <em lang="en">Fill in the information below to add a new inventory item to the system.</em>
        </p>
    </div>
</header>

{{-- Form Container with MYDS Grid --}}
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

        {{-- Main Form Card --}}
        <div class="myds-card">
            <div class="myds-card__body p-4 shadow-sm">
            <form method="POST" action="{{ route('inventories.store') }}" novalidate aria-labelledby="form-title"
                data-test="inventory-create-form" data-form-type="inventory-create" data-form-validation="enhanced"
                      data-localization="{{ app()->getLocale() }}"
                      data-submit-text="{{ __('forms.submit') }}"
                      data-submit-processing="{{ __('forms.processing') }}">
                @csrf

                <h2 id="form-title" class="sr-only">Borang Cipta Inventori Baharu</h2>

                {{-- Required Fields Notice --}}
                <div class="myds-alert myds-alert--info mb-4">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle me-2 mt-1 flex-shrink-0" aria-hidden="true"></i>
                        <div>
                            <p class="myds-body-sm font-medium mb-1">Panduan Pengisian Borang</p>
                            <p class="myds-body-sm myds-text--muted mb-0">
                                Pastikan semua medan wajib (*) telah diisi dengan lengkap dan tepat sebelum menyerahkan borang.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Name Field (MYDS Input Component) --}}
                <div class="mb-4">
                    <label for="name" class="myds-label myds-body-sm font-medium d-block mb-2">
                        Nama Item
                        <span class="myds-text--danger ms-1" aria-label="medan wajib">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="myds-input @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="{{ __('placeholders.example_inventory') }}"
                           aria-describedby="name-help @error('name') name-error @enderror"
                           aria-required="true"
                           maxlength="255"
                           required>
                    <div id="name-help" class="myds-body-xs myds-text--muted mt-1">
                        Masukkan nama lengkap dan deskriptif untuk item inventori (maksimum 255 aksara)
                    </div>
                    @error('name')
                        <div id="name-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert">
                            <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror

                    {{-- Users autocomplete (owner suggestions) - progressive enhancement hooks used by JS --}}
                    <div id="users-autocomplete" class="position-relative mt-2 autocomplete-wrapper" data-search-url="{{ route('users.search') }}">
                        <ul id="users-list" class="autocomplete-list visually-hidden bg-surface border rounded p-0 m-0" role="listbox" aria-label="Cadangan pengguna" aria-hidden="true"></ul>
                        <div id="users-list-live" class="visually-hidden" aria-live="polite" aria-atomic="true"></div>
                    </div>
                </div>
                </div>

                {{-- Quantity Field --}}
                <div class="mb-3">
                    <label for="qty" class="myds-label font-medium">
                        Kuantiti <span class="myds-text--danger" aria-label="wajib">*</span>
                    </label>
                    <input type="number"
                           id="qty"
                           name="qty"
                           class="myds-input @error('qty') is-invalid @enderror"
                           value="{{ old('qty', 1) }}"
                           min="0"
                           placeholder="{{ __('placeholders.example_quantity') }}"
                           aria-describedby="qty-help @error('qty') qty-error @enderror"
                           required>
                    <div id="qty-help" class="myds-body-xs myds-text--muted">
                        Masukkan bilangan unit item
                    </div>
                    @error('qty')
                        <div id="qty-error" class="myds-text--danger small mt-1" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Price Field --}}
                <div class="mb-3">
                    <label for="price" class="myds-label font-medium">Harga (RM)</label>
                    <div class="myds-input-group">
                        <span class="myds-input-group__addon">RM</span>
                        <input type="number"
                               id="price"
                               name="price"
                               class="myds-input @error('price') myds-input--error @enderror"
                               value="{{ old('price') }}"
                               step="0.01"
                               min="0"
                               placeholder="{{ __('placeholders.example_price') }}"
                               aria-describedby="price-help @error('price') price-error @enderror">
                    </div>
                    <div id="price-help" class="myds-body-xs myds-text--muted">
                        Masukkan harga per unit dalam Ringgit Malaysia
                    </div>
                    @error('price')
                        <div id="price-error" class="myds-text--danger small mt-1" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Description Field --}}
                <div class="mb-4">
                    <label for="description" class="myds-label font-medium">Keterangan</label>
                    <textarea id="description"
                              name="description"
                              class="myds-input @error('description') is-invalid @enderror"
                              rows="4"
                              placeholder="{{ __('placeholders.example_description') }}"
                              aria-describedby="description-help @error('description') description-error @enderror">{{ old('description') }}</textarea>
                    <div id="description-help" class="myds-body-xs myds-text--muted">
                        Berikan maklumat tambahan seperti model, spesifikasi, atau nota khas
                    </div>
                    @error('description')
                        <div id="description-error" class="myds-text--danger small mt-1" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Form Actions --}}
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('inventories.index') }}"
                       class="myds-btn myds-btn--secondary myds-tap-target"
                       data-action="cancel" data-redirect="{{ route('inventories.index') }}"
                       aria-label="Batal dan kembali ke senarai inventori">
                        Batal
                    </a>
            <button data-test="inventory-create-submit" type="submit" class="myds-btn myds-btn--primary myds-tap-target"
                data-action="submit" data-form-submit="inventory-create"
                aria-label="Simpan inventori baharu">
                        <i class="bi bi-save me-1" aria-hidden="true"></i>
                        Simpan Inventori
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MYDS Help Panel --}}
    <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-4">
        <div class="bg-muted p-4 rounded">
            <h2 class="font-heading font-semibold h6 mb-3">Panduan Pengisian</h2>
            <div class="small myds-text--muted">
                <div class="mb-3">
                    <strong class="text-primary">Nama Item</strong>
                    <p>Gunakan nama yang jelas dan mudah dicari. Contoh: "Komputer Desktop HP ProDesk 400 G7"</p>
                </div>
                <div class="mb-3">
                    <strong class="text-primary">Kuantiti</strong>
                    <p>Masukkan bilangan unit yang sebenar ada dalam stok</p>
                </div>
                <div class="mb-3">
                    <strong class="text-primary">Harga</strong>
                    <p>Harga per unit untuk tujuan inventori dan pelaporan</p>
                </div>
                <div>
                    <strong class="text-primary">Keterangan</strong>
                    <p>Maklumat tambahan seperti nombor siri, model, atau nota khas</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/pages/inventories-create.js')
@endpush
