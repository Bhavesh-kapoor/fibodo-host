<?php

namespace Tests\Feature\Otp;

use App\Models\Otp;
use App\Models\User;
use App\Notifications\EmailOtpVerification;
use Notification;


beforeEach(function () {
    // Create a user for testing
    $this->user = User::factory()->create();
});

it('can generate and send an OTP', function () {
    // Mock the EmailOtpVerification
    Notification::fake();

    // Call the method to send OTP
    $otp = Otp::generateOtp($this->user);
    $this->user->notify(new EmailOtpVerification($otp));

    // Assert that the OTP was sent
    Notification::assertSentTo([$this->user], \App\Notifications\EmailOtpVerification::class);
    expect($otp)->toBeString()->toHaveLength(6); // Assuming OTP length is 6
});
