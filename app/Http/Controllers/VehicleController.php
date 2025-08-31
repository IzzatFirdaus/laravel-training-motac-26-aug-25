<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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
        $query = Vehicle::with('owner')
            ->orderBy('created_at', 'desc')
            ->search(request()->string('search'))
            ->ownedBy(request()->input('owner_id'));

        // Allow any authenticated user to view the listing; per-item policies restrict actions like view/update/delete.

        $perPage = (int) request()->input('per_page', 10);
        $perPage = max(1, min($perPage, 100));
        $vehicles = $query->paginate($perPage)->appends(request()->only(['search', 'per_page', 'owner_id']));

        $users = User::query()->select('id', 'name')->orderBy('name')->get();

        return view('vehicles.index', [
            'vehicles' => $vehicles,
            'users' => $users,
        ]);
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

        return redirect()->route('vehicles.index')->with('success', __('ui.vehicles.deleted'));
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create(): View
    {
        $this->authorize('create', Vehicle::class);

        // Eagerly load users for owner assignment dropdown (admins only in UI)
        $users = User::query()->select('id', 'name')->orderBy('name')->get();

        return view('vehicles.create', ['users' => $users]);
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(StoreVehicleRequest $request): RedirectResponse
    {
        $this->authorize('create', Vehicle::class);
        $data = $request->validated();

        // Only admins may set arbitrary owner. Regular users will own the vehicle they create.
        if (! Auth::check()) {
            $data['user_id'] = null;
        } elseif (! Auth::user()?->hasRole('admin')) {
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

        return redirect()->route('vehicles.index')->with('status', __('ui.vehicles.created'));
    }

    /**
     * Display the specified vehicle.
     */
    public function show($vehicleId): View
    {
        $vehicle = Vehicle::with('owner', 'inventories')->findOrFail($vehicleId);

        $this->authorize('view', $vehicle);

        return view('vehicles.show', ['vehicle' => $vehicle]);
    }

    /**
     * Return JSON list of vehicles for a given inventory id.
     * This is used by client-side code to populate dynamic selects.
     */
    public function byInventory($inventoryId)
    {
        // Eager-load minimal fields and guard with auth middleware
        $vehicles = Vehicle::query()
            ->whereHas('inventories', function ($q) use ($inventoryId) {
                $q->where('inventories.id', $inventoryId);
            })
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($vehicles);
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

        return view('vehicles.edit', [
            'vehicle' => $vehicle,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified vehicle in storage.
     *
     * @param  int  $vehicleId
     */
    public function update(UpdateVehicleRequest $request, $vehicleId): RedirectResponse
    {
        $data = $request->validated();

        $vehicle = Vehicle::findOrFail($vehicleId);
        $this->authorize('update', $vehicle);

        $vehicle->fill([
            'name' => $data['name'] ?? $vehicle->name,
            'qty' => $data['qty'] ?? $vehicle->qty,
            'price' => $data['price'] ?? $vehicle->price,
            'description' => $data['description'] ?? $vehicle->description,
        ]);

        // Only admins may reassign ownership
    if (! empty($data['user_id']) && Auth::user()?->hasRole('admin')) {
            $vehicle->user_id = $data['user_id'];
        }

        $vehicle->save();

        return redirect()->route('vehicles.show', $vehicleId)->with('status', __('ui.vehicles.updated_message'));
    }
}
