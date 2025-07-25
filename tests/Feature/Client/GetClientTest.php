<?php

namespace Tests\Feature\Host;

beforeEach(function () {
    // Create a user for testing
    $this->host = mockHost();
    $this->client = \App\Models\User::factory()->create();
    $this->api_endpoint = '/api/v1/clients/' . $this->client->id;
});

it('fails and returns 401 unauthenticated request when Host is not logged in and tries to Get client data', function () {
    // Act
    $response = $this->getJson($this->api_endpoint);
    // Assert
    $response->assertUnauthorized();
});

it('fails and returns 403 unauthorized exception when logged in user is not a HOST and tries to Get client', function () {
    // arrange
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user, 'api');
    // Act
    $response = $this->getJson($this->api_endpoint);
    // Assert
    $response->assertForbidden();
});

it('returns the client data', function () {

    // arrange
    $this->actingAs($this->host, 'api');
    // act
    $response = $this->getJson($this->api_endpoint);

    // assert
    $response->assertOk()
        ->assertJsonFragment(
            ['email' => $this->client->email]
        );
});
