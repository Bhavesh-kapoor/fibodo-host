<?php

use App\Models\Product;

beforeEach(function () {
    // Create a user for testing
    $this->host = mockHost();
    $this->product = Product::factory()->create(['user_id' => $this->host->id]);
    $this->api_endpoint = '/api/v1/products/' . $this->product->id . '/location';
});

it('fails to access POST location service and returns 401 authentication error if the user is not authenticated', function () {

    // act
    $response = $this->putJson($this->api_endpoint, Product::factory()->make()->toArray());

    // assert
    $response->assertStatus(401);
});

it('fails to access POST location service and returns 403 Forbidden error if the user is not a HOST', function () {

    // arrange
    $user = App\Models\User::factory()->create();
    $this->actingAs($user, 'api');

    // act
    $response = $this->postJson($this->api_endpoint, []);

    // assert
    $response->assertForbidden();
});

it('fails when logged in user is not the product owner and tries to update the location for the product', function () {

    $user = mockHost();
    $this->actingAs($user, 'api');

    // act
    $response = $this->postJson($this->api_endpoint);

    // assert 
    $response->assertForbidden();
});

it('should not udpate the location records when empty data set is passed', function () {

    // arrange
    $this->actingAs($this->host, 'api');
    $location = [
        'location' => 'USA',
        'note' => 'your notes',
        'lat' => '28.6139',
        'long' => '77.2088'
    ];
    $this->product->update($location);

    // act
    $response = $this->putJson($this->api_endpoint, []);

    // Assert 
    $response->assertCreated()
        ->assertJsonFragment($location);
    $this->assertDatabaseHas('products', ['id' => $this->product->id] + $location);
});

it('creates product location with valid data', function () {

    // arrange
    $this->actingAs($this->host, 'api');

    $data = [
        'location' => 'USA',
        'note' => 'your notes',
        'lat' => '28.6139',
        'long' => '77.2088'
    ];

    // act
    $response = $this->postJson($this->api_endpoint, $data);

    // assert
    $response->assertCreated()
        ->assertJsonFragment($data);
    $this->assertDatabaseHas('products', ['id' => $this->product->id] + $data);
});
