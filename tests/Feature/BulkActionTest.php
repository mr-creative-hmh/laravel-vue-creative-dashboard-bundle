<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

test('super admin can bulk update status of multiple users', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo(Permission::firstOrCreate(['name' => 'users.edit']));

    $user1 = User::factory()->create(['is_active' => false]);
    $user2 = User::factory()->create(['is_active' => false]);

    $response = $this->actingAs($admin)
        ->post(route('users.bulk-action'), [
            'action' => 'activate',
            'ids' => [$user1->id, $user2->id],
        ]);

    $response->assertRedirect();
    $this->assertTrue((bool) $user1->fresh()->is_active);
    $this->assertTrue((bool) $user2->fresh()->is_active);
});

test('super admin can bulk delete multiple users', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo(Permission::firstOrCreate(['name' => 'users.delete']));

    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $response = $this->actingAs($admin)
        ->post(route('users.bulk-action'), [
            'action' => 'delete',
            'ids' => [$user1->id, $user2->id],
        ]);

    $response->assertRedirect();
    $this->assertDatabaseMissing('users', ['id' => $user1->id]);
    $this->assertDatabaseMissing('users', ['id' => $user2->id]);
});
