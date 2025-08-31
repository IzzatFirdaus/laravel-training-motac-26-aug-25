<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Compute dashboard counts in the controller to avoid queries in views
        $inventoriesCount = 0;
        $vehiclesCount = 0;
        $usersCount = 0;
        $applicationsCount = 0;

        try {
            $inventoriesCount = \App\Models\Inventory::count();
        } catch (\Throwable $e) {
            $inventoriesCount = 0;
        }

        try {
            $vehiclesCount = \App\Models\Vehicle::count();
        } catch (\Throwable $e) {
            $vehiclesCount = 0;
        }

        try {
            $usersCount = \App\Models\User::count();
        } catch (\Throwable $e) {
            $usersCount = 0;
        }

        try {
            $applicationsCount = \App\Models\Application::count();
        } catch (\Throwable $e) {
            $applicationsCount = 0;
        }

        // Prefer passing a small slice of notifications to the view to avoid querying inside the navbar
        $unread = collect();
        $unreadCount = 0;
        if (!\Illuminate\Support\Facades\Auth::check()){

        return view('home', compact('inventoriesCount', 'vehiclesCount', 'usersCount', 'applicationsCount', 'unread', 'unreadCount'));
    } 
            try {
                $unread = \Illuminate\Support\Facades\Auth::user()->unreadNotifications->take(10);
                $unreadCount = $unread->count();
            } catch (\Throwable $e) {
                $unread = collect();
                $unreadCount = 0;
            }
        

        return view('home', compact('inventoriesCount', 'vehiclesCount', 'usersCount', 'applicationsCount', 'unread', 'unreadCount'));
    }
}
