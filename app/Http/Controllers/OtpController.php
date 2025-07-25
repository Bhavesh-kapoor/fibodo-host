<?php

namespace App\Http\Controllers;

use App\Exceptions\otp\OtpExceptionInterface;
use App\Http\Requests\Otp\ResendOtpRequest;
use App\Http\Requests\Otp\VerifyOtpRequest;
use App\Models\Otp;
use App\Models\User;
use App\Notifications\EmailOtpVerification;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OtpController extends Controller
{



    /**
     * verify
     *
     * @param  mixed $request
     * @return void
     */
    public function verify(VerifyOtpRequest $request, OtpService $otpService)
    {

        try {
            return response()->success("otp.verified", $otpService->verify());
        } catch (OtpExceptionInterface $e) {
            return response()->error($e->getErrorMessage(), null, 400);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * Resend OTP
     *
     * @param  Request $request
     * @return Response
     */
    public function resend(ResendOtpRequest $request, OtpService $otpService): JsonResponse
    {
        try {
            $user = $otpService->resend();
            return response()->success(__('otp.sent', ['attribute' => $user->email]));
        } catch (OtpExceptionInterface $e) {
            return response()->error($e->getErrorMessage(), null, $e->getCode() ?? 400);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
