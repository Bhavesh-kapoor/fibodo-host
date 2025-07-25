<?php

namespace App\Services;

use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Models\Otp;
use App\Models\User;
use App\Notifications\LoginOtpNotification;
use App\Notifications\ResetPasswordNotification;
use Exception;
use Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class AuthService
{
    /**
     * Login service
     *
     * @return AuthResource|null
     */
    public function login(): AuthResource|null
    {
        try {

            // get user by email
            $user = User::role(['host', 'client'])->where(DB::raw('LOWER(email)'), strtolower(request()->email))->where('status', User::STATUS_ACTIVE)->first();

            if (!$user) throw new Exception(__('auth.failed'), Response::HTTP_UNAUTHORIZED);

            // check user creds 
            if (!Hash::check(request()->password, $user->password)) {
                throw new Exception(__('auth.failed'), Response::HTTP_UNAUTHORIZED);
            }

            $user->auth = $user->createToken('fibo_auth_token');

            return new AuthResource($user);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Login with OTP service
     *
     * @return AuthResource|null
     */
    public function loginWithOtp(): AuthResource|null
    {
        try {

            // get user by email
            $user = User::role(['host', 'client'])->where(['email' => request()->email, 'status' => User::STATUS_ACTIVE])->first();

            if (!$user) throw new Exception(__('auth.failed'), Response::HTTP_UNAUTHORIZED);

            if (!request()->input('otp', 0)) {
                // send email OTP 
                $user->notify(new LoginOtpNotification($user, Otp::generateOtp($user, 6, 1)));
                return null;
            }

            // verify OTP  
            if (request()->input('otp', 0) && !(new OtpService)->verify()) {
                throw new Exception(__('auth.failed'), Response::HTTP_UNAUTHORIZED);
            }

            $user->auth = $user->createToken('fibo_auth_token');

            return new AuthResource($user);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * Logout service
     *
     * @param  mixed $user
     * @return bool
     */
    public function logout(User $user): bool
    {
        try {
            // Revoke the authenticated user's token
            $user->tokens->each(function ($token) {
                $token->revoke();
            });
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * Forgot password service
     *
     */
    public function forgotPassowrd()
    {

        try {
            // Get user by email
            $user = User::getUserByEmail(request()->email, false);

            // if user is not active
            if (!$user->isActive())
                throw new Exception(__('auth.deactivated'), Response::HTTP_NOT_FOUND);


            // Send the OTP to the user
            // $user->notify(new ResetPasswordNotification(Otp::generateOtp(user: $user, source: Otp::SOURCE_RESET_PASSWORD)));

            if (Password::RESET_LINK_SENT !== ($message = Password::sendResetLink(['email' => request()->email])))
                throw new Exception($message, Response::HTTP_TOO_MANY_REQUESTS);


            // Return success response
            return true;
        } catch (\Exception $e) {
            // Return error response
            throw $e;
        }
    }


    /**
     * Reset Password service
     *
     * @return bool
     */
    public function resetPassword()
    {
        try {

            Auth::user()->update(['password' => Hash::make(request()->password)]);
            // Revoke all tokens for the user
            Auth::user()->tokens->each(function ($token) {
                $token->revoke();
            });
            // Send success response
            //TODO: $user->notify(new ResetPasswordNotification();
            // Return success response
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
