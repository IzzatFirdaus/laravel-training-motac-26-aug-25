@extends('layouts.app')

@section('title', 'Cipta Permohonan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1">
    <div class="mx-auto content-maxwidth-lg">
        <header class="mb-3">
            <h1 class="myds-heading-md font-heading">Cipta Permohonan</h1>
            <p class="myds-body-sm myds-text--muted">Isikan maklumat permohonan baru.</p>
        </header>

        <div class="bg-surface border rounded p-4 shadow-sm">
            <form method="POST" action="{{ route('applications.store') }}" role="form" aria-label="Borang cipta permohonan" data-myds-form>
                @csrf

                @include('application._form', ['application' => null])

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="myds-btn myds-btn--primary">Simpan</button>
                    <a href="{{ route('applications.index') }}" class="myds-btn myds-btn--tertiary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
