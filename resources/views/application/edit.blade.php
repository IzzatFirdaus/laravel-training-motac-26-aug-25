@extends('layouts.app')

@section('title', 'Edit Permohonan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="edit-application-heading">
    <div class="mx-auto content-maxwidth-lg">
        <header class="mb-3 d-flex justify-content-between align-items-start">
            <div>
                <h1 id="edit-application-heading" class="myds-heading-md font-heading">Edit Permohonan</h1>
                <p class="myds-body-sm myds-text--muted">Kemaskini maklumat permohonan.</p>
            </div>
            <div class="text-end">
                @can('update', $application)
                    <a href="{{ route('applications.show', $application->id) }}" class="myds-btn myds-btn--tertiary">Kembali</a>
                @endcan
            </div>
        </header>

        <div class="myds-card">
            <div class="myds-card__body">
                @if (session('status'))
                    <div class="myds-alert myds-alert--success mb-3" role="status">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('applications.update', $application->id) }}" role="form" aria-label="Borang kemaskini permohonan" data-myds-form novalidate>
                    @csrf
                    @method('PUT')

                    @include('application._form', ['application' => $application])

                    <div class="mt-3 d-flex gap-2 justify-content-end">
                        <a href="{{ route('applications.show', $application->id) }}" class="myds-btn myds-btn--tertiary" aria-label="Batal">Batal</a>
                        <button type="submit" class="myds-btn myds-btn--primary" aria-label="Simpan perubahan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
