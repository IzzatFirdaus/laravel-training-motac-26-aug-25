@extends('layouts.app')

@section('title', 'Pengguna — ' . config('app.name', 'second-crud'))

@section('content')
<main id="main-content" class="container" tabindex="-1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10">
            <header class="d-flex align-items-start justify-content-between mb-3">
                <div>
                    <h1 class="h3">Pengguna</h1>
                    <p class="text-muted mb-0">Senarai pengguna dalam sistem.</p>
                </div>
                <div class="text-end">
                    @can('create', App\Models\User::class)
                        <a href="{{ route('users.create') }}" class="myds-btn myds-btn--primary">Cipta pengguna</a>
                    @endcan
                </div>
            </header>

            <section aria-labelledby="users-heading">
                <h2 id="users-heading" class="visually-hidden">Jadual pengguna</h2>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <caption class="visually-hidden">Senarai pengguna; gunakan tindakan untuk lihat atau edit pengguna.</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Emel</th>
                                        <th scope="col">Dibuat</th>
                                        <th scope="col">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at?->format('Y-m-d') ?? '—' }}</td>
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
                                            <td colspan="5">
                                                <div role="status" class="p-3 text-center">
                                                    <p class="mb-2">Tiada pengguna dijumpai.</p>
                                                    @can('create', App\Models\User::class)
                                                        <a href="{{ route('users.create') }}" class="myds-btn myds-btn--primary">Cipta pengguna pertama</a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if (method_exists($users, 'links'))
                            <nav class="mt-3" aria-label="Paginasi pengguna">
                                {{ $users->links() }}
                            </nav>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection
