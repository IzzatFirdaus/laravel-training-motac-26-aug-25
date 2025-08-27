<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventories.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        // Fetch all inventories with their associated user, ordered by latest, and paginate results
        $inventories = Inventory::with('user')->latest()->paginate(15);

        // Return the view with the inventories data
        return view('inventories.index', compact('inventories'));
    }

    /**
     * Show the form for creating a new inventory item.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        // Fetch all users ordered by name to populate the dropdown in the form
        $users = User::orderBy('name')->get(['id', 'name']);

        // Return the view with the users data
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

        // Create a new inventory instance and assign validated data
        $inventory = new Inventory();
        $inventory->name = $data['name'];
        $inventory->qty = $data['qty'];
        $inventory->price = $data['price'];
        $inventory->description = $data['description'] ?? null; // Use null if description is not provided

        // Assign user_id based on the provided data or the authenticated user
        if (!empty($data['user_id'])) {
            $inventory->user_id = $data['user_id'];
        } elseif (Auth::check()) {
            $inventory->user_id = Auth::id();
        } else {
            $inventory->user_id = null; // Set to null if no user is associated
        }

        // Save the inventory to the database
        $inventory->save();

        // Redirect to the show page for the newly created inventory with a success message
        return redirect()->route('inventories.show', $inventory->id)->with('status', 'Inventory created.');
    }

    /**
     * Display a single inventory item.
     *
     * @param Inventory $inventory
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Inventory $inventory): \Illuminate\Contracts\View\View
    {
        // Return the view with the inventory data
        return view('inventories.show', compact('inventory'));
    }

    public function edit(Inventory $inventory): \Illuminate\Contracts\View\View
    {
        // Fetch users to populate owner dropdown
        $users = User::orderBy('name')->get(['id', 'name']);

        // Return the view with the inventory data
        return view('inventories.edit', compact('inventory', 'users'));
    }

    /**
     * Update the specified inventory in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Inventory $inventory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Inventory $inventory): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'qty' => ['nullable', 'integer', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $inventory->fill([
            'name' => $data['name'] ?? $inventory->name,
            'qty' => $data['qty'] ?? $inventory->qty,
            'price' => $data['price'] ?? $inventory->price,
            'description' => $data['description'] ?? $inventory->description,
        ]);

        if (!empty($data['user_id'])) {
            $inventory->user_id = $data['user_id'];
        }

        $inventory->save();

        return redirect()->route('inventories.show', $inventory)->with('status', 'Inventory updated.');
    }
}
