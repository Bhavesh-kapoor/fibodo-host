<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\Otp;
use App\Models\User;
use App\Notifications\EmailOtpVerification;
use App\Notifications\EmailVerificationSuccess;
use App\Traits\OtpTrait;
use Illuminate\Support\Facades\DB;
use Str;

class OtpService
{
    use OtpTrait;


    /**
     * verify
     *
     * @return array
     */
    public function verify(): array
    {

        try {

            // verify otp
            $otp = $this->verifyOtp(request()->email, request()->otp);

            // otp source operations
            switch ($otp->source) {
                case Otp::SOURCE_RESET_PASSWORD:
                    // update user verified_at 
                    // send Reset password Token
                    $token = Str::random(60);

                    // delete email token if any
                    DB::table('password_reset_tokens')->where('email', request()->email)->delete();

                    DB::table('password_reset_tokens')->insert([
                        'email' => request()->email,
                        'token' => $token,
                        'created_at' => now()
                    ]);
                    break;
                case Otp::VERIFY_EMAIL:
                    // update user verified_at 
                    $user = User::getUserByEmail(request()->email);
                    $user->update(['email_verified_at' => now()]);
                    // send email verification success
                    $user->notify(new EmailVerificationSuccess());
                    break;
            }

            return ['token' => $token ?? null];
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * Resend OTP service
     *
     * @return UserResource
     */
    public function resend(): UserResource
    {
        try {
            $user = User::getUserByEmail(request()->email);
            // Generate and send OTP
            $user->notify(new EmailOtpVerification(Otp::generateOtp($user, source: request()->source)));

            return new UserResource($user);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
