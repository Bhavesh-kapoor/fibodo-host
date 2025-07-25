<?php

namespace App\Services;

use App\Http\Requests\Host\HostUpdateRequest;
use App\Http\Resources\HostResource;
use App\Models\Host;
use App\Models\Otp;
use App\Models\User;
use App\Notifications\EmailOtpVerification;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class HostService
{
    /**
     * Host Signup
     *
     * @return array
     */
    public function signup(): array
    {
        try {

            // Start a transaction
            DB::beginTransaction();

            $user = User::create([
                'first_name' => request()->first_name,
                'last_name' => request()->last_name,
                'email' => request()->email,
                'country_code' => request()->country_code,
                'mobile_number' => request()->mobile_number,
                'password' => Hash::make(request()->password),
                'date_of_birth' => request()->get('date_of_birth', null),
                'gender' => request()->get('gender', null),
            ]);

            // assign host role, create host role if not already created
            $user->assignRole('host');

            // create host
            $host = $user->host()->create([
                'business_name' => request()->business_name,
                'profile_state' => Host::PROFILE_STATE['ACCOUNT'],
            ]);

            // log in host 
            $token = $user->createToken('fibo_auth_token');

            // DISPATCH HOST CREATED EVENT - to send email OTP to verify 
            $user->notify(new EmailOtpVerification(Otp::generateOtp($user)));

            // save record
            DB::commit();

            // return success response 
            return [
                'user' => new HostResource($host),
                'auth' => [
                    'token' => $token->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse($token->token->expires_at)->toISOString()
                ],
            ];
        } catch (Exception $e) {
            // Rollback the transaction and handle validation errors
            DB::rollBack();
            throw $e;
        }
    }


    /**
     * store
     *
     * @param  mixed $request
     * @param  mixed $product
     * @return LocationResource
     */
    public function update(HostUpdateRequest $request, Host $host): HostResource
    {
        try {

            // merge company and business data
            $hostData = array_merge(
                $this->appendPrefix($request->input('business', []), 'business_'),
                $this->appendPrefix($request->input('company', []), 'company_')
            );

            $userData = $request->only(
                'first_name',
                'last_name',
                'date_of_birth',
                'gender',
                'email',
            );

            // check if password is provided
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->input('password'));
            }

            // if email exists for update, TODO: send email verification
            if ($request->has('email') && $host->user->email !== $request->input('email')) {
                $host->user->notify(new EmailOtpVerification(Otp::generateOtp($host->user)));
                $userData['email_verified_at'] = null;
            }

            // get host profile state
            $profileState = $host->profile_state === Host::PROFILE_STATE['COMPLETED']  ? Host::PROFILE_STATE['COMPLETED'] :  $this->getProfileState();
            $statusData = [];
            if ($host->user->status !== Host::STATUS['ACTIVE']) {
                $statusData['status'] = Host::PROFILE_STATE['COMPLETED'] === $profileState ? Host::STATUS['ACTIVE'] : Host::STATUS['INACTIVE'];
            }

            //update user profile details 
            $host->user()->update(array_merge(
                $userData,
                $statusData
            ));


            //TODO:  if mobile number exists for update, TODO: send mobile verification

            // update Host profile details
            $host->update($hostData + [
                'profile_state' => $profileState,
            ]);

            // return Host Resource
            return new HostResource($host);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * appendPrefix
     *
     * @param  mixed $data
     * @param  mixed $prefix
     * @return array
     */
    function appendPrefix($data, $prefix): array
    {
        return collect($data)->mapWithKeys(function ($value, $key) use ($prefix) {
            return [$prefix . $key => $value];
        })->toArray();
    }


    /**
     * getProfileState
     *
     * @param  mixed $host
     * @return int
     */
    public function getProfileState(): int
    {

        if (request()->input('company.name') && request()->input('company.contact_no') && request()->input('company.email') && request()->input('company.vat')) {
            return Host::PROFILE_STATE['COMPLETED'];
        } elseif (request()->input('business_name') && request()->input('business_tagline') && request()->input('business_about')) {
            return Host::PROFILE_STATE['COMPANY'];

            // } elseif ($data['wallets']()->count() > 0) {
            //     return Host::PROFILE_STATE['BUSINESS'];

        } else {
            return Host::PROFILE_STATE['ACCOUNT'];
        }
    }
}
