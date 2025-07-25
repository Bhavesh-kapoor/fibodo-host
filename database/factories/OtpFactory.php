<?php

namespace Database\Factories;

use Carbon\Carbon;
use Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Otp>
 */
class OtpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'otp' => Hash::make($this->faker->randomNumber(6)),
            'email' => fake()->safeEmail(),
            'user_id' => $this->faker->text(26),
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
