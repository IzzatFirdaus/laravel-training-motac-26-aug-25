@extends('layouts.app')

@section('title', 'Vehicles — second-crud')

@section('content')
<main id="main-content" class="container" tabindex="-1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10">
            <header class="d-flex align-items-start justify-content-between mb-3">
                <div>
                    <h1 class="h3">Vehicles</h1>
                    <p class="text-muted mb-0">Inventory-style vehicle records maintained by the application.</p>
                </div>
                <div>
                    <a href="{{ route('vehicles.create') }}" class="btn btn-primary">Create vehicle</a>
                </div>
            </header>

            <section aria-labelledby="vehicles-heading">
                <h2 id="vehicles-heading" class="visually-hidden">Vehicles table</h2>

                @php
                    $vehiclesCount = method_exists($vehicles, 'total') ? $vehicles->total() : $vehicles->count();
                @endphp
                <div class="mb-2 text-muted" id="vehicles-count">Showing {{ $vehiclesCount }} vehicle{{ $vehiclesCount > 1 ? 's' : '' }}</div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" aria-describedby="vehicles-count">
                                <caption class="visually-hidden">A list of vehicles with quick actions to view details.</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Qty</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($vehicles as $vehicle)
                                        <tr>
                                            <td>{{ $vehicle->id }}</td>
                                            <td>{{ $vehicle->name }}</td>
                                            <td>{{ $vehicle->qty }}</td>
                                            <td>{{ $vehicle->price !== null ? number_format($vehicle->price, 2) : '—' }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($vehicle->description, 60) }}</td>
                                            <td>{{ optional($vehicle->created_at)->toDateString() }}</td>
                                            <td class="text-nowrap">
                                                <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-sm btn-outline-secondary" aria-label="View {{ $vehicle->name }}">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                <div role="status" class="p-3 text-center">
                                                    <p class="mb-2">No vehicles found.</p>
                                                    <a href="{{ route('vehicles.create') }}" class="btn btn-primary">Add a vehicle</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if (method_exists($vehicles, 'links'))
                            <nav class="mt-3" aria-label="Vehicles pagination">
                                {{ $vehicles->links() }}
                            </nav>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection
