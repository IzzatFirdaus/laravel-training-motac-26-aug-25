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
    // Require authentication for all inventory pages. Per-item authorization
    // is performed via policies so owners (and admins) can manage their own items.
    $this->middleware('auth');
    }

    /**
     * Display a listing of the inventories.
     */
    public function index(): View
    {
        // Use Eloquent with eager-loading so views can reference relations
        $query = Inventory::with('user', 'vehicles')
            ->orderBy('created_at', 'desc');

        // Admins may see all. Authenticated non-admin users should only see their own items.
        if (Auth::check() && ! Auth::user()->hasRole('admin')) {
            $query->where('user_id', Auth::id());
        }

        $inventories = $query->paginate(15);

        return view('inventories.index', compact('inventories'));
    }

    /**
     * Show the form for creating a new inventory item.
     */
    public function create(): View
    {
    // Authorization: allow creation per policy (admins and regular users as permitted).
    $this->authorize('create', Inventory::class);

    // Fetch all users ordered by name to populate the dropdown in the form as plain objects
    $users = DB::table('users')->select('id', 'name')->orderBy('name')->get();

    return view('inventories.create', compact('users'));
    }

    /**
     * Store a newly created inventory item in storage.
     */
    public function store(Request $request): RedirectResponse
    {
    $this->authorize('create', Inventory::class);

        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'qty' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        // Only admins may set an arbitrary owner. Regular users always own the
        // inventory they create.
        if (! Auth::check()) {
            $data['user_id'] = null;
        } elseif (! Auth::user()->hasRole('admin')) {
            $data['user_id'] = Auth::id();
        } else {
            // Admin: allow provided user_id or null
            $data['user_id'] = $data['user_id'] ?? null;
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

        return $route->with('toast', 'Inventori berjaya dicipta.');
    }

    /**
     * Display a single inventory item.
     *
     * @param  int  $inventoryId
     */
    public function show($inventoryId): View
    {
        $inventory = Inventory::with('user', 'vehicles')->findOrFail($inventoryId);

    // Authorize that the current user can view this inventory
    $this->authorize('view', $inventory);

    return view('inventories.show', compact('inventory'));
    }

    public function edit($inventoryId): View
    {
        // Fetch users to populate owner dropdown
        $users = DB::table('users')->select('id', 'name')->orderBy('name')->get();

        $inventory = Inventory::findOrFail($inventoryId);

    // Authorize the current user may update this inventory
    $this->authorize('update', $inventory);

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

    $this->authorize('update', $inventory);

        $inventory->fill([
            'name' => $data['name'] ?? $inventory->name,
            'qty' => $data['qty'] ?? $inventory->qty,
            'price' => $data['price'] ?? $inventory->price,
            'description' => $data['description'] ?? $inventory->description,
        ]);

        // Only allow admins to reassign ownership.
        if (! empty($data['user_id']) && Auth::check() && Auth::user()->hasRole('admin')) {
            $inventory->user_id = $data['user_id'];
        }

        $inventory->save();

        return redirect()->route('inventories.show', $inventoryId)->with('toast', 'Inventory updated.');
    }

    /**
     * Remove the specified inventory from storage.
     *
     * @param  int  $inventoryId
     */
    public function destroy($inventoryId): RedirectResponse
    {
        $inventory = Inventory::findOrFail($inventoryId);
    $this->authorize('delete', $inventory);
        $inventory->delete();

        return redirect()->route('inventories.index')->with('toast', 'Inventory deleted.');
    }
}
