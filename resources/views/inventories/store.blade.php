@extends('layouts.app')

@section('title', 'Cipta Inventori — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="create-status">
  <div class="mx-auto content-maxwidth-lg">
    <div class="myds-card">
      <div class="myds-card__body">
        @if (session('status'))
          <div class="myds-alert myds-alert--success mb-3" role="status" aria-live="polite">{{ session('status') }}</div>
        @endif

        <h2 id="create-status" class="myds-heading-sm mb-2">Inventori berjaya dicipta</h2>
        <p class="myds-body-sm mb-0">Item inventori telah berjaya ditambah ke dalam sistem.</p>

        <div class="mt-3 d-flex gap-2">
          <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--tertiary">Kembali ke senarai inventori</a>
          @isset($inventory)
            <a href="{{ route('inventories.show', $inventory->id) }}" class="myds-btn myds-btn--primary">Lihat Inventori</a>
          @endisset
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
