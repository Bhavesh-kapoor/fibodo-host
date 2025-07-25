<?php

use App\Models\Product;


beforeEach(function () {
    // Create a user for testing
    $this->host = mockHost();
    $this->product = Product::factory()->create(['user_id' => $this->host->id]);
    $this->api_endpoint = '/api/v1/products/' . $this->product->id . '/attendee-settings';
});


it('fails to access GET attendee-setting service and returns 401 authentication error if the user is not authenticated', function () {

    // act
    $response = $this->getJson($this->api_endpoint);

    // assert
    $response->assertStatus(401);
});


it('fails to access GET attendee-setting service and returns 403 Forbiddin error if the user is not a HOST', function () {

    // arrange
    $user = App\Models\User::factory()->create();
    $this->actingAs($user, 'api');

    // act
    $response = $this->getJson($this->api_endpoint);

    // assert
    $response->assertForbidden();
});

it('fails when logged in user is not the product owner and tries to get the attendee-seetings for the product', function () {

    $user = mockHost();
    $this->actingAs($user, 'api');

    // act
    $response = $this->getJson($this->api_endpoint);

    // assert 
    $response->assertForbidden();
});

it('returns the attendee-setting data', function () {

    // arrange 
    $this->actingAs($this->host, 'api');

    $attendeeSetting = [
        'ability_level' => 'beginner',
        'has_age_restriction' => 1,
        'age_below' => 20,
        'age_above' => 45,
        'gender_restrictions' => 'men',
        'is_family_friendly' => 0
    ];

    $this->product->update(['attendee_settings' => $attendeeSetting]);

    // act 
    $response = $this->getJson($this->api_endpoint);

    // assert 
    $response->assertOk()
        ->assertJsonFragment($attendeeSetting);
});
