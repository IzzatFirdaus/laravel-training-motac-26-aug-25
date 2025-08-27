{{-- Welcome page for the training app. Uses the app layout and sets an explicit title. --}}
@extends('layouts.app')

@section('title', 'Welcome — ' . config('app.name', 'second-crud'))

@section('content')
	<main class="container py-5">
		<header class="mb-4">
			<h1 class="h3">{{ config('app.name', 'Latihan MOTAC') }}</h1>
			<p class="text-muted">Aplikasi demo ringkas untuk latihan — pautan pantas di bawah membolehkan anda melihat senarai, borang cipta, dan aliran pengesahan.</p>
		</header>

		<section aria-labelledby="quick-links-heading">
			<h2 id="quick-links-heading" class="h5 visually-hidden">Pautan Pantas</h2>

			<div class="row g-3">
				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('inventories.index') }}" aria-label="View inventories list">
						<div class="card-body">
							<h3 class="h6">Inventori</h3>
							<p class="mb-0 text-muted">Lihat senarai inventori (nama, kuantiti, harga) dan butiran.</p>
						</div>
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('inventories.create') }}" aria-label="Create inventory">
						<div class="card-body">
							<h3 class="h6">Cipta Inventori</h3>
							<p class="mb-0 text-muted">Buka borang cipta untuk tambah inventori baharu.</p>
						</div>
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('vehicles.index') }}" aria-label="View vehicles list">
						<div class="card-body">
							<h3 class="h6">Kenderaan</h3>
							<p class="mb-0 text-muted">Lihat senarai kenderaan dan perincian inventori berkaitan.</p>
						</div>
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('vehicles.create') }}" aria-label="Create vehicle">
						<div class="card-body">
							<h3 class="h6">Cipta Kenderaan</h3>
							<p class="mb-0 text-muted">Buka borang cipta untuk tambah kenderaan/inventori baharu.</p>
						</div>
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('login') }}" aria-label="Login">
						<div class="card-body">
							<h3 class="h6">Log Masuk / Daftar</h3>
							<p class="mb-0 text-muted">Log masuk untuk akses halaman berautentikasi atau daftar akaun baharu.</p>
						</div>
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('home') }}" aria-label="Home">
						<div class="card-body">
							<h3 class="h6">Laman Utama</h3>
							<p class="mb-0 text-muted">Halaman utama/dasbor anda selepas log masuk.</p>
						</div>
					</a>
				</div>
			</div>
		</section>

		<section aria-labelledby="examples-heading" class="mt-4">
			<h2 id="examples-heading" class="h5">Contoh Tindakan</h2>

			<!-- Inventori actions -->
			<h3 class="h6 mt-3">Inventori</h3>
			<div class="row g-3 mb-3">
				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Index</h4>
							<p class="mb-2 text-muted">Lihat senarai inventori (nama, kuantiti, harga) dan butiran.</p>
							<a href="{{ route('inventories.index') }}" class="btn btn-sm btn-primary">Senarai</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Show</h4>
							<p class="mb-2 text-muted">Lihat butiran item contoh (ID = 1).</p>
							<a href="{{ route('inventories.show', 1) }}" class="btn btn-sm btn-outline-success">Lihat</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Create</h4>
							<p class="mb-2 text-muted">Buka borang untuk cipta inventori baharu.</p>
							<a href="{{ route('inventories.create') }}" class="btn btn-sm btn-outline-primary">Cipta</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Store (demo)</h4>
							<p class="mb-2 text-muted">Simulasi POST store — gunakan borang Cipta untuk menghantar.</p>
							<a href="{{ route('inventories.create') }}" class="btn btn-sm btn-outline-secondary" data-demo="store" data-model="Inventori">Store (demo)</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Edit</h4>
							<p class="mb-2 text-muted">Buka borang sunting untuk item contoh (ID = 1).</p>
							<a href="{{ route('inventories.edit', 1) }}" class="btn btn-sm btn-outline-primary">Edit</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Update (demo)</h4>
							<p class="mb-2 text-muted">Simulasi tindakan kemaskini; gunakan borang Sunting untuk POST.</p>
							<a href="{{ route('inventories.edit', 1) }}" class="btn btn-sm btn-outline-secondary" data-demo="update" data-model="Inventori">Update (demo)</a>
						</div>
					</div>
				</div>
			</div>

			<!-- Vehicles actions -->
			<h3 class="h6 mt-2">Kenderaan</h3>
			<div class="row g-3">
				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Index</h4>
							<p class="mb-2 text-muted">Lihat senarai kenderaan (nama, kuantiti, harga) dan butiran.</p>
							<a href="{{ route('vehicles.index') }}" class="btn btn-sm btn-primary">Senarai</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Show</h4>
							<p class="mb-2 text-muted">Lihat butiran kenderaan contoh (ID = 1).</p>
							<a href="{{ route('vehicles.show', 1) }}" class="btn btn-sm btn-outline-success">Lihat</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Create</h4>
							<p class="mb-2 text-muted">Buka borang untuk cipta kenderaan baharu.</p>
							<a href="{{ route('vehicles.create') }}" class="btn btn-sm btn-outline-primary">Cipta</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Store (demo)</h4>
							<p class="mb-2 text-muted">Simulasi POST store — gunakan borang Cipta untuk menghantar.</p>
							<a href="{{ route('vehicles.create') }}" class="btn btn-sm btn-outline-secondary" data-demo="store" data-model="Kenderaan">Store (demo)</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Edit</h4>
							<p class="mb-2 text-muted">Buka borang sunting untuk kenderaan contoh (ID = 1).</p>
							<a href="{{ route('vehicles.edit', 1) }}" class="btn btn-sm btn-outline-primary">Edit</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Update (demo)</h4>
							<p class="mb-2 text-muted">Simulasi tindakan kemaskini; gunakan borang Sunting untuk POST.</p>
							<a href="{{ route('vehicles.edit', 1) }}" class="btn btn-sm btn-outline-secondary" data-demo="update" data-model="Kenderaan">Update (demo)</a>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			// small helper to show a toast via SweetAlert2 if available, otherwise use a minimal fallback
			function showToast(message, icon) {
				icon = icon || 'info';
				if (window.Swal && typeof window.Swal.fire === 'function') {
					Swal.fire({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 3000,
						timerProgressBar: true,
						icon: icon,
						title: message
					});
					return;
				}

				// fallback toast (minimal, no CSS dependency)
				var container = document.getElementById('simple-toast-container');
				if (!container) {
					container = document.createElement('div');
					container.id = 'simple-toast-container';
					container.style.position = 'fixed';
					container.style.top = '1rem';
					container.style.right = '1rem';
					container.style.zIndex = 99999;
					document.body.appendChild(container);
				}

				var toast = document.createElement('div');
				toast.textContent = message;
				toast.style.background = 'rgba(0,0,0,0.8)';
				toast.style.color = 'white';
				toast.style.padding = '0.5rem 0.75rem';
				toast.style.marginTop = '0.5rem';
				toast.style.borderRadius = '0.375rem';
				toast.style.boxShadow = '0 2px 6px rgba(0,0,0,0.2)';
				container.appendChild(toast);

				setTimeout(function () {
					toast.style.transition = 'opacity 300ms';
					toast.style.opacity = '0';
					setTimeout(function () { container.removeChild(toast); }, 300);
			@push('scripts')
				@vite('resources/js/welcome-demo.js')
			@endpush
			var destroyBtn = document.getElementById('demo-destroy');
			if (destroyBtn) {
				destroyBtn.addEventListener('click', function () {
					if (window.Swal && typeof window.Swal.fire === 'function') {
						Swal.fire({
							title: 'Demo: Destroy',
							text: 'Ini hanya demo — tiada data akan dipadam. Teruskan?',
							icon: 'warning',
							showCancelButton: true,
							confirmButtonText: 'Yes, show demo',
							cancelButtonText: 'Cancel'
						}).then(function (result) {
							if (result.isConfirmed) {
								showToast('Destroy action simulated — no data changed.', 'info');
							}
						});
						return;
					}

					if (confirm('Demo: Ini hanya demo — tiada data akan dipadam. Teruskan?')) {
						showToast('Destroy action simulated — no data changed.', 'info');
					}
				});
			}
		});
	</script>
@endsection
