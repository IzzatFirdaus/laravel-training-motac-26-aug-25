@extends('layouts.app')

@section('title', 'Inventori Dikemaskini — ' . config('app.name', 'Sistem Kerajaan'))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="status-title">
  <div class="mx-auto content-maxwidth-lg">
    <div class="myds-card">
      <div class="myds-card__body">
        <h2 id="status-title" class="myds-heading-sm font-heading mb-2">Inventori dikemaskini</h2>
        <p class="myds-body-sm mb-0">Item inventori telah berjaya dikemaskini.</p>

        <div class="mt-3">
          <a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--primary">Kembali ke senarai inventori</a>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
