<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'nullable|integer|in:0,1',
            'setting_key' => 'nullable|string|max:255',
            'setting_group' => 'required|string|max:255',
            'per_page' => 'nullable|integer|min:1',
            'page' => 'nullable|integer|min:1',
        ];
    }
}
