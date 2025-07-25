<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trust>
 */
class TrustFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //'code' => $this->faker->unique()->word,
            'title' => $this->faker->company,
            'tagline' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'excerpt' => $this->faker->paragraph,
            'logo' => $this->faker->imageUrl,
            'status' => $this->faker->randomElement([1, 0]),
        ];
    }
}
