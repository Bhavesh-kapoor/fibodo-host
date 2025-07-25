<?php

namespace Tests\Feature\Product\Archive;

use App\Models\Product;

beforeEach(function () {
    $this->host = mockHost();
    $this->product = Product::factory()->create(['user_id' => $this->host->id]);
    $this->api_endpoint = '/api/v1/products/' . $this->product->id;
});

it('fails and returns 401 unauthenticated request when user is not logged in and tries to update the product archive', function () {
    $response = $this->putJson($this->api_endpoint);
    $response->assertUnauthorized();
});

it('fails and returns 403 unauthorized exception when logged in user is not a HOST and tries to update the product archive', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->putJson($this->api_endpoint);
    $response->assertForbidden();
});

it('fails when logged in user is not the product owner for archive', function () {

    $user = mockHost();
    $this->actingAs($user, 'api');

    // act
    $response = $this->putJson($this->api_endpoint);

    // assert 
    $response->assertForbidden();
});

it('successfully archives the product', function () {

    $this->actingAs($this->host, 'api');

    $response = $this->postJson('/api/v1/products/' . $this->product->id . '/archive');

    // Assert 
    $response->assertOk();
    $this->assertDatabaseHas('products', ['status' => 3]);
});

it('successfully restore the product', function () {

    $this->actingAs($this->host, 'api');

    $this->product->archive();

    $response = $this->postJson('/api/v1/products/' . $this->product->id . '/restore');

    // Assert 
    $response->assertOk();
    $this->assertDatabaseHas('products', ['status' => 1]);
});
