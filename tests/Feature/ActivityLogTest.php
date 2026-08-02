<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('admin can view activity logs index page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Super Admin');

    activity()
        ->performedOn($admin)
        ->causedBy($admin)
        ->log('Test activity log entry');

    $response = $this->actingAs($admin)->get(route('logs.index'));

    $response->assertStatus(200);
    $this->assertDatabaseHas('activity_log', [
        'description' => 'Test activity log entry',
    ]);
});
