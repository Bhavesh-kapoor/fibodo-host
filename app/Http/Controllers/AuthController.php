<?php

namespace App\Http\Controllers;

use App\Exceptions\Otp\OtpExceptionInterface;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\OtpLoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    /**
     * Login.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request, AuthService $authService): \Illuminate\Http\JsonResponse
    {
        try {
            return Response::success("auth.success", $authService->login());
        } catch (\Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode() ?? HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * loginWithOtp
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginWithOtp(OtpLoginRequest $request, AuthService $authService): \Illuminate\Http\JsonResponse
    {
        try {
            return Response::success(
                !request()->input('otp', 0) ? __('otp.sent', ['attribute' => request()->email]) : "auth.success",
                $authService->loginWithOtp()
            );
        } catch (OtpExceptionInterface $e) {
            return response()->error($e->getErrorMessage(), null, 400);
        } catch (\Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode() ?? HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Logout the authenticated user by revoking their token.
     */
    public function logout(Request $request, AuthService $authService): \Illuminate\Http\JsonResponse
    {
        try {
            // Call the logout method from AuthService
            $authService->logout($request->user());

            // Return success response
            return Response::success('auth.signed_out');
        } catch (\Exception $e) {
            // Return error response
            return Response::error($e->getMessage(), null, $e->getCode() ?? HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * ResetPassword
     *
     * @param  mixed $request
     * @return Illuminate\Http\JsonResponse
     */
    public function resetPassowrd(ResetPasswordRequest $request, AuthService $authService): \Illuminate\Http\JsonResponse
    {
        try {
            $authService->resetPassword();
            return Response::success('passwords.reset');
        } catch (\Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }
}
