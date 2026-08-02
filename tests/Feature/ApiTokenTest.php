<?php

use App\Models\User;

test('user can view api tokens page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('settings.api-tokens.index'));

    $response->assertOk();
});

test('user can generate a new personal access token', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('settings.api-tokens.store'), [
            'name' => 'Test CI Token',
        ]);

    $response->assertRedirect();
    $this->assertCount(1, $user->fresh()->tokens);
    $this->assertEquals('Test CI Token', $user->tokens->first()->name);
});

test('user can revoke an existing token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('Mobile App Token');

    $response = $this->actingAs($user)
        ->delete(route('settings.api-tokens.destroy', $token->accessToken->id));

    $response->assertRedirect();
    $this->assertCount(0, $user->fresh()->tokens);
});
