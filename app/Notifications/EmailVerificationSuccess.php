<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationSuccess extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
            ->subject('🎉 Welcome to Fibodo - Your Journey Begins Now!')
            ->greeting('Welcome aboard, ' . $notifiable->first_name . '! 🌟')
            ->line('**Congratulations!** Your email has been successfully verified and your Fibodo account is now active.')
            ->line('') // Empty line for spacing
            ->line('🚀 **You\'re all set to transform your business!**')
            ->line('')
            ->line('Here\'s what awaits you in your Fibodo dashboard:')
            ->line('')
            ->line('✨ **Create Stunning Activities** - Showcase your services with beautiful listings')
            ->line('🏢 **Build Your Business Profile** - Tell your story and attract more customers')
            ->line('📅 **Effortless Booking Management** - Never miss an appointment again')
            ->line('👥 **Client Relationship Tools** - Keep your customers happy and engaged')
            ->line('📊 **Powerful Analytics** - Make data-driven decisions to grow your business')
            ->line('⚙️ **Customizable Settings** - Tailor Fibodo to work exactly how you want')
            ->line('')
            ->line('**Ready to dive in?** Your dashboard is waiting for you!')
            ->action('Launch My Dashboard 🚀', config('app.frontend_url') . '/dashboard')
            ->line('')
            ->line('---')
            ->line('')
            ->line('💡 **Pro Tip:** Complete your business profile first to start attracting customers immediately!')
            ->line('')
            ->line('Need help getting started? Our friendly support team is just a message away, ready to guide you through every step of your Fibodo journey.')
            ->line('')
            ->line('Here\'s to your success! 🥂')
            ->line('')
            ->line('**The Fibodo Team**')
            ->line('*Empowering businesses, one booking at a time*');
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
