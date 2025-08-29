@extends('layouts.app')

@section('title', 'Import Inventori — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main">
    <div class="desktop:col-span-8 tablet:col-span-8 mobile:col-span-4 mx-auto" style="max-width:920px;">
        <header class="d-flex align-items-start justify-content-between mb-3">
            <div>
                <h1 class="myds-heading-md font-heading font-semibold">Import Inventori</h1>
                <p class="myds-body-sm text-muted mb-0">Muat naik fail Excel (.xlsx atau .csv), pratonton data, dan sahkan import.</p>
            </div>
            <div class="text-end">
                <a href="{{ route('excel.inventory.export') }}" class="myds-btn myds-btn--secondary">Muat turun templat</a>
            </div>
        </header>

        <div class="bg-surface border rounded-m p-4 shadow-sm mb-3">
            <form method="POST" action="{{ route('excel.inventory.preview') }}" enctype="multipart/form-data" class="d-flex gap-3 align-items-end">
                @csrf
                <div style="flex:1">
                    <label for="file" class="form-label myds-body-sm">Fail Excel</label>
                    <input id="file" name="file" type="file" class="myds-input" accept=".xlsx,.csv" required aria-describedby="file-help" />
                    <div id="file-help" class="myds-body-xs text-muted">Sokong .xlsx dan .csv sahaja</div>
                    @error('file')
                        <div class="text-danger mt-1" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div style="width:180px">
                    <button type="submit" class="myds-btn myds-btn--primary w-100">Pratonton</button>
                </div>
            </form>
        </div>

        @if(session('import_failures'))
            <div class="myds-alert myds-alert--danger mb-3" role="alert">
                <strong>Terdapat ralat semasa import:</strong>
                <ul class="mb-0">
                    @foreach(session('import_failures') as $failure)
                        <li>Baris {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @isset($failures)
            @if($failures->isNotEmpty())
                <div class="myds-alert myds-alert--danger mb-3" role="alert">
                    <strong>Terdapat ralat semasa pratonton:</strong>
                    <ul class="mb-0">
                        @foreach($failures as $failure)
                            <li>Baris {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endisset

        @isset($previewRows)
            <div class="bg-surface border rounded-m p-4 shadow-sm">
                <h2 class="myds-heading-sm mb-3">Pratonton</h2>
                @php
                    $columns = collect($previewRows[0] ?? [])->keys();
                @endphp
                @if(!empty($previewRows))
                    <div class="myds-table-responsive mb-3">
                        <table class="myds-table" role="table" aria-label="Pratonton data import">
                            <thead>
                                <tr>
                                    @foreach($columns as $col)
                                        <th scope="col" class="myds-body-sm text-muted">{{ $col }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($previewRows as $row)
                                    <tr>
                                        @foreach($columns as $col)
                                            <td class="myds-body-sm">{{ $row[$col] ?? '' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <form method="POST" action="{{ route('excel.inventory.import') }}" enctype="multipart/form-data" class="d-flex gap-2">
                        @csrf
                        <input type="hidden" name="_reupload" value="1">
                        <label class="myds-btn myds-btn--outline">
                            Muat naik semula fail untuk import
                            <input type="file" name="file" accept=".xlsx,.csv" class="d-none" required />
                        </label>
                        <button type="submit" class="myds-btn myds-btn--primary">Import</button>
                    </form>
                @else
                    <p class="myds-body-sm text-muted">Tiada data untuk dipratonton.</p>
                @endif
            </div>
        @endisset
    </div>
</main>
@endsection
