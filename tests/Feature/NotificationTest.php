<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('authenticated user can view notifications page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('notifications.index'));

    $response->assertStatus(200);
});

test('user can mark notification as read', function () {
    $user = User::factory()->create();

    $notification = DatabaseNotification::create([
        'id' => (string) Str::uuid(),
        'type' => 'App\Notifications\SystemAlert',
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'data' => ['title' => 'Test Notification', 'message' => 'System update complete'],
        'read_at' => null,
    ]);

    $response = $this->actingAs($user)->post(route('notifications.read', $notification->id));

    $response->assertRedirect();
    expect($notification->fresh()->read_at)->not->toBeNull();
});

test('user can mark all notifications as read', function () {
    $user = User::factory()->create();

    DatabaseNotification::create([
        'id' => (string) Str::uuid(),
        'type' => 'App\Notifications\SystemAlert',
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'data' => ['title' => 'Test 1', 'message' => 'Message 1'],
        'read_at' => null,
    ]);

    $response = $this->actingAs($user)->post(route('notifications.read-all'));

    $response->assertRedirect();
    expect($user->unreadNotifications()->count())->toBe(0);
});
