@extends('layouts.app')

@section('title', 'Cipta Kenderaan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main">

{{-- MYDS Breadcrumb Navigation --}}
<nav aria-label="Breadcrumb" class="mb-4">
    <ol class="d-flex list-unstyled text-muted myds-body-sm">
        <li><a href="{{ route('vehicles.index') }}" class="text-primary text-decoration-none hover:text-decoration-underline">Kenderaan</a></li>
        <li class="mx-2" aria-hidden="true">/</li>
        <li aria-current="page" class="text-muted">Cipta Baharu</li>
    </ol>
</nav>

{{-- Page Header (MyGOVEA clear display principles) --}}
<header class="mb-6">
    <h1 class="myds-heading-md font-heading font-semibold mb-3">Cipta Kenderaan Baharu</h1>
    <div class="myds-body-md text-muted">
        <p class="mb-2">
            Isi maklumat di bawah untuk menambah kenderaan baharu ke dalam sistem inventori.
            Medan bertanda bintang (*) adalah <strong>wajib diisi</strong>.
        </p>
        <p class="myds-body-sm text-muted mb-0">
            <em lang="en">Fill in the information below to add a new vehicle to the inventory system.</em>
        </p>
    </div>
</header>

{{-- Form Container with MYDS Grid --}}
<div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
    <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-8">

        {{-- Status Message --}}
        @if (session('status'))
            <div class="myds-alert myds-alert--success d-flex align-items-start mb-4" role="alert">
                <svg width="20" height="20" class="me-2 flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div>
                    <h4 class="myds-body-md font-medium">Berjaya</h4>
                    <p class="mb-0 myds-body-sm">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        {{-- Main Form Card --}}
        <div class="bg-surface border rounded-m p-4 shadow-sm">
            <form method="POST" action="{{ route('vehicles.store') }}" novalidate aria-labelledby="form-title">
                @csrf

                <h2 id="form-title" class="sr-only">Borang Cipta Kenderaan Baharu</h2>

                {{-- Required Fields Notice --}}
                <div class="bg-muted border-l-4 border-primary p-3 mb-4">
                    <div class="d-flex align-items-start">
                        <svg width="16" height="16" class="me-2 mt-0.5 text-primary flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                            <path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        <div>
                            <p class="myds-body-sm font-medium mb-1">Panduan Pengisian Borang</p>
                            <p class="myds-body-sm text-muted mb-0">
                                Pastikan semua medan wajib (*) telah diisi dengan lengkap dan tepat sebelum menyerahkan borang.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Name Field (MYDS Input Component) --}}
                <div class="mb-4">
                    <label for="name" class="form-label myds-body-sm font-medium d-block mb-2">
                        Nama Kenderaan
                        <span class="text-danger ms-1" aria-label="medan wajib">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="myds-input @error('name') is-invalid border-danger @enderror"
                           value="{{ old('name') }}"
                           placeholder="Contoh: Honda Civic 1.5 TC-P"
                           aria-describedby="name-help @error('name') name-error @enderror"
                           aria-required="true"
                           maxlength="255"
                           required>
                    <div id="name-help" class="myds-body-xs text-muted mt-1">
                        Masukkan nama lengkap dan deskriptif untuk kenderaan (maksimum 255 aksara)
                    </div>
                    @error('name')
                        <div id="name-error" class="d-flex align-items-start text-danger myds-body-xs mt-2" role="alert">
                            <svg width="14" height="14" class="me-1 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                <line x1="15" y1="9" x2="9" y2="15" stroke="currentColor" stroke-width="2"/>
                                <line x1="9" y1="9" x2="15" y2="15" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Owner Field --}}
                <div class="mb-4">
                    <label for="user_id" class="form-label myds-body-sm font-medium d-block mb-2">
                        Pemilik Kenderaan
                    </label>
                    @if(auth()->check() && auth()->user()->hasRole('admin'))
                        <select id="user_id"
                                name="user_id"
                                class="myds-input @error('user_id') is-invalid border-danger @enderror"
                                aria-describedby="user_id-help @error('user_id') user_id-error @enderror">
                            <option value="">(Tiada pemilik ditetapkan)</option>
                            @foreach(($users ?? collect()) as $user)
                                <option value="{{ $user->id }}" {{ (string) old('user_id') === (string) $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="user_id-help" class="myds-body-xs text-muted mt-1">
                            Pilih pengguna yang akan menjadi pemilik kenderaan ini (opsional)
                        </div>
                    @else
                        <input type="hidden" name="user_id" value="{{ auth()->id() ?? '' }}">
                        <div class="myds-input bg-muted" style="cursor: not-allowed;">
                            {{ auth()->user()->name ?? 'Pengguna Semasa' }}
                        </div>
                        <div id="user_id-help" class="myds-body-xs text-muted mt-1">
                            Anda akan menjadi pemilik kenderaan ini
                        </div>
                    @endif
                    @error('user_id')
                        <div id="user_id-error" class="d-flex align-items-start text-danger myds-body-xs mt-2" role="alert">
                            <svg width="14" height="14" class="me-1 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                <line x1="15" y1="9" x2="9" y2="15" stroke="currentColor" stroke-width="2"/>
                                <line x1="9" y1="9" x2="15" y2="15" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Quantity Field --}}
                <div class="mb-4">
                    <label for="qty" class="form-label myds-body-sm font-medium d-block mb-2">
                        Kuantiti
                        <span class="text-danger ms-1" aria-label="medan wajib">*</span>
                    </label>
                    <input type="number"
                           id="qty"
                           name="qty"
                           class="myds-input @error('qty') is-invalid border-danger @enderror"
                           value="{{ old('qty', 1) }}"
                           min="1"
                           placeholder="1"
                           aria-describedby="qty-help @error('qty') qty-error @enderror"
                           aria-required="true"
                           required>
                    <div id="qty-help" class="myds-body-xs text-muted mt-1">
                        Masukkan bilangan unit kenderaan (minimum 1)
                    </div>
                    @error('qty')
                        <div id="qty-error" class="d-flex align-items-start text-danger myds-body-xs mt-2" role="alert">
                            <svg width="14" height="14" class="me-1 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                <line x1="15" y1="9" x2="9" y2="15" stroke="currentColor" stroke-width="2"/>
                                <line x1="9" y1="9" x2="15" y2="15" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Price Field --}}
                <div class="mb-4">
                    <label for="price" class="form-label myds-body-sm font-medium d-block mb-2">
                        Harga (RM)
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-muted">RM</span>
                        <input type="number"
                               id="price"
                               name="price"
                               class="myds-input @error('price') is-invalid border-danger @enderror"
                               value="{{ old('price') }}"
                               step="0.01"
                               min="0"
                               placeholder="0.00"
                               aria-describedby="price-help @error('price') price-error @enderror">
                    </div>
                    <div id="price-help" class="myds-body-xs text-muted mt-1">
                        Masukkan harga per unit dalam Ringgit Malaysia (opsional)
                    </div>
                    @error('price')
                        <div id="price-error" class="d-flex align-items-start text-danger myds-body-xs mt-2" role="alert">
                            <svg width="14" height="14" class="me-1 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                <line x1="15" y1="9" x2="9" y2="15" stroke="currentColor" stroke-width="2"/>
                                <line x1="9" y1="9" x2="15" y2="15" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Description Field --}}
                <div class="mb-6">
                    <label for="description" class="form-label myds-body-sm font-medium d-block mb-2">
                        Keterangan
                    </label>
                    <textarea id="description"
                              name="description"
                              class="myds-input @error('description') is-invalid border-danger @enderror"
                              rows="4"
                              placeholder="Tambahan maklumat tentang kenderaan..."
                              aria-describedby="description-help @error('description') description-error @enderror"
                              maxlength="1000">{{ old('description') }}</textarea>
                    <div id="description-help" class="myds-body-xs text-muted mt-1">
                        Tambahan maklumat tentang kenderaan (maksimum 1000 aksara)
                    </div>
                    @error('description')
                        <div id="description-error" class="d-flex align-items-start text-danger myds-body-xs mt-2" role="alert">
                            <svg width="14" height="14" class="me-1 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                <line x1="15" y1="9" x2="9" y2="15" stroke="currentColor" stroke-width="2"/>
                                <line x1="9" y1="9" x2="15" y2="15" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Form Actions --}}
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-end">
                    <a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--secondary">
                        <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <polyline points="15,18 9,12 15,6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Batal
                    </a>
                    <button type="submit" class="myds-btn myds-btn--primary">
                        <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="2"/>
                            <polyline points="7,3 7,8 15,8" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        Cipta Kenderaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Help Sidebar (MyGOVEA user assistance principle) --}}
    <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-4 mt-4 desktop:mt-0">
        <div class="bg-muted border rounded-m p-4">
            <h3 class="myds-heading-xs font-heading font-medium mb-3">Bantuan</h3>
            <div class="space-y-3">
                <div>
                    <h4 class="myds-body-sm font-medium mb-1">Tips Pengisian</h4>
                    <ul class="myds-body-xs text-muted space-y-1">
                        <li>Gunakan nama yang mudah difahami</li>
                        <li>Pastikan kuantiti dan harga adalah tepat</li>
                        <li>Tambah keterangan untuk maklumat tambahan</li>
                    </ul>
                </div>
                <div>
                    <h4 class="myds-body-sm font-medium mb-1">Sokongan</h4>
                    <p class="myds-body-xs text-muted">
                        Jika menghadapi masalah, hubungi pasukan sokongan di
                        <a href="mailto:support@jdn.gov.my" class="text-primary">support@jdn.gov.my</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
@endsection
