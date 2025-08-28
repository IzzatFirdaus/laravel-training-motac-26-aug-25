@extends('layouts.app')

@section('title', 'Inventori — ' . config('app.name', 'second-crud'))

@section('content')
<main id="main-content" class="container" tabindex="-1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10">
            <header class="d-flex align-items-start justify-content-between mb-3">
                <div>
                    <h1 class="h3">Inventori</h1>
                    <p class="text-muted mb-0">Senarai inventori yang dipaparkan secara berhalaman dalam aplikasi.</p>
                </div>
                <div class="text-end">
                    @can('create', App\Models\Inventory::class)
                        <a href="{{ route('inventories.create') }}" class="myds-btn myds-btn--primary">Cipta inventori</a>
                    @endcan
                </div>
            </header>

            <section aria-labelledby="inventories-heading">
                <h2 id="inventories-heading" class="visually-hidden">Jadual item inventori</h2>

                @php
                    $inventoryCount = method_exists($inventories, 'total') ? $inventories->total() : $inventories->count();
                @endphp
                <div class="mb-2 text-muted" id="inventory-count">
                    Memaparkan {{ $inventoryCount }} item{{ $inventoryCount > 1 ? 's' : '' }}
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" aria-describedby="inventory-count">
                                <caption class="visually-hidden">Senarai inventori; gunakan tindakan untuk lihat atau sunting item.</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Kuantiti</th>
                                        <th scope="col">Pemilik</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col">Tindakan</th>
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
                                                <x-action-buttons
                                                    :showRoute="route('inventories.show', $inventory->id)"
                                                    :editRoute="route('inventories.edit', $inventory->id)"
                                                    :destroyRoute="route('inventories.destroy', $inventory->id)"
                                                    :label="$inventory->name"
                                                    :id="$inventory->id"
                                                    :model="$inventory"
                                                />
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                <div role="status" class="p-3 text-center">
                                                    <p class="mb-2">Tiada item inventori dijumpai.</p>
                                                    @can('create', App\Models\Inventory::class)
                                                        <a href="{{ route('inventories.create') }}" class="myds-btn myds-btn--primary">Cipta inventori pertama anda</a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if (method_exists($inventories, 'links'))
                            <nav class="mt-3" aria-label="Paginasi inventori">
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
