<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions grouped by module
        $permissions = [
            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.export',

            // Roles Management
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // Permissions Management
            'permissions.view',
            'permissions.manage',

            // Activity Logs
            'logs.view',
            'logs.delete',
            'logs.export',

            // System Settings
            'settings.view',
            'settings.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign created permissions

        // 1. Super Admin Role
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        // 2. Admin Role
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->givePermissionTo([
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.export',
            'roles.view',
            'roles.create',
            'roles.edit',
            'permissions.view',
            'logs.view',
            'logs.export',
            'settings.view',
            'settings.edit',
        ]);

        // 3. Manager Role
        $manager = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $manager->givePermissionTo([
            'users.view',
            'users.edit',
            'users.export',
            'logs.view',
        ]);

        // 4. User Role (Standard end-user with no admin permissions)
        $userRole = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);
        $userRole->syncPermissions([]);
    }
}
