<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return array_merge(
            [
                'title' => $this->faker->sentence,
                'sub_title' => $this->faker->sentence,
                'session_duration' => $this->faker->randomElement([30, 60, 90, 120]),
                'description' => $this->faker->paragraph,
                'kcal_burn' => $this->faker->numberBetween(100, 500),
                'status' => $this->faker->randomElement(range(0, 7)),
                'published_at' => $this->faker->date(),
            ],
            AttendeeSettingsFactory::make(),
            PriceSettingsFactory::make(),
            LocationSettingsFactory::make()
        );
    }
}
