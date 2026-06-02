<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\LogsAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

/**
 * UserController
 *
 * Handles CRUD operations for user management.
 * Admin-only access (enforced via route middleware).
 */
class UserController extends Controller
{
    use LogsAudit;

    /**
     * Display a listing of all users.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->input('role'));
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($validated['role']);

        $this->logAction('CREATE', 'User', $user->id, null, $user->toArray());

        return redirect()->route('users.index')
                         ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing an existing user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in the database.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|exists:roles,name',
        ]);

        $oldValues = $user->toArray();

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->syncRoles([$validated['role']]);

        $this->logAction('UPDATE', 'User', $user->id, $oldValues, $user->fresh()->toArray());

        return redirect()->route('users.index')
                         ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from the database.
     */
    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                             ->with('error', 'You cannot delete your own account.');
        }

        $oldValues = $user->toArray();
        $userId = $user->id;

        $user->delete();

        $this->logAction('DELETE', 'User', $userId, $oldValues, null);

        return redirect()->route('users.index')
                         ->with('success', 'User deleted successfully.');
    }
}
