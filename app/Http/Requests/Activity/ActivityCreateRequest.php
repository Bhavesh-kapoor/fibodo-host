<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;

class ActivityCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_time' => 'required|date|date_format:Y-m-d H:i|before:end_time|after_or_equal:now',
            'end_time' => 'date|date_format:Y-m-d H:i|after:start_time',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'weekly_schedule_ids' => 'nullable|array',
            'weekly_schedule_ids.*' => 'exists:weekly_schedules,id',
        ];
    }
}
