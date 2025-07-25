<?php

namespace App\Http\Requests\Host;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SignupRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // validate 
        return [
            'first_name' => 'nullable|alpha:ascii',
            'last_name' => 'nullable|alpha:ascii',
            'email' => 'required|email|unique:users',
            'business_name' => 'required|string',
            'mobile_number' => 'nullable|unique:users|string',
            'password' => ['required', 'string', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            'confirm_password' => 'required|confirmed:password'
        ];
    }
}
