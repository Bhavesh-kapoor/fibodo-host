<?php

namespace App\Http\Requests\Setting;

use App\Enums\SettingType;
use Illuminate\Foundation\Http\FormRequest;

class SettingCreateRequest extends FormRequest
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
            'setting_group' => 'required|string|max:255',
            'settings' => 'required|array',
            'settings.*.setting_key' => 'required|string|max:255',
            'settings.*.setting_value' => 'required|string',
            'settings.*.setting_type' => 'required|string|in:' . implode(',', SettingType::values()),
            'settings.*.description' => 'nullable|string|max:255',
            'settings.*.sort_order' => 'nullable|integer',
            'settings.*.status' => 'nullable|integer|in:0,1',
        ];
    }
}
