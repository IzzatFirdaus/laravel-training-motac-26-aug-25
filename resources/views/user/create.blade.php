@extends('layouts.app')

@section('title', 'Cipta Pengguna — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-6" role="main" tabindex="-1" aria-labelledby="page-heading">
    <header class="mb-4">
        <h1 id="page-heading" class="myds-heading-md font-heading font-semibold">Cipta Pengguna</h1>
                <p class="myds-body-sm myds-text--muted mb-0">Tambah pengguna baru ke dalam sistem</p>
    </header>

    <section aria-labelledby="create-user-form">
    <h2 id="create-user-form" class="sr-only">Borang cipta pengguna</h2>

        <div class="myds-card">
            <div class="myds-card__body">
                <form method="POST" action="{{ route('users.store') }}" novalidate>
                    @csrf

                    @include('user._form', ['user' => null])

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="myds-btn myds-btn--primary" aria-label="Simpan pengguna">Simpan</button>
                        <a href="{{ route('users.index') }}" class="myds-btn myds-btn--secondary" role="link" aria-label="Batal dan kembali ke senarai">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection