@extends('layouts.app')

@section('title', 'Ubah Kenderaan — ' . config('app.name', 'second-crud'))

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">Sunting Inventori</div>

				<div class="card-body">
					@if (session('status'))
						<div class="alert alert-success">{{ session('status') }}</div>
					@endif

					<form method="POST" action="{{ route('vehicles.update', $vehicle->id) }}">
						@csrf

						<div class="mb-3">
							<label for="name" class="form-label">Nama</label>
							<input id="name" name="name" type="text" class="form-control" value="{{ old('name', $vehicle->name) }}">
							@error('name') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
						</div>

						<div class="mb-3">
							<label for="user_id" class="form-label">Pemilik (pilihan)</label>
							@if(auth()->check() && auth()->user()->hasRole('admin'))
								<select id="user_id" name="user_id" class="form-control">
									<option value="">(tiada pemilik)</option>
									@foreach(($users ?? collect()) as $user)
										<option value="{{ $user->id }}" {{ (string) old('user_id', $vehicle->user_id) === (string) $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
									@endforeach
								</select>
								@error('user_id') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
							@else
								<input type="hidden" name="user_id" value="{{ $vehicle->user_id ?? '' }}">
								<div class="form-control-plaintext">{{ $vehicle->user?->name ?? '(tiada pemilik)' }}</div>
							@endif
						</div>

						<div class="mb-3">
							<label for="qty" class="form-label">Kuantiti</label>
							<input id="qty" name="qty" type="number" min="0" class="form-control" value="{{ old('qty', $vehicle->qty ?? 0) }}">
							@error('qty') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
						</div>

						<div class="mb-3">
							<label for="price" class="form-label">Harga</label>
							<input id="price" name="price" type="number" step="0.01" min="0" class="form-control" value="{{ old('price', $vehicle->price ?? '') }}">
							@error('price') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
						</div>

						<div class="mb-3">
							<label for="description" class="form-label">Keterangan</label>
							<textarea id="description" name="description" class="form-control">{{ old('description', $vehicle->description) }}</textarea>
							@error('description') <div class="text-danger myds-action--danger">{{ $message }}</div> @enderror
						</div>

						<div class="d-flex justify-content-end">
							<a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--secondary me-2">Batal</a>
							<button type="submit" class="myds-btn myds-btn--primary">Kemaskini</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
