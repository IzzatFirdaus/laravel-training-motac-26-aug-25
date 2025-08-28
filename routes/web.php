<?php

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
Auth::routes();

// Route for the home page after login
Route::get('/home', [HomeController::class, 'index'])->name('home');

// User routes

// Route to list all users
Route::get('/users', [UserController::class, 'index'])->name('users.index');

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

// Route to show a single inventory item
Route::get('/inventories/{inventory}', [InventoryController::class, 'show'])->name('inventories.show');

// Route to edit a single inventory item (use conventional /{id}/edit path to avoid name collision)
Route::get('/inventories/{inventory}/edit', [InventoryController::class, 'edit'])->name('inventories.edit');

// Route to update an inventory item (using POST instead of PATCH for environments that prefer POST)
Route::post('/inventories/{inventory}', [InventoryController::class, 'update'])->name('inventories.update');

// Route to destroy an inventory item (using POST to a /destroy endpoint)
Route::post('/inventories/{inventory}/destroy', [InventoryController::class, 'destroy'])->name('inventories.destroy');

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
Route::redirect('/vehicles/show', '/vehicles');
Route::redirect('/vehicles/show/', '/vehicles');

// Backwards compatibility: redirect GET /vehicles/store to the create form
Route::get('/vehicles/store', function () {
    return redirect()->route('vehicles.create');
});

// Route to edit a single vehicle item (use conventional /{id}/edit path to avoid name collision)
Route::get('/vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');

// Route to update a vehicle item (using POST instead of PATCH for environments that prefer POST)
Route::post('/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');

// Route to destroy a vehicle (using POST to a /destroy endpoint)
Route::post('/vehicles/{vehicle}/destroy', [VehicleController::class, 'destroy'])->name('vehicles.destroy');

// Vehicle detail route
Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');
// Add other routes below. Controller methods should be defined inside their classes, not in this file.
