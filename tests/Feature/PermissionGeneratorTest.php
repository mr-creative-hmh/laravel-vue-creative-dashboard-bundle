<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

test('artisan permissions:generate command creates CRUD permissions for a module', function () {
    Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);

    $this->artisan('permissions:generate', ['module' => 'Order'])
        ->assertSuccessful();

    $this->assertTrue(Permission::where('name', 'orders.view')->exists());
    $this->assertTrue(Permission::where('name', 'orders.create')->exists());
    $this->assertTrue(Permission::where('name', 'orders.edit')->exists());
    $this->assertTrue(Permission::where('name', 'orders.delete')->exists());
    $this->assertTrue(Permission::where('name', 'orders.export')->exists());
});

test('artisan permissions:sync scans models and generates permissions', function () {
    Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);

    $this->artisan('permissions:sync')
        ->assertSuccessful();
});

test('web route can generate permissions module suite', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo(Permission::firstOrCreate(['name' => 'permissions.manage']));

    $response = $this->actingAs($admin)
        ->post(route('permissions.generate-module'), [
            'module' => 'invoices',
        ]);

    $response->assertRedirect();
    $this->assertTrue(Permission::where('name', 'invoices.view')->exists());
    $this->assertTrue(Permission::where('name', 'invoices.create')->exists());
});
