<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
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
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        // The User model has a password cast to 'hashed', so assign directly.
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return redirect()->route('users.index')->with('status', 'Pengguna berjaya dicipta.');
    }

    /**
     * Display the specified user.
     */
    public function show($userId): View
    {
        $user = User::with('inventories', 'vehicles')->findOrFail($userId);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($userId): View
    {
        $user = User::findOrFail($userId);

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $userId): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', "unique:users,email,{$userId}"],
            'password' => ['nullable', 'string', 'confirmed', 'min:8'],
        ]);

        $user = User::findOrFail($userId);

        $user->fill([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
        ]);

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return redirect()->route('users.show', $userId)->with('status', 'Pengguna dikemaskini.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($userId): RedirectResponse
    {
        $user = User::findOrFail($userId);

        // Prevent a user from deleting themselves via the UI
        if (Auth::check() && Auth::id() === $user->getKey()) {
            return redirect()->route('users.index')->with('status', 'Anda tidak boleh memadam akaun sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('status', 'Pengguna dipadam.');
    }
}
