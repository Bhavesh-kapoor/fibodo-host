<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * The OTP to be sent.
     *
     * @var string
     */
    protected $otp;

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
     * Get the mail representation of the notification
     *
     * @param  mixed $notifiable
     * @return Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset Password OTP')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->line('Your OTP is: **' . $this->otp . '**')
            ->line('This OTP is valid for ' . config('otp.resend_timeout') . ' seconds.')
            ->line('If you did not request a password reset, no further action is required.')
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
