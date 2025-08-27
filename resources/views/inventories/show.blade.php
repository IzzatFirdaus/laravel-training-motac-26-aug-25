@extends('layouts.app')

@section('title', 'Inventory Detail — second-crud')

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
                    <p><strong>Pemilik:</strong> {{ $inventory->user?->name ?? '' }}</p> <!-- Show owner name or a dash if none -->
                    <p><strong>Kuantiti:</strong> {{ $inventory->qty }}</p>
                    <p><strong>Harga:</strong> {{ number_format($inventory->price, 2) }}</p> <!-- Format price to 2 decimal places -->
                    <p><strong>Keterangan:</strong></p>
                    <div>{!! nl2br(e($inventory->description)) !!}</div> <!-- Display description (preserve line breaks) -->

                    <div class="mt-3">
                        <!-- Navigation buttons -->
                        <a href="{{ route('inventories.index') }}" class="btn btn-secondary">Kembali</a>
                        <a href="{{ route('inventories.edit', $inventory->id) }}" class="btn btn-outline-primary">Edit</a>
                        {{-- <a href="{{ route('inventories.create') }}" class="btn btn-primary">Create another</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
