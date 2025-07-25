<?php

namespace App\Http\Requests\Policy;

use App\Enums\PolicyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PolicyRequest extends FormRequest
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
            'policy_type' => ['nullable', 'string', Rule::in(PolicyType::getAllSlugs())],
            'is_global' => ['nullable', 'boolean'],
            'sort_by' => ['nullable', 'string', Rule::in(['title'])],
            'sort_order' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
