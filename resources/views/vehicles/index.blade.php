@extends('layouts.app')

@section('title', 'Kenderaan — ' . config('app.name', 'second-crud'))

@section('content')
<main id="main-content" class="container" tabindex="-1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10">
            <header class="d-flex align-items-start justify-content-between mb-3">
                <div>
                    <h1 class="h3">Kenderaan</h1>
                    <p class="text-muted mb-0">Rekod kenderaan gaya inventori yang diselenggara oleh aplikasi.</p>
                </div>
                <div>
                    <a href="{{ route('vehicles.create') }}" class="btn btn-primary">Cipta kenderaan</a>
                </div>
            </header>

            <section aria-labelledby="vehicles-heading">
                <h2 id="vehicles-heading" class="visually-hidden">Jadual kenderaan</h2>

                @php
                    $vehiclesCount = method_exists($vehicles, 'total') ? $vehicles->total() : $vehicles->count();
                @endphp
                <div class="mb-2 text-muted" id="vehicles-count">Memaparkan {{ $vehiclesCount }} kenderaan{{ $vehiclesCount > 1 ? 's' : '' }}</div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" aria-describedby="vehicles-count">
                                <caption class="visually-hidden">Senarai kenderaan dengan tindakan pantas untuk lihat butiran.</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Kuantiti</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col">Pemilik</th>
                                        <th scope="col">Dicipta</th>
                                        <th scope="col">Tindakan</th>
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
                                            <td>{{ $vehicle->owner?->name ?? '—' }}</td>
                                            <td>{{ isset($vehicle->created_at) ? \Illuminate\Support\Carbon::parse($vehicle->created_at)->toDateString() : '—' }}</td>
                                            <td class="text-nowrap">
                                                <x-action-buttons
                                                    :showRoute="route('vehicles.show', $vehicle->id)"
                                                    :editRoute="route('vehicles.edit', $vehicle->id)"
                                                    :destroyRoute="route('vehicles.destroy', $vehicle->id)"
                                                    :label="$vehicle->name"
                                                    :id="$vehicle->id"
                                                />
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">
                                                <div role="status" class="p-3 text-center">
                                                    <p class="mb-2">Tiada kenderaan dijumpai.</p>
                                                    <a href="{{ route('vehicles.create') }}" class="btn btn-primary">Tambah kenderaan</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if (method_exists($vehicles, 'links'))
                            <nav class="mt-3" aria-label="Paginasi kenderaan">
                                {{ $vehicles->links('vendor.pagination.myds') }}
                            </nav>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection
