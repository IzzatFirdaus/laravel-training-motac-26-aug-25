{{-- resources/views/vehicles/show.blade.php --}}
@extends('layouts.app')

@section('title', ($vehicle->name ?? 'Vehicle') . ' — ' . config('app.name', 'second-crud'))

@section('content')
<div class="container mx-auto p-6">
    @if(session('success'))
        <div class="mb-4 rounded bg-green-100 border border-green-200 text-green-800 px-4 py-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Kenderaan #{{ $vehicle->id }} — {{ $vehicle->name ?? 'Tanpa Nama' }}</h1>

        <div class="space-x-2">
            <button type="button" class="inline-block px-3 py-2 rounded bg-gray-200 hover:bg-gray-300" aria-label="Kembali" onclick="window.location.href='{{ route('vehicles.index') }}'">Kembali</button>
            <button type="button" class="inline-block px-3 py-2 rounded bg-blue-200 hover:bg-blue-300" aria-label="Edit" onclick="window.location.href='{{ route('vehicles.edit', $vehicle->id) }}'">Edit</button>
            <x-vehicle-destroy :action="route('vehicles.destroy', $vehicle->id)" :label="$vehicle->name ?? 'Vehicle'" />
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-5">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Nama</dt>
                <dd class="mt-1 text-lg text-gray-900">{{ $vehicle->name ?? '—' }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Kuantiti</dt>
                <dd class="mt-1 text-lg text-gray-900">{{ $vehicle->qty ?? 0 }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Harga</dt>
                <dd class="mt-1 text-lg text-gray-900">{{ isset($vehicle->price) ? number_format($vehicle->price, 2) : '—' }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Keterangan</dt>
                <dd class="mt-1 text-lg text-gray-900">{{ $vehicle->description ?? '—' }}</dd>
            </div>

            @if(isset($vehicle->owner) && $vehicle->owner)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Pemilik</dt>
                    <dd class="mt-1 text-lg text-blue-600">{{ $vehicle->owner->name }}</dd>
                </div>
            @endif

            @if(isset($vehicle->inventories) && $vehicle->inventories->count())
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Inventori berkaitan</dt>
                    <dd class="mt-1 text-lg text-gray-900">
                        <ul>
                            @foreach($vehicle->inventories as $inv)
                                <li><a href="{{ route('inventories.show', $inv->id) }}">{{ $inv->name ?? '—' }}</a> (ID: {{ $inv->id }})</li>
                            @endforeach
                        </ul>
                    </dd>
                </div>
            @endif

            <div>
                <dt class="text-sm font-medium text-gray-500">Dicipta</dt>
                <dd class="mt-1 text-sm text-gray-600">
                    @if(!empty($vehicle->created_at))
                        {{ \Illuminate\Support\Carbon::parse($vehicle->created_at)->format('Y-m-d H:i') }}
                    @else
                        —
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Kemaskini terakhir</dt>
                <dd class="mt-1 text-sm text-gray-600">
                    @if(!empty($vehicle->updated_at))
                        {{ \Illuminate\Support\Carbon::parse($vehicle->updated_at)->format('Y-m-d H:i') }}
                    @else
                        —
                    @endif
                </dd>
            </div>
        </dl>
    </div>
</div>
@endsection
