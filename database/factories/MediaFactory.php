<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['fitness', 'wellness', 'sports', 'yoga', 'gym', 'workout', 'health', 'exercise'];
        $category = $this->faker->randomElement($categories);
        $width = 800;
        $height = 600;
        $fileUrl = "https://picsum.photos/{$width}/{$height}?random=" . $this->faker->numberBetween(1, 1000);

        return [
            'model_type' => \App\Models\Product::class,
            'model_id' => \App\Models\Product::factory(), // links to a product factory
            'uuid' => (string) \Str::uuid(),
            'collection_name' => $this->faker->randomElement(['products/portrait', 'products/landscape', 'products/gallery', 'hosts/avatar', 'hosts/cover-image', 'users/avatar']),
            'name' => $this->faker->word(),
            'file_name' => $fileUrl,
            'mime_type' => 'image/jpeg',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => $this->faker->numberBetween(50000, 500000),
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
            'order_column' => 1,
        ];
    }
}
