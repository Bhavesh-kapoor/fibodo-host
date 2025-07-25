<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;

class GetMediaRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'media_type' => 'required|in:landscape,portrait,gallery,avatar,cover-image'
        ];
    }
}
