<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Maatwebsite\Excel\Facades\Excel;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('authenticated user can download users excel export', function () {
    Excel::fake();

    $admin = User::factory()->create();
    $admin->assignRole('Super Admin');

    $response = $this->actingAs($admin)->get(route('exports.users'));

    $response->assertStatus(200);
    Excel::assertDownloaded('users-'.now()->format('Y-m-d').'.xlsx');
});

test('unauthorized user is forbidden from downloading users export', function () {
    Excel::fake();

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('exports.users'));

    $response->assertStatus(403);
});

test('authenticated user can download activity logs excel export', function () {
    Excel::fake();

    $admin = User::factory()->create();
    $admin->assignRole('Super Admin');

    $response = $this->actingAs($admin)->get(route('exports.logs'));

    $response->assertStatus(200);
    Excel::assertDownloaded('activity-logs-'.now()->format('Y-m-d').'.xlsx');
});

test('unauthorized user is forbidden from downloading activity logs export', function () {
    Excel::fake();

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('exports.logs'));

    $response->assertStatus(403);
});
