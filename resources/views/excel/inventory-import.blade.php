@extends('layouts.app')

@section('title', 'Import Inventori — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="page-title">
    <div class="mx-auto content-maxwidth-xl">

        {{-- Page header --}}
        <header class="d-flex flex-column flex-md-row align-items-start justify-content-between mb-3">
            <div>
                <h1 id="page-title" class="myds-heading-md font-heading">Import Inventori</h1>
                <p class="myds-body-sm myds-text--muted mb-0">
                    Muat naik fail Excel (.xlsx atau .csv), pratonton data kemudian sahkan import mengikut templat rasmi.
                </p>
            </div>

            <div class="mt-3 mt-md-0">
                <a href="{{ route('excel.inventory.export') }}" class="myds-btn myds-btn--tertiary" aria-label="Muat turun templat import">
                    Muat turun templat
                </a>
            </div>
        </header>

        {{-- Upload area: preview step --}}
        <section aria-labelledby="upload-title" class="myds-card mb-4" role="region">
            <div class="myds-card__body">
                <h2 id="upload-title" class="myds-heading-sm mb-3">Muat Naik Fail untuk Pratonton</h2>

                <form
                    method="POST"
                    action="{{ route('excel.inventory.preview') }}"
                    enctype="multipart/form-data"
                    class="d-flex flex-column flex-md-row gap-3 align-items-end"
                    role="form"
                    aria-label="Borang pratonton import"
                    data-myds-form
                    data-myds-file-input="#file"
                    >
                    @csrf

                    <div class="flex-grow-1">
                        <label for="file" class="myds-label myds-body-sm">Fail Excel <span class="myds-text--danger" aria-hidden="true">*</span></label>

                        {{-- Custom file control for enhanced UX; progressive enhancement script will wire this. --}}
                        <div class="d-flex gap-2 align-items-center">
                            <label for="file" id="file-select-label" class="myds-btn myds-btn--outline" role="button" tabindex="0" aria-controls="file" aria-label="Pilih fail untuk muat naik">
                                Pilih fail
                            </label>

                            {{-- Live region shows filename for assistive tech and visual users --}}
                            <div id="file-name" class="myds-body-sm myds-text--muted" aria-live="polite" aria-atomic="true">
                                Tiada fail dipilih
                            </div>
                        </div>

                        {{-- Real file input - visually hidden but keyboard accessible.
                             JS will remove 'visually-hidden' if a full native control is preferred. --}}
                        <input
                            id="file"
                            name="file"
                            type="file"
                            accept=".xlsx,.csv"
                            class="visually-hidden"
                            aria-describedby="file-help"
                            required
                            aria-required="true"
                        />

                        <noscript>
                            {{-- Fallback for users without JS: show native file input --}}
                            <div class="mt-2">
                                <input id="file-noscript" name="file" type="file" accept=".xlsx,.csv" required aria-describedby="file-help" />
                            </div>
                        </noscript>

                        <div id="file-help" class="myds-body-xs myds-text--muted mt-2">
                            Sokong .xlsx dan .csv sahaja. Saiz maksimum bergantung pada konfigurasi pelayan.
                        </div>

                        @error('file')
                            <div class="myds-alert myds-alert--danger mt-2" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="import-action-column">
                        <button type="submit" class="myds-btn myds-btn--primary w-100" aria-label="Pratonton data daripada fail">Pratonton</button>
                    </div>
                </form>
            </div>
        </section>

        {{-- Import/preview failures (session & local variable checks) --}}
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
            <section aria-labelledby="preview-title" class="myds-card" role="region">
                <div class="myds-card__body">
                    <h2 id="preview-title" class="myds-heading-sm mb-3">Pratonton Data</h2>

                    @php
                        // Defensive: ensure previewRows is iterable and non-empty
                        $previewRowsArray = is_iterable($previewRows) ? collect($previewRows) : collect();
                        $firstRow = $previewRowsArray->first();
                        $columns = $firstRow ? collect($firstRow)->keys()->all() : [];
                    @endphp

                    @if($previewRowsArray->isEmpty())
                        <p class="myds-body-sm myds-text--muted">Tiada data untuk dipratonton.</p>
                    @else
                        <div class="myds-table-responsive mb-3" role="region" aria-labelledby="preview-title" aria-live="polite">
                            <table class="myds-table" role="table" aria-describedby="preview-count">
                                <caption class="sr-only">Pratonton data import</caption>
                                <thead>
                                    <tr>
                                        @foreach($columns as $col)
                                            <th scope="col" class="myds-body-sm myds-text--muted">{{ $col }}</th>
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

                            <div id="preview-count" class="myds-body-xs myds-text--muted mt-2">
                                Memaparkan {{ $previewRowsArray->count() }} baris untuk pratonton.
                            </div>
                        </div>

                        {{-- Import confirmation form
                             - Uses server-side stored preview unless user re-uploads (controlled by JS)
                        --}}
                        <form method="POST" action="{{ route('excel.inventory.import') }}" enctype="multipart/form-data" class="d-flex flex-column gap-2" role="form" aria-label="Borang import data">
                            @csrf

                            {{-- Hidden flag controlled by JS: default 0 (use server-side preview) --}}
                            <input type="hidden" name="_reupload" id="reupload-flag" value="0">

                            <div class="d-flex flex-column gap-2">
                                <div class="myds-body-sm myds-text--muted">
                                    Anda boleh terus import data yang dipratonton, atau muat naik fail baru untuk import.
                                </div>

                                <div class="d-flex gap-2 align-items-center flex-wrap">
                                    <button type="submit" name="import" value="confirm" class="myds-btn myds-btn--primary" aria-label="Import data yang dipratonton">Import</button>

                                    <label for="reupload-file" id="reupload-label" class="myds-btn myds-btn--outline" role="button" tabindex="0" aria-controls="reupload-file" aria-label="Muat naik fail baru untuk import">
                                        Muat naik fail baru untuk import
                                    </label>

                                    <div id="reupload-filename" class="myds-body-sm myds-text--muted" aria-live="polite">Tiada fail baru dipilih</div>

                                    <input id="reupload-file" name="file" type="file" accept=".xlsx,.csv" class="visually-hidden" aria-describedby="reupload-help" />
                                </div>

                                <div id="reupload-help" class="myds-body-xs myds-text--muted">
                                    Jika anda memilih fail baru, pratonton sebelumnya akan diabaikan dan fail baru akan diproses.
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </section>
        @endisset

    </div>
</main>
@endsection

@push('scripts')
    {{-- Page-specific JS handles:
         - wiring custom file controls to hidden inputs
         - populating filename live regions
         - toggling the _reupload flag when a new file is chosen
         - accessibility helpers
         The file should be idempotent and safe to include on multiple pages.
    --}}
    @vite('resources/js/pages/excel-inventory-import.js')
@endpush
