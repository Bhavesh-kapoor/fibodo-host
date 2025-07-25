<?php

namespace App\Http\Requests\Voucher;

use Illuminate\Foundation\Http\FormRequest;

class VoucherRequest extends FormRequest
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
            'status' => 'nullable|boolean',
            'sort_order' => 'nullable|array',
            'sort_order.*' => 'in:asc,desc,ASC,DESC',
            'sort_by' => 'nullable|array',
            'sort_by.*' => 'nullable|string|in:name,created_at,expires_at,value',
            's' => 'nullable|string|min:3',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}
