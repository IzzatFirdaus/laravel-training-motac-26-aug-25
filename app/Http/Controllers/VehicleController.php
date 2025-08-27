<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    /**
     * Display a listing of vehicles.
     */
    public function index()
    {
        // Query vehicles as plain objects (POPO) with owner name and paginate per MYDS guidance
        $vehicles = DB::table('vehicles')
            ->leftJoin('users', 'vehicles.user_id', '=', 'users.id')
            ->select('vehicles.*', 'users.name as owner_name')
            ->orderBy('vehicles.created_at', 'desc')
            ->paginate(15);

        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Remove the specified vehicle from storage.
     *
     * @param int $vehicleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($vehicleId)
    {
        DB::table('vehicles')->where('id', $vehicleId)->delete();
        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted.');
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

        $payload = [
            'name' => $data['name'],
            'user_id' => $data['user_id'] ?? null,
            'qty' => $data['qty'],
            'price' => $data['price'],
            'description' => $data['description'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('vehicles')->insert($payload);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully.');
    }

    /**
     * Display the specified vehicle.
     */
    public function show($vehicleId)
    {
        $vehicle = DB::table('vehicles')->where('id', $vehicleId)->first();
        if (! $vehicle) {
            abort(404);
        }

        $vehicle->owner = $vehicle->user_id ? DB::table('users')->select('id','name')->where('id', $vehicle->user_id)->first() : null;

        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle.
     *
     * @param int $vehicleId
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($vehicleId): \Illuminate\Contracts\View\View
    {
        // Fetch users to populate owner dropdown
        $users = DB::table('users')->select('id','name')->orderBy('name')->get();

        $vehicle = DB::table('vehicles')->where('id', $vehicleId)->first();
        if (! $vehicle) {
            abort(404);
        }

        return view('vehicles.edit', compact('vehicle', 'users'));
    }

    /**
     * Update the specified vehicle in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $vehicleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $vehicleId): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'qty' => ['nullable', 'integer', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $existing = DB::table('vehicles')->where('id', $vehicleId)->first();
        if (! $existing) {
            abort(404);
        }

        $payload = [
            'name' => $data['name'] ?? $existing->name,
            'qty' => $data['qty'] ?? $existing->qty,
            'price' => $data['price'] ?? $existing->price,
            'description' => $data['description'] ?? $existing->description,
            'updated_at' => now(),
        ];

        if (! empty($data['user_id'])) {
            $payload['user_id'] = $data['user_id'];
        }

        DB::table('vehicles')->where('id', $vehicleId)->update($payload);

        return redirect()->route('vehicles.show', $vehicleId)->with('status', 'Vehicle updated.');
    }
}
