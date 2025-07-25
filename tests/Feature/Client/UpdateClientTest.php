<?php

namespace Tests\Feature\Host;

beforeEach(function () {
    // Create a user for testing
    $this->host = mockHost();
    $this->client = \App\Models\User::factory()->create();
    $this->api_endpoint = '/api/v1/clients/' . $this->client->id;
});

it('fails and returns 401 unauthenticated request when Host is not logged in and tries to Update client data', function () {
    // Act
    $response = $this->putJson($this->api_endpoint);
    // Assert
    $response->assertUnauthorized();
});

it('fails and returns 403 unauthorized exception when logged in user is not a HOST and tries to Update client', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->putJson($this->api_endpoint);

    $response->assertForbidden();
});

it('fails and returns 404 not found when trying to update a non-existing client', function () {
    // arrange
    $this->actingAs($this->host, 'api');
    $this->api_endpoint = '/api/v1/clients/clint-id-does-not-exist';

    // act
    $response = $this->putJson($this->api_endpoint);

    // assert
    $response->assertNotFound();
});

it('should not udpate the Client records when empty data set is passed', function () {

    // arrange
    $this->actingAs($this->host, 'api');
    $client = [
        'email' => 'ex@email.com',
        'mobile_number' => '1212121212',
        'first_name' => 'jon',
        'last_name' => 'day',
    ];
    $this->client->update($client);

    // act
    $response = $this->putJson($this->api_endpoint, []);

    // Assert 
    $response->assertCreated()
        ->assertJsonFragment($client);
    $this->assertDatabaseHas('users', ['id' => $this->client->id] + $client);
});

it('successfully updates client data', function () {

    // arrange
    $this->actingAs($this->host, 'api');
    $data = [
        'email' => 'ex@email.com',
        'mobile_number' => '1212121212',
        'first_name' => 'jon',
        'last_name' => 'day',
    ];
    // act
    $response = $this->putJson($this->api_endpoint, $data);

    // assert
    $response->assertCreated()
        ->assertJsonFragment($data);
    $this->assertDatabaseHas('users', $data + ['id' => $this->client->id]);
});
