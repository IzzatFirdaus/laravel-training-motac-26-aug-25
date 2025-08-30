@extends('layouts.app')

@section('title', __('ui.users.manage') . ' — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="users-heading">
    <header class="mb-6 d-flex flex-column flex-md-row align-items-md-start justify-content-md-between gap-4">
        <div>
            <h1 id="users-heading" class="myds-heading-md font-heading font-semibold mb-2">{{ __('ui.users.manage') }}</h1>
            <p class="myds-body-md myds-text--muted mb-0">{{ __('ui.users.description') }}</p>
        </div>

        @can('create', App\Models\User::class)
            <div>
                <a href="{{ route('users.create') }}" class="myds-btn myds-btn--primary" aria-label="{{ __('ui.users.create') }}">
                    <i class="bi bi-person-plus me-2" aria-hidden="true"></i>
                    {{ __('ui.users.create') }}
                </a>
            </div>
        @endcan
    </header>

    <section aria-labelledby="users-table-heading">
        <h2 id="users-table-heading" class="sr-only">Jadual Pengguna</h2>

        @php
            $usersCount = method_exists($users, 'total') ? $users->total() : (is_countable($users) ? count($users) : 0);
        @endphp
        <div id="users-count" class="myds-body-sm myds-text--muted mb-4" role="status" aria-live="polite">
            {{ __('ui.users.count', ['count' => $usersCount]) }}
        </div>

        <div class="myds-card bg-surface border rounded overflow-hidden">
            <div class="myds-table-responsive">
                <table class="myds-table" aria-describedby="users-count">
                    <caption class="sr-only">{{ __('ui.users.table_caption') }}</caption>
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
                                <td class="myds-body-sm myds-text--muted">#{{ $user->id }}</td>
                                <td class="myds-body-sm font-medium">{{ e($user->name) }}</td>
                                <td class="myds-body-sm">
                                    <a href="mailto:{{ e($user->email) }}" class="text-primary text-decoration-none" rel="noopener">{{ e($user->email) }}</a>
                                </td>
                                <td class="myds-body-sm myds-text--muted">{{ $user->created_at?->format('d/m/Y') ?? '—' }}</td>
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
                                        <i class="bi bi-people fs-1 mx-auto d-block mb-3 myds-text--muted" aria-hidden="true"></i>
                                        <h3 class="myds-heading-xs font-heading font-medium mb-2">{{ __('ui.users.none_found') }}</h3>
                                        <p class="myds-body-sm myds-text--muted mb-3">{{ __('ui.users.none_found_description') }}</p>
                                        @can('create', App\Models\User::class)
                                            <a href="{{ route('users.create') }}" class="myds-btn myds-btn--primary myds-btn--sm" aria-label="{{ __('ui.users.create_first') }}">
                                                <i class="bi bi-person-plus me-2" aria-hidden="true"></i>
                                                {{ __('ui.users.create_first') }}
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

        @if (method_exists($users, 'links'))
            <nav aria-label="Navigasi halaman pengguna" class="mt-4">
                {{ $users->withQueryString()->links() }}
            </nav>
        @endif
    </section>
</main>
@endsection
