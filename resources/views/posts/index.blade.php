@extends('layouts.app')

@section('title', 'Posts — ' . config('app.name'))

@section('content')
<main id="main-content" class="container" tabindex="-1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10">
            <header class="d-flex align-items-start justify-content-between mb-3">
                <div>
                    <h1 class="h3">Posts</h1>
                    <p class="text-muted mb-0">External posts fetched from jsonplaceholder.typicode.com</p>
                </div>
            </header>

            <section aria-labelledby="posts-heading">
                <h2 id="posts-heading" class="visually-hidden">Posts list</h2>

                <div class="card">
                    <div class="card-body">
                        @if(empty($posts))
                            <div class="p-3 text-center text-muted">Tiada pos ditemui.</div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach($posts as $post)
                                    <div class="list-group-item d-flex align-items-start justify-content-between">
                                        <div class="me-3">
                                            <div class="fw-semibold">{{ $post['title'] }}</div>
                                            <div class="text-muted small">{{ \Illuminate\Support\Str::limit($post['body'], 140) }}</div>
                                        </div>
                                        <div class="text-end">
                                            <a href="https://jsonplaceholder.typicode.com/posts/{{ $post['id'] }}" target="_blank" rel="noopener" class="myds-btn myds-btn--primary myds-btn--sm">Lihat</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection
