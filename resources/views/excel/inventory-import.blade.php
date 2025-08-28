@extends('layouts.app')

@section('title', 'Import Inventori — ' . config('app.name', 'second-crud'))

@section('content')
<main id="main-content" class="container" tabindex="-1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10">
            <header class="d-flex align-items-start justify-content-between mb-3">
                <div>
                    <h1 class="h3">Import Inventori</h1>
                    <p class="text-muted mb-0">Muat naik fail Excel (.xlsx atau .csv), pratonton data, dan sahkan import.</p>
                </div>
                <div class="text-end">
                    <a href="{{ route('excel.inventory.export') }}" class="myds-btn myds-btn--secondary">Muat turun templat</a>
                </div>
            </header>

            <div class="card mb-3">
                <div class="card-body">
                    <form method="POST" action="{{ route('excel.inventory.preview') }}" enctype="multipart/form-data" class="row g-2">
                        @csrf
                        <div class="col-12 col-md-8">
                            <label for="file" class="form-label">Fail Excel</label>
                            <input id="file" name="file" type="file" class="form-control" accept=".xlsx,.csv" required />
                            @error('file')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-4 d-flex align-items-end">
                            <button type="submit" class="myds-btn myds-btn--primary w-100">Pratonton</button>
                        </div>
                    </form>
                </div>
            </div>

            @if(session('import_failures'))
                <div class="alert alert-danger myds-alert myds-alert--danger">
                    Terdapat ralat semasa import:
                    <ul class="mb-0">
                        @foreach(session('import_failures') as $failure)
                            <li>Baris {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @isset($failures)
                @if($failures->isNotEmpty())
                    <div class="alert alert-danger myds-alert myds-alert--danger">
                        Terdapat ralat semasa pratonton:
                        <ul class="mb-0">
                            @foreach($failures as $failure)
                                <li>Baris {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endisset

            @isset($previewRows)
                <div class="card">
                    <div class="card-body">
                        <h2 class="h5">Pratonton</h2>
                        @php
                            $columns = collect($previewRows[0] ?? [])->keys();
                        @endphp
                        @if(!empty($previewRows))
                            <div class="table-responsive myds-table-responsive">
                                <table class="table table-striped table-hover myds-table">
                                    <thead>
                                        <tr>
                                            @foreach($columns as $col)
                                                <th scope="col">{{ $col }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($previewRows as $row)
                                            <tr>
                                                @foreach($columns as $col)
                                                    <td>{{ $row[$col] ?? '' }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <form method="POST" action="{{ route('excel.inventory.import') }}" enctype="multipart/form-data" class="mt-3">
                                @csrf
                                <input type="hidden" name="_reupload" value="1">
                                <div class="d-flex gap-2">
                                    <label class="myds-btn myds-btn--outline">
                                        Muat naik semula fail untuk import
                                        <input type="file" name="file" accept=".xlsx,.csv" class="d-none" required />
                                    </label>
                                    <button type="submit" class="myds-btn myds-btn--primary">Import</button>
                                </div>
                            </form>
                        @else
                            <p class="text-muted">Tiada data untuk dipratonton.</p>
                        @endif
                    </div>
                </div>
            @endisset
        </div>
    </div>
</main>
@endsection
