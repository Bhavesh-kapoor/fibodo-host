<?php

namespace Database\Seeders;

use App\Models\Voucher;
use App\Models\VoucherType;
use App\Models\User;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $host = User::where('email', 'host@example.com')->first();
        $voucherTypes = VoucherType::all();

        if (!$host || $voucherTypes->isEmpty()) {
            return;
        }

        $vouchers = [
            [
                'name' => 'Summer Special Discount',
                'code' => 'SUMMER2024',
                'description' => 'Get 20% off on all summer activities',
                'value' => 20.00,
                'is_transferrable' => true,
                'is_gift_eligible' => true,
                'can_combine' => false,
                'inventory_limit' => 100,
                'status' => 1,
                'expires_at' => now()->addMonths(3),
            ],
            [
                'name' => 'Buy 2 Get 1 Free',
                'code' => 'B2G1FREE',
                'description' => 'Buy any 2 activities and get 1 free',
                'pay_for_quantity' => 2,
                'get_quantity' => 1,
                'is_transferrable' => false,
                'is_gift_eligible' => false,
                'can_combine' => false,
                'inventory_limit' => 50,
                'status' => 1,
                'expires_at' => now()->addMonths(2),
            ],
            [
                'name' => 'First Time User',
                'code' => 'FIRSTTIME',
                'description' => '25% off on your first booking',
                'value' => 25.00,
                'is_transferrable' => false,
                'is_gift_eligible' => false,
                'can_combine' => true,
                'inventory_limit' => 200,
                'status' => 1,
                'expires_at' => now()->addYear(),
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create([
                'host_id' => $host->id,
                'voucher_type_id' => $voucherTypes->random()->id,
                ...$voucher
            ]);
        }
    }
}
