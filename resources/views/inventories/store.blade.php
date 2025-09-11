@extends('layouts.app')

@section('title', 'Inventori Dicipta — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="create-status">

    {{-- MYDS Breadcrumb Navigation --}}
    <nav aria-label="Breadcrumb" class="mb-4">
        <ol class="d-flex list-unstyled myds-text--muted myds-body-sm">
            <li><a href="{{ route('inventories.index') }}" class="text-primary text-decoration-none hover:text-decoration-underline">Inventori</a></li>
            <li class="mx-2" aria-hidden="true">/</li>
            <li><a href="{{ route('inventories.create') }}" class="text-primary text-decoration-none hover:text-decoration-underline">Cipta Baharu</a></li>
            <li class="mx-2" aria-hidden="true">/</li>
            <li aria-current="page" class="myds-text--muted">Berjaya Dicipta</li>
        </ol>
    </nav>

    {{-- MYDS Grid Container --}}
    <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
        <div class="mobile:col-span-4 tablet:col-span-6 tablet:col-start-2 desktop:col-span-8 desktop:col-start-3">

            {{-- Success Card --}}
            <div class="myds-card shadow-sm">
                <div class="myds-card__body p-4">

                    {{-- Status Alert --}}
                    @if (session('status'))
                        <div class="myds-alert myds-alert--success mb-4" role="status" aria-live="polite"
                             data-alert-type="success" data-alert-dismissible="false">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle me-2 flex-shrink-0" aria-hidden="true"></i>
                                <div>
                                    <h3 class="myds-body-md font-medium mb-1">Berjaya</h3>
                                    <p class="mb-0 myds-body-sm">{{ session('status') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Success Header --}}
                    <header class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;" aria-hidden="true"></i>
                        </div>
                        <h1 id="create-status" class="myds-heading-md font-heading font-semibold mb-2">
                            Inventori Berjaya Dicipta
                        </h1>
                        <p class="myds-body-md myds-text--muted mb-0">
                            Item inventori baharu telah berjaya ditambah ke dalam sistem.
                        </p>
                        <p class="myds-body-sm myds-text--muted mt-1">
                            <em lang="en">The new inventory item has been successfully added to the system.</em>
                        </p>
                    </header>

                    {{-- Action Buttons --}}
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="{{ route('inventories.index') }}"
                           class="myds-btn myds-btn--secondary myds-tap-target"
                           data-action="navigate" data-destination="inventory-list"
                           aria-label="Kembali ke senarai inventori">
                            <i class="bi bi-list me-1" aria-hidden="true"></i>
                            Kembali ke Senarai
                        </a>

                        @isset($inventory)
                            <a href="{{ route('inventories.show', $inventory->id) }}"
                               class="myds-btn myds-btn--primary myds-tap-target"
                               data-action="navigate" data-destination="inventory-details"
                               data-inventory-id="{{ $inventory->id }}"
                               aria-label="Lihat butiran inventori yang baru dicipta">
                                <i class="bi bi-eye me-1" aria-hidden="true"></i>
                                Lihat Inventori
                            </a>
                        @endisset

                        <a href="{{ route('inventories.create') }}"
                           class="myds-btn myds-btn--tertiary myds-tap-target"
                           data-action="navigate" data-destination="inventory-create"
                           aria-label="Cipta inventori baharu yang lain">
                            <i class="bi bi-plus me-1" aria-hidden="true"></i>
                            Cipta Lagi
                        </a>
                    </div>

                    {{-- Additional Information --}}
                    <div class="mt-4 p-3 bg-light rounded">
                        <h4 class="myds-body-sm font-medium mb-2">Langkah Seterusnya</h4>
                        <ul class="myds-body-xs myds-text--muted mb-0 ps-3">
                            <li>Semak maklumat inventori yang telah dicipta</li>
                            <li>Kemaskini keterangan tambahan jika diperlukan</li>
                            <li>Tetapkan kawalan akses jika perlu</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
