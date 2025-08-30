@extends('layouts.app')

@section('title', 'Cipta Permohonan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="create-application-heading">
    <div class="mx-auto content-maxwidth-lg">
        <header class="mb-3">
            <h1 id="create-application-heading" class="myds-heading-md font-heading">Cipta Permohonan</h1>
            <p class="myds-body-sm myds-text--muted">Isikan maklumat permohonan baru.</p>
        </header>

        <div class="myds-card">
            <div class="myds-card__body">
                <form method="POST" action="{{ route('applications.store') }}" role="form" aria-label="Borang cipta permohonan" data-myds-form novalidate>
                    @csrf

                    @include('application._form', ['application' => null])

                    <div class="mt-3 d-flex gap-2 justify-content-end">
                        <a href="{{ route('applications.index') }}" class="myds-btn myds-btn--tertiary" role="link" aria-label="Batal">Batal</a>
                        <button type="submit" class="myds-btn myds-btn--primary" aria-label="Simpan permohonan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
