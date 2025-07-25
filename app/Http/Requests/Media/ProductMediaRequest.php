<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;

class ProductMediaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'media_type' => 'required|in:landscape,portrait,gallery',
            'media' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (request('media_type') === 'gallery' && !is_array($value)) {
                        $fail('The media field must be an array when media_type is gallery.');
                    }

                    if (request('media_type') !== 'gallery' && is_array($value)) {
                        $fail('The media field must be a single file unless media_type is gallery.');
                    }
                }
            ],
            'media.*' => 'required_if:media_type,gallery|file|image|max:5024|mimes:jpeg,png,jpg,gif',
        ];
    }
}
