<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Attendee;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Host;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ActivityType;
use App\Models\Role;
use App\Models\User;
use App\Models\Media;
use App\Models\Transaction;
use App\Models\Trust;
use App\Models\Marketplace;
use App\Models\Schedules\Schedule;
use App\Models\Schedules\WeeklySchedule;
use App\Models\Schedules\ScheduleDay;
use App\Models\Schedules\ScheduleBreak;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\TransactionType;
use App\Enums\TransactionStatus;
use App\Models\Client;
use Artisan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DataSeeder extends Seeder
{
    /**
     * Seed the application's database with test data.
     */
    public function run(): void
    {
        // Get required data
        $category_id = Category::whereNull('parent_id')->first()->id;
        $sub_category_id = Category::where('parent_id', $category_id)->first()->id;
        $product_type_id = ProductType::first()->id;
        $activity_type_id = ActivityType::first()->id;
        $payment_method_id = PaymentMethod::first()->id;
        $hostRole = Role::findByName('host', 'api');
        $clientRole = Role::findByName('client', 'api');

        // Create 3 main hosts
        $hosts = $this->createMainHosts($hostRole);

        // Create 3 main clients under each host
        $mainClients = $this->createMainClients($clientRole, $hosts);

        // Create 30-40 unique clients under each host
        $hostClients = $this->createHostClients($hosts, $clientRole);

        // Create 20 clients under the first 3 Trusts
        $trustClients = $this->createTrustClients($clientRole);

        // Create products for each host
        $this->createProductsForHosts($hosts, $category_id, $sub_category_id, $product_type_id, $activity_type_id);

        // Create activities for products
        $this->createActivitiesForAllHosts($hosts);

        // Create bookings for all clients
        $this->createBookingsForAllClients($mainClients, $hostClients, $trustClients, $hosts, $payment_method_id);

        // Create vouchers for main hosts
        $this->createVouchersForMainHosts($hosts);
    }

    /**
     * Create 3 main hosts
     */
    private function createMainHosts($hostRole)
    {
        $hosts = [];

        $hostData = [
            [
                'email' => 'host.alpha@fibodo.com',
                'password' => 'alpha#2025',
                'first_name' => 'Alpha',
                'last_name' => 'Host',
                'business_name' => 'Alpha Fitness Studio'
            ],
            [
                'email' => 'host.beta@fibodo.com',
                'password' => 'beta#2025',
                'first_name' => 'Beta',
                'last_name' => 'Host',
                'business_name' => 'Beta Wellness Center'
            ],
            [
                'email' => 'host.gama@fibodo.com',
                'password' => 'gama#2025',
                'first_name' => 'Gama',
                'last_name' => 'Host',
                'business_name' => 'Gama Sports Club'
            ]
        ];

        foreach ($hostData as $data) {
            $host = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'password' => Hash::make($data['password']),
                    'status' => 1,
                    'mobile_number' => \Faker\Factory::create()->phoneNumber(),
                    'country_code' => 'GBR',
                    'gender' => \Faker\Factory::create()->randomElement(['m', 'f']),
                    'date_of_birth' => \Faker\Factory::create()->date(),
                ]
            );
            $host->assignRole($hostRole);

            $hostModel = Host::firstOrCreate(
                ['user_id' => $host->id],
                [
                    'business_name' => $data['business_name'],
                    'profile_state' => Host::PROFILE_STATE['COMPLETED']
                ]
            );

            // Add host avatar and cover image
            $this->addHostMedia($hostModel);

            $hosts[] = $host;
        }

        return collect($hosts);
    }

    /**
     * Create 3 main clients
     */
    private function createMainClients($clientRole, $hosts)
    {
        $clients = [];

        $clientData = [
            [
                'email' => 'client.alpha@fibodo.com',
                'password' => 'alpha#2025',
                'first_name' => 'Alpha',
                'last_name' => 'Client'
            ],
            [
                'email' => 'client.beta@fibodo.com',
                'password' => 'beta#2025',
                'first_name' => 'Beta',
                'last_name' => 'Client'
            ],
            [
                'email' => 'client.gama@fibodo.com',
                'password' => 'gama#2025',
                'first_name' => 'Gama',
                'last_name' => 'Client'
            ]
        ];

        $i = 0;
        foreach ($clientData as $data) {
            $client = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'password' => Hash::make($data['password']),
                    'status' => 1,
                    'mobile_number' => \Faker\Factory::create()->phoneNumber(),
                    'country_code' => 'GBR',
                    'gender' => \Faker\Factory::create()->randomElement(['m', 'f']),
                    'date_of_birth' => \Faker\Factory::create()->date(),
                ]
            );
            $client->assignRole($clientRole);

            // Add client avatar
            $this->addClientAvatar($client);

            // link client to host 
            (Client::find($client->id))->hosts()->attach($hosts->get($i)->id);
            $i++;

            $clients[] = $client;
        }

        return collect($clients);
    }

    /**
     * Create 30-40 unique clients under each host
     */
    private function createHostClients($hosts, $clientRole)
    {
        $allHostClients = collect();

        foreach ($hosts as $host) {
            $clientCount = rand(30, 40);
            $hostClients = User::factory($clientCount)->create()->each(function ($user) use ($clientRole, $host) {
                $user->assignRole($clientRole);

                // Add client avatar
                $this->addClientAvatar($user);

                // Make some clients archived (10% chance)
                if (rand(1, 10) === 1) {
                    $user->update(['status' => 0]);
                }

                // link client to host 
                (Client::find($user->id))->hosts()->attach($host->id);
            });

            $allHostClients = $allHostClients->merge($hostClients);
        }

        return $allHostClients;
    }

    /**
     * Create 20 clients under the first 3 Trusts
     */
    private function createTrustClients($clientRole)
    {
        $trusts = Trust::take(3)->get();
        $marketplace = Marketplace::first();
        $trustClients = collect();

        foreach ($trusts as $trust) {
            $trustClientCount = 20;
            $clients = User::factory($trustClientCount)->create()->each(function ($user) use ($clientRole, $trust, $marketplace) {
                $user->assignRole($clientRole);

                // Add client avatar
                $this->addClientAvatar($user);

                // Subscribe client to trust using marketplace_subscribers table
                DB::table('marketplace_subscribers')->insert([
                    'id' => Str::ulid(),
                    'user_id' => $user->id,
                    'marketplace_id' => $marketplace->id,
                    'trust_id' => $trust->id,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Make some clients archived (15% chance)
                if (rand(1, 20) <= 3) {
                    $user->update(['status' => 0]);
                }
            });

            $trustClients = $trustClients->merge($clients);
        }

        return $trustClients;
    }

    /**
     * Create products for each host
     */
    private function createProductsForHosts($hosts, $category_id, $sub_category_id, $product_type_id, $activity_type_id)
    {
        // Get product types excluding 'live streamed' and 'walk-ins'
        $excludedProductTypes = ProductType::whereIn('title', ['live streamed', 'walk-ins'])->pluck('id');
        $availableProductTypes = ProductType::whereNotIn('id', $excludedProductTypes)->get();

        // Ensure each product type gets at least 12 products
        $productsPerType = 12;
        $hostsArray = $hosts->toArray();

        foreach ($availableProductTypes as $productType) {
            // Create 12 products for each product type, distributed across hosts
            for ($i = 0; $i < $productsPerType; $i++) {
                $hostIndex = $i % count($hostsArray);
                $host = $hosts->get($hostIndex); // Get the actual host object

                // Create product without schedule
                $product = Product::factory()->create([
                    'category_id' => $category_id,
                    'sub_category_id' => $sub_category_id,
                    'product_type_id' => $productType->id,
                    'activity_type_id' => $activity_type_id,
                    'user_id' => $host->id,
                    'status' => 1,
                    'published_at' => now(),
                    'archived_at' => null,
                ]);

                // Create product policy 
                $product->policies()->attach(
                    \App\Models\Policy::factory()->count(2)->create()
                );
                // Create product forms
                $product->forms()->attach(
                    \App\Models\Form::factory()->count(2)->create()
                );
                // Seed product media
                $this->seedProductMedia($product);
            }
        }

        // Create additional random products for variety
        foreach ($hosts as $host) {
            // Create 10 additional products without schedules
            $productsWithoutSchedules = Product::factory(10)->create([
                'category_id' => $category_id,
                'sub_category_id' => $sub_category_id,
                'product_type_id' => $availableProductTypes->random()->id,
                'activity_type_id' => $activity_type_id,
                'user_id' => $host->id,
                'status' => 1,
                'published_at' => now(),
                'archived_at' => null,
            ])->each(function ($product) {
                // Create product policy 
                $product->policies()->attach(
                    \App\Models\Policy::factory()->count(2)->create()
                );
                // Create product forms
                $product->forms()->attach(
                    \App\Models\Form::factory()->count(2)->create()
                );
                // Seed product media
                $this->seedProductMedia($product);
            });

            // Create 10 additional products with schedules
            $productsWithSchedules = Product::factory(10)->create([
                'category_id' => $category_id,
                'sub_category_id' => $sub_category_id,
                'product_type_id' => $availableProductTypes->random()->id,
                'activity_type_id' => $activity_type_id,
                'user_id' => $host->id,
                'status' => 1,
                'published_at' => now(),
                'archived_at' => null,
            ])->each(function ($product) {
                // Create product policy 
                $product->policies()->attach(
                    \App\Models\Policy::factory()->count(2)->create()
                );
                // Create product forms
                $product->forms()->attach(
                    \App\Models\Form::factory()->count(2)->create()
                );
                // Seed product media
                $this->seedProductMedia($product);
                // Seed product schedule
                $this->seedProductSchedule($product);
            });

            // Set product statuses and archive some
            $this->setProductStatuses($productsWithoutSchedules);
            $this->setProductStatuses($productsWithSchedules);

            // Archive some products (10% chance)
            $allProducts = $productsWithoutSchedules->merge($productsWithSchedules);
            $allProducts->random(ceil($allProducts->count() * 0.1))->each(function ($product) {
                $product->update(['archived_at' => now()]);
            });
        }
    }

    /**
     * Create activities for all hosts
     */
    private function createActivitiesForAllHosts($hosts)
    {
        foreach ($hosts as $host) {
            $products = Product::where('user_id', $host->id)->get();

            // Create activities for 10 products from each host
            $this->createActivitiesForProducts($products->take(10), $host->id, false);
            $this->createActivitiesForProducts($products->skip(10)->take(10), $host->id, true);
        }
    }

    /**
     * Create bookings for all clients
     */
    private function createBookingsForAllClients($mainClients, $hostClients, $trustClients, $hosts, $payment_method_id)
    {
        $allClients = $mainClients->merge($hostClients)->merge($trustClients);
        $allActivities = Activity::whereIn('user_id', $hosts->pluck('id'))->get();

        // Create bookings for main clients
        foreach ($mainClients as $client) {
            $this->createBookingsForClient($client, $hosts->random()->id, $payment_method_id, rand(30, 100));
        }

        // Create bookings for host clients
        foreach ($hostClients as $client) {
            $this->createBookingsForClient($client, $hosts->random()->id, $payment_method_id, rand(1, 5));
        }

        // Create bookings for trust clients
        foreach ($trustClients as $client) {
            $this->createBookingsForClient($client, $hosts->random()->id, $payment_method_id, rand(1, 3));
        }
    }

    /**
     * Add host avatar and cover image
     */
    private function addHostMedia(Host $host)
    {
        // Add avatar using MediaFactory with placeholder URLs
        Media::factory()->create([
            'model_type' => Host::class,
            'model_id' => $host->id,
            'collection_name' => 'hosts/avatar',
        ]);

        // Add cover image using MediaFactory with placeholder URLs
        Media::factory()->create([
            'model_type' => Host::class,
            'model_id' => $host->id,
            'collection_name' => 'hosts/cover-image',
        ]);
    }

    /**
     * Add client avatar
     */
    private function addClientAvatar(User $client)
    {
        Media::factory()->create([
            'model_type' => User::class,
            'model_id' => $client->id,
            'collection_name' => 'users/avatar',
        ]);
    }

    /**
     * Set product statuses (40 published, 10 different statuses)
     */
    private function setProductStatuses($products)
    {
        $products->take(40)->each(function ($product) {
            $product->update([
                'status' => Product::STATUS_PUBLISH,
                'published_at' => now(),
            ]);
        });

        $products->skip(40)->each(function ($product, $index) {
            $statuses = [
                Product::STATUS_UNPUBLISH,
                Product::STATUS_DRAFT,
                Product::STATUS_REVIEW,
                Product::STATUS_OVERVIEW,
                Product::STATUS_PRICING,
                Product::STATUS_LOCATION,
                Product::STATUS_SCHEDULING,
                Product::STATUS_PUBLISH,
                Product::STATUS_DRAFT,
                Product::STATUS_REVIEW,
            ];

            $product->update([
                'status' => rand(1, 9),
                'published_at' => now(),
            ]);
        });
    }

    /**
     * Create activities for products
     */
    private function createActivitiesForProducts($products, $hostId, $hasSchedule)
    {
        $products->each(function ($product) use ($hostId, $hasSchedule) {
            $product->load('schedule');

            // Create future activities
            for ($i = 0; $i < 5; $i++) {
                $startTime = Carbon::now()->addDays(rand(1, 30))->addHours(rand(9, 18));
                $endTime = $startTime->copy()->addMinutes($product->session_duration ?? 60);

                $activity = Activity::create([
                    'title' => $product->title,
                    'duration' => $product->session_duration ?? 60,
                    'recurres_in' => $product->recurres_in ?? 0,
                    'note' => $product->note ?? null,
                    'is_time_off' => $product->is_time_off ?? 0,
                    'is_break' => $product->is_break ?? 0,
                    'user_id' => $hostId,
                    'product_id' => $product->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'status' => 1,
                    'seats_available' => $product->no_of_slots ?? 10,
                    'seats_booked' => 0,
                    'schedule_id' => $hasSchedule && $product->schedule ? $product->schedule->id : null,
                ]);
            }

            // Create past activities
            for ($i = 0; $i < 3; $i++) {
                $startTime = Carbon::now()->subDays(rand(1, 30))->addHours(rand(9, 18));
                $endTime = $startTime->copy()->addMinutes($product->session_duration ?? 60);

                $activity = Activity::create([
                    'user_id' => $hostId,
                    'product_id' => $product->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'status' => 1,
                    'seats_available' => $product->no_of_slots ?? 10,
                    'seats_booked' => 0,
                    'schedule_id' => $hasSchedule && $product->schedule ? $product->schedule->id : null,
                ]);
            }
        });
    }

    /**
     * Create bookings for a specific client
     */
    private function createBookingsForClient($client, $hostId, $paymentMethodId, $count)
    {
        $activities = Activity::where('user_id', $hostId)->get();

        for ($i = 0; $i < $count; $i++) {
            $activity = $activities->random();
            $activity->load('product');

            // Create booking
            $booking = Booking::create([
                'booking_number' => Booking::generateUniqueBookingNumber(),
                'activity_id' => $activity->id,
                'product_id' => $activity->product_id,
                'created_by' => $hostId,
                'host_id' => $hostId,
                'seats_booked' => 1,
                'product_title' => $activity->product->title,
                'product_type' => $activity->product->productType->title,
                'activity_start_time' => $activity->start_time,
                'activity_end_time' => $activity->end_time,
                'payment_method_id' => $paymentMethodId,
                'payment_status' => PaymentStatus::PAID,
                'is_walk_in' => rand(0, 1),
                'confirmed_at' => Carbon::now(),
                'status' => BookingStatus::CONFIRMED,
                'price_per_seat' => $activity->product->adult_price ?? 50.00,
                'discount_amount' => 0,
                'sub_total' => $activity->product->adult_price ?? 50.00,
                'tax_amount' => 0,
                'total_amount' => $activity->product->adult_price ?? 50.00,
                'created_at' => rand(1, 100) > 50 ? now()->subDays(rand(1, 30)) : now(),
            ]);

            // Create attendee
            Attendee::create([
                'booking_id' => $booking->id,
                'activity_id' => $activity->id,
                'client_id' => $client->id,
                'host_id' => $hostId,
                'is_lead_attendee' => 1,
                'first_name' => $client->first_name,
                'last_name' => $client->last_name,
                'email' => $client->email,
                'mobile_number' => $client->mobile_number,
            ]);

            // Create transaction
            $booking->transactions()->create([
                'client_id' => $client->id,
                'host_id' => $hostId,
                'transaction_type' => TransactionType::PAYMENT,
                'transaction_status' => TransactionStatus::COMPLETED,
                'payment_method_id' => $paymentMethodId,
                'amount' => $booking->total_amount,
                'paid_at' => Carbon::now(),
            ]);

            // Update activity seats
            $activity->increment('seats_booked', 1);
            $activity->decrement('seats_available', 1);
        }
    }

    /**
     * seedProductMedia
     */
    private function seedProductMedia(Product $product)
    {
        // Create portrait image using MediaFactory with placeholder URLs
        Media::factory()->create([
            'model_type' => Product::class,
            'model_id' => $product->id,
            'collection_name' => 'products/portrait',
        ]);

        // Create landscape image using MediaFactory with placeholder URLs
        Media::factory()->create([
            'model_type' => Product::class,
            'model_id' => $product->id,
            'collection_name' => 'products/landscape',
        ]);

        // Create gallery images using MediaFactory with placeholder URLs
        Media::factory()->count(5)->create([
            'model_type' => Product::class,
            'model_id' => $product->id,
            'collection_name' => 'products/gallery',
        ]);
    }

    /**
     * seedProductSchedule
     */
    private function seedProductSchedule(Product $product)
    {
        Schedule::factory()
            ->for($product)
            ->has(
                WeeklySchedule::factory()
                    ->count(2)
                    ->has(
                        ScheduleDay::factory()
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
                                ScheduleBreak::factory()->count(2), // Each day has 2 breaks
                                'breaks'
                            ),
                        'days'
                    ),
                'weeklySchedules'
            )
            ->create();
    }

    /**
     * Create vouchers for main hosts
     */
    private function createVouchersForMainHosts($hosts)
    {
        $voucherTypes = \App\Models\VoucherType::all();

        if ($voucherTypes->isEmpty()) {
            return;
        }

        foreach ($hosts as $host) {
            // Create different types of vouchers for each host
            $this->createHostVouchers($host, $voucherTypes);
        }
    }

    /**
     * Create vouchers for a specific host
     */
    private function createHostVouchers($host, $voucherTypes)
    {
        $vouchers = [
            [
                'name' => 'Welcome Discount',
                'code' => 'WELCOME' . strtoupper(substr($host->first_name, 0, 1)),
                'description' => 'Get 15% off on your first booking',
                'value' => 15.00,
                'is_transferrable' => false,
                'is_gift_eligible' => false,
                'can_combine' => true,
                'inventory_limit' => 100,
                'status' => \App\Enums\VoucherStatus::ACTIVE,
                'expires_at' => now()->addMonths(6),
                'voucher_type_code' => 'GIFT'
            ],
            [
                'name' => 'Buy 2 Get 1 Free',
                'code' => 'B2G1' . strtoupper(substr($host->first_name, 0, 1)),
                'description' => 'Buy any 2 activities and get 1 free',
                'pay_for_quantity' => 2,
                'get_quantity' => 1,
                'is_transferrable' => false,
                'is_gift_eligible' => false,
                'can_combine' => false,
                'inventory_limit' => 50,
                'status' => \App\Enums\VoucherStatus::ACTIVE,
                'expires_at' => now()->addMonths(3),
                'voucher_type_code' => 'XFY'
            ],
            [
                'name' => 'Summer Special',
                'code' => 'SUMMER' . strtoupper(substr($host->first_name, 0, 1)),
                'description' => 'Get 20% off on all summer activities',
                'value' => 20.00,
                'is_transferrable' => true,
                'is_gift_eligible' => true,
                'can_combine' => false,
                'inventory_limit' => 75,
                'status' => \App\Enums\VoucherStatus::ACTIVE,
                'expires_at' => now()->addMonths(4),
                'voucher_type_code' => 'GIFT'
            ],
            [
                'name' => 'Multi-Purchase Bundle',
                'code' => 'MULTI' . strtoupper(substr($host->first_name, 0, 1)),
                'description' => 'Pay £50 and get 3 vouchers',
                'value' => 50.00,
                'get_quantity' => 3,
                'is_transferrable' => true,
                'is_gift_eligible' => true,
                'can_combine' => false,
                'inventory_limit' => 30,
                'status' => \App\Enums\VoucherStatus::ACTIVE,
                'expires_at' => now()->addMonths(2),
                'voucher_type_code' => 'MULTI'
            ],
            [
                'name' => 'Loyalty Discount',
                'code' => 'LOYAL' . strtoupper(substr($host->first_name, 0, 1)),
                'description' => '10% off for returning customers',
                'value' => 10.00,
                'is_transferrable' => false,
                'is_gift_eligible' => false,
                'can_combine' => true,
                'inventory_limit' => 200,
                'status' => \App\Enums\VoucherStatus::ACTIVE,
                'expires_at' => now()->addYear(),
                'voucher_type_code' => 'GIFT'
            ]
        ];

        foreach ($vouchers as $voucherData) {
            $voucherTypeCode = $voucherData['voucher_type_code'];
            unset($voucherData['voucher_type_code']);

            $voucherType = $voucherTypes->where('code', $voucherTypeCode)->first();

            if ($voucherType) {
                \App\Models\Voucher::create([
                    'host_id' => $host->id,
                    'voucher_type_id' => $voucherType->id,
                    ...$voucherData
                ]);
            }
        }
    }
}
