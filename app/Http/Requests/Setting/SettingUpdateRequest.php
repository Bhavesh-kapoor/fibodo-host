<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
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
            'setting_key' => 'required|string|max:255|unique:settings,setting_key,' . $this->setting->id,
            'setting_value' => 'required|string|max:255',
            'setting_type' => 'required|string|in:string,number,boolean,json,url,email',
            'setting_group' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'status' => 'required|integer|in:0,1',
        ];
    }
}
