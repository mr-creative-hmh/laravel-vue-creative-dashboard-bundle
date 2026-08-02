<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

test('super admin can undo an activity log update action', function () {
    $role = Role::findOrCreate('Super Admin', 'web');
    $permission = Permission::findOrCreate('logs.view', 'web');
    $role->givePermissionTo($permission);

    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('Super Admin');

    $targetUser = User::factory()->create(['name' => 'John Original']);

    // Log update action
    $log = activity()
        ->performedOn($targetUser)
        ->causedBy($admin)
        ->withProperties([
            'old' => ['name' => 'John Original'],
            'attributes' => ['name' => 'John Modified'],
        ])
        ->event('updated')
        ->log('updated user profile');

    $targetUser->update(['name' => 'John Modified']);

    $response = $this->actingAs($admin)
        ->post(route('logs.undo', $log->id));

    $response->assertRedirect();
    $this->assertEquals('John Original', $targetUser->fresh()->name);
});
