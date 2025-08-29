LARAVEL TRAINING-DAY 3
Prepared by TARSOFT SDN BHD
Table of ContentsAuthorizationDynamic DropdownsQueue JobNotificationsHTTP RequestHands-on Workshop
Authorization
Introduction
Authorization in Laravel determines what a user can or cannot do.
For example:
Can this user update a post?
Can this user delete an inventory record?
Can this user view another user's profile?
Creating PoliciesGenerate a policy using Artisan:php artisan make:policy InventoryPolicy
This will create app/Policies/InventoryPolicy.php with methods for actions (view, create, update, delete).
Writing Policies
Inside the generated policy, you'll define the logic.use App\Models\Inventory;
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
Registering PoliciesIn app/Providers/AuthServiceProvider.php, add:use App\Models\Inventory;
use App\Policies\InventoryPolicy;

protected $policies = [
    Inventory::class => InventoryPolicy::class,
];

public function boot(): void
{
    $this->registerPolicies();
}
Authorizing Actions using PoliciesIn InventoryController:public function edit(Inventory $inventory)
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
In index.blade.php buttons:@can('update', $inventory)
    {{-- Button here --}}
@endcan

@can('delete', $inventory)
    {{-- Button here --}}
@endcan
Dynamic Dropdowns1. Database Setup (Migrations)Warehouse Migration:php artisan make:migration create_warehouses_table
```php
Schema::create('warehouses', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});
Shelf Migration:php artisan make:migration create_shelves_table
```php
Schema::create('shelves', function (Blueprint $table) {
    $table->id();
    $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
    $table->string('shelf_number');
    $table->timestamps();
});
Migrate: Rename migrations with earlier timestamps to run before the inventory migration.2. SeedersWarehouseSeeder:php artisan make:seeder WarehouseSeeder
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
ShelfSeeder:php artisan make:seeder ShelfSeeder
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
        // ... and so on for B and C
    }
}
DatabaseSeeder:$this->call([
    WarehouseSeeder::class,
    ShelfSeeder::class,
]);
Run the seeder:php artisan db:seed
3. Models & RelationshipsWarehouse:public function shelves()
{
    return $this->hasMany(Shelf::class);
}
Shelf:protected $fillable = ['warehouse_id', 'shelf_number'];

public function warehouse()
{
    return $this->belongsTo(Warehouse::class);
}
Inventory:public function warehouse()
{
    return $this->belongsTo(Warehouse::class);
}

public function shelf()
{
    return $this->belongsTo(Shelf::class);
}
4. Controller (InventoryController)index:public function index(Request $request)
{
    $inventories = Inventory::with(['warehouse', 'shelf'])->latest()->paginate($per_page);
    // ...
}
create:public function create()
{
    $warehouses = Warehouse::select('id', 'name')->orderBy('name')->get();
    return view('inventory.create', compact('warehouses'));
}
store:'warehouse_id' => 'required|exists:warehouses,id',
'shelf_id' => 'required|exists:shelves,id',
// ...
Inventory::create([...]);
edit:public function edit(Inventory $inventory)
{
    $this->authorize('update', $inventory);
    $warehouses = Warehouse::all();
    $shelves = Shelf::where('warehouse_id', $inventory->warehouse_id)->get();
    return view('inventory.edit', compact('inventory', 'warehouses', 'shelves'));
}
AJAX for Shelves:public function shelvesByWarehouse(Warehouse $warehouse)
{
    $shelves = $warehouse->shelves()
        ->select('id', 'shelf_number')
        ->orderBy('shelf_number')
        ->get();
    return response()->json($shelves);
}
5. Routes// Dynamic shelf loading based on selected warehouse
Route::get('/warehouses/{warehouse}/shelves', [InventoryController::class, 'shelvesByWarehouse'])->name('warehouses.shelves');
6. Viewsindex.blade.php:<th>Warehouse</th>
<th>Shelf</th>
...
<td>{{ optional($inventory->warehouse)->name ?? '-' }}</td>
<td>{{ optional($inventory->shelf)->shelf_number ?? '-' }}</td>
create.blade.php (HTML):<div class="mb-3">
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
create.blade.php (JavaScript):<script>
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
</script>
edit.blade.php (JavaScript):<script>
document.addEventListener('DOMContentLoaded', () => {
    const w = document.getElementById('warehouse_id');
    const s = document.getElementById('shelf_id');
    const currentShelf = s.dataset.current; // <select ... data-current="{{$inventory->shelf_id}}">

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

    if (w.value) loadShelves(w.value, currentShelf);

    w.addEventListener('change', () => {
        if (!w.value) { s.innerHTML = '<option value="">-- Select Shelf --</option>'; s.disabled = true; return; }
        loadShelves(w.value);
    });
});
</script>
Queue JobIntroductionWhat is a Queue? Queues allow time-consuming tasks (e.g., sending emails, processing images) to run in the background.Why use Queues? Improves application performance and prevents delays for users.Generating a Jobphp artisan make:job SendWelcomeEmail
Inside app/Jobs/SendWelcomeEmail.php:public function handle()
{
    \Mail::raw('Welcome to our app!', function ($message) {
        $message->to('user@example.com')->subject('Welcome');
    });
}
Jobs MiddlewareSimilar to HTTP middleware, but for queued jobs.use Illuminate\Queue\Middleware\RateLimited;

public function middleware()
{
    return [new RateLimited('emails')];
}
Dispatching JobsTo add a job to the queue, you dispatch it.use App\Jobs\SendWelcomeEmail;

Route::get('/welcome', function () {
    SendWelcomeEmail::dispatch();
    return 'Email job dispatched!';
});
Running the Queue WorkerThe queue worker listens to the queue and runs jobs.php artisan queue:work
Dealing with Failed JobsJobs may fail. Failed jobs are stored in the failed_jobs table.php artisan queue:failed-table
php artisan migrate
Retry a failed job:php artisan queue:retry 5
NotificationsIntroductionLaravel Notifications send messages to users through multiple channels (mail, database, SMS, Slack, etc.).Creating a Notificationphp artisan make:notification InvoicePaid
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
Mail Notificationsuse App\Notifications\InvoicePaid;
use Illuminate\Support\Facades\Notification;

public function sendMailNotification()
{
    Notification::route('mail', 'user@example.com')
        ->notify(new InvoicePaid());
    return back()->with('success', 'Notification sent!');
}
Database NotificationsFirst, create the notifications table:php artisan notifications:table
php artisan migrate
Send: $user->notify(new InvoicePaid());Retrieve: $notifications = $user->notifications;Queueing NotificationsSet up queue driver in .env (e.g., QUEUE_CONNECTION=database).Run php artisan queue:table and php artisan migrate.Make the notification queueable:class InvoicePaid extends Notification implements ShouldQueue
{
    use Queueable;
    // ...
}
Dispatch the notification as usual.Run the queue worker: php artisan queue:work.HTTP RequestIntroductionAPIs (Application Programming Interfaces) allow applications to communicate. Laravel's HTTP Client sends requests to external APIs.1. Setting Up the Routeuse App\Http\Controllers\ApiController;

Route::get('/api-posts', [ApiController::class, 'getPosts'])->name('api-posts');
2. Creating the Controlleruse Illuminate\Support\Facades\Http;

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
3. Creating the ViewInside resources/views/api-posts.blade.php:@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4">Posts from API</h1>
    <div class="row">
        @forelse ($posts as $post)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary">
                            Title: {{ $post->title }}
                        </h5>
                        <p class="card-text">
                            <strong>Body:</strong> {{ $post->body }}
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">No posts available.</p>
        @endforelse
    </div>
</div>
@endsection
Hands-on Workshop: Integrating Templates (StartBootstrap)
Download Template: Get the "SB Admin" template from StartBootstrap.
Create Folders:resources/views/adminresources/views/admin/includesresources/views/admin/layouts
Create Files:includes/footer.blade.phpincludes/sidebar.blade.phpincludes/navbar.blade.phplayouts/main.blade.php
Add Assets:Move all template assets (css, js, etc.) to the public directory.Copy index.html content to layouts/main.blade.php.Change asset paths using the asset() helper:From: <script src="js/scripts.js"></script>To: <script src="{!! asset('js/scripts.js') !!}"></script>
Separate Layout Components:Cut the <footer> section from main.blade.php and paste it into footer.blade.php. Replace it with @include('admin.includes.footer').Do the same for the top navbar (<nav class="sb-topnav ...">) into navbar.blade.php and include it with @include('admin.includes.navbar').Do the same for the sidebar (<div id="layoutSidenav_nav">...) into sidebar.blade.php and include it with @include('admin.includes.sidebar').
Define Content Area:In main.blade.php, find the <main> tag and replace its content with @yield('content').Update Inventory Views:In all inventory blade files (create, edit, show, index), change @extends('layouts.app') to @extends('admin.layouts.main').
Update Navbar and Sidebar:Navbar: Change the brand link to href="{{ url('/') }}".Sidebar: Display the logged-in user's name: {{ Auth::user()->name }}.