<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

test('super admin can start impersonating another user', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo(Permission::firstOrCreate(['name' => 'users.edit']));

    $targetUser = User::factory()->create(['name' => 'Target Impersonated']);

    $response = $this->actingAs($admin)
        ->post(route('impersonate.take', $targetUser->id));

    $response->assertRedirect(route('dashboard'));
    $this->assertEquals($targetUser->id, auth()->id());
    $this->assertTrue(session()->has('impersonator_id'));
    $this->assertEquals($admin->id, session('impersonator_id'));
});

test('admin can leave impersonation session and return to original account', function () {
    $admin = User::factory()->create();
    $targetUser = User::factory()->create();

    $this->actingAs($targetUser);
    session(['impersonator_id' => $admin->id]);

    $response = $this->post(route('impersonate.leave'));

    $response->assertRedirect(route('users.index'));
    $this->assertEquals($admin->id, auth()->id());
    $this->assertFalse(session()->has('impersonator_id'));
});
