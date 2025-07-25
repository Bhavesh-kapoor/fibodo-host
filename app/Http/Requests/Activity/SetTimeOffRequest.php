<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;

class SetTimeOffRequest extends FormRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'title' => 'string|max:255',
            'note' => 'string',
            'start_time' => 'required|date|date_format:Y-m-d H:i|before:end_time',
            'end_time' => 'required|date|date_format:Y-m-d H:i|after:start_time',
            'force' => 'boolean',
        ];
    }
}
