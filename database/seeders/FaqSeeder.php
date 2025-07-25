<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'app_id' => 'host',
                'question' => 'What do I need to bring along to an activity I\'m attending?',
                'answer' => 'All you need to do is bring proof of your booking confirmation email (you will receive this as soon as you have confirmed your booking).
You can do this by taking a print out with you or you can simply show it on your mobile or tablet. You will also need to bring any vouchers you might have used for your booking.',
                'order' => 1,
                'active' => true,
            ],
            [
                'app_id' => 'host',
                'question' => 'Can I ask a host a question?',
                'answer' => 'There are two ways of contacting the host. You can contact them privately via their profile page
or you can contact them on the questions tab of an activity they are hosting, this contact will be visible on the activity details.',
                'order' => 2,
                'active' => true,
            ],
            [
                'app_id' => 'host',
                'question' => 'Do I have to fill in my card details on my profile, or can I pay each time?',
                'answer' => 'fibodo saves your card details to make it quicker to book, so you don\'t have to enter your card details every time you book a place. You can edit your card payment details at any time.
All payment card information is stored securely and remotely in our PCI DSS Compliant Data Vault. To remove card payment details completely, please contact us at support@fibodo.com.',
                'order' => 3,
                'active' => true,
            ],
            [
                'app_id' => 'host',
                'question' => 'I have signed up but have not yet received an e-mail with my password?',
                'answer' => 'This is an automated email so should be received promptly but in some cases can take up to a few hours.
Please be sure to check your junk mail and if you have not received it then please contact support@fibodo.com.',
                'order' => 4,
                'active' => true,
            ],
            [
                'app_id' => 'host',
                'question' => 'Are the instructors qualified?',
                'answer' => 'All hosts are required to submit proof of membership to a relevant organisation
or provide details of the Business/ Sporting Facility where they operate.',
                'order' => 5,
                'active' => true,
            ],
            [
                'app_id' => 'host',
                'question' => 'I have searched on the fibodo website but there don\'t seem to be any activities in my area',
                'answer' => 'New activities are being added to fibodo constantly, and because everything on fibodo is live there may be times when there are no available activities near you.
Try changing the search criteria or checking again a little later. If you are still unable to find what you\'re looking for then please contact us at support@fibodo.com and we will aim to help provide more activities in your location.',
                'order' => 6,
                'active' => true,
            ],
            [
                'app_id' => 'host',
                'question' => 'How can I contact a host?',
                'answer' => 'You can send a direct message to any host you are following or when you are booked into an activity.
The host can remove the option to be messaged via his profile, but if you\'re booked into an activity you\'ll always have the ability to message.',
                'order' => 7,
                'active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
} 