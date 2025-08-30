@extends('layouts.app')

@section('title', 'Ubah Pengguna — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-6" role="main" tabindex="-1" aria-labelledby="edit-user-heading">
    <header class="mb-4">
        <h1 id="edit-user-heading" class="myds-heading-md font-heading font-semibold">Ubah Pengguna</h1>
                <p class="myds-body-sm myds-text--muted mb-0">Kemaskini maklumat pengguna</p>
    </header>

    <section aria-labelledby="edit-user-form">
        <div class="myds-card">
            <div class="myds-card__body">
                @if (session('status'))
                    <div class="myds-alert myds-alert--success mb-3" role="status">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('users.update', $user->id) }}" novalidate>
                    @csrf
                    @method('PUT')

                    @include('user._form', ['user' => $user])

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('users.index') }}" class="myds-btn myds-btn--secondary" aria-label="Batal dan kembali ke senarai">Batal</a>
                        <button type="submit" class="myds-btn myds-btn--primary" aria-label="Kemaskini pengguna">Kemaskini</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection