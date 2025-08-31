<?php

use App\Http\Controllers\APIPostController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DeletedInventoryController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Default route for the welcome page
Route::get('/', function () {
    return view('welcome');
});

// Backwards-compatibility route: allow /welcome to serve the same welcome view
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Authentication routes (login, register, etc.)
// Enable email verification routes so views that call route('verification.resend') resolve.
Auth::routes(['verify' => true]);

// Email verification routes are registered by Auth::routes(['verify' => true]) above.

// Route for the home page after login
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Locale switcher for header language toggle
Route::get('/locale/{locale}', function (string $locale) {
    $allowed = ['en', 'ms'];
    if (! in_array($locale, $allowed, true)) {
        $locale = config('app.locale');
    }
    session(['locale' => $locale]);

    return back();
})->name('locale.switch');

// User routes

// Route to list all users
Route::get('/users', [UserController::class, 'index'])->name('users.index');

// JSON endpoint: search users by q for autocomplete/select lists
Route::get('/users/search', [UserController::class, 'search'])->name('users.search');

// Route to show the form for creating a new user
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');

// Route to save a new user to the database
Route::post('/users', [UserController::class, 'store'])->name('users.store');

// Route to show a single user
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

// Route to edit a single user
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

// Route to update a user
Route::post('/users/{user}', [UserController::class, 'update'])->name('users.update');

// Route to destroy a user
Route::post('/users/{user}/destroy', [UserController::class, 'destroy'])->name('users.destroy');

// Inventory routes

// Route to list all inventories
Route::get('/inventories', [InventoryController::class, 'index'])->name('inventories.index');

// Route to show the form for creating a new inventory
Route::get('/inventories/create', [InventoryController::class, 'create'])->name('inventories.create');

// Route to save a new inventory to the database
Route::post('/inventories', [InventoryController::class, 'store'])->name('inventories.store');

// Backwards compatibility: Redirect GET /inventories/store to the create form
Route::get('/inventories/store', function () {
    return redirect()->route('inventories.create');
});

// Route to show a single inventory item (limit {inventory} to numeric IDs to avoid colliding with static paths)
Route::get('/inventories/{inventory}', [InventoryController::class, 'show'])->where('inventory', '\\d+')->name('inventories.show');

// Route to edit a single inventory item (use conventional /{id}/edit path to avoid name collision)
Route::get('/inventories/{inventory}/edit', [InventoryController::class, 'edit'])->where('inventory', '\\d+')->name('inventories.edit');

// Route to update an inventory item (using POST instead of PATCH for environments that prefer POST)
Route::post('/inventories/{inventory}', [InventoryController::class, 'update'])->where('inventory', '\\d+')->name('inventories.update');

// Route to destroy an inventory item (using POST to a /destroy endpoint)
Route::post('/inventories/{inventory}/destroy', [InventoryController::class, 'destroy'])->where('inventory', '\\d+')->name('inventories.destroy');

// Deleted Inventory routes (for soft-deleted items)

// Route to list all soft-deleted inventories
Route::get('/inventories/deleted', [DeletedInventoryController::class, 'index'])->name('inventories.deleted.index');

// Route to restore a soft-deleted inventory
Route::post('/inventories/{inventory}/restore', [DeletedInventoryController::class, 'restore'])->where('inventory', '\\d+')
    ->name('inventories.restore')
    ->withTrashed();

// Route to permanently delete a soft-deleted inventory
Route::post('/inventories/{inventory}/force-delete', [DeletedInventoryController::class, 'forceDelete'])->where('inventory', '\\d+')
    ->name('inventories.force-delete')
    ->withTrashed();

// Backwards compatibility: visiting /inventories/show without an id will redirect to the index
Route::get('/inventories/show', function () {
    return redirect()->route('inventories.index');
});

// Vehicle routes

// Route to list all vehicles
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');

Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('vehicles.create');

// Correct store route: use POST to create a new vehicle
Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
// Redirect legacy path `/vehicles/show` (no id) to the vehicles index to avoid 404s
// Redirect legacy path `/vehicles/show` (no id) to the vehicles index to avoid 404s
Route::redirect('/vehicles/show', '/vehicles');

