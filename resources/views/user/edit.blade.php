@extends('layouts.app')

@section('title', 'Sunting Pengguna — ' . config('app.name', 'second-crud'))

@section('content')
<main class="container" id="main-content" tabindex="-1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <header class="mb-3">
                <h1 class="h3">Sunting Pengguna</h1>
                <p class="text-muted">Kemas kini butiran pengguna.</p>
            </header>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        @include('user._form', ['user' => $user])

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Simpan perubahan</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
