<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\User;
use App\Models\Vehicle;
use Throwable;
use App\Models\Inventory;
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
            $inventoriesCount = Inventory::count();
        } catch (Throwable $e) {
            $inventoriesCount = 0;
        }

        try {
            $vehiclesCount = Vehicle::count();
        } catch (Throwable $e) {
            $vehiclesCount = 0;
        }

        try {
            $usersCount = User::count();
        } catch (Throwable $e) {
            $usersCount = 0;
        }

        try {
            $applicationsCount = Application::count();
        } catch (Throwable $e) {
            $applicationsCount = 0;
        }

        // Prefer passing a small slice of notifications to the view to avoid querying inside the navbar
        $unread = collect();
        $unreadCount = 0;
        if (!Auth::check()){

        return view('home', compact('inventoriesCount', 'vehiclesCount', 'usersCount', 'applicationsCount', 'unread', 'unreadCount'));
    }
            try {
                $unread = Auth::user()->unreadNotifications->take(10);
                $unreadCount = $unread->count();
            } catch (Throwable $e) {
                $unread = collect();
                $unreadCount = 0;
            }


        return view('home', compact('inventoriesCount', 'vehiclesCount', 'usersCount', 'applicationsCount', 'unread', 'unreadCount'));
    }
}
