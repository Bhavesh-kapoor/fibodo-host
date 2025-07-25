<?php

use App\Models\Price;
use App\Models\Product;
use Illuminate\Http\Response;

beforeEach(function () {
    // Create a user for testing
    $this->guest = App\Models\User::factory()->create();
    $this->user = mockHost();
});


it('returns 401 authentication error if the user is not authenticated', function () {

    // arrange
    $product = Product::factory()->create(['user_id' => $this->guest->id]);

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', Price::factory()->make()->toArray());

    // assert
    $response->assertStatus(401);
});

it('returns 403 Forbidden error if the user is not a HOST', function () {

    // arrange
    $product = Product::factory()->create(['user_id' => $this->guest->id]);
    $this->actingAs($this->guest, 'api');

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', Price::factory()->make()->toArray());

    // assert
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('returns an validation error if empty request is sent', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', []);

    // assert
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('passes when only standard pricing is sent without any age sensitive or walk-in or special pricing', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);
    $price = 20;

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', ['price' => $price]);

    // assert
    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson(['message' => __('messages.saved')]);
    $this->assertDatabaseHas('prices',  ['price' => $price]);
});


# Age sensitive validations
it('fails when is_age_sensitive is true but junior_price is not sent', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', ['is_age_sensitive' => 1]);

    // assert
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['junior_price']);
});
it('fails when is_age_sensitive is true but adult_price is not sent', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', ['is_age_sensitive' => 1]);

    // assert
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['adult_price']);
});
it('fails when is_age_sensitive is true but senior_price is not sent', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', ['is_age_sensitive' => 1]);

    // assert
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['senior_price']);
});
it('fails when is_age_sensitive is true but any of the age sensitive pricing are not sent', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', ['is_age_sensitive' => 1]);

    // assert
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors([
            'junior_price',
            'adult_price',
            'senior_price',
        ]);
});
it('passes when is_age_sensitive is true and only age sensitive pricing are sent', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    $data = [
        'is_age_sensitive' => 1,
        'junior_price' => fake()->randomFloat(2, 0, 999999),
        'adult_price' => fake()->randomFloat(2, 0, 999999),
        'senior_price' => fake()->randomFloat(2, 0, 999999)
    ];
    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', $data);

    // assert
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('prices', $data);
});
it('fails when standard price is not set as its always required', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);
    $price = 20;

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', ['walk_in_price' => $price]);

    // assert
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['price']);
});

# Walk In pricing validations
it('passes when only standard walk_in_pricing is sent without any walk-in age sensitive', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);
    $price = 20;

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', ['price' => $price, 'walk_in_price' => $price]);

    // assert
    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson(['message' => __('messages.saved')]);
    $this->assertDatabaseHas('prices',  ['price' => $price]);
});

it('fails when walk-in price is age sensitive but walk-in junior price is not sent', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', ['is_walk_in_pricing' => 1, 'is_walk_in_age_sensitive' => 1]);

    // assert
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['walk_in_junior_price']);
});
it('fails when walk-in price is age sensitive but walk-in adult pricing is not sent', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', ['is_walk_in_pricing' => 1, 'is_walk_in_age_sensitive' => 1]);

    // assert
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['walk_in_adult_price']);
});
it('fails when walk-in price is age sensitive but walk-in senior pricce is not sent', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', ['is_walk_in_pricing' => 1, 'is_walk_in_age_sensitive' => 1]);

    // assert
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['walk_in_senior_price']);
});
it('fails when walk-in price is age sensitive but any of the walk-in age sensitive pricing is not sent', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', ['is_walk_in_pricing' => 1, 'is_walk_in_age_sensitive' => 1]);

    // assert
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors([
            'walk_in_junior_price',
            'walk_in_adult_price',
            'walk_in_senior_price',
        ]);
});
it('passes when walk-in is age sensitve and all walk-in age sensitive pricing are sent with standard price', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    $data = [
        'is_walk_in_pricing' => 1,
        'is_walk_in_age_sensitive' => 1,
        'price' => fake()->randomFloat(2, 0, 999999),
        'walk_in_junior_price' => fake()->randomFloat(2, 0, 999999),
        'walk_in_adult_price' => fake()->randomFloat(2, 0, 999999),
        'walk_in_senior_price' => fake()->randomFloat(2, 0, 999999)
    ];

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', $data);

    // assert
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('prices', $data);
});

