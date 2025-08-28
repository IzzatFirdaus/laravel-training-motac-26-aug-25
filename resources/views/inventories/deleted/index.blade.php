@extends('layouts.app')

@section('title', 'Inventori Dipadam — ' . config('app.name', 'second-crud'))

@section('content')
<main class="container py-4">
    <header class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">Inventori Dipadam</h1>
            <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--secondary myds-btn--sm">Kembali ke Inventori</a>
        </div>
        @if (session('status'))
            <div class="alert alert-success mt-3 myds-alert myds-alert--success" role="status">{{ session('status') }}</div>
        @endif
        @if (session('toast'))
            <div class="alert alert-info mt-3 myds-alert myds-alert--info" role="toast">{{ session('toast') }}</div>
        @endif
    </header>

    @if($deletedInventories->count() > 0)
        <!-- Search form -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('inventories.deleted.index') }}" class="d-flex gap-2 flex-wrap align-items-center">
                    <label for="search" class="form-label mb-0">Cari:</label>
                    <input id="search" type="text" name="search" class="form-control myds-input" placeholder="Cari inventori dipadam..." value="{{ request('search') }}">
                    <label for="owner_id" class="form-label mb-0">Pemilik:</label>
                    <select id="owner_id" name="owner_id" class="form-control myds-select">
                        <option value="">(semua)</option>
                        @isset($users)
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ (string) request('owner_id') === (string) $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        @endisset
                    </select>
                    <button class="myds-btn myds-btn--primary" type="submit">Tapis</button>
                    @if(request('search') || request('owner_id'))
                        <a href="{{ route('inventories.deleted.index') }}" class="myds-btn myds-btn--secondary">Kosongkan</a>
                    @endif
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive myds-table-responsive">
                    <table class="table table-striped myds-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Kuantiti</th>
                                <th>Harga</th>
                                <th>Pemilik</th>
                                <th>Dipadam Pada</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deletedInventories as $inventory)
                                <tr>
                                    <td>{{ $inventory->id }}</td>
                                    <td>{{ $inventory->name }}</td>
                                    <td>{{ $inventory->qty }}</td>
                                    <td>RM {{ number_format($inventory->price, 2) }}</td>
                                    <td>{{ $inventory->user->name ?? 'Tidak diketahui' }}</td>
                                    <td>{{ $inventory->deleted_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @can('restore', $inventory)
                                            <form method="POST" action="{{ route('inventories.restore', $inventory) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="myds-btn myds-btn--primary myds-btn--sm" onclick="return confirm('Adakah anda pasti mahu memulihkan inventori ini?')">Pulihkan</button>
                                            </form>
                                        @endcan
                                        @can('forceDelete', $inventory)
                                            <form method="POST" action="{{ route('inventories.force-delete', $inventory) }}" class="d-inline ms-2">
                                                @csrf
                                                <button type="submit" class="myds-btn myds-btn--danger myds-btn--sm" onclick="return confirm('Adakah anda pasti mahu memadam inventori ini secara kekal? Tindakan ini tidak boleh dibuat asal.')">Padam Kekal</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $deletedInventories->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info myds-alert myds-alert--info">
            Tiada inventori yang dipadam dijumpai.
        </div>
    @endif
</main>
@endsection
