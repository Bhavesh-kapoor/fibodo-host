<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'name' => 'Video Library',
                'icon' => fake()->imageUrl(640, 480, 'video'),
                'slug' => 'video-library',
                'description' => 'Video Library',
            ],
            [
                'name' => 'Marketing',
                'icon' => fake()->imageUrl(640, 480, 'marketing'),
                'slug' => 'marketing',
                'description' => 'Marketing',
            ],
            [
                'name' => 'Membership',
                'icon' => fake()->imageUrl(640, 480, 'membership'),
                'slug' => 'membership',
                'description' => 'Membership',
            ],
            [
                'name' => 'Vouchers',
                'icon' => fake()->imageUrl(640, 480, 'voucher'),
                'slug' => 'vouchers',
                'description' => 'Vouchers',
            ],
            [
                'name' => 'Customer Apps',
                'icon' => fake()->imageUrl(640, 480, 'customer-app'),
                'slug' => 'customer-apps',
                'description' => 'Customer Apps',
            ],
            [
                'name' => 'Dynamic Pricing & Offers',
                'icon' => fake()->imageUrl(640, 480, 'dynamic-pricing-offers'),
                'slug' => 'dynamic-pricing-offers',
                'description' => 'Dynamic Pricing & Offers',
            ],
            [
                'name' => 'Rewards',
                'icon' => fake()->imageUrl(640, 480, 'reward'),
                'slug' => 'rewards',
                'description' => 'Rewards',
            ]
        ];

        foreach ($modules as $module) {
            Module::firstOrCreate(['slug' => $module['slug']], $module);
        }
    }
}
