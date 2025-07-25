<?php


use App\Models\Product;
use Database\Factories\AttendeeSettingsFactory;
use Illuminate\Http\Response;

beforeEach(function () {
    // Create a user for testing
    $this->host = mockHost();
    $this->product = Product::factory()->create(['user_id' => $this->host->id]);
    $this->api_endpoint = '/api/v1/products/' . $this->product->id . '/attendee-settings';
});

it('returns 401 authentication error if the user is not authenticated', function () {

    // act
    $response = $this->postJson($this->api_endpoint, AttendeeSettingsFactory::make());

    // assert
    $response->assertStatus(401);
});

it('returns 403 Forbidden error if the user is not a HOST', function () {

    // arrange
    $user = App\Models\User::factory()->create();
    $this->actingAs($user, 'api');

    // act
    $response = $this->postJson($this->api_endpoint, AttendeeSettingsFactory::make());

    // assert
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('creates attendee settings when valid data is provided', function () {

    // arrange
    $this->actingAs($this->host, 'api');
    $data = [
        'ability_level' => 'beginner',
        'has_age_restriction' => 1,
        'age_below' => 10,
        'age_above' => 40,
        'gender_restrictions' => 'men',
        'is_family_friendly' => 1
    ];

    // act
    $response = $this->postJson($this->api_endpoint, $data);

    // assert
    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJsonFragment($data);
    $this->assertDatabaseHas('attendee_settings', ['product_id' => $this->product->id] + $data);
});

it('removes age values if is_age_restriction is provided as 0 or false', function () {

    // arrange
    $this->actingAs($this->host, 'api');

    // act
    $response = $this->postJson($this->api_endpoint, ['ability_level' => 'beginner', 'is_age_restriction' => 0, 'age_below' => 10, 'age_above' => 40]);

    // assert
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('attendee_settings', ['product_id' => $this->product->id])
        ->assertDatabaseMissing('attendee_settings', ['product_id' => $this->product->id, 'age_below' => 10, 'age_above' => 40]);
});

it('deletes the attendee settings and returns 404 exception if empty data is passed to attendee settings', function () {

    // arrange
    $this->actingAs($this->host, 'api');
    $this->product->update(['attendee_settings' => ['ability_level' => 'beginner']]);

    // act
    $response = $this->postJson($this->api_endpoint, []);

    // assert
    $response->assertStatus(Response::HTTP_NOT_FOUND);
    $this->assertDatabaseMissing('attendee_settings', ['product_id' => $this->product->id]);
});
