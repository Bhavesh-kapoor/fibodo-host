<?php

namespace App\Http\Requests\Host;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class HostUpdateRequest extends FormRequest
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
            'first_name' => 'sometimes|required|alpha:ascii',
            'last_name' => 'sometimes|required|alpha:ascii',
            'email' => 'sometimes|required|email|unique:users,email,' . $this->user()?->id,
            'password' => ['sometimes', 'required', 'string', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            'confirm_password' => 'required_with:password|confirmed:password',
            'date_of_birth' => 'sometimes|nullable|date_format:Y-m-d|before:today', // -13 years
            'gender' => 'sometimes|nullable|in:m,f',
            'mobile_number' => 'sometimes|required|unique:users,mobile_number,' . $this->user()?->id . '|string',
            'business' => 'sometimes|required|array',
            'business.name' => 'sometimes|required|string',
            'business.tagline' => 'sometimes|required|string',
            'business.about' => 'sometimes|required|string',
            'business.website' => 'sometimes|nullable|string',
            'company' => 'sometimes|required|array',
            'company.name' => 'sometimes|required|string',
            'company.address_line1' => 'sometimes|nullable|string',
            'company.address_line2' => 'sometimes|nullable|string',
            'company.city' => 'sometimes|nullable|string',
            'company.zip' => 'sometimes|nullable|string',
            'company.country' => 'sometimes|nullable|string',
            'company.contact_no' => 'sometimes|required|string',
            'company.email' => 'sometimes|required|string|email',
            'company.vat' => 'sometimes|required|string',
            'company.website' => 'sometimes|nullable|string'
        ];
    }
}
