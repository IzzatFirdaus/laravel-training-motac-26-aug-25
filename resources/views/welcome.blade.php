{{-- Welcome page for the training app. Uses the app layout and sets an explicit title. --}}
@extends('layouts.app')

@section('title', 'Selamat Datang — ' . config('app.name', 'second-crud'))

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
					<a class="card h-100 text-decoration-none text-body" href="{{ route('users.index') }}" aria-label="Lihat pengguna">
						<div class="card-body">
							<h3 class="h6">Pengguna</h3>
							<p class="mb-0 text-muted">Lihat senarai pengguna yang didaftarkan.</p>
						</div>
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					@can('create', App\Models\User::class)
						<a class="card h-100 text-decoration-none text-body" href="{{ route('users.create') }}" aria-label="Cipta pengguna">
							<div class="card-body">
								<h3 class="h6">Cipta Pengguna</h3>
								<p class="mb-0 text-muted">Buka borang cipta akaun pengguna baharu.</p>
							</div>
						</a>
					@else
						<div class="card h-100 text-body">
							<div class="card-body">
								<h3 class="h6">Cipta Pengguna</h3>
								<p class="mb-0 text-muted">Buka borang cipta akaun pengguna baharu.</p>
							</div>
						</div>
					@endcan
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('inventories.index') }}" aria-label="Lihat senarai inventori">
						<div class="card-body">
							<h3 class="h6">Inventori</h3>
							<p class="mb-0 text-muted">Lihat senarai inventori (nama, kuantiti, harga) dan butiran.</p>
						</div>
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('inventories.create') }}" aria-label="Cipta inventori">
						<div class="card-body">
							<h3 class="h6">Cipta Inventori</h3>
							<p class="mb-0 text-muted">Buka borang cipta untuk tambah inventori baharu.</p>
						</div>
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('vehicles.index') }}" aria-label="Lihat senarai kenderaan">
						<div class="card-body">
							<h3 class="h6">Kenderaan</h3>
							<p class="mb-0 text-muted">Lihat senarai kenderaan dan perincian inventori berkaitan.</p>
						</div>
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('vehicles.create') }}" aria-label="Cipta kenderaan">
						<div class="card-body">
							<h3 class="h6">Cipta Kenderaan</h3>
							<p class="mb-0 text-muted">Buka borang cipta untuk tambah kenderaan baharu.</p>
						</div>
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('login') }}" aria-label="Log masuk">
						<div class="card-body">
							<h3 class="h6">Log Masuk / Daftar</h3>
							<p class="mb-0 text-muted">Log masuk untuk akses halaman berautentikasi atau daftar akaun baharu.</p>
						</div>
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<a class="card h-100 text-decoration-none text-body" href="{{ route('home') }}" aria-label="Laman utama">
						<div class="card-body">
							<h3 class="h6">Laman Utama</h3>
							<p class="mb-0 text-muted">Halaman utama pengguna selepas log masuk.</p>
						</div>
					</a>
				</div>
			</div>
		</section>

		<section aria-labelledby="examples-heading" class="mt-4">
			<h2 id="examples-heading" class="h5">Contoh Tindakan</h2>

			<!-- Users actions -->
			<h3 class="h6 mt-3">Pengguna</h3>
			<div class="row g-3 mb-3">
				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Index</h4>
							<p class="mb-2 text-muted">Lihat senarai pengguna yang didaftarkan.</p>
							<a href="{{ route('users.index') }}" class="myds-btn myds-btn--primary myds-btn--sm">Senarai</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Show</h4>
							<p class="mb-2 text-muted">Lihat butiran pengguna contoh (ID = 1).</p>
							<a href="{{ route('users.show', 1) }}" class="myds-btn myds-btn--success myds-btn--sm myds-btn--outline">Lihat</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Create</h4>
							<p class="mb-2 text-muted">Buka borang untuk cipta pengguna baharu.</p>
							@can('create', App\Models\User::class)
								<a href="{{ route('users.create') }}" class="myds-btn myds-btn--primary myds-btn--sm myds-btn--outline">Cipta</a>
							@endcan
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Store (demo)</h4>
							<p class="mb-2 text-muted">Simulasi POST store — gunakan borang Cipta untuk menghantar.</p>
							@can('create', App\Models\User::class)
								<a href="{{ route('users.create') }}" class="myds-btn myds-btn--secondary myds-btn--sm" data-demo="store" data-model="Pengguna">Store (demo)</a>
							@endcan
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Edit</h4>
							<p class="mb-2 text-muted">Buka borang edit untuk pengguna contoh (ID = 1).</p>
							<a href="{{ route('users.edit', 1) }}" class="myds-btn myds-btn--primary myds-btn--sm">Edit</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Update (demo)</h4>
							<p class="mb-2 text-muted">Simulasi tindakan kemaskini; gunakan borang Edit untuk POST.</p>
							<a href="{{ route('users.edit', 1) }}" class="myds-btn myds-btn--secondary myds-btn--sm" data-demo="update" data-model="Pengguna">Update (demo)</a>
						</div>
					</div>
				</div>
			</div>

			<!-- Inventori actions -->
			<h3 class="h6 mt-3">Inventori</h3>
			<div class="row g-3 mb-3">
				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Index</h4>
							<p class="mb-2 text-muted">Lihat senarai inventori (nama, kuantiti, harga) dan butiran.</p>
							<a href="{{ route('inventories.index') }}" class="myds-btn myds-btn--primary myds-btn--sm">Senarai</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Show</h4>
							<p class="mb-2 text-muted">Lihat butiran item contoh (ID = 1).</p>
							<a href="{{ route('inventories.show', 1) }}" class="myds-btn myds-btn--success myds-btn--sm myds-btn--outline">Lihat</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Create</h4>
							<p class="mb-2 text-muted">Buka borang untuk cipta inventori baharu.</p>
							<a href="{{ route('inventories.create') }}" class="myds-btn myds-btn--primary myds-btn--sm">Cipta</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Store (demo)</h4>
							<p class="mb-2 text-muted">Simulasi POST store — gunakan borang Cipta untuk menghantar.</p>
							<a href="{{ route('inventories.create') }}" class="myds-btn myds-btn--secondary myds-btn--sm" data-demo="store" data-model="Inventori">Store (demo)</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Edit</h4>
							<p class="mb-2 text-muted">Buka borang edit untuk item contoh (ID = 1).</p>
							<a href="{{ route('inventories.edit', 1) }}" class="myds-btn myds-btn--primary myds-btn--sm">Edit</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Update (demo)</h4>
							<p class="mb-2 text-muted">Simulasi tindakan kemaskini; gunakan borang Edit untuk POST.</p>
							<a href="{{ route('inventories.edit', 1) }}" class="myds-btn myds-btn--secondary myds-btn--sm" data-demo="update" data-model="Inventori">Update (demo)</a>
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
							<a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--primary myds-btn--sm">Senarai</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Show</h4>
							<p class="mb-2 text-muted">Lihat butiran kenderaan contoh (ID = 1).</p>
							<a href="{{ route('vehicles.show', 1) }}" class="myds-btn myds-btn--success myds-btn--sm myds-btn--outline">Lihat</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Create</h4>
							<p class="mb-2 text-muted">Buka borang untuk cipta kenderaan baharu.</p>
							<a href="{{ route('vehicles.create') }}" class="myds-btn myds-btn--primary myds-btn--sm">Cipta</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Store (demo)</h4>
							<p class="mb-2 text-muted">Simulasi POST store — gunakan borang Cipta untuk menghantar.</p>
							<a href="{{ route('vehicles.create') }}" class="myds-btn myds-btn--secondary myds-btn--sm" data-demo="store" data-model="Kenderaan">Store (demo)</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Edit</h4>
							<p class="mb-2 text-muted">Buka borang edit untuk kenderaan contoh (ID = 1).</p>
							<a href="{{ route('vehicles.edit', 1) }}" class="myds-btn myds-btn--primary myds-btn--sm">Edit</a>
						</div>
					</div>
				</div>

				<div class="col-6 col-md-4 col-lg-2">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="h6">Update (demo)</h4>
							<p class="mb-2 text-muted">Simulasi tindakan kemaskini; gunakan borang Edit untuk POST.</p>
							<a href="{{ route('vehicles.edit', 1) }}" class="myds-btn myds-btn--secondary myds-btn--sm" data-demo="update" data-model="Kenderaan">Update (demo)</a>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>

	@push('scripts')
		@vite('resources/js/welcome-demo.js')
	@endpush

@endsection
