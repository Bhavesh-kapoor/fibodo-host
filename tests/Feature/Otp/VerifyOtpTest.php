<?php

namespace Tests\Feature\Otp\Verify;

use App\Models\Otp;
use App\Models\User;
use function Pest\Laravel\actingAs;

const API_ROUTE = '/api/v1/otp/verify';

beforeEach(function () {
    // Create a user for testing
    $this->user = User::factory()->create();
});

it('can verify a valid OTP', function () {
    // Generate OTP
    $otp = Otp::generateOtp($this->user);

    // Verify the OTP
    actingAs($this->user, 'api')->postJson(API_ROUTE, ['otp' => $otp, 'email' => $this->user->email])
        ->assertStatus(200)
        ->assertJson(['message' => __('otp.verified')]);

    // Assert that the OTP record is deleted
    $this->assertDatabaseMissing('otps', [
        'user_id' => $this->user->id,
    ]);
});

it('fails to verify an expired OTP', function () {

    // Generate OTP
    $otp = Otp::generateOtp($this->user);

    // Manually set the OTP to expired
    $otpRecord = Otp::where('user_id', $this->user->id)->first();
    $otpRecord->expires_at = now()->subMinutes(1);
    $otpRecord->save();

    // Simulate the user verifying the expired OTP
    actingAs($this->user, 'api')->postJson(API_ROUTE, ['otp' => $otp, 'email' => $this->user->email])
        ->assertStatus(400)
        ->assertJson(['message' => __('otp.expired')]);
});

it('fails to verify after exceeding max attempts', function () {

    // Generate OTP
    Otp::generateOtp($this->user);

    // Simulate the user verifying with an invalid OTP multiple times
    $maxAttempts = config('otp.max_attempts');

    for ($i = 0; $i < $maxAttempts; $i++) {
        actingAs($this->user, 'api')->postJson(API_ROUTE, ['otp' => 'invalid-otp', 'email' => $this->user->email])
            ->assertStatus(400)
            ->assertJson(['message' => __('otp.invalid')]);
    }

    // Now, the next attempt should fail with max attempts exceeded
    actingAs($this->user, 'api')->postJson(API_ROUTE, ['otp' => 'invalid-otp', 'email' => $this->user->email])
        ->assertStatus(400)
        ->assertJson(['message' => __('otp.max_attempts_exceeded')]);
});
