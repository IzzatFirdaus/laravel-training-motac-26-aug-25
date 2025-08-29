<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DeletedInventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin'); // Only admins can access deleted inventories
    }

    /**
     * Display a listing of soft-deleted inventories.
     */
    public function index(Request $request): View
    {
        // Authorization is handled by middleware('role:admin')
        Log::info('DeletedInventoryController@index called');

        $query = Inventory::onlyTrashed()->with('user')
            ->search($request->string('search'))
            ->ownedBy($request->input('owner_id'));

        // NOTE: debugging logging was here earlier and has been removed.

        $perPage = (int) $request->input('per_page', 10);
        $perPage = max(1, min($perPage, 100));

        $deletedInventories = $query->paginate($perPage)->appends($request->only(['search', 'per_page', 'owner_id']));

        $users = \App\Models\User::query()->select('id', 'name')->orderBy('name')->get();

        return view('inventories.deleted.index', compact('deletedInventories', 'users'));
    }

    /**
     * Restore the specified soft-deleted inventory.
     */
    public function restore(Inventory $inventory): RedirectResponse
    {
        $this->authorize('restore', $inventory);

        $inventory->restore();

        return redirect()->route('inventories.deleted.index')->with('toast', 'Inventori telah dipulihkan.');
    }

    /**
     * Permanently delete the specified soft-deleted inventory.
     */
    public function forceDelete(Inventory $inventory): RedirectResponse
    {
        $this->authorize('forceDelete', $inventory);

        $inventory->forceDelete();

        return redirect()->route('inventories.deleted.index')->with('toast', 'Inventori telah dipadam secara kekal.');
    }
}
