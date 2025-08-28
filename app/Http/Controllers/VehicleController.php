<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function __construct()
    {
        // Require authentication for vehicles; per-item authorization handled by policy.
        $this->middleware('auth');
        // Creating vehicles is admin-only per project tests/requirements
        $this->middleware('role:admin')->only(['create', 'store']);
    }

    /**
     * Display a listing of vehicles.
     */
    public function index(): View
    {
        // Use Eloquent with eager-loading so views can reference the owner relation
        $query = Vehicle::with('owner')->orderBy('created_at', 'desc');

        // Allow any authenticated user to view the listing; per-item policies restrict actions like view/update/delete.

        $vehicles = $query->paginate(15);

        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Remove the specified vehicle from storage.
     *
     * @param  int  $vehicleId
     */
    public function destroy($vehicleId): RedirectResponse
    {
        $vehicle = Vehicle::findOrFail($vehicleId);

        // Authorize deletion via policy (owner or admin)
        $this->authorize('delete', $vehicle);

        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted.');
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create(): View
    {
        $this->authorize('create', Vehicle::class);

        // Eagerly load users for owner assignment dropdown (admins only in UI)
        $users = User::query()->select('id', 'name')->orderBy('name')->get();

        return view('vehicles.create', compact('users'));
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Vehicle::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'user_id' => ['nullable', 'exists:users,id'],
            'qty' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        // Only admins may set arbitrary owner. Regular users will own the vehicle they create.
        if (! Auth::check()) {
            $data['user_id'] = null;
        } elseif (! Auth::user()->hasRole('admin')) {
            $data['user_id'] = Auth::id();
        } else {
            $data['user_id'] = $data['user_id'] ?? null;
        }

        $vehicle = Vehicle::create([
            'name' => $data['name'],
            'user_id' => $data['user_id'],
            'qty' => $data['qty'],
            'price' => $data['price'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('vehicles.index')->with('status', 'Kenderaan berjaya dicipta.');
    }

    /**
     * Display the specified vehicle.
     */
    public function show($vehicleId): View
    {
        $vehicle = Vehicle::with('owner', 'inventories')->findOrFail($vehicleId);

        $this->authorize('view', $vehicle);

        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle.
     *
     * @param  int  $vehicleId
     */
    public function edit($vehicleId): View
    {
        // Fetch users to populate owner dropdown
        $users = User::query()->select('id', 'name')->orderBy('name')->get();

        $vehicle = Vehicle::findOrFail($vehicleId);

        $this->authorize('update', $vehicle);

        return view('vehicles.edit', compact('vehicle', 'users'));
    }

    /**
     * Update the specified vehicle in storage.
     *
     * @param  int  $vehicleId
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
        $this->authorize('update', $vehicle);

        $vehicle->fill([
            'name' => $data['name'] ?? $vehicle->name,
            'qty' => $data['qty'] ?? $vehicle->qty,
            'price' => $data['price'] ?? $vehicle->price,
            'description' => $data['description'] ?? $vehicle->description,
        ]);

        // Only admins may reassign ownership
        if (! empty($data['user_id']) && Auth::user()->hasRole('admin')) {
            $vehicle->user_id = $data['user_id'];
        }

        $vehicle->save();

        return redirect()->route('vehicles.show', $vehicleId)->with('status', 'Vehicle updated.');
    }
}
