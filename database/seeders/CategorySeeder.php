<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add root categories 
        $categories = [
            'Sports & Activities' => [
                'Angling',
                'Archery',
                'Athletics',
                'Badminton',
                'Basketball',
                'Bowls',
                'Boxing',
                'Climbing',
                'Cricket',
                'Cycling',
                'Equestrian',
                'Fencing',
                'Football',
                'Golf',
                'Gymnastics',
                'Handball',
                'Hiking',
                'Hockey',
                'Horse riding',
                'Kayaking',
                'Kickboxing',
                'Martial Arts',
                'Motor Sport',
                'Mountain Biking',
                'Netball',
                'Orienteering',
                'Polo',
                'Racketball',
                'Rowing',
                'Running',
                'Sailing',
                'Shooting',
                'Skateboarding',
                'Skiing',
                'Snooker',
                'Squash',
                'Stand Up Paddle boarding',
                'Surfing',
                'Table Tennis',
                'Tennis',
                'Tenpin Bowling',
                'Triathlon',
                'VX',
                'Weightlifting',
                'Wrestling',
            ],
            'Health, Beauty & Wellbeing' => [
                'Health Coaching',
                'Massage',
                'Therapy',
            ],
            'Fitness and Leisure' => [
                'Dance',
                'Fitness Classes',
                'Personal Training',
                'Pilates',
                'Swimming',
                'Walking',
                'Yoga',
                'Zumba',
            ],
            'Other' => [
                'Other'
            ]
        ];

        foreach ($categories as $cat => $sub) {
            $parent = Category::create([
                'name' => $cat,
                'parent_id' => null,
            ]);
            // create sub category
            collect($sub)->each(fn($subCategory) => Category::create([
                'name' => $subCategory,
                'parent_id' => $parent->id,
            ]));
        }
    }
}
