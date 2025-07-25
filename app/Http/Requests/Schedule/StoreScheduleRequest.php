<?php

namespace App\Http\Requests\Schedule;

use App\Models\Product;
use App\Models\User;
use App\Rules\HourOrHalfPast;
use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'recurres_in' => ['required', 'integer', new HourOrHalfPast],
            'weekly_schedules' => 'required|array',
            'weekly_schedules.*.name' => 'required|string',
            'weekly_schedules.*.is_default' => 'required|in:0,1',
            'weekly_schedules.*.status' => 'required|in:0,1',
            'weekly_schedules.*.days' => 'required|array',
            'weekly_schedules.*.days.*.day_of_week' => 'required|integer|between:0,6',
            'weekly_schedules.*.days.*.start_time' => 'required|date_format:H:i',
            'weekly_schedules.*.days.*.end_time' => 'required|date_format:H:i',
            'weekly_schedules.*.days.*.breaks' => 'nullable|array',
            'weekly_schedules.*.days.*.breaks.*.name' => 'nullable|string',
            'weekly_schedules.*.days.*.breaks.*.start_time' => 'required|date_format:H:i',
            'weekly_schedules.*.days.*.breaks.*.end_time' => 'required|date_format:H:i',
        ];
    }
}
