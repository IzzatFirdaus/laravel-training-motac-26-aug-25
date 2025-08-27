@extends('layouts.app')

@section('title', 'Inventory updated — second-crud')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-body">
					<h2 class="h5">Inventory updated</h2>
					<p class="mb-0">The inventory item has been updated successfully.</p>
					<div class="mt-3">
						<a href="{{ route('inventories.index') }}" class="btn btn-primary">Back to inventories</a>
					</div>
				</div>
			</div>
		</div>
	</div>
 </div>
@endsection
