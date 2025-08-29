@extends('layouts.app')

@section('title', 'Cipta Permohonan — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main">
    <div class="desktop:col-span-8 tablet:col-span-8 mobile:col-span-4 mx-auto" style="max-width:760px;">
        <header class="mb-3">
            <h1 class="myds-heading-md font-heading font-semibold">Cipta Permohonan</h1>
            <p class="myds-body-sm text-muted">Isikan maklumat permohonan baru.</p>
        </header>

        <div class="bg-surface border rounded-m p-4 shadow-sm">
            <form method="POST" action="{{ route('applications.store') }}">
                @csrf

                @include('application._form', ['application' => null])

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="myds-btn myds-btn--primary">Simpan</button>
                    <a href="{{ route('applications.index') }}" class="myds-btn myds-btn--secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
