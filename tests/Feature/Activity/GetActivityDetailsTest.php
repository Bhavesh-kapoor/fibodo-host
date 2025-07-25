<?php

namespace Tests\Feature\Activity;

use App\Models\Activity;
use App\Models\Product;

beforeEach(function () {
    // Create a user for testing
    $this->host = mockHost();
    $this->product = Product::factory()->create(['user_id' => $this->host->id]);
    $this->activity = Activity::factory()->create(['user_id' => $this->host->id, 'product_id' => $this->product->id]);
    $this->api_endpoint = '/api/v1/activities/' . $this->activity->id;
});

it('fails and returns 401 unauthenticated request when Host is not logged in and tries to get Activity Details', function () {
    // Act
    $response = $this->getJson($this->api_endpoint);
    // Assert
    $response->assertUnauthorized();
});

it('fails and returns 403 unauthorized exception when logged in user is not a HOST and tries to get Activity Details', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->getJson($this->api_endpoint);

    $response->assertForbidden();
});


it('fails and returns 404 not found when trying to get a non-existing activity', function () {
    // arrange
    $this->actingAs($this->host, 'api');
    $this->api_endpoint = '/api/v1/activities/Activiti-id-does-not-exist';

    // act
    $response = $this->getJson($this->api_endpoint);

    // assert
    $response->assertNotFound();
});

it('successfully returns the Activity details', function () {

    // arrange
    $this->actingAs($this->host, 'api');
    // act
    $response = $this->getJson($this->api_endpoint);

    // assert
    $response->assertOk()
        ->assertJsonCount(1, 'data.products')
        ->assertJsonFragment([
            'id' => $this->product->id,
            'title' => $this->product->title
        ]);
});
