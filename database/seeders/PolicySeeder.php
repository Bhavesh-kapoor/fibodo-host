<?php

namespace Database\Seeders;

use App\Enums\PolicyType;
use App\Models\Policy;
use Illuminate\Database\Seeder;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        array_map(
            fn($policy) => Policy::create([
                'id' => $policy['id'],
                'title' => $policy['title'],
                'status' => $policy['status'],
                'policy_type' => $policy['policy_type']
            ]),
            [
                [
                    'id' => '01hxzjzjzjzjzjzjzjzjzjzjz4',
                    'title' => 'Standard Policy',
                    'status' => 1,
                    'policy_type' => PolicyType::REFUND->value
                ],
                [
                    'id' => '01hxzjzjzjzjzjzjzjzjzjzjz5',
                    'title' => 'Zumba Policy',
                    'status' => 1,
                    'policy_type' => PolicyType::REFUND->value
                ],
                [
                    'id' => '01hxzjzjzjzjzjzjzjzjzjzjz6',
                    'title' => 'Privacy Policy',
                    'status' => 1,
                    'policy_type' => PolicyType::PRIVACY_POLICY->value
                ],
                [
                    'id' => '01hxzjzjzjzjzjzjzjzjzjzjz7',
                    'title' => 'Terms of Service',
                    'status' => 1,
                    'policy_type' => PolicyType::TERMS_OF_SERVICE->value
                ],
            ]
        );
    }
}
