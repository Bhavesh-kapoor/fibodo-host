<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_type_id' => 'nullable|exists:product_types,id',
            'sort_order' => 'nullable|array',
            'sort_order.*' => 'in:asc,desc,ASC,DESC', // Validate each element in the array
            'sort_by' => 'nullable|array',
            'sort_by.*' => 'nullable|string|in:price,created_at,incomplete,title',
            's' => 'nullable|string|min:3',
            'archive' => 'nullable|in:0,1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}
