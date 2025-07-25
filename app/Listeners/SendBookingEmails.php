<?php

namespace App\Listeners;

use App\Events\BookingEmailNotification;
use App\Notifications\BookingConfirmation;
use Illuminate\Support\Facades\Notification;

class SendBookingEmails
{
    /**
     * Handle the event.
     */
    public function handle(BookingEmailNotification $event): void
    {
        $booking = $event->booking->load(['attendees.client']);

        foreach ($booking->attendees as $attendee) {
            if ($attendee->client) {
                Notification::send($attendee->client, new BookingConfirmation($booking, $attendee));
            }
        }
    }
}
