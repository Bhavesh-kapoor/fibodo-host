<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        array_map(
            fn($activityType) => \App\Models\ActivityType::create([
                'id' => $activityType['id'],
                'title' => $activityType['title'],
                'status' => $activityType['status']
            ]),
            [
                [
                    'id' => '01hxzjzjzjzjzjzjzjzjzjzj8',
                    'title' => 'Activity Type 1',
                    'status' => 1
                ],
                [
                    'id' => '01hxzjzjzjzjzjzjzjzjzjzj9',
                    'title' => 'Activity Type 2',
                    'status' => 1
                ]
            ]
        );
    }
}
