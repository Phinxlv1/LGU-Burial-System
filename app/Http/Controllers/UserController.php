<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::orderBy('name')->paginate(25);

        if (Auth::user()->hasRole('super_admin') || Auth::user()->role === 'super_admin') {
            return view('superadmin.users.index', compact('users'));
        }

        return view('admin.users.index', compact('users'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|string',
        ]);

        if (Auth::user()->role !== 'super_admin' && $validated['role'] === 'super_admin') {
            return back()->withErrors(['role' => 'You cannot create super admin users.']);
        }

        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        if (Auth::user()->role !== 'super_admin' && $user->role === 'super_admin') {
            return back()->withErrors(['error' => 'You cannot update super admin users.']);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|string',
        ]);

        if (Auth::user()->role !== 'super_admin' && $validated['role'] === 'super_admin') {
            return back()->withErrors(['role' => 'You cannot assign super admin role.']);
        }

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'super_admin' && $user->role === 'super_admin') {
            return back()->withErrors(['error' => 'You cannot delete super admin users.']);
        }

        if (Auth::id() === $user->id) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $name = $user->name;

        \App\Models\ActivityLog::record(
            action: 'deleted',
            modelType: 'User',
            modelId: $user->id,
            modelLabel: $name,
            oldValues: $user->toArray(),
            description: "User account for {$name} deleted by " . auth()->user()->name
        );

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User account for {$name} deleted.");
    }
}