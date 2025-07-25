<?php

namespace App\Providers;

use App\Events\BookingConfirmed;
use App\Events\BookingEmailNotification;
use App\Events\BookingCancelled;
use App\Listeners\HandleBookingConfirmation;
use App\Listeners\SendBookingEmails;
use App\Listeners\SendBookingCancelledEmails;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}
