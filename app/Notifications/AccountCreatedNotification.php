<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class AccountCreatedNotification extends Notification //implements ShouldQueue
{
    use Queueable;

    public string $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification delivery channels.
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
        $resetUrl = url('/password/reset/' . $this->token . '?email=' . urlencode($notifiable->email));

        return (new MailMessage)
            ->subject('Welcome to ' . config('app.name') . '! Set Your Password')
            ->greeting('Hello ' . ($notifiable->first_name ?? 'there') . ',')
            ->line('An account has been created for you by ' . config('app.name') . '.')
            ->line('Please click the button below to set your password and complete your registration:')
            ->action('Set Password', $resetUrl)
            ->line('If you did not expect this email, no further action is required.')
            ->line(new HtmlString('<br>'))
            ->salutation("Regards,\n\r " . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'token' => $this->token,
        ];
    }
}
