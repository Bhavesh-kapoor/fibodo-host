<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use Illuminate\Support\HtmlString;

class ClientInvitation extends Notification //implements ShouldQueue
{
    use Queueable;

    public User $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     */
    public function toMail(object $notifiable): MailMessage
    {
        $acceptUrl = url('/clients/accept-invite?email=' . urlencode($this->user->email));

        return (new MailMessage)
            ->subject('Invitation to Join as a Client')
            ->greeting('Hello ' . $this->user->first_name . '!')
            ->line('You have been invited to join ' . config('app.name') . ' as a client.')
            ->action('Accept Invitation', $acceptUrl)
            ->line('Thank you for using our application!')
            ->line(new HtmlString('<br>'))
            ->salutation("Regards,\n\r " . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}
