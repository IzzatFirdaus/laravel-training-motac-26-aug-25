<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    // Use the MYDS pagination view as the application's default pagination template.
        Paginator::defaultView('vendor.pagination.myds');
        Paginator::defaultSimpleView('vendor.pagination.simple-myds');
    }
}