# Special Pricing 
it('fails when is_special_pricing is true but multi_attendee_price is not sent', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', ['is_special_pricing' => 1]);

    // assert
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['multi_attendee_price']);
});
it('fails when is_special_pricing is true but all_space_price is not sent', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', ['is_special_pricing' => 1]);

    // assert
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['all_space_price']);
});
it('fails when is_special_pricing is true but any of the special pricing is not sent', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', ['is_special_pricing' => 1]);

    // assert
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors([
            'multi_attendee_price',
            'all_space_price',
        ]);
});
it('passes when when is_special_pricing is true and all special pricing are sent with standard price', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    $data = [
        'is_special_pricing' => 1,
        'price' => fake()->randomFloat(2, 0, 999999),
        'all_space_price' => fake()->randomFloat(2, 0, 999999),
        'multi_attendee_price' => fake()->randomFloat(2, 0, 999999)
    ];

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', $data);

    // assert
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('prices', $data);
});

# Product test
it('fails and return 404 not found if the product does not exists', function () {
    // arrange
    $this->actingAs($this->user, 'api');

    // act
    $response = $this->postJson('/api/v1/products/' . 1234 . '/price', []);

    // assert
    $response->assertStatus(Response::HTTP_NOT_FOUND);
});


it('successfully records the full data set in database when all the validations passes', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    $data = [

        'price' => fake()->randomFloat(2, 0, 999999),
        'is_age_sensitive' => 1,
        'junior_price' => fake()->randomFloat(2, 0, 999999),
        'adult_price' => fake()->randomFloat(2, 0, 999999),
        'senior_price' => fake()->randomFloat(2, 0, 999999),

        'is_walk_in_pricing' => 1,
        'is_walk_in_age_sensitive' => 1,
        'walk_in_junior_price' => fake()->randomFloat(2, 0, 999999),
        'walk_in_adult_price' => fake()->randomFloat(2, 0, 999999),
        'walk_in_senior_price' => fake()->randomFloat(2, 0, 999999),

        'is_special_pricing' => 1,
        'all_space_price' => fake()->randomFloat(2, 0, 999999),
        'multi_attendee_price' => fake()->randomFloat(2, 0, 999999)
    ];

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', $data);

    // assert
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('prices', $data);
});

# test One to One relationship
it('records only one price record per product', function () {

    // arrange
    $this->actingAs($this->user, 'api');

    // create host role to be assigned to user 
    $product = Product::factory()->create(['user_id' => $this->user->id]);

    $dataOne = [

        'price' => fake()->randomFloat(2, 0, 999999),
        'is_age_sensitive' => 1,
        'junior_price' => fake()->randomFloat(2, 0, 999999),
        'adult_price' => fake()->randomFloat(2, 0, 999999),
        'senior_price' => fake()->randomFloat(2, 0, 999999),

        'is_walk_in_pricing' => 1,
        'is_walk_in_age_sensitive' => 1,
        'walk_in_junior_price' => fake()->randomFloat(2, 0, 999999),
        'walk_in_adult_price' => fake()->randomFloat(2, 0, 999999),
        'walk_in_senior_price' => fake()->randomFloat(2, 0, 999999),

        'is_special_pricing' => 1,
        'all_space_price' => fake()->randomFloat(2, 0, 999999),
        'multi_attendee_price' => fake()->randomFloat(2, 0, 999999)
    ];

    $dataTwo = [

        'price' => fake()->randomFloat(2, 0, 999999),
        'is_age_sensitive' => 1,
        'junior_price' => fake()->randomFloat(2, 0, 999999),
        'adult_price' => fake()->randomFloat(2, 0, 999999),
        'senior_price' => fake()->randomFloat(2, 0, 999999),

        'is_walk_in_pricing' => 1,
        'is_walk_in_age_sensitive' => 1,
        'walk_in_junior_price' => fake()->randomFloat(2, 0, 999999),
        'walk_in_adult_price' => fake()->randomFloat(2, 0, 999999),
        'walk_in_senior_price' => fake()->randomFloat(2, 0, 999999),

        'is_special_pricing' => 1,
        'all_space_price' => fake()->randomFloat(2, 0, 999999),
        'multi_attendee_price' => fake()->randomFloat(2, 0, 999999)
    ];

    // act
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', $dataOne);
    $response = $this->postJson('/api/v1/products/' . $product->id . '/price', $dataTwo);

    // assert
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseCount('prices', 1);
});
