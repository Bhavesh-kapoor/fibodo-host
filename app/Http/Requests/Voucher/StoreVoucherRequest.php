<?php

namespace App\Http\Requests\Voucher;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVoucherRequest extends FormRequest
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
        $rules =  [
            'voucher_type_id' => 'required|exists:voucher_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_transferrable' => 'nullable|boolean',
            'is_gift_eligible' => 'nullable|boolean',
            'can_combine' => 'nullable|boolean',
            'inventory_limit' => 'nullable|integer|min:1',
            'product_ids' => 'nullable|array',
            'product_ids.*' => [
                'exists:products,id',
                function ($attribute, $value, $fail) {
                    $product = \App\Models\Product::find($value);
                    if (!$product) {
                        $fail('The selected product does not exist.');
                    } elseif ($product->status !== Product::STATUS_PUBLISH || !$product->isPublished()) {
                        $fail('The selected product must be active and published.');
                    }
                }
            ],
            'status' => 'nullable|integer|in:0,1,2,3',
            'expires_at' => 'nullable|date|after:now'
        ];

        // Get the voucher type code based on the submitted voucher_type_id
        $voucherTypeId = $this->input('voucher_type_id');
        $voucherType = \App\Models\VoucherType::find($voucherTypeId);

        if ($voucherType) {
            switch ($voucherType->code) {
                case 'GIFT':
                    // If voucher type is GIFT, value is required
                    $rules['value'] = 'required|numeric|min:1';
                    $rules['pay_for_quantity'] = 'nullable|integer|min:1';
                    $rules['get_quantity'] = 'nullable|integer|min:1';
                    break;

                case 'MULTI':
                    // For Multi-Purchase: Pay £X and get Y Vouchers
                    $rules['value'] = 'required|numeric|min:1'; // Required amount to pay (£X)
                    $rules['get_quantity'] = 'required|integer|min:1'; // Required number of vouchers to get (Y)
                    $rules['pay_for_quantity'] = 'nullable|integer|min:1';
                    $rules['product_ids'] = 'required|array|min:1'; // At least one product must be selected
                    break;

                default:
                    // For other voucher types (like X-for-Y), get_quantity and pay_for_quantity are required
                    $rules['value'] = 'nullable|numeric|min:1';
                    $rules['pay_for_quantity'] = 'required|integer|min:1';
                    $rules['get_quantity'] = 'required|integer|min:1';
                    break;
            }
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'voucher_type_id.required' => 'The voucher type is required.',
            'voucher_type_id.exists' => 'The selected voucher type is invalid.',
            'name.required' => 'The voucher name is required.',
            'value.min' => 'The voucher value must be at least 0.',
            'pay_for_quantity.min' => 'The pay for quantity must be at least 1.',
            'get_quantity.min' => 'The get quantity must be at least 1.',
            'inventory_limit.min' => 'The inventory limit must be at least 1.',
            'expires_at.after' => 'The expiry date must be a future date.',
            'product_ids.exists' => 'The selected product does not exist.',
            'product_ids.*.exists' => 'The selected product does not exist.',
            'product_ids.*.published' => 'The selected product must be active and published.',
        ];
    }
}
