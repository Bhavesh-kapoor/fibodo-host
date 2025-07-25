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

it('fails and returns 401 unauthenticated request when Host is not logged in and tries to Update Activities', function () {
    // Act
    $response = $this->putJson($this->api_endpoint);
    // Assert
    $response->assertUnauthorized();
});

it('fails and returns 403 unauthorized exception when logged in user is not a HOST and tries to Update activities', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->putJson($this->api_endpoint);

    $response->assertForbidden();
});


it('fails and returns 404 not found when trying to update a non-existing activity', function () {
    // arrange
    $this->actingAs($this->host, 'api');
    $this->api_endpoint = '/api/v1/activities/Activiti-id-does-not-exist';

    // act
    $response = $this->putJson($this->api_endpoint);

    // assert
    $response->assertNotFound();
});


it('should not udpate the Activity records when empty data set is passed', function () {

    // arrange
    $this->actingAs($this->host, 'api');
    $activity = [
        'start_time' => '1973-08-30 13:43:45',
        'end_time' => '1973-08-30 13:43:45',
    ];

    // update manually
    $this->activity->update($activity);

    // act, try updating and setting up empty values
    $response = $this->putJson($this->api_endpoint, []);

    // Assert 
    $response->assertOk()
        ->assertJsonFragment($activity);
    $this->assertDatabaseHas('activities', $activity + ['id' => $this->activity->id]);
});


it('successfully updates the activity when valid data is passed', function () {

    // arrange
    $this->actingAs($this->host, 'api');
    $new_product_id = Product::factory()->create(['user_id' => $this->host->id])->id;

    $activity = [
        'start_time' => '2024-08-30 13:43',
        'end_time' => '2024-08-30 14:43'
    ];
    // act
    $response = $this->putJson($this->api_endpoint, $activity + ['product_ids' => [$new_product_id]]);

    // assert
    $response->assertOk()
        ->assertJsonFragment($activity);
    $this->assertDatabaseHas('activities', $activity);
});
