@extends('layouts.app')

@section('title', 'Cipta Inventori — ' . config('app.name', 'second-crud'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Inventory</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success myds-alert myds-alert--success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('inventories.store') }}">
                        @csrf

                        <x-form-field name="name" label="Name" :value="old('name')" required />

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Owner (optional)</label>
                            @if(auth()->check() && auth()->user()->hasRole('admin'))
                                <select id="user_id" name="user_id" class="form-control myds-select" aria-describedby="user_id-error">
                                    <option value="">(no owner)</option>
                                    @foreach(($users ?? collect()) as $user)
                                        <option value="{{ $user->id }}" @selected((string) old('user_id') === (string) $user->id)>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id') <div id="user_id-error" class="text-danger myds-action--danger">{{ $message }}</div> @enderror
                            @else
                                <input type="hidden" name="user_id" value="{{ auth()->id() ?? '' }}">
                                <div class="form-text">You will be the owner of this item.</div>
                            @endif
                        </div>

                        <x-form-field name="qty" type="number" label="Quantity" :value="old('qty', 0)" required />

                        <x-form-field name="price" type="number" label="Price" :value="old('price', '')" required />

                        <x-form-field name="description" type="textarea" label="Description">{{ old('description') }}</x-form-field>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--secondary me-2">Cancel</a>
                            <button type="submit" class="myds-btn myds-btn--primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
