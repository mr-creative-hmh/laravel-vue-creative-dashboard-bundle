<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index(): Response
    {
        Gate::authorize('roles.view');

        $roles = Role::with(['permissions'])
            ->withCount('users')
            ->orderBy('id', 'asc')
            ->get();

        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);

            return count($parts) > 1 ? ucfirst($parts[0]) : 'General';
        });

        return Inertia::render('Roles/Index', [
            'roles' => $roles,
            'groupedPermissions' => $permissions,
        ]);
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): Response
    {
        Gate::authorize('roles.create');

        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);

            return count($parts) > 1 ? ucfirst($parts[0]) : 'General';
        });

        return Inertia::render('Roles/Create', [
            'groupedPermissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(RoleRequest $request): RedirectResponse
    {
        Gate::authorize('roles.create');

        $role = Role::create([
            'name' => $request->validated('name'),
            'guard_name' => 'web',
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->validated('permissions'));
        }

        activity()
            ->performedOn($role)
            ->causedBy($request->user())
            ->log("Created role '{$role->name}'");

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role): Response
    {
        Gate::authorize('roles.edit');

        $role->load('permissions');

        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);

            return count($parts) > 1 ? ucfirst($parts[0]) : 'General';
        });

        return Inertia::render('Roles/Edit', [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ],
            'groupedPermissions' => $permissions,
        ]);
    }

    /**
     * Update the specified role in storage.
     */
    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        Gate::authorize('roles.edit');

        if ($role->name === 'Super Admin' && $request->validated('name') !== 'Super Admin') {
            return back()->with('error', 'Cannot rename Super Admin role.');
        }

        $role->update([
            'name' => $request->validated('name'),
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->validated('permissions'));
        }

        activity()
            ->performedOn($role)
            ->causedBy($request->user())
            ->log("Updated role '{$role->name}'");

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        Gate::authorize('roles.delete');

        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Super Admin role cannot be deleted.');
        }

        $roleName = $role->name;
        $role->delete();

        activity()
            ->causedBy(auth()->user())
            ->log("Deleted role '{$roleName}'");

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
