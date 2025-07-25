<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'merchant_ref' => $this->faker->unique()->randomNumber(6),
            'worldnet_ref' => $this->faker->unique()->randomNumber(6),
            'number' => $this->faker->unique()->randomNumber(4),
            'type' => $this->faker->randomElement(['credit', 'debit']),
            'expiry' => $this->faker->date('my'),
            'holder_name' => $this->faker->name(),
            'holder_email' => $this->faker->email(),
            'holder_phone' => $this->faker->phoneNumber(),
            'description' => json_encode([
                'name' => $this->faker->name(),
                'email' => $this->faker->email(),
                'phone' => $this->faker->phoneNumber(),
            ]),
            'is_stored' => true,
            'is_default' => false,
        ];
    }
}
