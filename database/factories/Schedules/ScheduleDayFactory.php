<?php

namespace Database\Factories\Schedules;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScheduleDay>
 */
class ScheduleDayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = fake()->time('H:i');
        return [
            'weekly_schedule_id' => \App\Models\Schedules\WeeklySchedule::factory(),
            'day_of_week' => fake()->numberbetween(0, 6), // 0=sunday, 6=satruday
            'start_time' => $startTime,
            'end_time' => Carbon::parse($startTime)->addHours(rand(1, 4))->format('H:i') // Ensure end_time is later
        ];
    }
}
