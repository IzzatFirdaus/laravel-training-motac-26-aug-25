@extends('layouts.app')

@section('title', 'Import Inventori — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="page-title">
    <div class="mx-auto content-maxwidth-xl">
        <header class="d-flex flex-column flex-md-row align-items-start justify-content-between mb-3">
            <div>
                <h1 id="page-title" class="myds-heading-md font-heading">Import Inventori</h1>
                <p class="myds-body-sm text-muted mb-0">Muat naik fail Excel (.xlsx atau .csv), pratonton data dan sahkan import mengikut templat.</p>
            </div>

            <div class="mt-3 mt-md-0">
                <a href="{{ route('excel.inventory.export') }}" class="myds-btn myds-btn--tertiary" aria-label="Muat turun templat import">
                    Muat turun templat
                </a>
            </div>
        </header>

        {{-- Upload area: preview step --}}
        <section aria-labelledby="upload-title" class="bg-surface border rounded p-4 shadow-sm mb-4">
            <h2 id="upload-title" class="myds-heading-sm mb-3">Muat Naik Fail untuk Pratonton</h2>

            <form method="POST" action="{{ route('excel.inventory.preview') }}" enctype="multipart/form-data" class="d-flex flex-column flex-md-row gap-3 align-items-end" role="form" aria-label="Borang pratonton import" data-myds-form>
                @csrf

                <div class="flex-grow-1">
                    <label for="file" class="form-label myds-body-sm">Fail Excel</label>

                    {{-- Visible custom file control with progressive enhancement --}}
                    <div class="d-flex gap-2 align-items-center">
                        <label for="file" class="myds-btn myds-btn--outline" id="file-select-label" aria-hidden="false" role="button">
                            Pilih fail
                        </label>

                        {{-- Filename preview for screen readers and visual --}}
                        <div id="file-name" class="myds-body-sm text-muted" aria-live="polite">
                            Tiada fail dipilih
                        </div>
                    </div>

                    {{-- Real file input (visually hidden but keyboard accessible) --}}
                    <input
                        id="file"
                        name="file"
                        type="file"
                        class="sr-only"
                        accept=".xlsx,.csv"
                        aria-describedby="file-help"
                        aria-required="true"
                        required
                    />

                    <div id="file-help" class="myds-body-xs text-muted mt-2">Sokong .xlsx dan .csv sahaja. Saiz maksimum bergantung pada konfigurasi pelayan.</div>

                    @error('file')
                        <div class="myds-alert myds-alert--danger mt-2" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div style="width:180px;">
                    <button type="submit" class="myds-btn myds-btn--primary w-100" aria-label="Pratonton data daripada fail">Pratonton</button>
                </div>
            </form>
        </section>

        {{-- Display import or preview failures --}}
        @if(session('import_failures'))
            <div class="myds-alert myds-alert--danger mb-3" role="alert" aria-live="polite">
                <strong>Terdapat ralat semasa import:</strong>
                <ul class="mb-0 mt-2">
                    @foreach(session('import_failures') as $failure)
                        <li>Baris {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @isset($failures)
            @if($failures->isNotEmpty())
                <div class="myds-alert myds-alert--danger mb-3" role="alert" aria-live="polite">
                    <strong>Terdapat ralat semasa pratonton:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($failures as $failure)
                            <li>Baris {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endisset

        {{-- Preview Section (if controller returned $previewRows) --}}
        @isset($previewRows)
            <section aria-labelledby="preview-title" class="bg-surface border rounded p-4 shadow-sm">
                <h2 id="preview-title" class="myds-heading-sm mb-3">Pratonton Data</h2>

                @php
                    // Defensive: ensure previewRows is an array/collection and has at least one row
                    $previewRowsArray = is_iterable($previewRows) ? collect($previewRows) : collect();
                    $firstRow = $previewRowsArray->first();
                    $columns = $firstRow ? collect($firstRow)->keys()->all() : [];
                @endphp

                @if($previewRowsArray->isEmpty())
                    <p class="myds-body-sm text-muted">Tiada data untuk dipratonton.</p>
                @else
                    <div class="myds-table-responsive mb-3" role="region" aria-labelledby="preview-title" aria-live="polite">
                        <table class="myds-table" role="table" aria-describedby="preview-count">
                            <caption class="sr-only">Pratonton data import</caption>
                            <thead>
                                <tr>
                                    @foreach($columns as $col)
                                        <th scope="col" class="myds-body-sm text-muted">{{ $col }}</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($previewRowsArray as $row)
                                    <tr>
                                        @foreach($columns as $col)
                                            <td class="myds-body-sm">{{ $row[$col] ?? '' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div id="preview-count" class="myds-body-xs text-muted mt-2">
                            Paparan {{ $previewRowsArray->count() }} baris untuk pratonton.
                        </div>
                    </div>

                    {{-- Import confirmation form
                         - Allow import using server-side stored preview (no reupload) OR
                         - Optionally reupload a different file before import.
                         - _reupload will be set to 1 only if user selects a new file.
                    --}}
                    <form method="POST" action="{{ route('excel.inventory.import') }}" enctype="multipart/form-data" class="d-flex flex-column flex-md-row gap-2 align-items-start" role="form" aria-label="Borang import data">
                        @csrf

                        {{-- Hidden flag controlled by JS: default 0 (use server-side preview) --}}
                        <input type="hidden" name="_reupload" id="reupload-flag" value="0">

                        <div class="d-flex flex-column gap-2 flex-grow-1">
                            <div class="myds-body-sm text-muted">Anda boleh terus import data yang dipratonton, atau muat naik fail baru untuk import.</div>

                            <div class="d-flex gap-2 align-items-center">
                                <button type="submit" name="import" value="confirm" class="myds-btn myds-btn--primary">Import</button>

                                {{-- Reupload control: visible label and hidden file input --}}
                                <label for="reupload-file" class="myds-btn myds-btn--outline" id="reupload-label" role="button" aria-controls="reupload-file">
                                    Muat naik fail baru untuk import
                                </label>
                                <div id="reupload-filename" class="myds-body-sm text-muted" aria-live="polite">Tiada fail baru dipilih</div>

                                <input id="reupload-file" name="file" type="file" accept=".xlsx,.csv" class="sr-only" />
                            </div>
                        </div>
                    </form>
                @endif
            </section>
        @endisset
    </div>
</main>

@push('scripts')
<script>
/*
 * Small progressive JS to improve file input UX:
 * - Mirror selected filename into visible label/text.
 * - Toggle hidden _reupload flag when user chooses a new file for import.
 * - Keep accessibility (aria-live) for announcement of file changes.
 *
 * This is enhancement only; forms still work without JS.
 */
document.addEventListener('DOMContentLoaded', function () {
    // Upload preview file input (initial upload)
    var fileInput = document.getElementById('file');
    var fileNameEl = document.getElementById('file-name');
    var fileSelectLabel = document.getElementById('file-select-label');

    if (fileSelectLabel && fileInput && fileNameEl) {
        // Clicking the visible label should focus the hidden file input
        fileSelectLabel.addEventListener('click', function (e) {
            fileInput.click();
        });

        fileInput.addEventListener('change', function () {
            var f = fileInput.files && fileInput.files[0];
            if (f) {
                fileNameEl.textContent = f.name + ' (' + Math.round(f.size / 1024) + ' KB)';
            } else {
                fileNameEl.textContent = 'Tiada fail dipilih';
            }
        });
    }

    // Reupload file (during import)
    var reuploadFile = document.getElementById('reupload-file');
    var reuploadFlag = document.getElementById('reupload-flag');
    var reuploadFilename = document.getElementById('reupload-filename');
    var reuploadLabel = document.getElementById('reupload-label');

    if (reuploadLabel && reuploadFile && reuploadFlag && reuploadFilename) {
        reuploadLabel.addEventListener('click', function (e) {
            reuploadFile.click();
        });

        reuploadFile.addEventListener('change', function () {
            var f = reuploadFile.files && reuploadFile.files[0];
            if (f) {
                reuploadFilename.textContent = f.name + ' (' + Math.round(f.size / 1024) + ' KB)';
                reuploadFlag.value = '1';
            } else {
                reuploadFilename.textContent = 'Tiada fail baru dipilih';
                reuploadFlag.value = '0';
            }
        });
    }
});
</script>
@endpush
@endsection
