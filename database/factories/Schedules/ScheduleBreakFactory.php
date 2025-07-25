<?php

namespace Database\Factories\Schedules;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScheduleBreak>
 */
class ScheduleBreakFactory extends Factory
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
            'schedule_day_id' => \App\Models\Schedules\ScheduleDay::factory(),
            'name' => fake()->sentence(3),
            'start_time' => $startTime,
            'end_time' => Carbon::parse($startTime)->addMinutes(fake()->randomElement([30, 60]))->format('H:i') // Ensure end_time is later
        ];
    }
}
