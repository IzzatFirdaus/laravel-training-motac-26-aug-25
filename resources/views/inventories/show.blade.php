@extends('layouts.app')

@section('title', 'Inventory Detail — second-crud')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <!-- Display the inventory ID in the header -->
                <div class="card-header">Inventory #{{ $inventory->id }}</div>

                <div class="card-body">
                    <!-- Display inventory details -->
                    <p><strong>Name:</strong> {{ $inventory->name }}</p>
                    <p><strong>Owner:</strong> {{ $inventory->user?->name ?? '—' }}</p> <!-- Show owner name or a dash if none -->
                    <p><strong>Quantity:</strong> {{ $inventory->qty }}</p>
                    <p><strong>Price:</strong> {{ number_format($inventory->price, 2) }}</p> <!-- Format price to 2 decimal places -->
                    <p><strong>Description:</strong></p>
                    <div>{{ $inventory->description }}</div> <!-- Display description -->

                    <div class="mt-3">
                        <!-- Navigation buttons -->
                        <a href="{{ route('inventories.index') }}" class="btn btn-secondary">Back</a> <!-- Go back to inventory list -->
                        <a href="{{ route('inventories.create') }}" class="btn btn-primary">Create another</a> <!-- Redirect to create new inventory -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
