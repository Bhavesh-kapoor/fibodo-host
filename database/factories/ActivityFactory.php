<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'product_id' => \App\Models\Product::factory(),
            'start_time' => fake()->dateTime(),
            'end_time' => fake()->dateTime(),
            'status' => fake()->numberBetween(0, 1),
            'title' => $this->faker->sentence,
            'duration' => $this->faker->randomElement(30, 60, 90, 120),
            'recurres_in' => $this->faker->randomElement(30, 60, 90),
            'note' => $this->faker->paragraph,
            'is_time_off' => $this->faker->boolean,
            'is_break' => $this->faker->boolean,
            'schedule_id' => \App\Models\Schedules\Schedule::factory(),
            'schedule_day_id' => \App\Models\Schedules\ScheduleDay::factory(),
            'status' => $this->faker->randomElement(range(0, 7)),
            'published_at' => $this->faker->date(),
        ];
    }
}
