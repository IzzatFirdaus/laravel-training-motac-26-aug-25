@extends('layouts.app')

@section('title', 'Cipta Kenderaan — ' . config('app.name', 'second-crud'))

@section('content')
<div class="container" style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <!-- Page Title -->
    <h1>Create Vehicle</h1>

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

    <!-- Form starts here. It sends data to vehicles.store route using POST method -->
    <form method="POST" action="{{ route('vehicles.store') }}">
        @csrf

        <x-form-field name="name" label="Name" :value="old('name')" required />

        <div style="margin-bottom: 15px;">
            <label for="user_id">Owner (optional)</label><br>
            <select id="user_id" name="user_id" style="width: 100%; padding: 8px;" aria-describedby="user_id-error">
                <option value="">(no owner)</option>
                @foreach(($users ?? collect()) as $user)
                    <option value="{{ $user->id }}" @selected((string) old('user_id') === (string) $user->id)>{{ $user->name }}</option>
                @endforeach
            </select>
            @error('user_id') <div id="user_id-error" style="color: red;">{{ $message }}</div> @enderror
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
        <div style="margin-top: 20px;">
            <button type="submit" style="padding: 10px 20px; background-color: #007bff; color: white; border: none;">Save</button>
            <a href="{{ route('vehicles.index') }}" style="margin-left: 10px; text-decoration: none; color: #007bff;">Cancel</a>
        </div>
    </form>
</div>
@endsection
