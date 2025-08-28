@extends('layouts.app')

@section('title', 'Ubah Pengguna — ' . config('app.name', 'second-crud'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Pengguna</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf

                        @include('user._form', ['user' => $user])

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('users.index') }}" class="myds-btn myds-btn--secondary me-2" aria-label="Batal dan kembali ke senarai">Batal</a>
                            <button type="submit" class="myds-btn myds-btn--primary" aria-label="Kemaskini pengguna">Kemaskini</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
