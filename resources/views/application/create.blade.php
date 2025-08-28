@extends('layouts.app')

@section('title', 'Cipta Permohonan — ' . config('app.name', 'second-crud'))

@section('content')
<main class="container" id="main-content" tabindex="-1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <header class="mb-3">
                <h1 class="h3">Cipta Permohonan</h1>
                <p class="text-muted">Isikan maklumat permohonan baru.</p>
            </header>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('applications.store') }}">
                        @csrf

                        @include('application._form', ['application' => null])

                        <div class="mt-3">
                            <button type="submit" class="myds-btn myds-btn--primary">Simpan</button>
                            <a href="{{ route('applications.index') }}" class="myds-btn myds-btn--secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
