<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of vehicles.
     */
    public function index(): View
    {
        // Use Eloquent with eager-loading so views can reference the owner relation
        $vehicles = Vehicle::with('owner')->orderBy('created_at', 'desc')->paginate(15);

        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Remove the specified vehicle from storage.
     *
     * @param int $vehicleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($vehicleId): RedirectResponse
    {
    $vehicle = Vehicle::findOrFail($vehicleId);
    $vehicle->delete();

    return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted.');
    }
    /**
     * Show the form for creating a new vehicle.
     */
    public function create(): View
    {
        return view('vehicles.create');
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'user_id' => ['nullable', 'exists:users,id'],
            'qty' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);
        $vehicle = Vehicle::create([
            'name' => $data['name'],
            'user_id' => $data['user_id'] ?? null,
            'qty' => $data['qty'],
            'price' => $data['price'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully.');
    }

    /**
     * Display the specified vehicle.
     */
    public function show($vehicleId): View
    {
    $vehicle = Vehicle::with('owner', 'inventories')->findOrFail($vehicleId);

    return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle.
     *
     * @param int $vehicleId
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($vehicleId): View
    {
    // Fetch users to populate owner dropdown
    $users = DB::table('users')->select('id','name')->orderBy('name')->get();

    $vehicle = Vehicle::findOrFail($vehicleId);

    return view('vehicles.edit', compact('vehicle', 'users'));
    }

    /**
     * Update the specified vehicle in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $vehicleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $vehicleId): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'qty' => ['nullable', 'integer', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $vehicle = Vehicle::findOrFail($vehicleId);

        $vehicle->fill([
            'name' => $data['name'] ?? $vehicle->name,
            'qty' => $data['qty'] ?? $vehicle->qty,
            'price' => $data['price'] ?? $vehicle->price,
            'description' => $data['description'] ?? $vehicle->description,
        ]);

        if (! empty($data['user_id'])) {
            $vehicle->user_id = $data['user_id'];
        }

        $vehicle->save();

        return redirect()->route('vehicles.show', $vehicleId)->with('status', 'Vehicle updated.');
    }
}
