<?php

namespace App\Traits;

use App\Exceptions\Otp\ExpiredOtpException;
use App\Exceptions\Otp\InvalidOtpException;
use App\Exceptions\Otp\MaxOtpAttemptsExceededException;
use App\Exceptions\Otp\OtpTimeoutException;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Response;

trait OtpTrait
{

    /**
     * Generate OTP
     *
     * @param  User $user
     * @param  string $length
     * @return string|OtpTimeoutException|ExpiredOtpException
     */

    public static function generateOtp(User $user, int $length = 6, $source = null): string|OtpTimeoutException|ExpiredOtpException
    {
        if ($user->otp) {
            // Get the resend timeout from the configuration
            $currentTime = Carbon::now();
            $lastSentTime = Carbon::parse($user->otp->created_at);
            $resendTimeout = config('otp.resend_timeout');
            $elapsedTime = abs($currentTime->diffInSeconds($lastSentTime));
            if ($elapsedTime < $resendTimeout) {
                $remainingTime = round($resendTimeout - $elapsedTime);
                throw new OtpTimeoutException(__('otp.resend_timeout', ['seconds' =>  $remainingTime]), Response::HTTP_TOO_MANY_REQUESTS);
            }

            // else delete existing OTP 
            $user->otp()->delete();
        }

        // Else: generate new OTP
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // Generate a random string and take the first $length characters
        $otp = substr(str_shuffle(str_repeat($characters, ceil($length / strlen($characters)))), 0, $length);

        // Hash the OTP
        $hashedOtp = Hash::make($otp);

        // Save OTP to the database with an expiration time
        Otp::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'otp' => $hashedOtp,
            'source' => $source,
            'expires_at' => Carbon::now()->addMinutes(config('otp.expires_in')), // Set expiration time
        ]);

        return $otp; // Return the plain OTP to send to the user
    }

    /**
     * verifyOtp
     *
     * @param  mixed $user_id
     * @param  mixed $otp
     * @return bool
     */
    public function verifyOtp($email, string $otp): Otp
    {

        // Find the OTP record
        $otpRecord = Otp::where('email', $email)->first();

        // Check if the OTP record exists and if it has expired
        if (!$otpRecord) {
            throw new InvalidOtpException(); // No OTP record found
        }

        if (Carbon::now()->greaterThan($otpRecord->expires_at)) {
            throw new ExpiredOtpException(); // OTP has expired
        }

        // Check if the maximum attempts have been exceeded
        //TODO: last attempt time to detect if the user is trying to brute force the OTP
        if ($otpRecord->attempts >= config('otp.max_attempts')) {
            throw new MaxOtpAttemptsExceededException();
        }

        // Verify the hashed OTP
        if (Hash::check($otp, $otpRecord->otp)) {
            // OTP is valid, delete the OTP record
            $otpRecord->delete();
            return $otpRecord;
        }

        // increment the OTP attempt 
        $otpRecord->attempts += 1;
        $otpRecord->save();

        throw new InvalidOtpException(); // OTP is invalid

    }
}
