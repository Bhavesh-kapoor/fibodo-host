<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true), // Generate a unique category name
            'parent_id' => null, // Default to no parent
        ];
    }

    /**
     * Indicate that the category has a parent.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withParent(Category $parent)
    {
        return $this->state(function () use ($parent) {
            return [
                'parent_id' => $parent->id,
            ];
        });
    }
}
