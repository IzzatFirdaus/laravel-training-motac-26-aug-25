<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share simple policy booleans to avoid referencing models in Blade templates.
        view()->composer('*', function ($view) {
            // Use Gate::allows which respects the current auth user and avoids calling methods on possibly null user instances.
            $view->with('canCreateInventory', Gate::allows('create', \App\Models\Inventory::class));
            $view->with('canViewAnyInventory', Gate::allows('viewAny', \App\Models\Inventory::class));
            $view->with('canCreateVehicle', Gate::allows('create', \App\Models\Vehicle::class));
            $view->with('canCreateUser', Gate::allows('create', \App\Models\User::class));
            $view->with('canCreateApplication', Gate::allows('create', \App\Models\Application::class));
        });
    }
}
