@extends('layouts.app')

@section('title', ($application->name ?? 'Permohonan') . ' — ' . config('app.name', 'second-crud'))

@section('content')
<main class="container" id="main-content" tabindex="-1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <header class="mb-3 d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="h3">{{ $application->name ?? 'Permohonan' }}</h1>
                    <p class="text-muted mb-0">Butiran permohonan.</p>
                </div>
                <div class="text-end">
                    @can('update', $application)
                        <a href="{{ route('applications.edit', $application->id) }}" class="myds-btn myds-btn--primary">Edit</a>
                    @endcan
                </div>
            </header>

            <section>
                <div class="card">
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">ID</dt>
                            <dd class="col-sm-9">{{ $application->id }}</dd>

                            <dt class="col-sm-3">Nama</dt>
                            <dd class="col-sm-9">{{ $application->name ?? '—' }}</dd>

                            <dt class="col-sm-3">Dicipta</dt>
                            <dd class="col-sm-9">{{ $application->created_at?->format('Y-m-d') ?? '—' }}</dd>

                            <dt class="col-sm-3">Keterangan</dt>
                            <dd class="col-sm-9">{{ $application->description ?? '—' }}</dd>
                        </dl>

                        <div class="mt-3">
                            <a href="{{ route('applications.index') }}" class="myds-btn myds-btn--secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection
