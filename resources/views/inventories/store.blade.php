@extends('layouts.app')

@section('title', 'Cipta Inventori — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="page-title">
  <header class="mb-3">
    <h1 id="page-title" class="myds-heading-md font-heading">Cipta Inventori</h1>
  </header>

  <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
    <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-8">
      <div class="bg-surface border rounded p-4 shadow-sm">
        @if (session('status'))
          <div class="myds-alert myds-alert--success mb-3" role="status" aria-live="polite">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('inventories.store') }}" aria-label="Borang Cipta Inventori" novalidate>
          @csrf

          <x-form-field name="name" label="Nama" :value="old('name')" required />

          <div class="mb-3">
            <label for="user_id" class="form-label myds-body-sm font-medium">Pemilik (pilihan)</label>
            @if(auth()->check() && auth()->user()->hasRole('admin'))
              <select id="user_id" name="user_id" class="form-control myds-input" aria-describedby="user_id-help">
                <option value="">(tiada pemilik)</option>
                @foreach(($users ?? collect()) as $user)
                  <option value="{{ $user->id }}" @selected((string) old('user_id') === (string) $user->id)>{{ $user->name }}</option>
                @endforeach
              </select>
              <div id="user_id-help" class="myds-body-xs text-muted mt-1">Biarkan kosong jika pemilik belum ditetapkan.</div>
              @error('user_id') <div class="text-danger myds-body-xs mt-1" role="alert">{{ $message }}</div> @enderror
            @else
              <input type="hidden" name="user_id" value="{{ auth()->id() ?? '' }}">
              <div class="form-text myds-body-xs text-muted">Anda akan menjadi pemilik item ini.</div>
            @endif
          </div>

          <x-form-field name="qty" type="number" label="Kuantiti" :value="old('qty', 0)" required />
          <x-form-field name="price" type="number" label="Harga" :value="old('price', '')" />
          <x-form-field name="description" type="textarea" label="Keterangan">{{ old('description') }}</x-form-field>

          <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--secondary">Batal</a>
            <button type="submit" class="myds-btn myds-btn--primary">Cipta</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>
@endsection
