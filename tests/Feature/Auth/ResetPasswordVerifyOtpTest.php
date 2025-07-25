<?php

use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Response;

it('send Password Reset Token on successfull OTP verification', function () {
    // prepare data
    $user = User::factory()->create();
    $user->markActive();

    // Generate OTP
    $otp = Otp::generateOtp($user, source: Otp::SOURCE_RESET_PASSWORD);

    // Send the request
    $response = $this->postJson('/api/v1/otp/verify', ['otp' => $otp, 'email' => $user->email]);

    // Assert the response
    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure(['message', 'data' => ['token']]);

    // test if token is stored in the database
    $this->assertDatabaseHas('password_reset_tokens', ['token' => $response->json('data.token')]);
});
