<?php

namespace Database\Seeders;

use App\Models\Marketplace;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

class MarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create hardcode NHS marketplace
        $marketplace = Marketplace::create([
            'id' => '01j0f3m5vxqtb1e8mzkga7cn2n',
            'code' => 'TEA',
            'title' => 'The Everything App',
            'slug' => 'the-everything-app',
            'tagline' => 'Wellbeing made simple. Access made free',
            'description' => "The Everything App is a groundbreaking partnership between the physical activity sector and public health, created to support millions of NHS staff and their families.  This powerful digital platform offers free access to a wide range of on demand and live streamed activities, as well as discounted in person fitness, sport, and wellbeing services.",
            'excerpt' => "The Everything App is a groundbreaking partnership between the physical activity sector and public health, created to support millions of NHS staff and their families.  This powerful digital platform offers free access to a wide range of on demand and live streamed activities, as well as discounted in person fitness, sport, and wellbeing services.",
            'logo' => config('app.url') . "/assets/media/everything-app.svg",
            'address' => '123 London Road, London, UK',
            'contact_number' => '020 7946 0000',
            'contact_email' => 'info@nhs.uk',
            'website_url' => 'https://www.nhs.uk',
            'facebook_url' => 'https://www.facebook.com/nhs',
            'instagram_url' => 'https://www.instagram.com/nhs',
            'x_url' => 'https://www.x.com/nhs',
            'linkedin_url' => 'https://www.linkedin.com/company/nhs',
            'youtube_url' => 'https://www.youtube.com/nhs',
            'tiktok_url' => 'https://www.tiktok.com/nhs',
            'status' => 1,
        ]);

        $marketplace->copyMedia(public_path("/assets/media/everything-app.svg"))
            ->toMediaCollection('marketplaces/logo');

        $marketplace->copyMedia(public_path("/assets/media/everything-app-cover.png"))
            ->toMediaCollection('marketplaces/cover-image');
    }
}
