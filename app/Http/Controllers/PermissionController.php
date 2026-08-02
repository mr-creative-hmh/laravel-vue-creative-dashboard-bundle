<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index(): Response
    {
        Gate::authorize('permissions.view');

        $permissions = Permission::withCount('roles')
            ->get()
            ->groupBy(function ($permission) {
                $parts = explode('.', $permission->name);

                return count($parts) > 1 ? ucfirst($parts[0]) : 'General';
            });

        return Inertia::render('Permissions/Index', [
            'groupedPermissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created permission in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('permissions.manage');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        $permission = Permission::create([
            'name' => strtolower($validated['name']),
            'guard_name' => 'web',
        ]);

        activity()
            ->performedOn($permission)
            ->causedBy($request->user())
            ->log("Created permission '{$permission->name}'");

        return back()->with('success', 'Permission created successfully.');
    }

    /**
     * Generate standard CRUD permission suite for a module.
     */
    public function generateModule(Request $request): RedirectResponse
    {
        Gate::authorize('permissions.manage');

        $validated = $request->validate([
            'module' => 'required|string|max:255',
        ]);

        $module = strtolower(trim($validated['module']));
        $actions = ['view', 'create', 'edit', 'delete', 'export'];

        foreach ($actions as $action) {
            Permission::firstOrCreate([
                'name' => "{$module}.{$action}",
                'guard_name' => 'web',
            ]);
        }

        // Forget cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Assign to Super Admin
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo(Permission::all());
        }

        activity()
            ->causedBy($request->user())
            ->log("Generated CRUD permissions suite for module '{$module}'");

        return back()->with('success', "Generated CRUD permissions for module '{$module}'.");
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        Gate::authorize('permissions.manage');

        $permName = $permission->name;
        $permission->delete();

        activity()
            ->causedBy(auth()->user())
            ->log("Deleted permission '{$permName}'");

        return back()->with('success', 'Permission deleted successfully.');
    }
}
