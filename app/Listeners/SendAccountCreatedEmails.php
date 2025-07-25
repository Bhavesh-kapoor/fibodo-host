<?php

namespace App\Listeners;

use App\Events\AccountCreated;
use App\Notifications\AccountCreatedNotification;
use Illuminate\Support\Facades\Password;

class SendAccountCreatedEmails
{
    /**
     * Handle the event.
     */
    public function handle(AccountCreated $event): void
    {
        $user = $event->user;
        // Generate a password reset token
        $token = Password::broker()->createToken($user);
        // Send the account created notification with reset link
        $user->notify(new AccountCreatedNotification($token));
    }
}
