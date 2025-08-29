@extends('layouts.app')

@section('title', ($application->name ?? 'Permohonan') . ' — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main">
    <div class="desktop:col-span-8 tablet:col-span-8 mobile:col-span-4 mx-auto" style="max-width:760px;">
        <header class="mb-3 d-flex justify-content-between align-items-start">
            <div>
                <h1 class="myds-heading-md font-heading font-semibold">{{ $application->name ?? 'Permohonan' }}</h1>
                <p class="myds-body-sm text-muted mb-0">Butiran permohonan.</p>
            </div>
            <div class="text-end">
                @can('update', $application)
                    <a href="{{ route('applications.edit', $application->id) }}" class="myds-btn myds-btn--primary">Edit</a>
                @endcan
            </div>
        </header>

        <section>
            <div class="bg-surface border rounded-m p-4 shadow-sm">
                <dl class="row g-3">
                    <dt class="col-4 myds-body-sm text-muted">ID</dt>
                    <dd class="col-8 myds-body-md">{{ $application->id }}</dd>

                    <dt class="col-4 myds-body-sm text-muted">Nama</dt>
                    <dd class="col-8 myds-body-md">{{ $application->name ?? '—' }}</dd>

                    <dt class="col-4 myds-body-sm text-muted">Dicipta</dt>
                    <dd class="col-8 myds-body-md">{{ $application->created_at?->format('Y-m-d') ?? '—' }}</dd>

                    <dt class="col-4 myds-body-sm text-muted">Keterangan</dt>
                    <dd class="col-8 myds-body-md">{{ $application->description ?? '—' }}</dd>
                </dl>

                <div class="mt-3">
                    <a href="{{ route('applications.index') }}" class="myds-btn myds-btn--secondary">Kembali</a>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection
