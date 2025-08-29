# LARAVEL TRAINING-DAY 3

Prepared by TARSOFT SDN BHD

This document covers authorization, dynamic dropdowns, queue jobs, notifications, HTTP requests, and a hands-on workshop for integrating templates.

## Table of contents

- Authorization
- Dynamic dropdowns
- Queue jobs
- Notifications
- HTTP requests
- Hands-on workshop

## Authorization

### Introduction

Authorization in Laravel determines what a user can or cannot do. For example:

- Can this user update a post?
- Can this user delete an inventory record?
- Can this user view another user's profile?

### Creating policies

Generate a policy using Artisan:

```bash
php artisan make:policy InventoryPolicy
```

This creates `app/Policies/InventoryPolicy.php` with methods for actions (view, create, update, delete).

### Writing policies

Inside the generated policy, define the logic:

```php
use App\Models\Inventory;
use App\Models\User;

public function view(User $user, Inventory $inventory): bool
{
    return $user->role === 'admin' || $user->id === $inventory->user_id;
}

public function update(User $user, Inventory $inventory): bool
{
    return $user->id === $inventory->user_id;
}

public function delete(User $user, Inventory $inventory): bool
{
    return $user->id === $inventory->user_id;
}
```

### Registering policies

In `app/Providers/AuthServiceProvider.php` add:

```php
use App\Models\Inventory;
use App\Policies\InventoryPolicy;

protected $policies = [
    Inventory::class => InventoryPolicy::class,
];

public function boot(): void
{
    $this->registerPolicies();
}
```

### Authorizing actions using policies

In `InventoryController`:

```php
public function edit(Inventory $inventory)
{
    $this->authorize('update', $inventory);
    // ...
}

public function update(Request $request, Inventory $inventory)
{
    $this->authorize('update', $inventory);
    // ...
}

public function destroy(Inventory $inventory)
{
    $this->authorize('delete', $inventory);
    // ...
}
```

In Blade views use `@can` to conditionally show buttons:

```blade
@can('update', $inventory)
    {{-- Button here --}}
@endcan

@can('delete', $inventory)
    {{-- Button here --}}
@endcan
```

## Dynamic dropdowns

### 1. Database setup (migrations)

Create warehouse migration:

```bash
php artisan make:migration create_warehouses_table
```

```php
Schema::create('warehouses', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});
```

Create shelf migration:

```bash
php artisan make:migration create_shelves_table
```

```php
Schema::create('shelves', function (Blueprint $table) {
    $table->id();
    $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
    $table->string('shelf_number');
    $table->timestamps();
});
```

Note: Ensure these migrations run before the inventory migration by adjusting timestamps if necessary.

### 2. Seeders

Create `WarehouseSeeder`:

```bash
php artisan make:seeder WarehouseSeeder
```

```php
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    public function run()
    {
        Warehouse::create(['name' => 'Branch A']);
        Warehouse::create(['name' => 'Branch B']);
        Warehouse::create(['name' => 'Branch C']);
    }
}
```

Create `ShelfSeeder`:

```bash
php artisan make:seeder ShelfSeeder
```

```php
use App\Models\Shelf;
use App\Models\Warehouse;

class ShelfSeeder extends Seeder
{
    public function run()
    {
        $branchA = Warehouse::where('name', 'Branch A')->first();
        $branchB = Warehouse::where('name', 'Branch B')->first();
        $branchC = Warehouse::where('name', 'Branch C')->first();

        // Branch A shelves
        Shelf::create(['warehouse_id' => $branchA->id, 'shelf_number' => 'A1']);
        Shelf::create(['warehouse_id' => $branchA->id, 'shelf_number' => 'A2']);
        // ... add more shelves for B and C
    }
}
```

Add them to `DatabaseSeeder` and run:

```php
$this->call([
    WarehouseSeeder::class,
    ShelfSeeder::class,
]);
```

```bash
php artisan db:seed
```

### 3. Models & relationships

Warehouse:

```php
public function shelves()
{
    return $this->hasMany(Shelf::class);
}
```

Shelf:

```php
protected $fillable = ['warehouse_id', 'shelf_number'];

public function warehouse()
{
    return $this->belongsTo(Warehouse::class);
}
```

Inventory:

```php
public function warehouse()
{
    return $this->belongsTo(Warehouse::class);
}

public function shelf()
{
    return $this->belongsTo(Shelf::class);
}
```

### 4. Controller (InventoryController)

Index method (eager load relations):

