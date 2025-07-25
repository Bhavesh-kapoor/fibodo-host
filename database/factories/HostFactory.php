<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hosts>
 */
class HostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'business_name' => fake()->company(),
            'business_tagline' => fake()->sentence(),
            'business_about' => fake()->paragraph(),
            'business_website' => fake()->url(),
            'company_name' => fake()->company(),
            'company_address_line1' => fake()->streetAddress(),
            'company_address_line2' => fake()->secondaryAddress(),
            'company_city' => fake()->city(),
            'company_zip' => fake()->postcode(),
            'company_country' => fake()->country(),
            'company_contact_no' => fake()->phoneNumber(),
            'company_email' => fake()->email(),
            'company_vat' => fake()->word(),
            'company_website' => fake()->url(),
            'profile_state' => fake()->numberBetween(1, 5),
            'business_profile_slug' => fake()->slug()
        ];
    }
}
