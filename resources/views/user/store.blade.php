@extends('layouts.app')

@section('title', 'Mencipta Pengguna — ' . config('app.name', 'second-crud'))

@section('content')
<main class="myds-container" id="main-content" tabindex="-1">
	<div class="row justify-content-center">
		<div class="col-12 col-md-8">
			<div class="card">
				<div class="card-body">
					<p>Pengguna sedang dicipta. Anda akan dialihkan semula.</p>
					<p class="text-muted">Jika pengalihan tidak berlaku, <a href="{{ route('users.index') }}">klik di sini</a>.</p>
				</div>
			</div>
		</div>
	</div>
</main>

@push('scripts')
	<meta id="users-redirect-url" content="{{ route('users.index') }}">
	<meta id="users-redirect-delay" content="1200">
	@vite('resources/js/pages/users-redirect.js')
@endpush

@endsection
