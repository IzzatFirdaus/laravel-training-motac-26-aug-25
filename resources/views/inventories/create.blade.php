@extends('layouts.app')

@section('title', 'Create Inventory — second-crud')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Inventory Create') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('inventories.store') }}">
                        @csrf

                        <x-form-field name="name" label="Name" :value="old('name')" />

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Owner (optional)</label>
                            <select id="user_id" name="user_id" class="form-control" aria-describedby="user_id-error">
                                <option value="">(no owner)</option>
                                @foreach(($users ?? collect()) as $user)
                                    <option value="{{ $user->id }}" {{ (string) old('user_id') === (string) $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id') <div id="user_id-error" class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <x-form-field name="qty" type="number" label="Quantity" :value="old('qty', 0)" />

                        <x-form-field name="price" type="number" label="Price" :value="old('price', '')" />

                        <x-form-field name="description" type="textarea" label="Description">{{ old('description') }}</x-form-field>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('inventories.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
