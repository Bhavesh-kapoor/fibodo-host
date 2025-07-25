<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingCreateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'activity_id' => 'required|exists:activities,id',
            'payment_method_id' => [
                'required',
                'string',
                Rule::exists('payment_methods', 'id'),
            ],
            'notes' => 'nullable|string|max:255',
            'attendees' => 'required|array|min:1',
            'attendees.*.first_name' => 'required|string|max:255',
            'attendees.*.last_name' => 'required|string|max:255',
            'attendees.*.email' => 'required|email|max:255',
            'attendees.*.mobile_no' => 'nullable|string|max:20',
            'attendees.*.is_lead_attendee' => 'nullable|boolean',
            'attendees.*.notes' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'activity_id' => 'activity',
            'no_of_seats' => 'number of seats',
            'payment_method_id' => 'payment method',
            'attendees' => 'attendee list',
            'attendees.*.first_name' => 'first name',
            'attendees.*.last_name' => 'last name',
            'attendees.*.email' => 'email address',
            'attendees.*.mobile_no' => 'mobile number',
            'attendees.*.is_lead_attendee' => 'lead attendee status',
            'attendees.*.notes' => 'notes',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'payment_method_id.required' => 'Payment method is required.',
            'payment_method_id.exists' => 'Invalid payment method selected.',
            'amount.required' => 'Amount is required.',
            'amount.numeric' => 'Amount must be a number.',
            'amount.min' => 'Amount must be greater than or equal to 0.',
        ];
    }
}
