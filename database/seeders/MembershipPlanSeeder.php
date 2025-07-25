<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    public function run(): void
    {
        $host = User::where('email', 'host@example.com')->first();

        if (!$host) {
            return;
        }

        $plans = [
            [
                'title' => 'Individual Peak Plan',
                'type' => 'peak',
                'plan_type' => 'individual',
                'description' => 'Access to all facilities during peak hours',
                'individual_plan_type' => 'normal',
                'joining_fee' => 50.00,
                'billing_period' => 'monthly',
                'amount' => 99.99,
                'payment_types' => ['credit_card', 'bank_transfer'],
                'renewal_day' => 1,
                'grace_period_days' => 7,
                'cancellation_period_days' => 30,
                'is_transferable' => false,
                'can_pause' => true,
            ],
            [
                'title' => 'Family Off-Peak Plan',
                'type' => 'off_peak',
                'plan_type' => 'family',
                'description' => 'Family access during off-peak hours',
                'junior_count' => 2,
                'adult_count' => 2,
                'senior_count' => 1,
                'unlimited_junior' => false,
                'joining_fee' => 75.00,
                'billing_period' => 'monthly',
                'amount' => 199.99,
                'payment_types' => ['credit_card', 'bank_transfer'],
                'renewal_day' => 1,
                'grace_period_days' => 7,
                'cancellation_period_days' => 30,
                'is_transferable' => true,
                'can_pause' => true,
            ],
            [
                'title' => 'Pay As You Go Plan',
                'type' => 'peak',
                'plan_type' => 'individual',
                'description' => 'Flexible access with pay-as-you-go option',
                'individual_plan_type' => 'pay_as_you_go',
                'joining_fee' => 25.00,
                'billing_period' => 'monthly',
                'amount' => 49.99,
                'payment_types' => ['credit_card'],
                'renewal_day' => 1,
                'grace_period_days' => 3,
                'cancellation_period_days' => 15,
                'is_transferable' => false,
                'can_pause' => false,
            ],
        ];

        foreach ($plans as $plan) {
            MembershipPlan::create([
                'host_id' => $host->id,
                ...$plan
            ]);
        }
    }
}
