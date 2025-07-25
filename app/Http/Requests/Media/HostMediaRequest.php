<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;

class HostMediaRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'media' => 'required|file|image|max:2048|mimes:jpeg,png,jpg',
            'media_type' => 'required|in:avatar,cover-image',
        ];
    }
}
