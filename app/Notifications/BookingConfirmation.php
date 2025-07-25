<?php

namespace App\Notifications;

use App\Models\Attendee;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmation extends Notification
{
    use Queueable;

    protected Booking $booking;
    protected Attendee $attendee;
    protected bool $isNewClient;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking, Attendee $attendee, bool $isNewClient = false)
    {
        $this->booking = $booking;
        $this->attendee = $attendee;
        $this->isNewClient = $isNewClient;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        $activity_start_time = Carbon::parse($this->booking->activity_start_time);
        $message = (new MailMessage)
            ->subject('Your Booking is Confirmed!')
            ->greeting('Hello ' . $this->attendee->full_name . ',')
            ->line('We\'re excited to confirm your booking. Here are your details:')
            ->line('**Booking Number:** ' . $this->booking->booking_number)
            ->line('**Activity:** ' . $this->booking->product->title)
            ->line('**Date:** ' . $activity_start_time->format('l, F j, Y'))
            ->line('**Time:** ' . $activity_start_time->format('g:i A') . ' - ' . $this->booking->activity_end_time->format('g:i A'))
            ->line('**Seats Booked:** ' . $this->booking->seats_booked)
            ->line('**Total Amount:** ' . config('app.currency_symbol') . ' ' . number_format($this->booking->total_amount, 2));

        if ($this->isNewClient) {
            $message->line('To view and manage your booking, please create an account:')
                ->action('Create Account', route('register', ['email' => $this->attendee->email]));
        } else {
            $message->action('View Booking', config('app.frontend_url') . '/bookings/' . $this->booking->id);
        }

        return $message->line('If you have any questions, feel free to reply to this email.')
            ->salutation('Thanks, and see you soon!' . "\n\r" . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
