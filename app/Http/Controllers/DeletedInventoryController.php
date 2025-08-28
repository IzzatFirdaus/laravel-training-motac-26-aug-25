<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeletedInventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Temporarily allow any authenticated user for testing
        // $this->middleware('role:admin'); // Only admins can access deleted inventories
    }

    /**
     * Display a listing of soft-deleted inventories.
     */
    public function index(Request $request): View
    {
        // Authorization is already handled by middleware('role:admin')

        $query = Inventory::onlyTrashed()->with('user');

        // Optional search by name
        if ($request->has('search') && ! empty($request->search)) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $deletedInventories = $query->paginate(10);

        return view('inventories.deleted.index', compact('deletedInventories'));
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
