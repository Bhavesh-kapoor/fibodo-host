<?php

beforeEach(fn() => $this->host = mockHost());

it('returns 401 unauthenticated request when user is not logged in', function () {

    $response = $this->getJson('/api/v1/products');

    $response->assertUnauthorized();
});

it('returns 403 unauthorized exception when logged in user is not a HOST', function () {

    $user = App\Models\User::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->getJson('/api/v1/products');

    $response->assertForbidden();
});

it('returns successfull response when logged in user is a HOST', function () {

    $this->actingAs($this->host, 'api');

    $response = $this->getJson('/api/v1/products');

    $response->assertOk();
});

it('returns all the products list created by the authenticated host', function () {
    // Mock user 
    $this->actingAs($this->host, 'api');

    // prepare Product Data 
    $this->host->products()->createMany(
        App\Models\Product::factory(10)->make()->toArray()
    );

    // act 
    $response = $this->getJson('/api/v1/products');

    // assert
    $response->assertOk();
    expect($response->json('data'))->toHaveCount(10);
});

it('returns the products only created by the authenticated host', function () {

    // prepare Product Data 
    $this->host->products()->createMany(
        App\Models\Product::factory(5)->make()->toArray()
    );

    // create products for another user
    mockHost()->products()->createMany(
        App\Models\Product::factory(5)->make()->toArray()
    );

    $this->actingAs($this->host, 'api');

    $response = $this->getJson('/api/v1/products');

    // assert
    $response->assertOk();
    expect($response->json('data'))->toHaveCount(5);
});
