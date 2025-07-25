<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class AttendeeSettingsFactory
{

    /**
     * make
     *
     * @return array
     */
    public static function make(): array
    {
        return [
            'ability_level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'has_age_restriction' => fake()->numberBetween(0, 1),
            'age_below' => fake()->numberBetween(0, 16),
            'age_above' => fake()->numberBetween(40, 80),
            'gender_restrictions' => fake()->randomElement(['men', 'women']),
            'is_family_friendly' => fake()->numberBetween(0, 1),
        ];
    }
}
