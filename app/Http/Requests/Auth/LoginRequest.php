<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'password' => 'required|string|min:8'
        ];
    }


    /**
     * messages
     *
     * @return void
     */
    public function messages()
    {
        return ['email.exists' => __('auth.exists')];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->has('email')) {
                return;
            }

            $email = $this->input('email');
            if ($email) {
                // Check for case-insensitive email uniqueness
                $existingUser = \App\Models\User::whereRaw('LOWER(email) = ?', [strtolower($email)])->first();
                if (!$existingUser) {
                    $validator->errors()->add('email', __('auth.exists'));
                }
            }
        });
    }
}
