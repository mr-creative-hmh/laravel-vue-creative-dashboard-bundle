<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Notifications\SystemAlertNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('users.view');

        $query = User::with('roles');

        // Search Filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role Filter
        if ($role = $request->input('role')) {
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        // Status Filter
        if ($request->has('status') && $request->input('status') !== null && $request->input('status') !== '') {
            $query->where('is_active', $request->boolean('status'));
        }

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $allowedSorts = ['id', 'name', 'email', 'created_at', 'is_active'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        $users = $query->paginate(10)->through(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'avatar_url' => $user->avatar ? asset('storage/'.$user->avatar) : null,
                'is_active' => $user->is_active,
                'roles' => $user->getRoleNames()->toArray(),
                'created_at' => $user->created_at->format('Y-m-d H:i'),
            ];
        });

        $roles = Role::pluck('name');

        return Inertia::render('Users/Index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => $request->only(['search', 'role', 'status', 'sort', 'direction']),
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): Response
    {
        Gate::authorize('users.create');

        $roles = Role::pluck('name');

        return Inertia::render('Users/Create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        Gate::authorize('users.create');

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
            'avatar' => $avatarPath,
            'is_active' => $request->validated('is_active'),
            'email_verified_at' => now(),
        ]);

        $user->syncRoles($request->validated('roles'));

        activity()
            ->performedOn($user)
            ->causedBy($request->user())
            ->log("Created user '{$user->email}'");

        $request->user()->notify(new SystemAlertNotification(
            'New User Created',
            "User '{$user->name}' was added to the platform.",
            '/users'
        ));

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): Response
    {
        Gate::authorize('users.edit');

        $user->load('roles');
        $roles = Role::pluck('name');

        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'avatar_url' => $user->avatar ? asset('storage/'.$user->avatar) : null,
                'is_active' => $user->is_active,
                'roles' => $user->getRoleNames()->toArray(),
            ],
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('users.edit');

        $data = [
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'is_active' => $request->validated('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->validated('password'));
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);
        $user->syncRoles($request->validated('roles'));

        activity()
            ->performedOn($user)
            ->causedBy($request->user())
            ->log("Updated user '{$user->email}'");

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('users.delete');

        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $userName = $user->name;
        $user->delete();

        activity()
            ->causedBy(auth()->user())
            ->log("Deleted user '{$userName}'");

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Bulk perform actions on selected users.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|string|in:delete,activate,disable',
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:users,id',
        ]);

        $ids = array_diff($validated['ids'], [$request->user()->id]);
        $users = User::whereIn('id', $ids)->get();

        if ($validated['action'] === 'delete') {
            Gate::authorize('users.delete');
            foreach ($users as $u) {
                if ($u->avatar && Storage::disk('public')->exists($u->avatar)) {
                    Storage::disk('public')->delete($u->avatar);
                }
                $u->delete();
            }
            activity()->causedBy($request->user())->log('Bulk deleted '.count($users).' users');

            return back()->with('success', count($users).' users deleted successfully.');
        }

        Gate::authorize('users.edit');
        $status = $validated['action'] === 'activate';
        User::whereIn('id', $ids)->update(['is_active' => $status]);

        activity()->causedBy($request->user())->log('Bulk updated status for '.count($users).' users');

        return back()->with('success', 'Status updated for '.count($users).' users.');
    }
}
