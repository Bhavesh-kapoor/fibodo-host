<?php

use Laravel\Passport\Client;

beforeEach(function () {
    $client = Client::create([
        'name' => 'Fibo Test',
        'redirect' => 'http://localhost',
        'personal_access_client' => true,
        'password_client' => false,
        'revoked' => false,
        'secret' => \Illuminate\Support\Str::random(40),
    ]);

    // Register Personal Access Client
    DB::table('oauth_personal_access_clients')->insert([
        'client_id' => $client->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
});


it('returns 401 when user is not logged in', function () {
    $response = $this->postJson('/api/v1/auth/logout');

    $response->assertUnauthorized();
});

it('throws an exception when logout fails', function () {
    // Create a user for testing
    $user = App\Models\User::factory()->create();
    $this->actingAs($user);

    // Call the method to logout the user
    $response = $this->postJson('/api/v1/auth/logout');

    // Assert the response
    $response->assertForbidden();
});

it('logs out the user', function () {

    // Create a user for testing
    $user = mockHost();

    // Create a personal access token for the user
    $token = $user->createToken('fibo_auth_token')->token;

    // Call the method to logout the user
    $this->actingAs($user, 'api')
        ->postJson('/api/v1/auth/logout')
        ->assertOk();

    // Refresh the token from the database to check revocation
    $token->refresh();

    // Assert the token is revoked
    $this->assertTrue($token->revoked);
});
