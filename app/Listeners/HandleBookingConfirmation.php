<?php

namespace App\Listeners;

use App\Events\BookingConfirmed;

class HandleBookingConfirmation
{
    /**
     * Handle the event.
     */
    public function handle(BookingConfirmed $event): void {}
}
