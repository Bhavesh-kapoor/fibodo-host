<?php

namespace App\Http\Requests\Voucher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVoucherRequest extends FormRequest
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
            'voucher_type_id' => 'nullable|exists:voucher_types,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'value' => 'nullable|numeric|min:0',
            'pay_for_quantity' => 'nullable|integer|min:1',
            'get_quantity' => 'nullable|integer|min:1',
            'is_transferrable' => 'nullable|boolean',
            'is_gift_eligible' => 'nullable|boolean',
            'can_combine' => 'nullable|boolean',
            'inventory_limit' => 'nullable|integer|min:1',
            'active_for_sale' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'expires_at' => 'nullable|date|after:now'
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
            'voucher_type_id.exists' => 'The selected voucher type is invalid.',
            'value.min' => 'The voucher value must be at least 0.',
            'pay_for_quantity.min' => 'The pay for quantity must be at least 1.',
            'get_quantity.min' => 'The get quantity must be at least 1.',
            'inventory_limit.min' => 'The inventory limit must be at least 1.',
            'expires_at.after' => 'The expiry date must be a future date.'
        ];
    }
}
