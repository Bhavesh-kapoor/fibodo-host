<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $this->call([
            CategorySeeder::class,
            ProductTypeSeeder::class,
            ActivityTypeSeeder::class,
            FormSeeder::class,
            PolicySeeder::class,
            WelcomePageSeeder::class,
            PaymentMethodSeeder::class,
            FaqSeeder::class,
            VoucherTypeSeeder::class,
            ModuleSeeder::class,
            MembershipBenefitSeeder::class,
            OfferTypeSeeder::class,
            MarketplaceSeeder::class,
            TrustSeeder::class,
        ]);

        // create HOST role 
        Role::factory()->create(['name' => 'host', 'guard_name' => 'api']);

        // create client role
        Role::factory()->create(['name' => 'client', 'guard_name' => 'api']);
    }
}
