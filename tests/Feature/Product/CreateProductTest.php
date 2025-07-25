<?php

namespace Tests\Feature\Product\Create;

use App\Models\ProductType;

const API_ROUTE = '/api/v1/products';


beforeEach(fn() => $this->host = mockHost());

it('fails and returns 401 unauthenticated request when user is not logged in', function () {
    $response = $this->postJson(API_ROUTE);
    $response->assertUnauthorized();
});

it('fails and returns 403 unauthorized exception when logged in user is not a HOST', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->postJson(API_ROUTE);
    $response->assertForbidden();
});


// any required missing field validation
it('returns validation errors for missing required product fields', function () {
    // authenticate
    $this->actingAs($this->host, 'api');

    // act
    $response = $this->postJson(API_ROUTE, []);

    //assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'title',
            'product_type_id',
        ]);
});


it('returns validation error for missing product_type_id in product_types table', function () {
    // authenticate
    $this->actingAs($this->host, 'api');

    // act
    $response = $this->postJson(API_ROUTE, ['product_type_id' => '_not_exists_product_type_id']);

    //assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'product_type_id',
        ]);
});


it('allows an authenticated user with the HOST role to create a product with valid data', function () {
    // Authenticate as the 'host' user
    $this->actingAs($this->host, 'api');

    // Setup necessary dependencies (e.g., categories, activity types)
    $product_type_id = ProductType::factory()->create();

    // Create a product as a valid 'host' user
    $response = $this->postJson(API_ROUTE, [
        'title' => 'Valid Product Title',
        'product_type_id' => $product_type_id->id,

    ]);

    // Assert the product was successfully created
    $response->assertOk()
        ->assertJsonFragment(['title' => 'Valid Product Title']);

    // Ensure the product exists in the database
    $this->assertDatabaseHas('products', [
        'title' => 'Valid Product Title',
        'product_type_id' => $product_type_id->id,
    ]);
});


it('passes when created product is belongs to the logged in user or OWNER', function () {
    // Authenticate as the 'host' user
    $this->actingAs($this->host, 'api');

    // Setup necessary dependencies (e.g., categories, activity types)
    $product_type_id = ProductType::factory()->create();

    // Create a product as a valid 'host' user
    $response = $this->postJson(API_ROUTE, [
        'title' => 'Valid Product Title',
        'product_type_id' => $product_type_id->id,
    ]);

    // Assert the product was successfully created
    $response->assertOk()
        ->assertJsonFragment(['title' => 'Valid Product Title']);

    // Ensure the product exists in the database
    $this->assertDatabaseHas('products', [
        'title' => 'Valid Product Title',
        'product_type_id' => $product_type_id->id,
        'user_id' => $this->host->id
    ]);
});
