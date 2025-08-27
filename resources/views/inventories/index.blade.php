@extends('layouts.app')

@section('title', 'Inventories — second-crud')

@section('content')
<main id="main-content" class="container" tabindex="-1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10">
            <header class="d-flex align-items-start justify-content-between mb-3">
                <div>
                    <h1 class="h3">Inventories</h1>
                    <p class="text-muted mb-0">A paginated list of inventory items in the application.</p>
                </div>
                <div class="text-end">
                    <a href="{{ route('inventories.create') }}" class="btn btn-primary">Create inventory</a>
                </div>
            </header>

            <section aria-labelledby="inventories-heading">
                <h2 id="inventories-heading" class="visually-hidden">Inventory items table</h2>

                @php
                    $inventoryCount = method_exists($inventories, 'total') ? $inventories->total() : $inventories->count();
                @endphp
                <div class="mb-2 text-muted" id="inventory-count">
                    Showing {{ $inventoryCount }} item{{ $inventoryCount > 1 ? 's' : '' }}
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" aria-describedby="inventory-count">
                                <caption class="visually-hidden">List of inventories; use the actions to view or edit an item.</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Owner</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($inventories as $inventory)
                                        <tr>
                                            <td>{{ $inventory->id }}</td>
                                            <td>{{ $inventory->name }}</td>
                                            <td>{{ $inventory->qty }}</td>
                                            <td>{{ $inventory->user?->name ?? '—' }}</td>
                                            <td>{{ $inventory->price !== null ? number_format($inventory->price, 2) : '—' }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($inventory->description, 60) }}</td>
                                            <td class="text-nowrap">
                                                <a href="{{ route('inventories.show', $inventory) }}" class="btn btn-sm btn-outline-secondary" aria-label="View {{ $inventory->name }}">View</a>
                                                <a href="{{ route('inventories.edit', $inventory) }}" class="btn btn-sm btn-outline-primary ms-1" aria-label="Edit {{ $inventory->name }}">Edit</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                <div role="status" class="p-3 text-center">
                                                    <p class="mb-2">No inventory items found.</p>
                                                    <a href="{{ route('inventories.create') }}" class="btn btn-primary">Create your first inventory</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if (method_exists($inventories, 'links'))
                            <nav class="mt-3" aria-label="Inventory pagination">
                                {{ $inventories->links() }}
                            </nav>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection
