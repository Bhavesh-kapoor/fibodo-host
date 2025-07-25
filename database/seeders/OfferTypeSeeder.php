<?php

namespace Database\Seeders;

use App\Models\OfferType;
use Illuminate\Database\Seeder;

class OfferTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'id' => '01HXZJZJZJZJZJZJZJZJZJZJ1',
                'name' => 'First Time Booking',
                'code' => 'FIRST_TIME_BOOKING',
                'description' => 'First time booking discount',
                'requires_account' => false,
                'default_expiry_days' => 30,
                'status' => true
            ],
            [
                'id' => '01HXZJZJZJZJZJZJZJZJZJZJ2',
                'name' => 'Early Bird Booking',
                'code' => 'EARLY_BIRD_BOOKING',
                'description' => 'Early bird booking discount',
                'requires_account' => false,
                'default_expiry_days' => 30,
                'status' => true
            ],
            [
                'id' => '01HXZJZJZJZJZJZJZJZJZJZJ3',
                'name' => 'Last Minute Booking',
                'code' => 'LAST_MINUTE_BOOKING',
                'description' => 'Last minute booking discount',
                'requires_account' => true,
                'default_expiry_days' => 30,
                'status' => true
            ],
            [
                'id' => '01HXZJZJZJZJZJZJZJZJZJZJ4',
                'name' => 'Off-Peak Pricing',
                'code' => 'OFF_PEAK_PRICING',
                'description' => 'Off-peak pricing discount',
                'requires_account' => true,
                'default_expiry_days' => 30,
                'status' => true
            ],
            [
                'id' => '01HXZJZJZJZJZJZJZJZJZJZJ5',
                'name' => 'Peak Pricing',
                'code' => 'PEAK_PRICING',
                'description' => 'Peak pricing discount',
                'requires_account' => true,
                'default_expiry_days' => 30,
                'status' => true
            ]
        ];

        foreach ($types as $type) {
            OfferType::firstOrCreate(['code' => $type['code']], $type);
        }
    }
}
