<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class PaymentCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'card_id' => 'exists:cards,id',
            'booking_id' => 'exists:bookings,id',
            'pay_order_id' => 'required',
            'pay_amount' => 'required|decimal',
            'pay_response_code' => 'required',
            'pay_unique_ref' => 'required',
            'pay_datetime' => 'date',
            'pay_description' => 'required',
        ];
    }
}