<?php

namespace App\Http\Requests\Offer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOfferRequest extends FormRequest
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
            'offer_type_id' => 'nullable|exists:offer_types,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'value' => 'nullable|numeric|min:0',
            'is_discount' => 'nullable|boolean',
            'target_audience' => 'nullable|integer|in:1,2,3', // 1 = All Attendees, 2 = Lead Broker, 3 = New Clients
            'apply_to_all_products' => 'nullable|boolean',
            'product_ids' => 'required_if:apply_to_all_products,false|array',
            'product_ids.*' => 'exists:products,id',
            'terms_conditions' => 'nullable|string',
            'status' => 'nullable|boolean',
            'starts_at' => 'nullable|date|after:now',
            'expires_at' => 'nullable|date|after:starts_at'
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
            'offer_type_id.exists' => 'The selected offer type is invalid.',
            'value.min' => 'The offer value must be at least 0.',
            'target_audience.in' => 'The selected target audience is invalid.',
            'product_ids.required_if' => 'Please select at least one product.',
            'product_ids.*.exists' => 'One or more selected products are invalid.',
            'starts_at.after' => 'The start date must be a future date.',
            'expires_at.after' => 'The expiry date must be after the start date.'
        ];
    }
}
