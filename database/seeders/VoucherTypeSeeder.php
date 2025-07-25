<?php

namespace Database\Seeders;

use App\Models\VoucherType;
use Illuminate\Database\Seeder;

class VoucherTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Gift Voucher',
                'code' => 'GIFT',
                'description' => 'Monetary value gift voucher that can be redeemed for any service',
                'requires_account' => false,
                'default_expiry_days' => null,
                'settings' => [
                    'allow_personal_message' => true,
                    'allow_scheduled_delivery' => true,
                    'min_value' => 10,
                    'max_value' => 1000
                ],
                'status' => true
            ],
            [
                'name' => 'X for Y Voucher',
                'code' => 'XFY',
                'description' => 'Buy X quantity and pay for Y quantity',
                'requires_account' => true,
                'default_expiry_days' => 90,
                'settings' => [
                    'min_quantity' => 2,
                    'max_quantity' => 10
                ],
                'status' => true
            ],
            [
                'name' => 'Multi-Purchase Voucher',
                'code' => 'MULTI',
                'description' => 'Pay X amount and get Y vouchers',
                'requires_account' => true,
                'default_expiry_days' => 90,
                'settings' => [
                    'min_quantity' => 2,
                    'max_quantity' => 10
                ],
                'status' => true
            ]
            // ,
            // [
            //     'name' => 'Special Offer Voucher',
            //     'code' => 'SPECIAL',
            //     'description' => 'Special price for a specific product or service',
            //     'requires_account' => true,
            //     'default_expiry_days' => 90,
            //     'settings' => [
            //         'min_discount_percentage' => 10,
            //         'max_discount_percentage' => 50
            //     ],
            //     'status' => true
            // ]
        ];

        foreach ($types as $type) {
            VoucherType::create($type);
        }
    }
}
