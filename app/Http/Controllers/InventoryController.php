<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the inventories.
     */
    public function index(): View
    {
        // Use Eloquent with eager-loading so views can reference relations
        $inventories = Inventory::with('user', 'vehicles')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('inventories.index', compact('inventories'));
    }

    /**
     * Show the form for creating a new inventory item.
     */
    public function create(): View
    {
        // Fetch all users ordered by name to populate the dropdown in the form as plain objects
        $users = DB::table('users')->select('id', 'name')->orderBy('name')->get();

        return view('inventories.create', compact('users'));
    }

    /**
     * Store a newly created inventory item in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'qty' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        if (empty($data['user_id']) && Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        $inventory = Inventory::create([
            'name' => $data['name'],
            'qty' => $data['qty'],
            'price' => $data['price'],
            'description' => $data['description'] ?? null,
            'user_id' => $data['user_id'] ?? null,
        ]);

        $route = redirect()->route(
            'inventories.show',
            $inventory->getKey()
        );

        return $route->with('status', 'Inventori berjaya dicipta.');
    }

    /**
     * Display a single inventory item.
     *
     * @param  int  $inventoryId
     */
    public function show($inventoryId): View
    {
        $inventory = Inventory::with('user', 'vehicles')->findOrFail($inventoryId);

        return view('inventories.show', compact('inventory'));
    }

    public function edit($inventoryId): View
    {
        // Fetch users to populate owner dropdown
        $users = DB::table('users')->select('id', 'name')->orderBy('name')->get();

        $inventory = Inventory::findOrFail($inventoryId);

        return view('inventories.edit', compact('inventory', 'users'));
    }

    /**
     * Update the specified inventory in storage.
     *
     * @param  int  $inventoryId
     */
    public function update(Request $request, $inventoryId): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'qty' => ['nullable', 'integer', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $inventory = Inventory::findOrFail($inventoryId);

        $inventory->fill([
            'name' => $data['name'] ?? $inventory->name,
            'qty' => $data['qty'] ?? $inventory->qty,
            'price' => $data['price'] ?? $inventory->price,
            'description' => $data['description'] ?? $inventory->description,
        ]);

        if (! empty($data['user_id'])) {
            $inventory->user_id = $data['user_id'];
        }

        $inventory->save();

        return redirect()->route('inventories.show', $inventoryId)->with('status', 'Inventory updated.');
    }

    /**
     * Remove the specified inventory from storage.
     *
     * @param  int  $inventoryId
     */
    public function destroy($inventoryId): RedirectResponse
    {
        $inventory = Inventory::findOrFail($inventoryId);
        $inventory->delete();

        return redirect()->route('inventories.index')->with('status', 'Inventory deleted.');
    }
}
