@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Vehicles Index') }}</div>

                {{--
                    Expected variable: $vehicles (Collection of App\Models\Vehicle)
                    Each $vehicle should have: id, name, qty, price, description, created_at
                --}}
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vehicles as $vehicle)
                                <tr>
                                    <td>{{ $vehicle->id }}</td>
                                    <td>{{ $vehicle->name }}</td>
                                    <td>{{ $vehicle->qty }}</td>
                                    <td>{{ number_format($vehicle->price, 2) }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($vehicle->description, 60) }}</td>
                                    <td>{{ optional($vehicle->created_at)->toDateString() }}</td>
                                    <td><!-- show/edit links here --></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
