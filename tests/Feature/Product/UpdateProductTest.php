<?php

namespace Tests\Feature\Product\Update;

use App\Models\ActivityType;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Category;


beforeEach(function () {
    $this->host = mockHost();
    $this->product = Product::factory()->create(['user_id' => $this->host->id]);
    $this->api_endpoint = '/api/v1/products/' . $this->product->id;
});

it('fails and returns 401 unauthenticated request when user is not logged in and tries to update the product', function () {
    $response = $this->putJson($this->api_endpoint);
    $response->assertUnauthorized();
});

it('fails and returns 403 unauthorized exception when logged in user is not a HOST and tries to update the product', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->putJson($this->api_endpoint);
    $response->assertForbidden();
});

it('fails when logged in user is not the product owner', function () {

    $user = mockHost();
    $this->actingAs($user, 'api');

    // act
    $response = $this->putJson($this->api_endpoint);

    // assert 
    $response->assertForbidden();
});


it('returns validation errors for invalid title', function () {

    // Authenticate as the 'host' user
    $this->actingAs($this->host, 'api');

    // Attempt to create a product with missing or invalid data
    $response = $this->putJson($this->api_endpoint, [
        'title' => '', // Empty title (required field)
    ]);

    // Assert that the status is 422 (unprocessable entity)
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['title']); // Ensure validation error is returned for 'title'
});


it('returns validation error for invalid price', function () {

    // Authenticate as the 'host' user
    $this->actingAs($this->host, 'api');

    // Attempt to create a product with an invalid price (non-numeric)
    $response = $this->putJson($this->api_endpoint, [
        'price' => 'invalid_price',
    ]);

    // Assert that the validation error is for 'price'
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['price']);
});

it('returns validation errors for invalid product_type_id', function () {

    // Authenticate as the 'host' user
    $this->actingAs($this->host, 'api');

    // Attempt to create a product with missing or invalid data
    $response = $this->putJson($this->api_endpoint, [
        'product_type_id' => '_id_not_exists_in_db',
    ]);

    // Assert that the status is 422 (unprocessable entity)
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['product_type_id']);
});

it('fails to create product when category_id does not exist', function () {
    // Authenticate as the 'host' user
    $this->actingAs($this->host, 'api');

    // Attempt to create a product with a non-existent category_id
    $response = $this->putJson($this->api_endpoint, [
        'title' => 'Product with Invalid Category',
        'category_id' => 99999,  // Non-existent category ID
    ]);

    // Assert validation error for 'category_id'
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['category_id']);
});

it('returns validation error for sub_category_id if not belonging to the parent category', function () {

    // Authenticate as the 'host' user
    $this->actingAs($this->host, 'api');

    // Create a category and a different one that does not match the category rule
    $category = Category::factory()->create();
    $subCategory = Category::factory()->create();

    // Pass data with a sub_category_id that violates the custom validation
    $response = $this->putJson($this->api_endpoint, [
        'category_id' => $category->id,
        'sub_category_id' => $subCategory->id,  // Different category
    ]);

    // Assert validation error for sub_category_id
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['sub_category_id']);
});


it('fails to create product when activity_type does not exist', function () {
    // Authenticate as the 'host' user
    $this->actingAs($this->host, 'api');

    // Attempt to create a product with a non-existent activity_type
    $response = $this->putJson($this->api_endpoint, [
        'activity_type_id' => 99999,  // Non-existent activity_type ID
    ]);

    // Assert validation error for 'activity_type_id'
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['activity_type_id']);
});

it('allows an authenticated user with the HOST role to update a product with valid data', function () {
    // Authenticate as the 'host' user
    $this->actingAs($this->host, 'api');

    // Setup necessary dependencies (e.g., categories, activity types)
    $category = Category::factory()->create();
    $subCategory = Category::factory()->create(['parent_id' => $category->id]);
    $activityType = ActivityType::factory()->create();
    $product_type_id = ProductType::factory()->create();
    // create product 
    Product::factory()->create([
        'title' => 'Invalid  Title',
        'product_type_id' => $product_type_id->id,
    ]);

    $data = [
        'title' => 'Valid Product Title',
        'sub_title' => 'Valid sub title',
        'description' => 'valid description',
        'kcal_burn' => 80,
        'price' => 499.99,
        'seats' => 40,
        'category_id' => $category->id,
        'sub_category_id' => $subCategory->id,
        'activity_type_id' => $activityType->id,
        'product_type_id' => $product_type_id->id,
    ];

    // Create a product as a valid data
    $response = $this->putJson($this->api_endpoint, $data);

    // Assert the product was successfully created
    $response->assertCreated();

    // Ensure the product exists in the database
    $this->assertDatabaseHas('products', $data);
});
