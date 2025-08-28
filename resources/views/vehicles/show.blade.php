@extends('layouts.app')

@section('title', 'Butiran Kenderaan — ' . config('app.name', 'second-crud'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Kenderaan #{{ $vehicle->id }}</div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <p><strong>Nama:</strong> {{ $vehicle->name ?? '—' }}</p>
                    <p><strong>Pemilik:</strong> {{ $vehicle->owner?->name ?? '—' }}</p>
                    <p><strong>Kuantiti:</strong> {{ $vehicle->qty ?? 0 }}</p>
                    <p><strong>Harga:</strong> {{ isset($vehicle->price) ? number_format($vehicle->price, 2) : '—' }}</p>

                    <p><strong>Keterangan:</strong></p>
                    <div>{!! nl2br(e($vehicle->description)) !!}</div>

                    <div class="mt-3">
                        <a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--secondary" data-myds="link">Kembali</a>
                        <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="myds-btn myds-btn--primary myds-btn--outline" data-myds="link">Edit</a>
                        <x-destroy :action="route('vehicles.destroy', $vehicle->id)" :label="$vehicle->name ?? 'Kenderaan'" />
                    </div>

                    @if(isset($vehicle->inventories) && $vehicle->inventories->count())
                        <hr class="my-3">
                        <h5>Inventori berkaitan</h5>
                        <ul>
                            @foreach($vehicle->inventories as $inv)
                                <li><a href="{{ route('inventories.show', $inv->id) }}">{{ $inv->name ?? '—' }}</a> (ID: {{ $inv->id }})</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
