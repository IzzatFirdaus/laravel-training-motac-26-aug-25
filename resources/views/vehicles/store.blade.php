@extends('layouts.app')

@section('title', 'Cipta Kenderaan — ' . config('app.name', 'second-crud'))

@section('content')
<div class="myds-container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <header class="mb-3">
                <h1 class="h3">Cipta Kenderaan</h1>
                <p class="myds-text--muted mb-0">Isikan maklumat kenderaan baharu.</p>
            </header>

            @if(session('success'))
                <div class="myds-alert myds-alert--success" role="alert">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="myds-alert myds-alert--danger" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="myds-card">
                <div class="myds-card__body">
                    <form method="POST" action="{{ route('vehicles.store') }}">
                        @csrf

                        <x-form-field name="name" label="Nama" :value="old('name')" required />

                        <div class="mb-3">
                            <label for="user_id" class="myds-label">Pemilik (pilihan)</label>
                            @if(auth()->check() && auth()->user()->hasRole('admin'))
                                <select id="user_id" name="user_id" class="myds-input" aria-describedby="user_id-error">
                                    <option value="">(tiada pemilik)</option>
                                    @foreach(($users ?? collect()) as $user)
                                        <option value="{{ $user->id }}" @selected((string) old('user_id') === (string) $user->id)>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id') <div id="user_id-error" class="myds-text--danger">{{ $message }}</div> @enderror
                            @else
                                <input type="hidden" name="user_id" value="{{ auth()->id() ?? '' }}">
                                <div class="myds-body-xs myds-text--muted">Anda akan menjadi pemilik item ini.</div>
                            @endif
                        </div>

                        <x-form-field name="qty" type="number" label="Kuantiti" :value="old('qty', 0)" required />

                        <x-form-field name="price" type="number" label="Harga" :value="old('price', '0.00')" required />

                        <x-form-field name="description" type="textarea" label="Keterangan">{{ old('description') }}</x-form-field>

                        @if(isset($categories) && count($categories) > 0)
                            <div class="mb-3">
                                <label for="category_id" class="myds-label">Kategori</label>
                                <select id="category_id" name="category_id" class="myds-input" aria-describedby="category_id-error">
                                    <option value="">— Pilih kategori —</option>
                                    @foreach($categories as $id => $label)
                                        <option value="{{ $id }}" @selected(old('category_id') == $id)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div id="category_id-error" class="myds-text--danger">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--secondary mr-2">Batal</a>
                            <button type="submit" class="myds-btn myds-btn--primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
