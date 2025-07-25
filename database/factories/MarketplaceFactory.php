<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Marketplace>
 */
class MarketplaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->company(),
            'tagline' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'excerpt' => fake()->sentence(),
            'logo' => fake()->imageUrl(),
            'address' => fake()->address(),
            'contact_number' => fake()->phoneNumber(),
            'contact_email' => fake()->email(),
            'website_url' => fake()->url(),
            'facebook_url' => fake()->url(),
            'instagram_url' => fake()->url(),
            'x_url' => fake()->url(),
            'linkedin_url' => fake()->url(),
            'youtube_url' => fake()->url(),
            'tiktok_url' => fake()->url(),
            'status' => fake()->randomElement([1, 0]),
        ];
    }
}
