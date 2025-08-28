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
                    <a href="{{ route('vehicles.create') }}" class="myds-btn myds-btn--primary">Cipta kenderaan</a>
                </div>
            </header>

            <section aria-labelledby="vehicles-heading">
                <h2 id="vehicles-heading" class="visually-hidden">Jadual kenderaan</h2>

                @php
                    $vehiclesCount = method_exists($vehicles, 'total') ? $vehicles->total() : $vehicles->count();
                @endphp
                <div class="mb-2 text-muted" id="vehicles-count">Memaparkan {{ $vehiclesCount }} kenderaan{{ $vehiclesCount > 1 ? 's' : '' }}</div>

                <form method="GET" class="mb-3 d-flex align-items-center gap-2 flex-wrap" aria-label="Tetapan paparan dan carian">
                    <label for="search" class="form-label mb-0">Cari:</label>
                    <input id="search" name="search" class="form-control myds-input" value="{{ request('search','') }}" placeholder="Nama atau keterangan" />
                    <label for="owner_id" class="form-label mb-0">Pemilik:</label>
                    <select id="owner_id" name="owner_id" class="form-control myds-select">
                        <option value="">(semua)</option>
                        @isset($users)
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ (string) request('owner_id') === (string) $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        @endisset
                    </select>
                    <label for="per_page" class="form-label mb-0">Item/halaman:</label>
                    <select id="per_page" name="per_page" class="form-control myds-select" onchange="this.form.submit()">
                        @foreach([5,10,15,25,50,100] as $n)
                            <option value="{{ $n }}" {{ (int) request('per_page', 10) === $n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="myds-btn myds-btn--primary">Tapis</button>
                </form>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive myds-table-responsive">
                            <table class="table table-hover myds-table" aria-describedby="vehicles-count">
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
                                                    :model="$vehicle"
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
                                                    <a href="{{ route('vehicles.create') }}" class="myds-btn myds-btn--primary">Tambah kenderaan</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if (method_exists($vehicles, 'links'))
                            <nav class="mt-3" aria-label="Paginasi kenderaan">
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
