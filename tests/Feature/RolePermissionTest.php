<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('authenticated user with permission can view roles index page', function () {
    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $response = $this->actingAs($user)->get(route('roles.index'));

    $response->assertStatus(200);
});

test('unauthorized user without permission is forbidden from viewing roles index page', function () {
    $user = User::factory()->create(); // No roles assigned

    $response = $this->actingAs($user)->get(route('roles.index'));

    $response->assertStatus(403);
});

test('authorized user can create a new role', function () {
    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $response = $this->actingAs($user)->post(route('roles.store'), [
        'name' => 'Editor',
        'permissions' => ['users.view', 'users.edit'],
    ]);

    $response->assertRedirect(route('roles.index'));
    $this->assertDatabaseHas('roles', ['name' => 'Editor']);

    $role = Role::findByName('Editor');
    expect($role->hasPermissionTo('users.view'))->toBeTrue();
});

test('unauthorized user is forbidden from creating a role', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('roles.store'), [
        'name' => 'UnauthorizedRole',
        'permissions' => [],
    ]);

    $response->assertStatus(403);
});

test('super admin role cannot be deleted', function () {
    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $superAdminRole = Role::findByName('Super Admin');

    $response = $this->actingAs($user)->delete(route('roles.destroy', $superAdminRole->id));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('roles', ['name' => 'Super Admin']);
});
