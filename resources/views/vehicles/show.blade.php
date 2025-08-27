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
        <h1 class="text-2xl font-semibold">Vehicle #{{ $vehicle->id }} — {{ $vehicle->name ?? 'Untitled' }}</h1>

        <div class="space-x-2">
            <a href="{{ route('vehicles.index') }}" class="inline-block px-3 py-2 rounded bg-gray-200 hover:bg-gray-300">Back</a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-5">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Name</dt>
                <dd class="mt-1 text-lg text-gray-900">{{ $vehicle->name ?? '—' }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Quantity</dt>
                <dd class="mt-1 text-lg text-gray-900">{{ $vehicle->qty ?? 0 }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Price</dt>
                <dd class="mt-1 text-lg text-gray-900">{{ isset($vehicle->price) ? number_format($vehicle->price, 2) : '—' }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Description</dt>
                <dd class="mt-1 text-lg text-gray-900">{{ $vehicle->description ?? '—' }}</dd>
            </div>

            @if(isset($vehicle->owner) && $vehicle->owner)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Owner</dt>
                    <dd class="mt-1 text-lg text-blue-600">{{ $vehicle->owner->name }}</dd>
                </div>
            @endif

            <div>
                <dt class="text-sm font-medium text-gray-500">Created</dt>
                <dd class="mt-1 text-sm text-gray-600">{{ $vehicle->created_at?->format('Y-m-d H:i') ?? '—' }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Last updated</dt>
                <dd class="mt-1 text-sm text-gray-600">{{ $vehicle->updated_at?->format('Y-m-d H:i') ?? '—' }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
