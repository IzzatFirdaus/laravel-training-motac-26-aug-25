@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <!-- Page header -->
                <div class="card-header">{{ __('Inventories Index') }}</div>

                {{--
                    Expected variable: $inventories (Collection of App\Models\Inventory)
                    Each $inventory should have: id, name, qty, price, description
                --}}

                <div class="card-body">
                    <!-- Table to display inventory items -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Owner</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventories as $inventory)
                                <tr>
                                    <!-- Display inventory details -->
                                    <td>{{ $inventory->id }}</td>
                                    <td>{{ $inventory->name }}</td>
                                    <td>{{ $inventory->qty }}</td>
                                    <td>{{ $inventory->user?->name ?? '—' }}</td> <!-- Show owner name or a dash if none -->
                                    <td>{{ number_format($inventory->price, 2) }}</td> <!-- Format price to 2 decimal places -->
                                    <td>{{ \Illuminate\Support\Str::limit($inventory->description, 60) }}</td> <!-- Limit description to 60 characters -->
                                    <td>
                                        <!-- Action button to view inventory details -->
                                        <a href="{{ route('inventories.show', $inventory->id) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination links -->
                    <div class="mt-3">
                        {{ $inventories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
