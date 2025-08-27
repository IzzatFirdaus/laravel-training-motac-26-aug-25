<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Default route for the welcome page
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes (login, register, etc.)
Auth::routes();

// Route for the home page after login
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Inventory routes

// Route to list all inventories
Route::get('/inventories', [App\Http\Controllers\InventoryController::class, 'index'])->name('inventories.index');

// Route to show the form for creating a new inventory
Route::get('/inventories/create', [App\Http\Controllers\InventoryController::class, 'create'])->name('inventories.create');

// Route to save a new inventory to the database
Route::post('/inventories', [App\Http\Controllers\InventoryController::class, 'store'])->name('inventories.store');

// Backwards compatibility: Redirect GET /inventories/store to the create form
Route::get('/inventories/store', function () {
    return redirect()->route('inventories.create');
});

// Route to show a single inventory item
Route::get('/inventories/{inventory}', [App\Http\Controllers\InventoryController::class, 'show'])->name('inventories.show');

// Route to edit a single inventory item (use conventional /{id}/edit path to avoid name collision)
Route::get('/inventories/{inventory}/edit', [App\Http\Controllers\InventoryController::class, 'edit'])->name('inventories.edit');

// Route to update an inventory item (using POST instead of PATCH for environments that prefer POST)
Route::post('/inventories/{inventory}', [App\Http\Controllers\InventoryController::class, 'update'])->name('inventories.update');

// Route to destroy an inventory item (using POST to a /destroy endpoint)
Route::post('/inventories/{inventory}/destroy', [App\Http\Controllers\InventoryController::class, 'destroy'])->name('inventories.destroy');

// Backwards compatibility: visiting /inventories/show without an id will redirect to the index
Route::get('/inventories/show', function () {
    return redirect()->route('inventories.index');
});

// Vehicle routes

// Route to list all vehicles
Route::get('/vehicles', [App\Http\Controllers\VehicleController::class, 'index'])->name('vehicles.index');

Route::get('/vehicles/create', [App\Http\Controllers\VehicleController::class, 'create'])->name('vehicles.create');

// Correct store route: use POST to create a new vehicle
Route::post('/vehicles', [App\Http\Controllers\VehicleController::class, 'store'])->name('vehicles.store');
// Redirect legacy path `/vehicles/show` (no id) to the vehicles index to avoid 404s
Route::redirect('/vehicles/show', '/vehicles');
Route::redirect('/vehicles/show/', '/vehicles');

// Backwards compatibility: redirect GET /vehicles/store to the create form
Route::get('/vehicles/store', function () {
    return redirect()->route('vehicles.create');
});

// Route to edit a single vehicle item (use conventional /{id}/edit path to avoid name collision)
Route::get('/vehicles/{vehicle}/edit', [App\Http\Controllers\VehicleController::class, 'edit'])->name('vehicles.edit');

// Route to update a vehicle item (using POST instead of PATCH for environments that prefer POST)
Route::post('/vehicles/{vehicle}', [App\Http\Controllers\VehicleController::class, 'update'])->name('vehicles.update');


// Route to destroy a vehicle (using POST to a /destroy endpoint)
Route::post('/vehicles/{vehicle}/destroy', [App\Http\Controllers\VehicleController::class, 'destroy'])->name('vehicles.destroy');

// Vehicle detail route
Route::get('/vehicles/{vehicle}', [App\Http\Controllers\VehicleController::class, 'show'])->name('vehicles.show');
// Add other routes below. Controller methods should be defined inside their classes, not in this file.
