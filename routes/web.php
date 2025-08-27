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

// Vehicle routes

// Route to list all vehicles
Route::get('/vehicles', [App\Http\Controllers\VehicleController::class, 'index'])->name('vehicles.index');

// Add other routes below. Controller methods should be defined inside their classes, not in this file.
