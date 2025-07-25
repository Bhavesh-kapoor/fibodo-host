<?php

use App\Models\Price;
use App\Models\Product;
use Illuminate\Http\Response;


beforeEach(function () {
    // Create a user for testing
    $this->guest = App\Models\User::factory()->create();
    $this->product = Product::factory()->create(['user_id' => $this->guest->id]);
    $this->user = mockHost();
});

it('returns 401 authentication error if the user is not authenticated', function () {
    // arrange


    // act
    $response = $this->putJson('/api/v1/products/' . $this->product->id . '/price');

    // assert
    $response->assertStatus(401);
});

it('returns 403 Forbiddin error if the user is not a HOST and tries to update price', function () {
    // arrange
    $this->actingAs($this->guest, 'api');

    // act
    $response = $this->putJson('/api/v1/products/' . $this->product->id . '/price');

    // assert
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});


it('returns error when logged in user not a product owner', function () {
    // arrange

    $this->actingAs($this->user, 'api');
    $product = Product::factory()->create(['user_id' => $this->user->id]);


    // act
    $response = $this->putJson('/api/v1/products/' . $this->product->id . '/price');

    // assert
    $response->assertForbidden();
});

it('updates standard pricing successfully without age-sensitive, walk-in, or special pricing', function () {
    // arrange
    $this->actingAs($this->user, 'api');

    $product = Product::factory()->create(['user_id' => $this->user->id]);
    Price::factory()->create(['product_id' => $product->id, 'price' => 100]);

    // act
    $response = $this->putJson('/api/v1/products/' . $product->id . '/price', ['price' => 150]);

    // assert
    $response->assertCreated(201);

    $this->assertDatabaseHas('prices', ['price' => 150]);
});
