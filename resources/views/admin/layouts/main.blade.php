<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    {{-- Use Vite to load compiled CSS/JS (handles dev server and production manifest) --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    @include('admin.includes.navbar')

    <div class="container-fluid">
        <div class="row">
            <aside class="col-md-2 bg-light vh-100 p-3">
                @include('admin.includes.sidebar')
            </aside>
            <main class="col-md-10 p-4">
                @yield('content')
            </main>
        </div>
    </div>

    @include('admin.includes.footer')

    @stack('scripts')
</body>
</html>
