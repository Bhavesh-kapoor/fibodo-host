<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'location' => 'sometimes|required|string',
            'note' => 'sometimes|required|string|max:1000',
            'lat' => 'sometimes|required|string',
            'long' => 'sometimes|required|string',
        ];
    }
}
