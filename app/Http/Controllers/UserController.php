<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
    // Require authentication for all user routes. Admin-only actions remain protected by middleware.
    $this->middleware('auth');
    $this->middleware('role:admin')->only(['create', 'store', 'destroy']);
    }

    /**
     * Display a listing of users.
     */
    public function index(): View
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);

        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('user.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // The User model has a password cast to 'hashed', so assign directly.
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return redirect()->route('users.index')->with('toast', 'Pengguna berjaya dicipta.');
    }

    /**
     * Display the specified user.
     */
    public function show($userId): View
    {
        $user = User::with('inventories', 'vehicles')->findOrFail($userId);
    $this->authorize('view', $user);

    return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($userId): View
    {
        $user = User::findOrFail($userId);
    $this->authorize('update', $user);

    return view('user.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, $userId): RedirectResponse
    {
        $data = $request->validated();

    $user = User::findOrFail($userId);

    $this->authorize('update', $user);

        $user->fill([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
        ]);

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return redirect()->route('users.show', $userId)->with('toast', 'Pengguna dikemaskini.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($userId): RedirectResponse
    {
        $user = User::findOrFail($userId);

        // Prevent a user from deleting themselves via the UI
        if (Auth::check() && Auth::id() === $user->getKey()) {
            return redirect()->route('users.index')->with('toast', 'Anda tidak boleh memadam akaun sendiri.');
        }

    $this->authorize('delete', $user);

    $user->delete();

        return redirect()->route('users.index')->with('toast', 'Pengguna dipadam.');
    }

    /**
     * JSON search endpoint used by client-side autocomplete/select widgets.
     */
    public function search(Request $request)
    {
        $q = $request->query('q', '');

        $users = User::query()
            ->select('id', 'name')
            ->when($q !== '', function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get();

        return response()->json($users);
    }
}
