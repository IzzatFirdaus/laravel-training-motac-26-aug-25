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
    /**
     * Show the form for creating a new vehicle.
     */
    public function create()
    {
        return view('vehicles.create');
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'user_id' => ['nullable', 'exists:users,id'],
            'qty' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        Vehicle::create($data);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully.');
    }

    /**
     * Display the specified vehicle.
     */
    public function show(Vehicle $vehicle)
    {
    // Ensure owner relationship is loaded to avoid extra queries in the view
    $vehicle->loadMissing('owner');

    return view('vehicles.show', compact('vehicle'));
    }
}
