<?php

namespace Database\Seeders;

use App\Models\WelcomePage;
use Illuminate\Database\Seeder;

class WelcomePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default welcome pages if they don't exist
        if (WelcomePage::where('app_id', 'default')->count() === 0) {
            WelcomePage::create([
                'app_id' => 'default',
                'title' => 'Welcome',
                'text' => 'Welcome to our application. Customize your experience through our admin interface.',
                'sort_order' => 0,
                'status' => true,
            ]);
        }

        // Create fibodo welcome pages if they don't exist
        if (WelcomePage::where('app_id', 'fibodo')->count() === 0) {
            $pages = [
                [
                    'title' => 'Welcome to Fibodo',
                    'text' => 'Discover activities, coaches and events in your area - join the largest community of activity enthusiasts and professionals.',
                ],
                [
                    'title' => 'Personalized Experience',
                    'text' => 'Tell us what you love, and we\'ll recommend activities and coaches tailored just for you.',
                ],
                [
                    'title' => 'Share With Friends',
                    'text' => 'Invite friends to join your activities and share your experiences on social media with just a few taps.',
                ],
                [
                    'title' => 'Book Instantly',
                    'text' => 'Find and book activities instantly - no more phone calls or emails, just simple, secure bookings at your fingertips.',
                ]
            ];

            foreach ($pages as $index => $page) {
                WelcomePage::create([
                    'app_id' => 'fibodo',
                    'title' => $page['title'],
                    'text' => $page['text'],
                    'sort_order' => $index,
                    'status' => true,
                ]);
            }
        }

        // Create host welcome pages if they don't exist
        if (WelcomePage::where('app_id', 'host')->count() === 0) {
            $pages = [
                [
                    'title' => 'Host Your Activities',
                    'text' => 'Create and manage your activities with our easy to use tools - track bookings, payments, and participant information all in one place.',
                ],
                [
                    'title' => 'Connect With Clients',
                    'text' => 'Build your client base and keep them engaged with automatic notifications and personalized communications.',
                ],
                [
                    'title' => 'Get Paid Securely',
                    'text' => 'Receive payments directly to your account with our secure payment processing system - no more chasing payments!',
                ]
            ];

            foreach ($pages as $index => $page) {
                WelcomePage::create([
                    'app_id' => 'host',
                    'title' => $page['title'],
                    'text' => $page['text'],
                    'sort_order' => $index,
                    'status' => true,
                ]);
            }
        }

        if (WelcomePage::where('app_id', 'everything')->count() === 0) {
            $pages = [
                [
                    'title' => 'Welcome',
                    'text' => 'Wellbeing made simple. Access made free.',
                ]
            ];

            foreach ($pages as $index => $page) {
                WelcomePage::create([
                    'app_id' => 'everything',
                    'title' => $page['title'],
                    'text' => $page['text'],
                    'sort_order' => $index,
                    'status' => true,
                ]);
            }
        }
    }
}
