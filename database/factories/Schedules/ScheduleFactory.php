<?php

namespace Database\Factories\Schedules;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Schedules\Schedule;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'recurres_in' => fake()->randomElement([15, 20, 25, 30]),
            'status' => $this->faker->numberBetween(0, 1),
        ];
    }


    /**
     * withDays
     *
     * @param  mixed $count
     * @return void
     */
    public function withDays($count = 7)
    {
        return $this->afterCreating(function (Schedule $schedule) use ($count) {
            // Ensure exactly 7 unique schedule days (Sunday to Saturday)
            foreach (range(0, 6) as $day) {
                \App\Models\Schedules\ScheduleDay::factory()->create([
                    'day_of_week' => $day, // Assign unique days from 0 (Sunday) to 6 (Saturday)
                ]);
            }
        });
    }
}
