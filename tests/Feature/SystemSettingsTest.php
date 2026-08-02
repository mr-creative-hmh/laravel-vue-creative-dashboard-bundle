<?php

use App\Models\SystemSetting;
use App\Models\User;
use Spatie\Permission\Models\Permission;

test('system settings page renders for authorized user', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo(Permission::firstOrCreate(['name' => 'settings.view']));

    $response = $this->actingAs($admin)
        ->get(route('settings.system.edit'));

    $response->assertOk();
});

test('system settings can be updated successfully', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo(Permission::firstOrCreate(['name' => 'settings.edit']));

    $response = $this->actingAs($admin)
        ->put(route('settings.system.update'), [
            'app_name' => 'Updated Dashboard Name',
            'support_email' => 'contact@company.com',
            'default_locale' => 'ar',
            'maintenance_mode' => true,
        ]);

    $response->assertRedirect();
    $this->assertEquals('Updated Dashboard Name', SystemSetting::get('app_name'));
    $this->assertEquals('contact@company.com', SystemSetting::get('support_email'));
    $this->assertEquals('ar', SystemSetting::get('default_locale'));
    $this->assertEquals('1', SystemSetting::get('maintenance_mode'));
});

test('unauthorized user gets 403 forbidden error page when accessing system settings', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('settings.system.edit'));

    $response->assertStatus(403);
    $response->assertInertia(fn ($page) => $page->component('errors/403'));
});
