@extends('layouts.app')

@section('title', 'Inventori Dipadam — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="page-heading">
    <div class="mx-auto content-maxwidth-xl">
        <header class="mb-4 d-flex flex-column flex-md-row align-items-start justify-content-between" role="banner">
            <div>
                <h1 id="page-heading" class="myds-heading-md font-heading mb-1">Inventori Dipadam</h1>
                <p class="myds-body-sm myds-text--muted mb-0">Senarai item inventori yang telah dipadamkan. Anda boleh memulihkan atau memadam secara kekal berdasarkan kebenaran anda.</p>
            </div>

            <div class="mt-3 mt-md-0">
                <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--secondary" aria-label="Kembali ke Inventori">Kembali ke Inventori</a>
            </div>
        </header>

        {{-- Flash messages (MYDS alerts) --}}
        @if (session('status'))
            <div class="myds-alert myds-alert--success mb-3" role="status" aria-live="polite">
                {{ session('status') }}
            </div>
        @endif

        @if (session('toast'))
            <div class="myds-alert myds-alert--info mb-3" role="status" aria-live="polite">
                {{ session('toast') }}
            </div>
        @endif

        @if($deletedInventories->count() > 0)
            {{-- Filter / Search --}}
            <section aria-labelledby="search-heading" class="bg-surface border rounded p-3 mb-3">
                <h2 id="search-heading" class="sr-only">Carian dan Penapis</h2>

                <form method="GET" action="{{ route('inventories.deleted.index') }}" class="d-flex flex-column flex-md-row gap-3 align-items-end" role="search" aria-label="Carian inventori dipadam">
                    <div class="flex-grow-1">
                        <label for="search" class="myds-label myds-body-sm">Cari</label>
                        <input id="search" name="search" type="search" value="{{ request('search') }}" class="myds-input" placeholder="{{ __('Cari mengikut nama atau kod') }}" aria-label="Carian inventori">
                    </div>

                    <div style="min-width:220px;">
                        <label for="owner_id" class="myds-label myds-body-sm">Pemilik</label>
                        <select id="owner_id" name="owner_id" class="myds-input" aria-label="Penapis pemilik">
                            <option value="">{{ __('(semua)') }}</option>
                            @isset($users)
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ (string) request('owner_id') === (string) $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="myds-btn myds-btn--primary">Tapis</button>
                        @if(request('search') || request('owner_id'))
                            <a href="{{ route('inventories.deleted.index') }}" class="myds-btn myds-btn--tertiary">Kosongkan</a>
                        @endif
                    </div>
                </form>
            </section>

            {{-- Table --}}
            <section aria-labelledby="table-heading" class="bg-surface border rounded p-3">
                <h2 id="table-heading" class="sr-only">Jadual inventori dipadam</h2>

                <div class="myds-table-responsive" role="region" aria-live="polite" aria-atomic="true">
                    <table class="myds-table" role="table" aria-describedby="inventories-count">
                        <caption class="sr-only">Senarai inventori yang dipadam; tindakan tersedia: pulihkan, padam kekal.</caption>
                        <thead>
                            <tr>
                                <th scope="col" class="myds-body-sm myds-text--muted">ID</th>
                                <th scope="col" class="myds-body-sm myds-text--muted">Nama</th>
                                <th scope="col" class="myds-body-sm myds-text--muted">Kuantiti</th>
                                <th scope="col" class="myds-body-sm myds-text--muted">Harga</th>
                                <th scope="col" class="myds-body-sm myds-text--muted">Pemilik</th>
                                <th scope="col" class="myds-body-sm myds-text--muted">Dipadam Pada</th>
                                <th scope="col" class="myds-body-sm myds-text--muted">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deletedInventories as $inventory)
                                <tr>
                                    <td class="myds-body-sm myds-text--muted">{{ $inventory->id }}</td>
                                    <td class="myds-body-md">{{ $inventory->name }}</td>
                                    <td class="myds-body-sm">{{ $inventory->qty }}</td>
                                    <td class="myds-body-sm">RM {{ number_format($inventory->price ?? 0, 2) }}</td>
                                    <td class="myds-body-sm myds-text--muted">{{ optional($inventory->user)->name ?? 'Tidak diketahui' }}</td>
                                    <td class="myds-body-sm myds-text--muted">{{ optional($inventory->deleted_at)->format('d/m/Y H:i') ?? '—' }}</td>
                                    <td class="text-nowrap">
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            @can('restore', $inventory)
                                                <form method="POST" action="{{ route('inventories.restore', $inventory) }}" class="d-inline" data-myds-form data-model="Inventori {{ $inventory->id }}">
                                                    @csrf
                                                    <button type="submit" class="myds-btn myds-btn--primary myds-btn--sm" aria-label="Pulihkan inventori {{ $inventory->id }}">
                                                        Pulihkan
                                                    </button>
                                                </form>
                                            @endcan

                                            @can('forceDelete', $inventory)
                                                <form method="POST" action="{{ route('inventories.force-delete', $inventory) }}" class="d-inline" data-myds-form data-model="Inventori {{ $inventory->id }}">
                                                    @csrf
                                                    <button type="submit" class="myds-btn myds-btn--danger myds-btn--sm" aria-label="Padam kekal inventori {{ $inventory->id }}">
                                                        Padam Kekal
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div id="inventories-count" class="myds-body-xs myds-text--muted mt-2">
                        Memaparkan {{ $deletedInventories->count() }} daripada {{ $deletedInventories->total() ?? $deletedInventories->count() }} inventori dipadam.
                    </div>
                </div>

                {{-- Pagination --}}
                @if(method_exists($deletedInventories, 'links'))
                    <nav class="mt-4 d-flex justify-content-center" role="navigation" aria-label="Paginasi inventori dipadam">
                        {{ $deletedInventories->links() }}
                    </nav>
                @endif
            </section>
        @else
            <div class="myds-alert myds-alert--info" role="status" aria-live="polite">
                Tiada inventori yang dipadam dijumpai.
            </div>
        @endif
    </div>
</main>
@endsection
