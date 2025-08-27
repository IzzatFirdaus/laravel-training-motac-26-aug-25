<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventories.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        // Fetch inventories as plain objects (POPO) and paginate
        $inventories = DB::table('inventories')
            ->leftJoin('users', 'inventories.user_id', '=', 'users.id')
            ->select('inventories.*', 'users.name as owner_name')
            ->orderBy('inventories.created_at', 'desc')
            ->paginate(15);

        // Return the view with the inventories data (LengthAwarePaginator of stdClass)
        return view('inventories.index', compact('inventories'));
    }

    /**
     * Show the form for creating a new inventory item.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        // Fetch all users ordered by name to populate the dropdown in the form as plain objects
        $users = DB::table('users')->select('id', 'name')->orderBy('name')->get();

        return view('inventories.create', compact('users'));
    }

    /**
     * Store a newly created inventory item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Validate the incoming request data
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'], // Ensure user_id exists in users table
            'name' => ['required', 'string', 'max:255'], // Name is required and must be a string
            'qty' => ['required', 'integer', 'min:0'], // Quantity is required and must be a non-negative integer
            'price' => ['required', 'numeric', 'min:0'], // Price is required and must be a non-negative number
            'description' => ['nullable', 'string'], // Description is optional
        ]);
        // Prepare insert payload
        $payload = [
            'name' => $data['name'],
            'qty' => $data['qty'],
            'price' => $data['price'],
            'description' => $data['description'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (!empty($data['user_id'])) {
            $payload['user_id'] = $data['user_id'];
        } elseif (Auth::check()) {
            $payload['user_id'] = Auth::id();
        } else {
            $payload['user_id'] = null;
        }

        $id = DB::table('inventories')->insertGetId($payload);

        return redirect()->route('inventories.show', $id)->with('status', 'Inventory created.');
    }

    /**
     * Display a single inventory item.
     *
     * @param int $inventoryId
     * @return \Illuminate\Contracts\View\View
     */
    public function show($inventoryId): \Illuminate\Contracts\View\View
    {
        // Fetch inventory as a POPO and attach owner info if present
        $inventory = DB::table('inventories')->where('id', $inventoryId)->first();

        if (! $inventory) {
            abort(404);
        }

        $inventory->user = $inventory->user_id ? DB::table('users')->select('id','name')->where('id', $inventory->user_id)->first() : null;

        return view('inventories.show', compact('inventory'));
    }

    public function edit($inventoryId): \Illuminate\Contracts\View\View
    {
        // Fetch users to populate owner dropdown
        $users = DB::table('users')->select('id','name')->orderBy('name')->get();

        $inventory = DB::table('inventories')->where('id', $inventoryId)->first();
        if (! $inventory) {
            abort(404);
        }

        return view('inventories.edit', compact('inventory', 'users'));
    }

    /**
     * Update the specified inventory in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $inventoryId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $inventoryId): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'qty' => ['nullable', 'integer', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $existing = DB::table('inventories')->where('id', $inventoryId)->first();
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

        DB::table('inventories')->where('id', $inventoryId)->update($payload);

        return redirect()->route('inventories.show', $inventoryId)->with('status', 'Inventory updated.');
    }

    /**
     * Remove the specified inventory from storage.
     *
     * @param int $inventoryId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($inventoryId)
    {
        DB::table('inventories')->where('id', $inventoryId)->delete();
        return redirect()->route('inventories.index')->with('status', 'Inventory deleted.');
    }
}
