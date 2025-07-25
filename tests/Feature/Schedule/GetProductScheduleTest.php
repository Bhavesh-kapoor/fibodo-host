<?php

use App\Models\Product;
use App\Models\Schedules\Schedule;

beforeEach(function () {
    // Create a user for testing
    $this->host = mockHost();
    $this->product = Product::factory()->create(['user_id' => $this->host->id]);
    $this->api_endpoint = '/api/v1/schedules/?product_id=' . $this->product->id;
});


it('fails when logged in user is not the product owner and tries to get the schedules for the product', function () {

    $user = mockHost();
    $this->actingAs($user, 'api');

    // act
    $response = $this->getJson($this->api_endpoint);

    // assert 
    $response->assertForbidden();
});

it('returns the schedule for the given product', function () {

    // arrange 
    $this->actingAs($this->host, 'api');
    $scheduleData = Schedule::factory()->make(['product_id' => $this->product->id])->toArray();
    $schedule = Schedule::create($scheduleData);

    $scheduleWeekData = \App\Models\Schedules\WeeklySchedule::factory()->make(['schedule_id' => $schedule['id']])->toArray();
    $scheduleWeek = \App\Models\Schedules\WeeklySchedule::create($scheduleWeekData);

    // Ensure exactly 7 unique schedule days (Sunday to Saturday)
    $scheduleDaysData = array_map(function ($day) use ($schedule, $scheduleWeek) {
        return \App\Models\Schedules\ScheduleDay::factory()->make([
            'weekly_schedule_id' => $scheduleWeek['id'],
            'day_of_week' => $day, // Assign unique days from 0 (Sunday) to 6 (Saturday)
        ])->toArray();
    }, range(0, 6));

    $scheduleDays = \App\Models\Schedules\ScheduleDay::factory()->createMany($scheduleDaysData)->toArray();

    $scheduleBreaksData = array_map(function ($scheduleDay) use ($schedule) {
        return \App\Models\Schedules\ScheduleBreak::factory()->make([
            'schedule_day_id' => $scheduleDay['id']
        ])->toArray();
    }, $scheduleDays);
    \App\Models\Schedules\ScheduleBreak::factory()->createMany($scheduleBreaksData);


    // act 
    $response = $this->getJson($this->api_endpoint);

    // assert 
    $response->assertOk()
        ->assertJsonFragment([
            'id' => $schedule['id'],
            'recurres_in' => $schedule['recurres_in'],
            'status' => $schedule['status'],
        ])
        ->assertJsonFragment($scheduleWeekData)
        ->assertJsonFragment($scheduleDaysData[0])
        ->assertJsonFragment($scheduleBreaksData[0]);
});
