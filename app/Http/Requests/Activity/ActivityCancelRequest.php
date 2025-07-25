<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;

class ActivityCancelRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'start_time' => 'required|date_format:Y-m-d H:i|after_or_equal:now',
            'end_time' => 'required|date_format:Y-m-d H:i|after:start_time',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'note' => 'required|string|max:500',
            'force' => 'boolean'
        ];
    }
}
