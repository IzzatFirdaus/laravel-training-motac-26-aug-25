@php
use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.app')

@section('title', 'Ubah Inventori — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="page-heading">
    <nav aria-label="Breadcrumb" class="mb-4">
        <ol class="d-flex list-unstyled myds-text--muted myds-body-sm m-0 p-0">
            <li><a href="{{ route('inventories.index') }}" class="text-primary text-decoration-none" data-nav="inventories">Inventori</a></li>
            <li class="mx-2" aria-hidden="true">/</li>
            <li aria-current="page" class="myds-text--muted">Ubah</li>
        </ol>
    </nav>

    <header class="mb-6">
        <h1 id="page-heading" class="myds-heading-md font-heading font-semibold mb-2">Ubah Inventori</h1>
        <p class="myds-body-sm myds-text--muted">Kemas kini maklumat item inventori. Medan bertanda * adalah wajib.</p>
    </header>

    <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile gap-4">
        <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-8">
            @if (session('status'))
                <div class="myds-alert myds-alert--success mb-4" role="status" aria-live="polite">{{ session('status') }}</div>
            @endif

            <div class="bg-surface border rounded-m p-4 shadow-sm" data-myds-card="inventory-edit">
                <form method="POST" action="{{ route('inventories.update', $inventory->id) }}" novalidate aria-labelledby="form-title" data-myds-form="inventory-edit">
                    @csrf
                    @method('PUT')

                    <h2 id="form-title" class="sr-only">Borang Ubah Inventori</h2>

                    <div class="mb-4" data-field="name">
                        <label for="name" class="myds-label myds-body-sm font-medium d-block mb-2" data-field-label="Nama Item">Nama Item <span class="myds-text--danger ms-1" aria-hidden="true">*</span></label>
                        <input id="name"
                               name="name"
                               type="text"
                               class="myds-input myds-tap-target @error('name') is-invalid myds-input--error @enderror"
                               value="{{ old('name', $inventory->name) }}"
                               maxlength="255"
                               required
                               aria-required="true"
                               aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                               aria-describedby="name-help @error('name') name-error @enderror"
                               data-myds-input="text">
                        <div id="name-help" class="myds-body-xs myds-text--muted mt-1" data-field-help="Nama yang jelas dan mudah dicari.">Nama yang jelas dan mudah dicari.</div>
                        @error('name')
                            <div id="name-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert" data-field-error="{{ $message }}">
                                <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror

                        <div id="users-autocomplete" class="position-relative mt-2" data-search-url="{{ route('users.search') }}">
                            <ul id="users-list" class="autocomplete-list visually-hidden" role="listbox" aria-label="Cadangan pengguna"></ul>
                            <div id="users-list-live" class="visually-hidden" aria-live="polite" aria-atomic="true"></div>
                        </div>
                    </div>

                    <div class="mb-3" data-field="user_id">
                        <label for="user_id" class="myds-label myds-body-sm font-medium d-block mb-2" data-field-label="Pemilik">Pemilik (pilihan)</label>
                        @if(Auth::check() && Auth::user()->hasRole('admin'))
                            <select id="user_id"
                                    name="user_id"
                                    class="myds-input myds-tap-target @error('user_id') is-invalid myds-input--error @enderror"
                                    aria-describedby="user-help @error('user_id') user_id-error @enderror"
                                    data-myds-select="owner">
                                  <option value="">(tiada pemilik)</option>
                                @foreach(($users ?? collect()) as $user)
                                    <option value="{{ $user->id }}" @selected((string) old('user_id', $inventory->user_id) === (string) $user->id)>{{ e($user->name) }}</option>
                                @endforeach
                            </select>
                            <div id="user-help" class="myds-body-xs myds-text--muted mt-1" data-field-help="Biarkan kosong jika tiada pemilik ditetapkan.">Biarkan kosong jika tiada pemilik ditetapkan.</div>
                            @error('user_id')
                                <div id="user_id-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert" data-field-error="{{ $message }}">
                                    <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        @else
                            <input type="hidden" name="user_id" value="{{ $inventory->user_id ?? '' }}">
                            <div class="myds-input bg-muted cursor-not-allowed" role="textbox" aria-readonly="true" tabindex="-1">{{ $inventory->user?->name ?? '(tiada pemilik)' }}</div>
                        @endif
                    </div>

                        <div class="mb-3">
                            <label for="qty" class="myds-label font-medium">Kuantiti <span class="myds-text--danger" aria-hidden="true">*</span></label>
                            <input id="qty" name="qty" type="number" min="0" class="myds-input @error('qty') is-invalid @enderror" value="{{ old('qty', $inventory->qty ?? 0) }}" required aria-required="true" aria-describedby="qty-help @error('qty') qty-error @enderror">
                            <div id="qty-help" class="myds-body-xs myds-text--muted mt-1">Bilangan unit.</div>
                            @error('qty') <div id="qty-error" class="myds-field-error mt-1" role="alert">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="myds-label font-medium">Harga (RM)</label>
                            <div class="myds-input-group">
                                <span class="myds-input-group__addon" aria-hidden="true">RM</span>
                                <input id="price" name="price" type="number" step="0.01" min="0" class="myds-input @error('price') is-invalid @enderror" value="{{ old('price', $inventory->price) }}" aria-describedby="price-help @error('price') price-error @enderror">
                            </div>
                            <div id="price-help" class="myds-body-xs myds-text--muted mt-1">Harga per unit.</div>
                            @error('price') <div id="price-error" class="myds-field-error mt-1" role="alert">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="myds-label font-medium">Keterangan</label>
                            <textarea id="description" name="description" rows="4" class="myds-input @error('description') is-invalid @enderror" aria-describedby="description-help @error('description') description-error @enderror">{{ old('description', $inventory->description) }}</textarea>
                            <div id="description-help" class="myds-body-xs myds-text--muted mt-1">Maklumat tambahan seperti nombor siri atau model.</div>
                            @error('description') <div id="description-error" class="myds-field-error mt-1" role="alert">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="vehicle_ids" class="myds-label">Kenderaan berkaitan</label>
                            <select id="vehicle_ids" name="vehicle_ids[]" class="myds-select @error('vehicle_ids') is-invalid @enderror" multiple size="5" aria-describedby="vehicles-help">
                                @foreach($inventory->vehicles ?? collect() as $v)
                                    <option value="{{ $v->id }}" @selected(in_array($v->id, old('vehicle_ids', $inventory->vehicles->pluck('id')->toArray() ?? [])))>{{ $v->name }}</option>
                                @endforeach
                            </select>
                            <div id="vehicles-help" class="myds-body-xs myds-text--muted mt-1">Pilih kenderaan berkaitan jika perlu.</div>
                            @error('vehicle_ids') <div class="myds-field-error mt-1" role="alert">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="warehouse_id" class="myds-label">Gudang</label>
                            <select id="warehouse_id" name="warehouse_id" class="myds-select" data-warehouses-url="{{ url('/api/warehouses') }}" data-initial="{{ old('warehouse_id', $inventory->warehouse_id ?? '') }}">
                            <option value="">(Pilih gudang)</option>
                            </select>
                            @error('warehouse_id') <div class="myds-field-error mt-1" role="alert">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--tertiary">Batal</a>
                            <button type="submit" class="myds-btn myds-btn--primary">Kemas kini</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <aside class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-4">
            <div class="myds-card">
                <div class="myds-card__body">
                    <h2 class="myds-heading-xs font-heading mb-2">Panduan Pengisian</h2>
                    <div class="myds-body-sm myds-text--muted">
                        <p><strong>Nama Item:</strong> Gunakan nama deskriptif yang mudah dicari.</p>
                        <p><strong>Kuantiti:</strong> Masukkan bilangan unit dalam stok.</p>
                        <p><strong>Harga:</strong> Harga per unit untuk tujuan pelaporan.</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</main>

@push('scripts')
@vite('resources/js/pages/inventories-edit.js')
@endpush

@endsection
                    <div class="myds-form-group mb-4">
