@extends('layouts.app')

@section('title', 'Pengguna — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main">
    {{-- MYDS Page Header with MyGOVEA principles --}}
    <header class="mb-6">
        <div class="d-flex flex-column flex-md-row align-items-md-start justify-content-md-between gap-4">
            <div>
                <h1 class="myds-heading-md font-heading font-semibold mb-2">Pengurusan Pengguna</h1>
                <p class="myds-body-md text-muted mb-0">
                    Senarai dan pengurusan pengguna yang berdaftar dalam sistem inventori kerajaan.
                </p>
                <p class="myds-body-sm text-muted mt-1" lang="en">
                    <em>User management for the government inventory system.</em>
                </p>
            </div>
            @can('create', App\Models\User::class)
                <div>
                    <a href="{{ route('users.create') }}" class="myds-btn myds-btn--primary">
                        <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"/>
                            <circle cx="8.5" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                            <line x1="20" y1="8" x2="20" y2="14" stroke="currentColor" stroke-width="2"/>
                            <line x1="23" y1="11" x2="17" y2="11" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        Cipta Pengguna
                    </a>
                </div>
            @endcan
        </div>
    </header>

    {{-- MYDS Data Table Section --}}
    <section aria-labelledby="users-table-heading">
        <h2 id="users-table-heading" class="sr-only">Jadual Pengguna</h2>

        {{-- Results Count (MyGOVEA data-driven principle) --}}
        @php
            $usersCount = method_exists($users, 'total') ? $users->total() : $users->count();
        @endphp
        <div class="myds-body-sm text-muted mb-4" id="users-count" role="status">
            Memaparkan {{ $usersCount }} pengguna{{ $usersCount !== 1 ? '' : '' }} yang berdaftar
        </div>

        {{-- MYDS Data Table --}}
        <div class="bg-surface border rounded-m overflow-hidden">
            <div class="table-responsive">
                <table class="myds-table" aria-describedby="users-count">
                    <caption class="sr-only">Senarai pengguna; gunakan tindakan untuk lihat atau edit pengguna.</caption>
                    <thead>
                        <tr>
                            <th scope="col" class="myds-body-sm font-medium">ID</th>
                            <th scope="col" class="myds-body-sm font-medium">Nama</th>
                            <th scope="col" class="myds-body-sm font-medium">Emel</th>
                            <th scope="col" class="myds-body-sm font-medium">Dicipta</th>
                            <th scope="col" class="myds-body-sm font-medium">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="myds-body-sm text-muted">{{ $user->id }}</td>
                                <td class="myds-body-sm font-medium">{{ $user->name }}</td>
                                <td class="myds-body-sm">
                                    <a href="mailto:{{ $user->email }}" class="text-primary text-decoration-none hover:text-decoration-underline">
                                        {{ $user->email }}
                                    </a>
                                </td>
                                <td class="myds-body-sm text-muted">{{ $user->created_at?->format('d/m/Y') ?? '—' }}</td>
                                <td class="text-nowrap">
                                    <x-action-buttons
                                        :model="$user"
                                        :showRoute="route('users.show', $user->id)"
                                        :editRoute="route('users.edit', $user->id)"
                                        :destroyRoute="route('users.destroy', $user->id)"
                                        :label="$user->name"
                                        :id="$user->id"
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6">
                                    <div role="status" class="p-4">
                                        <svg width="48" height="48" class="mx-auto mb-3 text-muted" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"/>
                                            <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                        <h3 class="myds-heading-xs font-heading font-medium mb-2">Tiada Pengguna Dijumpai</h3>
                                        <p class="myds-body-sm text-muted mb-3">
                                            Belum ada pengguna yang didaftarkan dalam sistem.
                                        </p>
                                        @can('create', App\Models\User::class)
                                            <a href="{{ route('users.create') }}" class="myds-btn myds-btn--primary">
                                                <svg width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"/>
                                                    <circle cx="8.5" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                                                    <line x1="20" y1="8" x2="20" y2="14" stroke="currentColor" stroke-width="2"/>
                                                    <line x1="23" y1="11" x2="17" y2="11" stroke="currentColor" stroke-width="2"/>
                                                </svg>
                                                Cipta Pengguna Pertama
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MYDS Pagination --}}
        @if (method_exists($users, 'links'))
            <nav aria-label="Navigasi halaman pengguna" class="mt-4">
                {{ $users->withQueryString()->links() }}
            </nav>
        @endif
    </section>
</main>
@endsection
