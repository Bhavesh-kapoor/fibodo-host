<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'sometimes|email',
            'first_name' => 'sometimes|alpha:ascii',
            'last_name' => 'sometimes|alpha:ascii',
            'mobile_number' => 'sometimes|max:20',
        ];
    }
}
