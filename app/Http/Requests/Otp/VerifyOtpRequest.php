<?php

namespace App\Http\Requests\Otp;

use App\Models\Otp;
use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'otp' => 'required|string|min:6',
            'email' => 'required|email|exists:users,email',
            'source' => 'required|integer|in:' . implode(',', [Otp::SOURCE_LOGIN, Otp::SOURCE_RESET_PASSWORD, Otp::VERIFY_EMAIL])
        ];
    }
}
