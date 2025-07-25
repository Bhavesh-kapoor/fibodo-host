<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class EmailOtpVerification extends Notification
{
    use Queueable;

    public $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $otp)
    {
        $this->otp = $otp;
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
        return (new MailMessage)
            ->subject('Host email verification OTP')
            ->greeting('Hello!')
            ->line('Your One-Time Password (OTP) is: **' . $this->otp . '**')
            ->line('This OTP is valid for a short period of time. Please do not share it with anyone.')
            //->action('Verify OTP', url('/verify-otp')) 
            ->line('Thank you for using our application!')
            ->line(new HtmlString('<br>'))
            ->salutation("Regards,\n\r " . config('app.name'));
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
