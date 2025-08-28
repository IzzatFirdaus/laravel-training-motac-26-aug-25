<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Mail\InventoryCreated;
use App\Models\Inventory;
use App\Notifications\StoreInventoryNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

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

        // Listing access is controlled by policies (viewAny) — allow all authenticated users to see the index.

    // Allow page size override via query string (training note)
    $perPage = (int) request()->input('per_page', 5);
    $perPage = max(1, min($perPage, 100));

    $inventories = $query->paginate($perPage)->appends(['per_page' => $perPage]);

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
        ]);

        // Queue mailable to notify the owner (or fallback configured address)
        $recipient = $inventory->user?->email ?? config('mail.from.address', 'user@example.com');
        Mail::to($recipient)->queue(new InventoryCreated($inventory));

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
}
