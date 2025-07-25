<?php

namespace App\Http\Requests\Otp;

use App\Models\Otp;
use Illuminate\Foundation\Http\FormRequest;

class ResendOtpRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'source' => 'required|integer|in:' . implode(',', [Otp::VERIFY_EMAIL, Otp::SOURCE_RESET_PASSWORD, Otp::SOURCE_LOGIN])
        ];
    }
}
