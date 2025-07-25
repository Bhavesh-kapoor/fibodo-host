<?php

namespace Test\Feature\ActivityType;

use App\Models\ActivityType;
use Database\Seeders\ActivityTypeSeeder;
use Illuminate\Http\Response;

it('runs the Activity Type seeder successfully', function () {

    $this->seed(ActivityTypeSeeder::class);

    $this->assertCount(2, ActivityType::all());

    $activityTypes = ActivityType::all();

    $this->assertEquals('Activity Type 1', $activityTypes[0]->title);
    $this->assertEquals('Activity Type 2', $activityTypes[1]->title);
});

it('returns the fixed activity type list', function () {

    $this->actingAs(mockHost(), 'api');
    $testData = [];
    // arrange 
    foreach (['private sessions', 'live streamed', 'home visits', 'classes', 'courses', 'walk-ins'] as $title) {
        $testData[] = (ActivityType::factory()->create(['title' => $title, 'status' => 1]))->only(['id', 'title']);
    }

    // act
    $response = $this->getJson('/api/v1/activity-types');

    // assert
    $response->assertStatus(Response::HTTP_OK);
    expect($response->json('data'))->toBe($testData);
});
