<?php

namespace Tests\Feature\Host;

beforeEach(function () {
    // Create a user for testing
    $this->host = mockHost();
    $this->api_endpoint = '/api/v1/host/profile';
});

it('returns 401 unauthenticated request when host is not logged in', function () {

    $response = $this->getJson($this->api_endpoint);

    $response->assertUnauthorized();
});

it('returns logged in host profile data', function () {

    $this->actingAs($this->host, 'api');
    \App\Models\Host::factory()->create(['user_id' => $this->host->id]);

    $response = $this->getJson($this->api_endpoint);

    $response->assertOk();
});

it('successfully returns the logged in host profile', function () {
    // Arrange
    $this->actingAs($this->host, 'api');
    \App\Models\Host::factory()->create([
        'user_id' => $this->host->id,
        'business_name' => "Weber PLC"
    ]);

    $hostData = [
        'email' => "host@example.com",
        'mobile_number' => "740-301-1623",
        'first_name' => "Host",
        'last_name' => "Fibo",
        'date_of_birth' => "1970-11-23",
        'gender' => "female",
        'avatar' => "http://via.placeholder.com/640x480.png/005555?text=non"
    ];

    $this->host->update($hostData);

    // Act
    $response = $this->getJson($this->api_endpoint);

    // Assert
    $response->assertOk()
        ->assertJsonFragment($hostData);
});
