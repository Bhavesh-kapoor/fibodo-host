<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            's' => 'nullable|string|min:3',
            'per_page' => 'nullable|integer|min:1|max:100',
            'is_archived' => 'nullable|boolean',
            'sort_by' => 'nullable|string|in:created_at,updated_at,archived_at',
            'sort_order' => 'nullable|string|in:asc,desc',
        ];
    }
}