// Backwards compatibility: redirect GET /vehicles/store to the create form
Route::get('/vehicles/store', function () {
    return redirect()->route('vehicles.create');
});

// Route to edit a single vehicle item (use conventional /{id}/edit path to avoid name collision)
Route::get('/vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])->where('number', 'vehicle')->name('vehicles.edit');

// Route to update a vehicle item (using POST instead of PATCH for environments that prefer POST)
Route::post('/vehicles/{vehicle}', [VehicleController::class, 'update'])->where('number', 'vehicle')->name('vehicles.update');

// Route to destroy a vehicle (using POST to a /destroy endpoint)
Route::post('/vehicles/{vehicle}/destroy', [VehicleController::class, 'destroy'])->where('number', 'vehicle')->name('vehicles.destroy');

// Vehicle detail route
Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->where('number', 'vehicle')->name('vehicles.show');
// JSON endpoint: return vehicles belonging to an inventory (for dynamic selects)
Route::get('/inventories/{inventory}/vehicles', [VehicleController::class, 'byInventory'])->name('inventories.vehicles');
// JSON endpoint: return shelves for a warehouse (dynamic dropdown)
Route::get('/warehouses/{warehouse}/shelves', [InventoryController::class, 'shelvesByWarehouse'])->name('warehouses.shelves');
// JSON endpoint: return warehouses for dynamic selects
Route::get('/warehouses', [InventoryController::class, 'warehouses'])->name('warehouses.index');
// Add other routes below. Controller methods should be defined inside their classes, not in this file.

// Excel: Inventory export/import/preview
Route::get('/excel/inventories/export', [ExcelController::class, 'exportInventory'])->name('excel.inventory.export');
Route::get('/excel/inventories/import', [ExcelController::class, 'importInventoryForm'])->name('excel.inventory.form');
Route::post('/excel/inventories/preview', [ExcelController::class, 'previewInventory'])->name('excel.inventory.preview');
Route::post('/excel/inventories/import', [ExcelController::class, 'importInventory'])->name('excel.inventory.import');

// Application routes
// List all applications
Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');

// Show form to create a new application
Route::get('/applications/create', [ApplicationController::class, 'create'])->name('applications.create');

// Store a new application
Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');

// Show single application
Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');

// Edit application
Route::get('/applications/{application}/edit', [ApplicationController::class, 'edit'])->name('applications.edit');

// Update application (using POST to match project convention)
Route::post('/applications/{application}', [ApplicationController::class, 'update'])->name('applications.update');

// Destroy application (using POST to a /destroy endpoint)
Route::post('/applications/{application}/destroy', [ApplicationController::class, 'destroy'])->name('applications.destroy');

// API routes
// Simple API POST endpoint for demo clients
Route::post('/api/posts', [APIPostController::class, 'store'])->name('api.posts.store');

// Mail route: trigger notification email for an inventory (API)
Route::post('/api/mail/inventories/{inventory}/notify', [InventoryController::class, 'notifyMail'])->name('api.mail.inventories.notify');

// Notifications: index and detail (mark-as-read)
Route::get('/notifications', [App\Http\Controllers\NotificationsController::class, 'index'])->name('notifications.index');
Route::get('/notifications/{notification}', [App\Http\Controllers\NotificationsController::class, 'show'])->name('notifications.show');
// Mark a single notification as read (POST)
Route::post('/notifications/{notification}/read', [App\Http\Controllers\NotificationsController::class, 'read'])->name('notifications.read');
// Mark a single notification as unread (PUT)
Route::put('/notifications/{notification}/unread', [App\Http\Controllers\NotificationsController::class, 'unread'])->name('notifications.unread');
// Mark all notifications as read (POST)
Route::post('/notifications/read-all', [App\Http\Controllers\NotificationsController::class, 'readAll'])->name('notifications.readAll');

// External posts listing (consumes jsonplaceholder.typicode.com)
Route::get('/posts', [APIPostController::class, 'index'])->name('posts.index');
