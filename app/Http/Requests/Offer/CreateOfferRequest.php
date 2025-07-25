<?php

namespace App\Http\Requests\Offer;

use Illuminate\Foundation\Http\FormRequest;

class CreateOfferRequest extends FormRequest
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
            'offer_type_id' => 'required|exists:offer_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'value' => 'required|numeric|min:0',
            'is_discount' => 'required|boolean',
            'target_audience' => 'required|integer|in:1,2,3',
            'apply_to_all_products' => 'required|boolean',
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
            'offer_type_id.required' => 'Please select an offer type.',
            'offer_type_id.exists' => 'The selected offer type is invalid.',
            'name.required' => 'Please enter an offer name.',
            'value.required' => 'Please enter an offer value.',
            'value.min' => 'The offer value must be at least 0.',
            'is_discount.required' => 'Please specify if this is a discount or fixed price offer.',
            'target_audience.required' => 'Please select a target audience.',
            'target_audience.in' => 'The selected target audience is invalid.',
            'apply_to_all_products.required' => 'Please specify if this offer applies to all products.',
            'product_ids.required_if' => 'Please select at least one product.',
            'product_ids.*.exists' => 'One or more selected products are invalid.',
            'starts_at.after' => 'The start date must be a future date.',
            'expires_at.after' => 'The expiry date must be after the start date.'
        ];
    }
}
