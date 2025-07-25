<?php

namespace Tests\Feature\Activity;

use App\Models\Activity;
use App\Models\Product;

beforeEach(function () {
    // Create a user for testing
    $this->host = mockHost();
    $this->product = Product::factory()->create(['user_id' => $this->host->id]);
    $this->api_endpoint = '/api/v1/activities';
});

it('fails and returns 401 unauthenticated request when Host is not logged in and tries to get Activities', function () {
    // Act
    $response = $this->getJson($this->api_endpoint);
    // Assert
    $response->assertUnauthorized();
});

it('fails and returns 403 unauthorized exception when logged in user is not a HOST and tries to get activities', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->getJson($this->api_endpoint);

    $response->assertForbidden();
});


it('successfully returns all the activities of a product', function () {

    // arrange
    $this->actingAs($this->host, 'api');
    $this->product->activities()->createMany(Activity::factory(5)->raw(['user_id' => $this->host->id]));

    // act
    $response = $this->getJson($this->api_endpoint . '/?product_id=' . $this->product->id);


    // assert
    $response->assertOk()
        ->assertJsonFragment([
            'id' => $this->product->id,
            'title' => $this->product->title
        ])
        ->assertJsonCount(5, 'data.products');
});
