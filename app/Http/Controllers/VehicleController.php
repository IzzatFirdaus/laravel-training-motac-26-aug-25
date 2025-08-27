<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    /**
     * Display a listing of vehicles.
     */
    public function index()
    {
        // query all vehicles from the table 'vehicles' using model
        $vehicles = Vehicle::all();

        // return to view with $vehicles (resources/views/vehicles/index.blade.php)
        return view('vehicles.index', compact('vehicles'));
    }
}
