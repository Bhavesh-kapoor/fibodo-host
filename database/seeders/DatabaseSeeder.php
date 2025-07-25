<?php

namespace Database\Seeders;

use App\Models\ActivityType;
use App\Models\Host;
use App\Models\Product;
use App\Models\Role;
use App\Models\Category;
use App\Models\ProductType;
use App\Models\User;
use Artisan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Media;
use Database\Factories\CardFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Generate Passport Keys & Personal Client
        Artisan::call('passport:keys --force');
        Artisan::call('passport:client --personal --no-interaction');

        // create Roles & Permissions
        $this->call([
            RolesAndPermissionsSeeder::class,
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
            MembershipPlanSeeder::class,
            VoucherSeeder::class,
            OfferSeeder::class,
            MarketplaceSeeder::class,
            TrustSeeder::class,
        ]);

        // assign superAdmin Role 
        $superAdmin = User::factory()->create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'status' => 1
        ]);
        // dummy HOST
        $host = User::factory()->create([
            'first_name' => 'Host',
            'last_name' => 'Fibo',
            'email' => 'host@example.com',
            'password' => Hash::make('password'),
            'status' => 1
        ]);
        $superAdmin->assignRole(Role::findByName('super-admin', 'api'));
        $adminRole = Role::findByName('admin', 'api');
        $hostRole = Role::findByName('host', 'api');
        $clientRole = Role::findByName('client', 'api');

        User::factory(10)->create()->each(fn($user) => $user->assignRole($adminRole));


        $category_id = Category::whereNull('parent_id')->first()->id;
        $sub_category_id = Category::where('parent_id', $category_id)->first()->id;
        $product_type_id = ProductType::first()->id;
        $activity_type_id = ActivityType::first()->id;

        (User::factory(10)->create()->push($host))->each(function ($user) use ($hostRole, $category_id, $sub_category_id, $activity_type_id, $product_type_id) {

            $user->assignRole($hostRole);
            Host::factory()->create(['user_id' => $user->id]);

            // create 5 products for each user
            Product::factory(5)->create([
                'category_id' => $category_id,
                'sub_category_id' => $sub_category_id,
                'product_type_id' => $product_type_id,
                'activity_type_id' => $activity_type_id,
                'user_id' => $user->id,
                'status' => 1,
                'published_at' => now(),
                'archived_at' => null,
            ])->each(function ($product) use ($user) {

                // create product policy 
                $product->policies()->attach(
                    \App\Models\Policy::factory()->count(2)->create()
                );
                // create product forms
                $product->forms()->attach(
                    \App\Models\Form::factory()->count(2)->create()
                );

                // seed product media from Media Factory 
                $this->seedProductMedia($product);

                // seed product schedule
                $this->seedProductSchedule($product);
            });
        });

        User::factory(10)->create()->each(fn($user) => $user->assignRole($clientRole));
    }


    /**
     * seedProductMedia
     *
     * @param  mixed $product
     * @return void
     */
    private function seedProductMedia(Product $product)
    {
        Media::factory()->create([
            'model_type' => Product::class,
            'model_id' => $product->id,
            'collection_name' => 'products/portrait',
        ]);
        Media::factory()->create([
            'model_type' => Product::class,
            'model_id' => $product->id,
            'collection_name' => 'products/landscape',
        ]);
        Media::factory()->count(5)->create([
            'model_type' => Product::class,
            'model_id' => $product->id,
            'collection_name' => 'products/gallery',
        ]);
    }

    /**
     * seedProductSchedule
     *
     * @param  mixed $product
     * @return void
     */
    private function seedProductSchedule(Product $product)
    {
        \App\Models\Schedules\Schedule::factory()
            ->for($product)
            ->has(
                \App\Models\Schedules\WeeklySchedule::factory()
                    ->count(2)
                    ->has(
                        \App\Models\Schedules\ScheduleDay::factory()
                            ->count(7) // 7 Days a week
                            ->sequence(
                                ['day_of_week' => 0],
                                ['day_of_week' => 1],
                                ['day_of_week' => 2],
                                ['day_of_week' => 3],
                                ['day_of_week' => 4],
                                ['day_of_week' => 5],
                                ['day_of_week' => 6],
                            )
                            ->has(
                                \App\Models\Schedules\ScheduleBreak::factory()->count(2), // Each day has 2 breaks
                                'breaks'
                            ),
                        'days'
                    ),
                'weeklySchedules'
            )
            ->create();
    }
}
