<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActivityGetRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => Rule::exists('products', 'id')->where('user_id', request()->user()->id),
            'start_time' => 'required_with:end_time|date|date_format:Y-m-d H:i|before:end_time',
            'end_time' => 'required_with:start_time|date|date_format:Y-m-d H:i|after:start_time',
        ];
    }
}
