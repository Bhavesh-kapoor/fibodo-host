<?php

namespace App\Services;

use App\Models\Card;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    /**
     * Secure Payment Token - WorldNet 
     *
     * @return array
     */
    public function createSecureToken(): array
    {
        try {

            $wnResponse = [
                'RESPONSECODE' => request()->RESPONSECODE,
                'RESPONSETEXT' => request()->RESPONSETEXT,
                'MERCHANTREF' => request()->MERCHANTREF,
                'DATETIME' => request()->DATETIME,
                'HASH' => request()->CARDTYPE,
                'CARDTYPE' => request()->CARDTYPE,
                'MASKEDCARDNUMBER' => request()->MASKEDCARDNUMBER,
                'CARDEXPIRY' => request()->CARDEXPIRY,
                'CARDHOLDERNAME' => request()->CARDHOLDERNAME,
                'CARDREFERENCE' => request()->CARDREFERENCE,
            ];

            DB::beginTransaction();
            $card = Card::create([
                'user_id' => explode('?^', request()->MERCHANTREF)[1],
                'merchant_ref' => request()->MERCHANTREF,
                'worldnet_ref' => request()->CARDREFERENCE,
                'number' => request()->MASKEDCARDNUMBER,
                'type' => request()->CARDTYPE,
                'expiry' => request()->CARDEXPIRY,
                'holder_name' => request()->CARDHOLDERNAME ?? '',
                'holder_email' => '',
                'holder_phone' => '',
                'is_stored' => true,
                'description' => json_encode($wnResponse)
            ]);

            // save record
            DB::commit();

            // DISPATCH HOST CREATED EVENT - to send email OTP to verify 
            //$user->notify(new EmailOtpVerification(Otp::generateOtp($user)));

            // return success response 
            return [];

        } catch (Exception $e) {
            // Rollback the transaction and handle validation errors
            DB::rollBack();
            throw $e;
        }
    }
}
