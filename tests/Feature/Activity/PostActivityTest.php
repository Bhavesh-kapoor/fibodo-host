<?php

namespace Tests\Feature\Activity;

use App\Models\Product;

beforeEach(function () {
    // Create a user for testing
    $this->host = mockHost();
    $this->product = Product::factory()->create(['user_id' => $this->host->id]);
    $this->api_endpoint = '/api/v1/activities';
});

// create schedule 
function createSchedule($product, $start_time, $end_time)
{
    $schedule = \App\Models\Schedules\Schedule::factory(['status' => 1])
        ->for($product)
        ->has(
            \App\Models\Schedules\WeeklySchedule::factory()
                ->count(2)
                ->has(
                    \App\Models\Schedules\ScheduleDay::factory(['start_time' => $start_time, 'end_time' => $end_time])
                        ->count(7) // 7 Days a week
                        ->has(
                            \App\Models\Schedules\ScheduleBreak::factory()->count(2), // Each day has 2 breaks
                            'breaks'
                        ),
                    'days'
                ),
            'weeklySchedules'
        )
        ->create();
}

it('fails and returns 401 unauthenticated request when Host is not logged in and tries to Create Activities', function () {
    // Act
    $response = $this->postJson($this->api_endpoint);
    // Assert
    $response->assertUnauthorized();
});

it('fails and returns 403 unauthorized exception when logged in user is not a HOST and tries to create activities', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->postJson($this->api_endpoint);

    $response->assertForbidden();
});


it('returns validation errors for missing required activity fields', function () {
    // authenticate
    $this->actingAs($this->host, 'api');

    // act
    $response = $this->postJson($this->api_endpoint, []);

    //assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['start_time', 'end_time', 'product_ids']);
});

it('fails if any product does not have a schedule', function () {
    // arrange
    $this->actingAs($this->host, 'api');

    $activity = [
        'start_time' => '2024-08-30 13:30',
        'end_time' => '2024-08-30 14:40',
        'product_ids' => [$this->product->id],
    ];

    // act
    $response = $this->postJson($this->api_endpoint, $activity);


    // assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['schedules']);
});


it('successfully creates activity when valid data is passed', function () {

    // arrange
    $this->actingAs($this->host, 'api');

    // create schdule 
    createSchedule($this->product, '11:30', '18:30');

    $activity = [
        'start_time' => '2024-08-30 09:30',
        'end_time' => '2024-08-30 20:00',
        'product_ids' => [$this->product->id],
    ];

    // act
    $response = $this->postJson($this->api_endpoint, $activity);

    // assert
    $response->assertCreated();
});
