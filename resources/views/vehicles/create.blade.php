@extends('layouts.app')

@section('title', 'Cipta Kenderaan — ' . config('app.name', 'second-crud'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Cipta Kenderaan</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('vehicles.store') }}">
                        @csrf

                        <x-form-field name="name" label="Nama" :value="old('name')" required />

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Pemilik (pilihan)</label>
                            <select id="user_id" name="user_id" class="form-control" aria-describedby="user_id-error">
                                <option value="">(tiada pemilik)</option>
                                @foreach(($users ?? collect()) as $user)
                                    <option value="{{ $user->id }}" @selected((string) old('user_id') === (string) $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id') <div id="user_id-error" class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <x-form-field name="qty" type="number" label="Kuantiti" :value="old('qty', 0)" required />

                        <x-form-field name="price" type="number" label="Harga" :value="old('price', '')" required />

                        <x-form-field name="description" type="textarea" label="Keterangan">{{ old('description') }}</x-form-field>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary me-2" aria-label="Batal dan kembali ke senarai">Batal</a>
                            <button type="submit" class="btn btn-primary" aria-label="Cipta kenderaan">Cipta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
