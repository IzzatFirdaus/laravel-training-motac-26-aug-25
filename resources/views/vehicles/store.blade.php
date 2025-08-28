@extends('layouts.app')

@section('title', 'Cipta Kenderaan — ' . config('app.name', 'second-crud'))

@section('content')
<div class="container" style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <!-- Page Title -->
    <h1>Create Vehicle</h1>

    <!-- If there is a success message, show it -->
    @if(session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    <!-- If there are any validation errors, list them here -->
    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form starts here. It sends data to vehicles.store route using POST method -->
    <form method="POST" action="{{ route('vehicles.store') }}">
        @csrf

        <x-form-field name="name" label="Name" :value="old('name')" required />

        <div style="margin-bottom: 15px;">
            <label for="user_id">Owner (optional)</label><br>
            <select id="user_id" name="user_id" class="form-control" aria-describedby="user_id-error">
                <option value="">(no owner)</option>
                @foreach(($users ?? collect()) as $user)
                    <option value="{{ $user->id }}" @selected((string) old('user_id') === (string) $user->id)>{{ $user->name }}</option>
                @endforeach
            </select>
            @error('user_id') <div id="user_id-error" class="text-danger myds-action--danger">{{ $message }}</div> @enderror
        </div>

        <x-form-field name="qty" type="number" label="Quantity" :value="old('qty', 0)" required />

        <x-form-field name="price" type="number" label="Price" :value="old('price', '0.00')" required />

        <x-form-field name="description" type="textarea" label="Description">{{ old('description') }}</x-form-field>

        <!-- Optional: Category Dropdown if categories are provided -->
        @if(isset($categories) && count($categories) > 0)
            <div style="margin-bottom: 15px;">
                <label for="category_id">Category</label><br>
                <select id="category_id" name="category_id" style="width: 100%; padding: 8px;">
                    <option value="">-- Select category --</option>
                    @foreach($categories as $id => $label)
                        <option value="{{ $id }}" @selected(old('category_id') == $id)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <div style="color: red;">{{ $message }}</div>
                @enderror
            </div>
        @endif

        <!-- Submit and Cancel buttons -->
        <div class="d-flex justify-content-end" style="margin-top: 20px;">
            <a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--secondary me-2">Cancel</a>
            <button type="submit" class="myds-btn myds-btn--primary">Save</button>
        </div>
    </form>
</div>
@endsection
