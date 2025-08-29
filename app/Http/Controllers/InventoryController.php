<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Jobs\InventoryCreatedJob;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Warehouse;
use App\Notifications\StoreInventoryNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class InventoryController extends Controller
{
    public function __construct()
    {
        // Require authentication for inventory pages, but allow public access to
        // lightweight JSON endpoints used by dynamic selects and the navbar.
        $this->middleware('auth')->except(['warehouses', 'shelvesByWarehouse']);
    }

    /**
     * Display a listing of the inventories.
     */
    public function index(): View
    {
        // Use Eloquent with eager-loading so views can reference relations
        $query = Inventory::with('user', 'vehicles', 'warehouse', 'shelf')
            ->orderBy('created_at', 'desc');

        // Listing access is controlled by policies (viewAny) — allow all authenticated users to see the index.

        // Allow page size override via query string (training note)
        $perPage = (int) request()->input('per_page', 5);
        $perPage = max(1, min($perPage, 100));
        // Optional search across name/description and owner filter
        $query->search(request()->string('search'))
            ->ownedBy(request()->input('owner_id'));

        $inventories = $query->paginate($perPage)->appends(request()->only(['search', 'per_page', 'owner_id']));

        // Provide owners list for filter UI
        $users = User::query()->select('id', 'name')->orderBy('name')->get();

        return view('inventories.index', compact('inventories', 'users'));
    }

    /**
     * Show the form for creating a new inventory item.
     */
    public function create(): View
    {
        // Authorization: allow creation per policy (admins and regular users as permitted).
        $this->authorize('create', Inventory::class);

        // Fetch all users ordered by name to populate the dropdown in the form using Eloquent
        $users = User::query()->select('id', 'name')->orderBy('name')->get();

        return view('inventories.create', compact('users'));
    }

    /**
     * Store a newly created inventory item in storage.
     */
    public function store(StoreInventoryRequest $request): RedirectResponse
    {
        $this->authorize('create', Inventory::class);
        $data = $request->validated();

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
            'warehouse_id' => $data['warehouse_id'] ?? null,
            'shelf_id' => $data['shelf_id'] ?? null,
        ]);

        // Attach any selected vehicles (many-to-many). Use array values or empty array.
        if (! empty($data['vehicle_ids']) && is_array($data['vehicle_ids'])) {
            $inventory->vehicles()->sync(array_values($data['vehicle_ids']));
        }

        // Dispatch a job to send the inventory-created mail (keeps controller lightweight)
        $recipient = $inventory->user?->email ?? config('mail.from.address', 'user@example.com');
        InventoryCreatedJob::dispatch($inventory);

        // Also create a notification record and send notification (mail + database)
        // This uses the StoreInventoryNotification class. If there is an owning user,
        // notify that user; otherwise route a notification to the configured fallback email.
        $this->notifyInventoryCreated($inventory, $recipient);

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
        $inventory = Inventory::with('user', 'vehicles', 'warehouse', 'shelf')->findOrFail($inventoryId);

        // Authorize that the current user can view this inventory
        $this->authorize('view', $inventory);

        return view('inventories.show', compact('inventory'));
    }

    public function edit($inventoryId): View
    {
        // Fetch users to populate owner dropdown
        $users = User::query()->select('id', 'name')->orderBy('name')->get();

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
    public function update(UpdateInventoryRequest $request, $inventoryId): RedirectResponse
    {
        $data = $request->validated();

        $inventory = Inventory::findOrFail($inventoryId);

        $this->authorize('update', $inventory);

        $inventory->fill([
            'name' => $data['name'] ?? $inventory->name,
            'qty' => $data['qty'] ?? $inventory->qty,
            'price' => $data['price'] ?? $inventory->price,
            'description' => $data['description'] ?? $inventory->description,
            'warehouse_id' => $data['warehouse_id'] ?? $inventory->warehouse_id,
            'shelf_id' => $data['shelf_id'] ?? $inventory->shelf_id,
        ]);

        // Only allow admins to reassign ownership.
        if (! empty($data['user_id']) && Auth::check() && Auth::user()->hasRole('admin')) {
            $inventory->user_id = $data['user_id'];
        }

        $inventory->save();

        // Sync related vehicles if provided
        if (! empty($data['vehicle_ids']) && is_array($data['vehicle_ids'])) {
            $inventory->vehicles()->sync(array_values($data['vehicle_ids']));
        }

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

    /**
     * Notify interested parties that an inventory was created.
     */
    private function notifyInventoryCreated(Inventory $inventory, ?string $fallbackEmail = null): void
    {
        // If the inventory has an associated user, notify them (database + mail)
        if ($inventory->user) {
            $inventory->user->notify(new StoreInventoryNotification($inventory));

            return;
        }

        // No owner on the inventory: send mail to fallback address if provided
        $email = $fallbackEmail ?? config('mail.from.address', null);
        if ($email) {
            Notification::route('mail', $email)->notify(new StoreInventoryNotification($inventory));
        }
    }

    /**
     * API: trigger mail notification for an inventory and return JSON.
     *
     * This is the handler for the route POST /api/mail/inventories/{inventory}/notify
     */
    public function notifyMail($inventoryId)
    {
        $inventory = Inventory::with('user')->findOrFail($inventoryId);

        if ($inventory->user) {
            $inventory->user->notify(new StoreInventoryNotification($inventory));
        } else {
            $fallback = config('mail.from.address');
            Notification::route('mail', $fallback)->notify(new StoreInventoryNotification($inventory));
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Return all warehouses as JSON for dynamic selects.
     */
    public function warehouses()
    {
        $warehouses = Warehouse::query()->select('id', 'name')->orderBy('name')->get();

        return response()->json($warehouses);
    }

    /**
     * Return shelves for a given warehouse as JSON.
     * Used by the front-end dynamic dropdown when selecting a warehouse.
     */
    public function shelvesByWarehouse($warehouse)
    {
        // Accept either a Warehouse model (route model binding) or an id.
        if ($warehouse instanceof Warehouse) {
            $w = $warehouse;
        } else {
            $w = Warehouse::findOrFail($warehouse);
        }

        $shelves = $w->shelves()->select('id', 'shelf_number')->orderBy('shelf_number')->get();

        return response()->json($shelves);
    }
}
