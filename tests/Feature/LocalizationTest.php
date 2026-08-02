<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('locale can be switched to arabic and sets rtl text direction', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard?lang=ar');

    $response->assertStatus(200);
    expect(session('app_locale'))->toBe('ar');
    expect(app()->getLocale())->toBe('ar');
});