```php
public function index(Request $request)
{
    $inventories = Inventory::with(['warehouse', 'shelf'])->latest()->paginate($per_page);
    // ...
}
```

Create method:

```php
public function create()
{
    $warehouses = Warehouse::select('id', 'name')->orderBy('name')->get();
    return view('inventory.create', compact('warehouses'));
}
```

Store validation example:

```php
'warehouse_id' => 'required|exists:warehouses,id',
'shelf_id' => 'required|exists:shelves,id',
```

Edit method:

```php
public function edit(Inventory $inventory)
{
    $this->authorize('update', $inventory);
    $warehouses = Warehouse::all();
    $shelves = Shelf::where('warehouse_id', $inventory->warehouse_id)->get();
    return view('inventory.edit', compact('inventory', 'warehouses', 'shelves'));
}
```

AJAX endpoint to return shelves for a warehouse:

```php
public function shelvesByWarehouse(Warehouse $warehouse)
{
    $shelves = $warehouse->shelves()
        ->select('id', 'shelf_number')
        ->orderBy('shelf_number')
        ->get();
    return response()->json($shelves);
}
```

### 5. Routes

```php
// Dynamic shelf loading based on selected warehouse
Route::get('/warehouses/{warehouse}/shelves', [InventoryController::class, 'shelvesByWarehouse'])->name('warehouses.shelves');
```

### 6. Views

In `index.blade.php` show warehouse and shelf:

```blade
<th>Warehouse</th>
<th>Shelf</th>
...
<td>{{ optional($inventory->warehouse)->name ?? '-' }}</td>
<td>{{ optional($inventory->shelf)->shelf_number ?? '-' }}</td>
```

Create view HTML example (`create.blade.php`):

```blade
<div class="mb-3">
    <label for="warehouse" class="form-label">Warehouse</label>
    <select id="warehouse" name="warehouse_id" class="form-control" required>
        <option value="">-- Select Warehouse --</option>
        @foreach($warehouses as $warehouse)
            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="shelf" class="form-label">Shelf</label>
    <select id="shelf" name="shelf_id" class="form-control" required>
        <option value="">-- Select Shelf --</option>
    </select>
</div>
```

Create view JavaScript example:

```js
document.addEventListener('DOMContentLoaded', function() {
    const warehouseSelect = document.getElementById('warehouse');
    const shelfSelect = document.getElementById('shelf');

    warehouseSelect.addEventListener('change', function() {
        const id = this.value;
        shelfSelect.innerHTML = '<option>Loading...</option>';

        fetch(`/warehouses/${id}/shelves`)
            .then(res => res.json())
            .then(data => {
                shelfSelect.innerHTML = '<option value="">-- Select Shelf --</option>';
                data.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.text = s.shelf_number;
                    shelfSelect.appendChild(opt);
                });
            });
    });
});
```

Edit view JavaScript example:

```js
document.addEventListener('DOMContentLoaded', () => {
    const w = document.getElementById('warehouse_id');
    const s = document.getElementById('shelf_id');
    const currentShelf = s?.dataset?.current; // <select ... data-current="{{$inventory->shelf_id}}">

    function loadShelves(warehouseId, selected = null) {
        s.innerHTML = '<option>Loading...</option>';
        fetch(`/warehouses/${warehouseId}/shelves`)
            .then(r => r.json())
            .then(data => {
                s.innerHTML = '<option value="">-- Select Shelf --</option>';
                data.forEach(sh => {
                    const opt = new Option(sh.shelf_number, sh.id);
                    if (sh.id == selected) opt.selected = true;
                    s.appendChild(opt);
                });
                s.disabled = false;
            }).catch(() => { s.innerHTML = '<option value="">No shelves</option>'; });
    }

    if (w && w.value) loadShelves(w.value, currentShelf);

    w?.addEventListener('change', () => {
        if (!w.value) { s.innerHTML = '<option value="">-- Select Shelf --</option>'; s.disabled = true; return; }
        loadShelves(w.value);
    });
});
```

## Queue jobs

### Queues — Overview

Queues allow time-consuming tasks (e.g., sending emails, processing images) to run in the background. They improve application performance and prevent delays for users.

### Generating a job

```bash
php artisan make:job SendWelcomeEmail
```

Example job handler (`app/Jobs/SendWelcomeEmail.php`):

```php
public function handle()
{
    \Mail::raw('Welcome to our app!', function ($message) {
        $message->to('user@example.com')->subject('Welcome');
    });
}
```

