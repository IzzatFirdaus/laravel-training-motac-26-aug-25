@extends('layouts.app')

@section('title', __('ui.vehicles.updated_title', ['app' => config('app.name', 'Sistem Kerajaan')]))

@section('content')
<main id="main-content" class="myds-container py-6" role="main" tabindex="-1" aria-labelledby="vehicle-updated-heading">
	<header class="mb-4">
		<h1 id="vehicle-updated-heading" class="myds-heading-md font-heading font-semibold">{{ __('ui.vehicles.updated_heading') }}</h1>
	</header>

	<div class="myds-card">
		<div class="myds-card__body">
			<p role="status" class="myds-body-md">{{ __('ui.vehicles.updated_body') }}</p>
			<div class="mt-3">
				<a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--primary">{{ __('ui.vehicles.back_to_list') }}</a>
			</div>
		</div>
	</div>
</main>
@endsection
