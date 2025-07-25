<?php

namespace Database\Factories\Schedules;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WeeklySchedule>
 */
class WeeklyScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'schedule_id' => \App\Models\Schedules\Schedule::factory(),
            'name' => fake()->sentence(3),
            'is_default' => $this->faker->numberBetween(0, 1),
            'status' => $this->faker->numberBetween(0, 1),
        ];
    }
}
