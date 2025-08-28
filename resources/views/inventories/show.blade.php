@extends('layouts.app')

@section('title', 'Butiran Inventori — ' . config('app.name', 'second-crud'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <!-- Display the inventory ID in the header -->
                <div class="card-header">Inventori #{{ $inventory->id }}</div>

                <div class="card-body">
                    <!-- Display inventory details -->
                    <p><strong>Nama:</strong> {{ $inventory->name }}</p>
                    <p><strong>Pemilik:</strong> {{ $inventory->user?->name ?? '—' }}</p>
                    <p><strong>Kuantiti:</strong> {{ $inventory->qty ?? 0 }}</p>
                    <p><strong>Harga:</strong> {{ isset($inventory->price) ? number_format($inventory->price, 2) : '—' }}</p>
                    <p><strong>Keterangan:</strong></p>
                    <div>{!! nl2br(e($inventory->description)) !!}</div> <!-- Display description (preserve line breaks) -->

                    <div class="mt-3">
                        <!-- Navigation buttons -->
                            <a href="{{ route('inventories.index') }}" class="btn btn-secondary myds-btn myds-btn--secondary" data-myds="link">Kembali</a>
                            <a href="{{ route('inventories.edit', $inventory->id) }}" class="btn btn-outline-primary myds-btn myds-btn--primary" data-myds="link">Edit</a>
                        <x-destroy :action="route('inventories.destroy', $inventory->id)" :label="$inventory->name ?? 'Inventory'" />
                    </div>
                    @if(isset($inventory->vehicles) && $inventory->vehicles->count())
                        <hr class="my-3">
                        <h5>Kenderaan berkaitan</h5>
                        <ul>
                            @foreach($inventory->vehicles as $v)
                                <li><a href="{{ route('vehicles.show', $v->id) }}">{{ $v->name ?? '—' }}</a> (ID: {{ $v->id }})</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
