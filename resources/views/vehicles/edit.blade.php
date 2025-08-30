@extends('layouts.app')

@section('title', 'Ubah Kenderaan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="edit-heading">
    <header class="mb-4">
        <h1 id="edit-heading" class="myds-heading-md font-heading font-semibold">Ubah Kenderaan</h1>
        <p class="myds-body-sm myds-text--muted mb-0">Kemaskini maklumat kenderaan. Kosongkan kata laluan (jika ada) untuk mengekalkan kata laluan sedia ada.</p>
    </header>

    <div class="myds-card">
        <div class="myds-card__body">
            @if (session('status'))
                <div class="myds-alert myds-alert--success mb-3" role="status">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('vehicles.update', $vehicle->id) }}" novalidate aria-labelledby="form-edit-title">
                @csrf
                @method('PUT')

                <h2 id="form-edit-title" class="visually-hidden">Borang Ubah Kenderaan</h2>

                <div class="mb-3">
                    <label for="name" class="myds-label d-block mb-2">Nama <span class="myds-text--danger">*</span></label>
                    <input id="name" name="name" type="text" class="myds-input @error('name') is-invalid @enderror" value="{{ old('name', $vehicle->name) }}" required aria-describedby="name-error" maxlength="255" />
                    @error('name') <div id="name-error" class="myds-body-xs myds-text--danger mt-2" role="alert">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="user_id" class="myds-label d-block mb-2">Pemilik (pilihan)</label>
                    @if(auth()->check() && auth()->user()->hasRole('admin'))
                        <select id="user_id" name="user_id" class="myds-input @error('user_id') is-invalid @enderror" aria-describedby="user_id-error">
                            <option value="">(Tiada pemilik)</option>
                            @foreach(($users ?? collect()) as $user)
                                <option value="{{ $user->id }}" {{ (string) old('user_id', $vehicle->user_id) === (string) $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id') <div id="user_id-error" class="myds-body-xs myds-text--danger mt-2" role="alert">{{ $message }}</div> @enderror
                    @else
                        <input type="hidden" name="user_id" value="{{ $vehicle->user_id ?? '' }}">
                        <div class="myds-input bg-muted">{{ $vehicle->user?->name ?? '(tiada pemilik)' }}</div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="qty" class="myds-label d-block mb-2">Kuantiti</label>
                    <input id="qty" name="qty" type="number" min="0" class="myds-input @error('qty') is-invalid @enderror" value="{{ old('qty', $vehicle->qty ?? 0) }}" aria-describedby="qty-error" />
                    @error('qty') <div id="qty-error" class="myds-body-xs myds-text--danger mt-2" role="alert">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="price" class="myds-label d-block mb-2">Harga (RM)</label>
                    <div class="d-flex gap-2">
                        <span class="myds-input-addon">RM</span>
                        <input id="price" name="price" type="number" step="0.01" min="0" class="myds-input @error('price') is-invalid @enderror" value="{{ old('price', $vehicle->price ?? '') }}" aria-describedby="price-error" />
                    </div>
                    @error('price') <div id="price-error" class="myds-body-xs myds-text--danger mt-2" role="alert">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="myds-label d-block mb-2">Keterangan</label>
                    <textarea id="description" name="description" rows="4" class="myds-input @error('description') is-invalid @enderror" aria-describedby="description-error">{{ old('description', $vehicle->description) }}</textarea>
                    @error('description') <div id="description-error" class="myds-body-xs myds-text--danger mt-2" role="alert">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--secondary" aria-label="Batal">Batal</a>
                    <button type="submit" class="myds-btn myds-btn--primary" aria-label="Kemaskini Kenderaan">Kemaskini</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection