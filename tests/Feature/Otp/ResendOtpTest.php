<?php

namespace Tests\Feature\Otp\Resend;

use App\Models\User;
use App\Models\Otp;
use App\Notifications\EmailOtpVerification;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;

const API_ROUTE = '/api/v1/otp/resend';

beforeEach(function () {
    // Create a user for testing
    $this->user = User::factory()->create(['status' => 1]);
});

it('resend OTP when no OTP exists', function () {

    // Mock the notification
    Notification::fake();

    // Act
    $response = $this->postJson(API_ROUTE, ['email' => $this->user->email]);


    // Assert
    $response->assertStatus(Response::HTTP_OK)
        ->assertJson(['message' => __('otp.sent', ['attribute' => $this->user->email])]);

    Notification::assertSentTo($this->user, EmailOtpVerification::class);
});

it('gives timeout error if resend timeout has not elapsed', function () {

    // Create an OTP with a recent timestamp
    Otp::factory()->create([
        'user_id' => $this->user->id,
        'email' => $this->user->email,
        'created_at' => Carbon::now(), // Set `created_at` to the frozen time
    ]);

    // Set a resend timeout in the config
    Config::set('otp.resend_timeout', 60); // 60 seconds

    // Act
    $response = $this->postJson(API_ROUTE, ['email' => $this->user->email]);

    // Assert
    $response->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);
});

it('resend OTP when resend timeout has elapsed', function () {

    // Create an OTP with an old timestamp
    $otp = Otp::factory()->create(['user_id' => $this->user->id, 'email' => $this->user->email, 'created_at' => now()->subSeconds(61)]);

    // Set a resend timeout in the config
    Config::set('otp.resend_timeout', 60); // 60 seconds

    // Mock the notification
    Notification::fake();

    // Act
    $response = $this->postJson(API_ROUTE, ['email' => $this->user->email]);

    // Assert
    $response->assertStatus(Response::HTTP_OK)
        ->assertJson(['message' => __('otp.sent', ['attribute' => $this->user->email])]);

    Notification::assertSentTo($this->user, EmailOtpVerification::class);

    // Ensure the old OTP is deleted
    $this->assertDatabaseMissing('otps', ['id' => $otp->id]);
});
