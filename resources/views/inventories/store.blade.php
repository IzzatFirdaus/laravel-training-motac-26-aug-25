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
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('inventories.store') }}">
                        @csrf

                        <x-form-field name="name" label="Name" :value="old('name')" required />

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Owner (optional)</label>
                            <select id="user_id" name="user_id" class="form-control" aria-describedby="user_id-error">
                                <option value="">(no owner)</option>
                                @foreach(($users ?? collect()) as $user)
                                    <option value="{{ $user->id }}" @selected((string) old('user_id') === (string) $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id') <div id="user_id-error" class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <x-form-field name="qty" type="number" label="Quantity" :value="old('qty', 0)" required />

                        <x-form-field name="price" type="number" label="Price" :value="old('price', '')" required />

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
@extends('layouts.app')

@section('title', 'Create Inventory — second-crud')

@section('content')
<div class="container" style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <!-- Page Title -->
    <h1>Create Inventory Item</h1>

    <!-- If there is a success message, show it -->
    @if(session('success'))
        <div style="background-color: #d4edda; padding: 10px; border: 1px solid #c3e6cb; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- If there are any validation errors, list them here -->
    @if($errors->any())
        <div style="background-color: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; margin-bottom: 20px;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form starts here. It sends data to inventories.store route using POST method -->
    <form method="POST" action="{{ route('inventories.store') }}">
        @csrf  <!-- This token is required for security -->

        <!-- Inventory Name Input -->
        <div style="margin-bottom: 15px;">
            <label for="name">Name</label><br>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required style="width: 100%; padding: 8px;">
            @error('name')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <!-- SKU Input (optional) -->
        <div style="margin-bottom: 15px;">
            <label for="sku">SKU</label><br>
            <input id="sku" name="sku" type="text" value="{{ old('sku') }}" style="width: 100%; padding: 8px;">
            @error('sku')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Quantity Input -->
        <div style="margin-bottom: 15px;">
            <label for="quantity">Quantity</label><br>
            <input id="quantity" name="quantity" type="number" min="0" value="{{ old('quantity', 0) }}" style="width: 100%; padding: 8px;">
            @error('quantity')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Price Input -->
        <div style="margin-bottom: 15px;">
            <label for="price">Price</label><br>
            <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', '0.00') }}" style="width: 100%; padding: 8px;">
            @error('price')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description Textarea -->
        <div style="margin-bottom: 15px;">
            <label for="description">Description</label><br>
            <textarea id="description" name="description" rows="4" style="width: 100%; padding: 8px;">{{ old('description') }}</textarea>
            @error('description')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

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
        <div style="margin-top: 20px;">
            <button type="submit" style="padding: 10px 20px; background-color: #007bff; color: white; border: none;">Save</button>
            <a href="{{ route('inventories.index') }}" style="margin-left: 10px; text-decoration: none; color: #007bff;">Cancel</a>
        </div>
    </form>
</div>
@endsection
