<?php

namespace App\Notifications;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancelled extends Notification
{
    use Queueable;

    private Booking $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $start = Carbon::parse($this->booking->activity_start_time);
        $end = Carbon::parse($this->booking->activity_end_time);

        $message = (new MailMessage)
            ->subject('Your Booking Has Been Cancelled')
            ->greeting('Hello ' . ($notifiable->first_name ?? 'Valued Guest') . ',')
            ->line('We regret to inform you that your booking.')
            ->line('**Booking Number:** ' . $this->booking->booking_number)
            ->line('**Activity:** ' . $this->booking->product->title)
            ->line('**Date:** ' . $start->format('l, F j, Y'))
            ->line('**Time:** ' . $start->format('g:i A') . ' - ' . $end->format('g:i A'))
            ->line('A refund of ' . $this->booking->currency . number_format($this->booking->total_amount, 2) . ' has been processed to your payment method.')
            ->line('We apologize for any inconvenience caused.');

        return $message->salutation("Regards, \n\r" . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}