### Jobs middleware

Jobs support middleware similar to HTTP middleware:

```php
use Illuminate\Queue\Middleware\RateLimited;

public function middleware()
{
    return [new RateLimited('emails')];
}
```

### Dispatching jobs

```php
use App\Jobs\SendWelcomeEmail;

Route::get('/welcome', function () {
    SendWelcomeEmail::dispatch();
    return 'Email job dispatched!';
});
```

### Running the queue worker

```bash
php artisan queue:work
```

### Dealing with failed jobs

Create failed jobs table and migrate:

```bash
php artisan queue:failed-table
php artisan migrate
```

Retry a failed job:

```bash
php artisan queue:retry 5
```

## Notifications

### Notifications — Overview

Notifications send messages to users through multiple channels (mail, database, SMS, Slack, etc.).

### Creating a notification

```bash
php artisan make:notification InvoicePaid
```

Example methods:

```php
public function via(object $notifiable): array
{
    return ['mail', 'database'];
}

public function toMail(object $notifiable): MailMessage
{
    return (new MailMessage)
        ->line('Your invoice has been paid!')
        ->action('View Invoice', url('/invoices/1'))
        ->line('Thank you!');
}

public function toDatabase(object $notifiable): array
{
    return [
        'message' => 'Your invoice has been paid!',
        'invoice_id' => 1,
    ];
}
```

### Mail notifications

```php
use App\Notifications\InvoicePaid;
use Illuminate\Support\Facades\Notification;

public function sendMailNotification()
{
    Notification::route('mail', 'user@example.com')
        ->notify(new InvoicePaid());
    return back()->with('success', 'Notification sent!');
}
```

### Database notifications

Create notifications table and migrate:

```bash
php artisan notifications:table
php artisan migrate
```

Send and retrieve notifications:

```php
$user->notify(new InvoicePaid());
$notifications = $user->notifications;
```

### Queueing notifications

Set `QUEUE_CONNECTION=database` in `.env`, create the jobs table and migrate.
Make the notification queueable:

```php
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoicePaid extends Notification implements ShouldQueue
{
    use Queueable;
    // ...
}
```

Run the queue worker to process notifications:

```bash
php artisan queue:work
```

## HTTP requests

### HTTP client — Overview

Laravel's HTTP client (the `Http` facade) sends requests to external APIs.

### 1. Setting up the route

```php
use App\Http\Controllers\ApiController;

Route::get('/api-posts', [ApiController::class, 'getPosts'])->name('api-posts');
```

### 2. Creating the controller

```php
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function getPosts()
    {
        // Step 1: Make the API request
        $response = Http::get('https://jsonplaceholder.typicode.com/posts');

        // Step 2: Get object data
        $posts = $response->object();

        // Step 3: Pass data to the view
        return view('api-posts', ['posts' => $posts]);
    }
}
```

### 3. Creating the view

In `resources/views/api-posts.blade.php`:

```blade
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4">Posts from API</h1>
    <div class="row">
        @forelse ($posts as $post)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Title: {{ $post->title }}</h5>
                        <p class="card-text"><strong>Body:</strong> {{ $post->body }}</p>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">No posts available.</p>
        @endforelse
    </div>
</div>
@endsection
```

## Hands-on workshop: integrating templates (StartBootstrap)

1. Download the "SB Admin" template from StartBootstrap.
1. Create folders:

```text
resources/views/admin
resources/views/admin/includes
resources/views/admin/layouts
```

1. Create files:

- `resources/views/admin/includes/footer.blade.php`
- `resources/views/admin/includes/sidebar.blade.php`
- `resources/views/admin/includes/navbar.blade.php`
- `resources/views/admin/layouts/main.blade.php`

1. Add assets: move template assets (css, js, images) into `public/`.

1. Copy `index.html` content into `resources/views/admin/layouts/main.blade.php` and change asset paths to use the `asset()` helper. Example:

```html
<!-- From: -->
<script src="js/scripts.js"></script>

<!-- To: -->
<script src="{{ asset('js/scripts.js') }}"></script>
```

1. Split layout components: extract the footer, navbar, and sidebar into their own included files and use `@include('admin.includes.footer')`, etc.

1. Replace the main content area with `@yield('content')` and update inventory views to extend `admin.layouts.main` instead of `layouts.app`.

1. Update navbar and sidebar as needed (e.g., brand link `href="{{ url('/') }}"` and display `{{ Auth::user()->name }}` in the sidebar).
