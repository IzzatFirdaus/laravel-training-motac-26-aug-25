<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Models\Application;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function __construct()
    {
        // Require authentication for application routes
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $q = $request->query('q');

        $applications = Application::query()
            ->when($q, function ($query, $q) {
                $like = '%'.$q.'%';
                $query->where('name', 'like', $like)
                    ->orWhere('description', 'like', $like);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Also search inventories using the same query term so the view can show related inventory matches.
        $inventories = Inventory::query()
            ->when($q, function ($query, $q) {
                $like = '%'.$q.'%';
                $query->where('name', 'like', $like)
                    ->orWhere('description', 'like', $like);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('application.index', [
            'applications' => $applications,
            'inventories' => $inventories,
            'q' => $q,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Application::class);

        // Provide a small list of inventories and users to optionally associate with the application.
        $inventories = Inventory::query()->select('id', 'name')->orderBy('name')->limit(200)->get();
        $users = User::query()->select('id', 'name')->orderBy('name')->get();

        return view('application.create', [
            'inventories' => $inventories,
            'users' => $users,
        ]);
    }

    public function store(StoreApplicationRequest $request): RedirectResponse
    {
        $this->authorize('create', Application::class);

        $data = $request->validated();

        // Allow admins to set an explicit owner; otherwise default to creator when available.
        $userId = null;
        if (Auth::check()) {
            $currentUser = Auth::user();
            if ($currentUser && method_exists($currentUser, 'hasRole') && $currentUser->hasRole('admin')) {
                $userId = $data['user_id'] ?? null;
            } else {
                $userId = Auth::id();
            }
        }

        $application = Application::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'inventory_id' => $data['inventory_id'] ?? null,
            'user_id' => $userId,
        ]);

        return redirect()->route('applications.index')->with('toast', __('ui.applications.created'));
    }

    public function show(Application $application): View
    {
        $this->authorize('view', $application);

        return view('application.show', ['application' => $application]);
    }

    public function edit(Application $application): View
    {
        $this->authorize('update', $application);

        return view('application.edit', ['application' => $application]);
    }

    public function update(UpdateApplicationRequest $request, Application $application): RedirectResponse
    {
        $this->authorize('update', $application);

        $data = $request->validated();

        $application->fill($data);
        $application->save();

        return redirect()->route('applications.show', $application->getKey())->with('toast', __('ui.applications.updated'));
    }

    public function destroy(Application $application): RedirectResponse
    {
        $this->authorize('delete', $application);

        $application->delete();

        return redirect()->route('applications.index')->with('toast', __('ui.applications.deleted'));
    }
}
