<?php

namespace Database\Factories;


class PriceSettingsFactory
{
    /**
     * make
     *
     * @return array
     */
    public static function make(): array
    {

        $is_age_sensitive = fake()->boolean();
        $is_walk_in_pricing = fake()->boolean();
        $is_walk_in_age_sensitive = fake()->boolean();
        $is_special_pricing = fake()->boolean();
        return [
            'no_of_slots' => fake()->numberBetween(1, 100),
            # standard pricing
            'price' => !$is_age_sensitive ? fake()->optional()->randomFloat(2, 10, 100) : null,
            'is_age_sensitive' => $is_age_sensitive,
            'junior_price' => $is_age_sensitive ? fake()->optional()->randomFloat(2, 10, 100) : null,
            'adult_price' => $is_age_sensitive ? fake()->optional()->randomFloat(2, 20, 200) : null,
            'senior_price' => $is_age_sensitive ? fake()->optional()->randomFloat(2, 15, 150) : null,

            # walk in pricing 
            'is_walk_in_pricing' => $is_walk_in_pricing,
            'walk_in_price' => $is_walk_in_pricing && !$is_walk_in_age_sensitive ? fake()->optional()->randomFloat(2, 10, 100) : null,
            'walk_in_junior_price' => $is_walk_in_pricing && $is_walk_in_age_sensitive ? fake()->optional()->randomFloat(2, 10, 100) : null,
            'walk_in_adult_price' => $is_walk_in_pricing && $is_walk_in_age_sensitive ? fake()->optional()->randomFloat(2, 20, 200) : null,
            'walk_in_senior_price' => $is_walk_in_pricing && $is_walk_in_age_sensitive ? fake()->optional()->randomFloat(2, 15, 150) : null,

            # special pricing
            'is_special_pricing' => $is_special_pricing,
            'multi_attendee_price' => $is_special_pricing ? fake()->optional()->randomFloat(2, 50, 500) : null,
            'all_space_price' => $is_special_pricing ? fake()->optional()->randomFloat(2, 100, 1000) : null,

        ];
    }
}
