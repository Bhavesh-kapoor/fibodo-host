<?php

namespace App\Listeners;

use App\Events\BookingCancelled;
use App\Notifications\BookingCancelled as BookingCancelledNotification;
use Illuminate\Support\Facades\Notification;

class SendBookingCancelledEmails
{
    /**
     * Handle the event.
     */
    public function handle(BookingCancelled $event): void
    {
        $booking = $event->booking->load(['attendees.client']);

        foreach ($booking->attendees as $attendee) {
            if ($attendee->client) {
                Notification::send(
                    $attendee->client,
                    new BookingCancelledNotification($booking)
                );
            }
        }
    }
}
