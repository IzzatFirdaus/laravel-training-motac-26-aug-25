@extends('layouts.app')

@section('title', 'Edit Permohonan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1">
    <div class="mx-auto content-maxwidth-lg">
        <header class="mb-3">
            <h1 class="myds-heading-md font-heading">Edit Permohonan</h1>
            <p class="myds-body-sm myds-text--muted">Kemaskini maklumat permohonan.</p>
        </header>

        <div class="bg-surface border rounded p-4 shadow-sm">
            <form method="POST" action="{{ route('applications.update', $application->id) }}" role="form" aria-label="Borang kemaskini permohonan" data-myds-form>
                @csrf
                {{-- If using PUT/PATCH in route, include method field --}}
                @method('PUT')

                @include('application._form', ['application' => $application])

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="myds-btn myds-btn--primary">Simpan</button>
                    <a href="{{ route('applications.show', $application->id) }}" class="myds-btn myds-btn--tertiary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
