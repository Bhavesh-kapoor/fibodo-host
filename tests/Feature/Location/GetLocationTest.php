<?php

use App\Models\Product;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    // Create a user for testing
    $this->host = mockHost();
    $this->product = Product::factory()->create(['user_id' => $this->host->id]);
    $this->api_endpoint = '/api/v1/products/' . $this->product->id . '/location';
});

it('fails to access GET location service and returns 401 authentication error if the user is not authenticated', function () {

    // act
    $response = $this->getJson($this->api_endpoint, Product::factory()->make()->toArray());

    // assert
    $response->assertStatus(401);
});

it('fails to access GET location service and returns 403 Forbidden error if the user is not a HOST', function () {

    // arrange
    $user = App\Models\User::factory()->create();
    $this->actingAs($user, 'api');

    // act
    $response = $this->getJson($this->api_endpoint, []);

    // assert
    $response->assertForbidden();
});

it('fails when logged in user is not the product owner and tries to get the location for the product', function () {

    $user = mockHost();
    $this->actingAs($user, 'api');

    // act
    $response = $this->getJson($this->api_endpoint);

    // assert 
    $response->assertForbidden();
});

it('returns the location data', function () {

    // arrange 
    actingAs($this->host, 'api');
    $location = [
        'location' => 'USA',
        'note' => 'your notes',
        'lat' => '28.6139',
        'long' => '77.2088'
    ];
    $this->product->update($location);

    // act 
    $response = $this->getJson($this->api_endpoint);

    // assert 
    $response->assertOk()
        ->assertJsonFragment($location);
});
