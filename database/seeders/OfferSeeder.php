<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\OfferType;
use App\Models\User;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        $host = User::where('email', 'host@example.com')->first();
        $offerTypes = OfferType::all();

        if (!$host || $offerTypes->isEmpty()) {
            return;
        }

        $offers = [
            [
                'name' => 'Weekend Special',
                'description' => 'Special weekend rates for all activities',
                'value' => 15.00,
                'is_discount' => true,
                'target_audience' => 1, // All Attendees
                'apply_to_all_products' => true,
                'terms_conditions' => 'Valid only on weekends. Cannot be combined with other offers.',
                'status' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(3),
            ],
            [
                'name' => 'New Client Welcome',
                'description' => 'Special welcome offer for new clients',
                'value' => 30.00,
                'is_discount' => true,
                'target_audience' => 3, // New Clients
                'apply_to_all_products' => true,
                'terms_conditions' => 'Valid for first booking only. New clients only.',
                'status' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addYear(),
            ],
            [
                'name' => 'Fixed Price Special',
                'description' => 'Special fixed price for selected activities',
                'value' => 49.99,
                'is_discount' => false,
                'target_audience' => 1, // All Attendees
                'apply_to_all_products' => false,
                'terms_conditions' => 'Fixed price offer for selected activities. Limited time only.',
                'status' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(2),
            ],
        ];

        foreach ($offers as $offer) {
            Offer::create([
                'host_id' => $host->id,
                'offer_type_id' => $offerTypes->random()->id,
                ...$offer
            ]);
        }
    }
}
