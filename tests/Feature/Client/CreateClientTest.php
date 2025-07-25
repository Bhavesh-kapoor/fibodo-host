<?php

namespace Tests\Feature\Host;

beforeEach(function () {
    // Create a user for testing
    $this->host = mockHost();
    $this->api_endpoint = '/api/v1/clients';
});

it('fails and returns 401 unauthenticated request when Host is not logged in and tries to Create client', function () {
    // Act
    $response = $this->postJson($this->api_endpoint);
    // Assert
    $response->assertUnauthorized();
});

it('fails and returns 403 unauthorized exception when logged in user is not a HOST and tries to create client', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->postJson($this->api_endpoint);

    $response->assertForbidden();
});

it('returns validation errors for missing required client fields', function () {
    // authenticate
    $this->actingAs($this->host, 'api');

    // act
    $response = $this->postJson($this->api_endpoint, []);

    //assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'email',
            'mobile_number',
            'first_name',
            'last_name',
        ]);
});

it('successfully creates client', function () {

    // arrange
    $this->actingAs($this->host, 'api');
    \App\Models\Role::firstOrCreate(['name' => 'client', 'guard_name' => 'api']);

    $client = [
        'email' => 'ex@mail.com',
        'mobile_number' => '1019181716',
        'first_name' => 'jon',
        'last_name' => 'day',
    ];

    // act
    $response = $this->postJson($this->api_endpoint, $client);
    // assert
    $response->assertCreated();
});


it('returns an error for duplicate Client email', function () {

    $this->actingAs($this->host, 'api');
    \App\Models\Role::firstOrCreate(['name' => 'client', 'guard_name' => 'api']);
    $fakeEmail = fake()->safeEmail();
    \App\Models\User::factory()->create(['email' => $fakeEmail]);

    $response = $this->postJson($this->api_endpoint, [
        'email' => $fakeEmail,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});
