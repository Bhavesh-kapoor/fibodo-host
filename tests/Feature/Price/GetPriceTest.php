<?php

use App\Models\Product;

beforeEach(function () {
    // Create a user for testing
    $this->user = mockHost();
    $this->product = Product::factory()->create(['user_id' => $this->user->id]);
});

it('returns 401 authentication error if the user is not authenticated', function () {
    // act
    $response = $this->getJson('/api/v1/products/' . $this->product->id . '/price');

    // assert
    $response->assertStatus(401);
});


it('returns 403 Forbidden error if the user is not a HOST and tries to get price', function () {
    // arrange    
    $this->guest = App\Models\User::factory()->create();
    $this->actingAs($this->guest, 'api');

    // act
    $response = $this->getJson('/api/v1/products/' . $this->product->id . '/price');

    // assert
    $response->assertForbidden();
});

it('fails when logged in user is not the product owner and tries to get the prices for the product', function () {

    $user = mockHost();
    $this->actingAs($user, 'api');

    // act
    $response = $this->getJson('/api/v1/products/' . $this->product->id . '/price');

    // assert 
    $response->assertForbidden();
});

it('returns the partial data if is_age_sensitive is_walk_in_pricing is_walk_in_age_sensitive is_special_pricing false ', function () {

    // arrange 
    $this->actingAs($this->user, 'api');
    $priceData = ([
        'price' => 1000,
        'is_age_sensitive' => 0,
        'is_walk_in_pricing' => 0,
        'is_walk_in_age_sensitive' => 0,
        'is_special_pricing' => 0,
    ]);

    $this->product->price()->create($priceData);

    // act 
    $response = $this->getJson('/api/v1/products/' . $this->product->id . '/price');

    // assert 
    $response->assertOk()
        ->assertJsonFragment($priceData);
});

it('returns the price data when all the fields are filled', function () {

    // arrange 
    $this->actingAs($this->user, 'api');
    $priceData = ([
        'price' => 1000,
        'is_age_sensitive' => 1,
        'junior_price' => 500,
        'adult_price' => 1000,
        'senior_price' => 1500,
        'is_walk_in_pricing' => 1,
        'walk_in_price' => 2000,
        'is_walk_in_age_sensitive' => 1,
        'walk_in_junior_price' => 1000,
        'walk_in_adult_price' => 2000,
        'walk_in_senior_price' => 3000,
        'is_special_pricing' => 1,
        'multi_attendee_price' => 500,
        'all_space_price' => 2000,
    ]);

    $this->product->price()->create($priceData);

    // act 
    $response = $this->getJson('/api/v1/products/' . $this->product->id . '/price');

    // assert 
    $response->assertOk()
        ->assertJsonFragment($priceData);
});
