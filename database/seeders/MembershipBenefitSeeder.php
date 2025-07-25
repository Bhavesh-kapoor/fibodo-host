<?php

namespace Database\Seeders;

use App\Models\MembershipBenefit;
use Illuminate\Database\Seeder;

class MembershipBenefitSeeder extends Seeder
{
    public function run(): void
    {
        $benefits = [
            [
                'id' => '01HXZJZJZJZJZJZJZJZJZJZJ1',
                'name' => 'Video Access',
                'type' => 'video_access',
                'description' => 'Access to recorded video content',
            ],
            [
                'id' => '01HXZJZJZJZJZJZJZJZJZJZJ2',
                'name' => 'Live Streamed Video',
                'type' => 'live_stream',
                'description' => 'Access to live streamed video content',
            ],
            [
                'id' => '01HXZJZJZJZJZJZJZJZJZJZJ3',
                'name' => 'Class Passes',
                'type' => 'class_passes',
                'description' => 'Access to a number of class passes',
            ],
            [
                'id' => '01HXZJZJZJZJZJZJZJZJZJZJ4',
                'name' => 'Private Session',
                'type' => 'private_session',
                'description' => 'Access to private training sessions',
            ],
            [
                'id' => '01HXZJZJZJZJZJZJZJZJZJZJ5',
                'name' => 'Booking Discount',
                'type' => 'booking_discount',
                'description' => 'Discount on class bookings',
            ],
            [
                'id' => '01HXZJZJZJZJZJZJZJZJZJZJ6',
                'name' => 'Book in Advance',
                'type' => 'advance_booking',
                'description' => 'Ability to book classes in advance',
            ],
        ];

        foreach ($benefits as $benefit) {
            MembershipBenefit::create($benefit);
        }
    }
}
