<?php

namespace Tests\Feature\Host;

beforeEach(function () {
    // Create a user for testing
    $this->host = mockHost();
    $this->api_endpoint = '/api/v1/host/profile';
});

it('fails and returns 401 unauthenticated request when Host is not logged in and tries to update the Host Profile', function () {

    $response = $this->putJson($this->api_endpoint);

    $response->assertUnauthorized();
});

it('successfully upate logged in host profile data', function () {

    $this->actingAs($this->host, 'api');
    \App\Models\Host::factory()->create(['user_id' => $this->host->id]);

    $response = $this->putJson($this->api_endpoint);

    $response->assertCreated();
});

it('returns validation errors for invalid business_name', function () {

    // Authenticate as the 'host' user
    $this->actingAs($this->host, 'api');
    \App\Models\Host::factory()->create([
        'user_id' => $this->host->id,
    ]);

    // Attempt to create a product with missing or invalid data
    $response = $this->putJson($this->api_endpoint, [
        'business_name' => '', // Empty title (required field)
    ]);

    // Assert that the status is 422 (unprocessable entity)
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['business_name']); // Ensure validation error is returned for 'business_name'
});
