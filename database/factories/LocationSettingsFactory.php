<?php

namespace Database\Factories;


class LocationSettingsFactory
{
    /**
     * make
     *
     * @return array
     */
    public static function make(): array
    {
        return [
            'address' => fake()->address,
            'note' => fake()->sentence,
            'lat' => fake()->latitude,
            'long' => fake()->longitude,
        ];
    }
}
