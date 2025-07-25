<?php

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;

it('fails when the user is not found', function () {

    // Send the request
    $response = $this->postJson('/api/v1/auth/forgot-password', ['email' => 'email.notexists@example.com']);

    // Assert the response
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['email']);
});

it('sends OTP to the user email successfully', function () {
    // Arrange
    $user = User::factory()->create(['email' => 'test@example.com']);
    $user->markActive();

    // Act
    $response = $this->postJson('/api/v1/auth/forgot-password', ['email' => $user->email]);

    // Assert
    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'message' => __('otp.sent', ['attribute' => $user->email]),
        ]);
});


it('generates an OTP and sends the reset password OTP notification to user\'s email', function () {

    $user = User::factory()->create();
    $user->markActive();

    // Fake notification 
    Notification::fake();

    // Send the request
    $response = $this->postJson('/api/v1/auth/forgot-password', ['email' => $user->email]);

    // Assert the response
    $response->assertStatus(Response::HTTP_OK)
        ->assertJson(['message' => __('otp.sent', ['attribute' => $user->email])]);

    // Assert the OTP was generated and stored in the database
    $this->assertDatabaseHas('otps', ['user_id' => $user->id]);

    // Assert a notification was sent with the OTP
    Notification::assertSentTo(
        $user,
        ResetPasswordNotification::class
    );
});
