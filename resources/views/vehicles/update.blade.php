@extends('layouts.app')

@section('title', 'Kenderaan Dikemaskini — ' . config('app.name', 'second-crud'))

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-body">
					<h2 class="h5">Vehicle updated</h2>
					<p class="mb-0">The vehicle has been updated successfully.</p>
					<div class="mt-3">
						<a href="{{ route('vehicles.index') }}" class="btn btn-primary">Back to vehicles</a>
					</div>
				</div>
			</div>
		</div>
	</div>
 </div>
@endsection
