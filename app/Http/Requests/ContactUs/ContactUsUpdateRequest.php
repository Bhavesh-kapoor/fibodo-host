<?php

namespace App\Http\Requests\ContactUs;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,in_progress,resolved',
        ];
    }
} 