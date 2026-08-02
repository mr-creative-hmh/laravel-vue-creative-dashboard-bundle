<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('authenticated user with permission can view users index page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Super Admin');

    $response = $this->actingAs($admin)->get(route('users.index'));

    $response->assertStatus(200);
});

test('standard user with User role is forbidden from viewing users index page', function () {
    $user = User::factory()->create();
    $user->assignRole('User');

    $response = $this->actingAs($user)->get(route('users.index'));

    $response->assertStatus(403);
    $response->assertInertia(fn ($page) => $page->component('errors/403'));
});

test('authorized user can create a new user', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Super Admin');

    $response = $this->actingAs($admin)->post(route('users.store'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'is_active' => true,
        'roles' => ['User'],
    ]);

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);

    $createdUser = User::where('email', 'jane@example.com')->first();
    expect($createdUser->hasRole('User'))->toBeTrue();
});

test('unauthorized user is forbidden from creating a user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('users.store'), [
        'name' => 'Hack User',
        'email' => 'hack@example.com',
        'password' => 'password123',
        'is_active' => true,
        'roles' => ['User'],
    ]);

    $response->assertStatus(403);
});

test('user cannot delete their own account', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Super Admin');

    $response = $this->actingAs($admin)->delete(route('users.destroy', $admin->id));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});
