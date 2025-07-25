<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        array_map(
            fn($productType) => \App\Models\ProductType::create([
                'id' => $productType['id'],
                'title' => $productType['title'],
                'slug' => $productType['slug'],
                'description' => $productType['description']
            ]),
            [
                ['id' => '01hxzjzjzjzjzjzjzjzjzjzj1', 'title' => 'private sessions', 'slug' => 'private-sessions', 'description' => 'Private sessions are one-on-one sessions with a therapist.'],
                ['id' => '01hxzjzjzjzjzjzjzjzjzjzj2', 'title' => 'live streamed', 'slug' => 'live-streamed', 'description' => 'Live streamed sessions are sessions that are streamed live to a therapist.'],
                ['id' => '01hxzjzjzjzjzjzjzjzjzjzj3', 'title' => 'home visits', 'slug' => 'home-visits', 'description' => 'Home visits are sessions that are conducted in the comfort of your own home.'],
                ['id' => '01hxzjzjzjzjzjzjzjzjzjzj4', 'title' => 'classes', 'slug' => 'classes', 'description' => 'Classes are group sessions that are conducted in a classroom setting.'],
                ['id' => '01hxzjzjzjzjzjzjzjzjzjzj5', 'title' => 'courses', 'slug' => 'courses', 'description' => 'Courses are a series of sessions that are conducted in a classroom setting.'],
                ['id' => '01hxzjzjzjzjzjzjzjzjzjzj6', 'title' => 'events', 'slug' => 'events', 'description' => 'Events are sessions that are conducted in a walk-in clinic setting.']
            ]
        );
    }
}
