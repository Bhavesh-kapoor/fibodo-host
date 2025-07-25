<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;

class ActivityUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_time' => 'required|date_format:Y-m-d H:i|before_or_equal:end_time',
            'end_time' => 'sometimes|date_format:Y-m-d H:i|after_or_equal:start_time',
            'status' => 'in:0,1',
            'product_ids' => 'array',
            'product_ids.*.' => 'exists:products,id'
        ];
    }
}
