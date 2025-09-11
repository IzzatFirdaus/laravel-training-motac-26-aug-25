@extends('layouts.app')

@section('title', 'Pengguna — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="users-heading">
    {{-- MYDS Breadcrumb Navigation --}}
    <nav aria-label="Breadcrumb" class="mb-4">
        <ol class="d-flex list-unstyled myds-text--muted myds-body-sm m-0 p-0">
            <li><a href="{{ route('home') }}" class="text-primary text-decoration-none hover:text-decoration-underline">Papan Pemuka</a></li>
            <li class="mx-2" aria-hidden="true">/</li>
            <li aria-current="page" class="myds-text--muted">Pengguna</li>
        </ol>
    </nav>

    <header class="mb-6 d-flex flex-column flex-md-row align-items-md-start justify-content-md-between gap-4">
        <div>
            <h1 id="users-heading" class="myds-heading-lg font-heading font-semibold mb-2" data-heading="Urus Pengguna">Urus Pengguna</h1>
            <p class="myds-body-md myds-text--muted mb-0" data-description="Urus akaun pengguna dan akses sistem dengan selamat.">Urus akaun pengguna dan akses sistem dengan selamat.</p>
        </div>

        @if($canCreateUser)
            <div>
                <a href="{{ route('users.create') }}"
                   class="myds-btn myds-btn--primary myds-tap-target"
                   aria-label="Cipta pengguna baharu"
                   data-create-user>
                    <i class="bi bi-person-plus me-2" aria-hidden="true"></i>
                    Cipta Pengguna
                </a>
            </div>
        @endif
    </header>

    <section aria-labelledby="users-stats-heading" class="mb-4">
        <h2 id="users-stats-heading" class="sr-only">Statistik Pengguna</h2>

        @php
            $usersCount = method_exists($users, 'total') ? $users->total() : (is_countable($users) ? count($users) : 0);
        @endphp

        <div id="users-count" class="myds-body-md mb-4" role="status" aria-live="polite" data-count="{{ $usersCount }}">
            <strong>{{ number_format($usersCount) }}</strong> pengguna berdaftar
        </div>
    </section>

    <section aria-labelledby="users-table-heading">
        <h2 id="users-table-heading" class="sr-only">Jadual Pengguna</h2>

        <div class="myds-card bg-surface border rounded overflow-hidden">
            <div class="myds-table-responsive" role="region" aria-live="polite" aria-atomic="true">
                <table class="myds-table" aria-describedby="users-count">
                    <caption class="sr-only">Senarai pengguna berdaftar dalam sistem; gunakan tindakan untuk melihat atau mengurus.</caption>

                    <thead>
                        <tr>
                            <th scope="col" class="myds-body-sm font-medium">ID</th>
                            <th scope="col" class="myds-body-sm font-medium">Nama</th>
                            <th scope="col" class="myds-body-sm font-medium">Alamat E-mel</th>
                            <th scope="col" class="myds-body-sm font-medium">Dicipta</th>
                            <th scope="col" class="myds-body-sm font-medium">Tindakan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $user)
                            <tr data-user-id="{{ $user->id }}">
                                <td class="myds-body-sm myds-text--muted">#{{ $user->id }}</td>
                                <td class="myds-body-sm font-medium">{{ e($user->name) }}</td>
                                <td class="myds-body-sm">
                                    <a href="mailto:{{ e($user->email) }}"
                                       class="text-primary text-decoration-none hover:text-decoration-underline"
                                       rel="noopener"
                                       aria-label="Hantar e-mel kepada {{ e($user->name) }}">
                                        {{ e($user->email) }}
                                    </a>
                                </td>
                                <td class="myds-body-sm myds-text--muted">
                                    <time datetime="{{ $user->created_at?->toIso8601String() }}">
                                        {{ $user->created_at?->format('d/m/Y') ?? '—' }}
                                    </time>
                                </td>
                                <td class="text-nowrap">
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('users.show', $user->id) }}"
                                           class="myds-btn myds-btn--secondary myds-btn--sm myds-tap-target"
                                           aria-label="Lihat profil {{ e($user->name) }}"
                                           data-user-view>
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </a>

                                        @can('update', $user)
                                            <a href="{{ route('users.edit', $user->id) }}"
                                               class="myds-btn myds-btn--secondary myds-btn--sm myds-tap-target"
                                               aria-label="Ubah pengguna {{ e($user->name) }}"
                                               data-user-edit>
                                                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            </a>
                                        @endcan

                                        @can('delete', $user)
                                            <form method="POST"
                                                  action="{{ route('users.destroy', $user->id) }}"
                                                  class="d-inline"
                                                  data-myds-form
                                                  aria-label="Padam pengguna {{ e($user->name) }}"
                                                  data-confirm-message="Anda pasti mahu memadam pengguna '{{ e($user->name) }}'? Tindakan ini tidak boleh dibatalkan."
                                                  data-confirm-title="Padam Pengguna">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="myds-btn myds-btn--danger myds-btn--sm myds-tap-target"
                                                        aria-label="Padam pengguna {{ e($user->name) }}"
                                                        data-user-delete>
                                                    <i class="bi bi-trash3" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6">
                                    <div role="status" class="p-4" data-empty-state>
                                        <i class="bi bi-people fs-1 mb-3 myds-text--muted" aria-hidden="true"></i>
                                        <h3 class="myds-heading-sm font-heading font-medium mb-2"
                                            data-empty-heading="Tiada Pengguna Dijumpai">
                                            Tiada Pengguna Dijumpai
                                        </h3>
                                        <p class="myds-body-sm myds-text--muted mb-3"
                                           data-empty-description="Belum ada pengguna didaftarkan dalam sistem.">
                                            Belum ada pengguna didaftarkan dalam sistem.
                                        </p>
                                        @if($canCreateUser)
                                            <a href="{{ route('users.create') }}"
                                               class="myds-btn myds-btn--primary myds-btn--sm myds-tap-target"
                                               aria-label="Cipta pengguna pertama"
                                               data-create-first>
                                                <i class="bi bi-person-plus me-2" aria-hidden="true"></i>
                                                Cipta Pengguna Pertama
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if (method_exists($users, 'links'))
            <nav aria-label="Navigasi halaman pengguna" class="mt-4 d-flex justify-content-center">
                {{ $users->withQueryString()->links() }}
            </nav>
        @endif
    </section>
</main>

@push('scripts')
    @vite('resources/js/pages/users-index.js')
@endpush
@endsection
