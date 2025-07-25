<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class LoginOtpNotification extends Notification
{
    use Queueable;

    public $otp;
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, string $otp)
    {
        $this->otp = $otp;
        $this->user = $user;
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
            ->subject('Signin OTP')
            ->greeting("Hello {$this->user->fullName},")
            ->line('Your One-Time Password (OTP) is: **' . $this->otp . '**')
            ->line('This OTP is valid for a short period of time. Please do not share it with anyone.')
            ->line('Thank you for using our application!')
            ->line(new HtmlString('<br>'))
            ->salutation("Regards,\n\r " . config('app.name'));
    }
}
