<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

it('fails when wrong credentials are entered', function () {
    // Given a user with a password
    User::factory()->create([
        'email' => 'john.dev@example.com',
        'password' => Hash::make('Abc@2025#'),
    ]);

    // When the login request is made with wrong password
    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'john.dev@example.com',
        'password' => 'Abc@2025##',
    ]);


    // Then the response should return an invalid credentials error
    $response->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJson([
            'success' => false,
            'message' => __('auth.failed')
        ]);
});

it('returns an access token for valid login', function () {

    // Given a user with a password
    User::factory()->create([
        'email' => 'john.dev@example.com',
        'password' => Hash::make('Abc@2025#'),
        'status' => 1
    ]);

    // When the login request is made
    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'john.dev@example.com',
        'password' => 'Abc@2025#',
    ]);

    // Then the response should return a valid access token
    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'success',
            'data' => ['auth' => ['token', 'token_type', 'expires_at']],
            'message',
        ]);
});
