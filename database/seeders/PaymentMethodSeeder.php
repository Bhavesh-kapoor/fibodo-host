<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specific payment methods with predefined codes and ULIDs
        PaymentMethod::updateOrCreate(
            ['id' => '01HXZJZJZJZJZJZJZJZJZJZJ1'],
            [
                'name' => 'Cash',
                'code' => 'cash',
                'description' => 'Pay with cash at the venue',
                'is_active' => true,
            ]
        );

        PaymentMethod::updateOrCreate(
            ['id' => '01HXZJZJZJZJZJZJZJZJZJZJ2'],
            [
                'name' => 'Online Payment',
                'code' => 'online',
                'description' => 'Pay online through various payment gateways',
                'is_active' => true,
            ]
        );

        PaymentMethod::updateOrCreate(
            ['id' => '01HXZJZJZJZJZJZJZJZJZJZJ3'],
            [
                'name' => 'Card Payment',
                'code' => 'card',
                'description' => 'Pay with credit or debit card',
                'is_active' => true,
            ]
        );

        // Create additional random payment methods if needed
        // PaymentMethod::factory()->count(5)->create();
    }
} 