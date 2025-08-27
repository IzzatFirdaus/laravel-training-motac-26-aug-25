{{-- Welcome page for the training app. Uses the app layout and sets an explicit title. --}}
@extends('layouts.app')

@section('title', 'Welcome — ' . config('app.name', 'second-crud'))

@section('content')
	<main class="container py-5">
		<header class="mb-4">
			<h1 class="h3">{{ config('app.name', 'MOTAC Training') }}</h1>
			<p class="text-muted">A small demo app used for training — quick links below let you inspect lists, create forms, and authentication flows.</p>
		</header>

		<section aria-labelledby="quick-links-heading">
			<h2 id="quick-links-heading" class="h5 visually-hidden">Quick links</h2>

			<div class="row g-3">
				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('inventories.index') }}" aria-label="View inventories list">
						<div class="card-body">
							<h3 class="h6">Inventories</h3>
							<p class="mb-0 text-muted">Browse inventory items (name, quantity, price) and view details.</p>
						</div>
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('inventories.create') }}" aria-label="Create inventory">
						<div class="card-body">
							<h3 class="h6">Create Inventory</h3>
							<p class="mb-0 text-muted">Open the create form to add a new inventory item.</p>
						</div>
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('vehicles.index') }}" aria-label="View vehicles list">
						<div class="card-body">
							<h3 class="h6">Vehicles</h3>
							<p class="mb-0 text-muted">Browse vehicle-like inventory entries and inspect details.</p>
						</div>
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('vehicles.create') }}" aria-label="Create vehicle">
						<div class="card-body">
							<h3 class="h6">Create Vehicle</h3>
							<p class="mb-0 text-muted">Open the create form to add a new vehicle/inventory entry.</p>
						</div>
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('login') }}" aria-label="Login">
						<div class="card-body">
							<h3 class="h6">Login / Register</h3>
							<p class="mb-0 text-muted">Sign in to access authenticated pages or create an account.</p>
						</div>
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('home') }}" aria-label="Home">
						<div class="card-body">
							<h3 class="h6">Home</h3>
							<p class="mb-0 text-muted">Your authenticated home/dashboard page.</p>
						</div>
					</a>
				</div>
			</div>
		</section>
	</main>
@endsection
