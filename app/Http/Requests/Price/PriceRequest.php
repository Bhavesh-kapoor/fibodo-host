<?php

namespace App\Http\Requests\Price;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PriceRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $is_age_sensitive = request()->get('is_age_sensitive');
        $is_walk_in_pricing = request()->get('is_walk_in_pricing');
        $is_walk_in_age_sensitive = request()->get('is_walk_in_age_sensitive');
        $is_special_pricing = request()->get('is_special_pricing');
        return [
            'no_of_slots' => 'nullable|numeric|min:1',
            'price' => [Rule::requiredIf(!$is_age_sensitive), 'numeric'],
            'is_age_sensitive' => 'nullable|boolean',
            'junior_price' => [Rule::requiredIf($is_age_sensitive), 'numeric'],
            'adult_price' => [Rule::requiredIf($is_age_sensitive), 'numeric'],
            'senior_price' => [Rule::requiredIf($is_age_sensitive), 'numeric'],

            'is_walk_in_pricing' => 'nullable|boolean',
            'walk_in_price' => [Rule::requiredIf($is_walk_in_pricing && !$is_walk_in_age_sensitive), 'numeric'],

            'is_walk_in_age_sensitive' => 'nullable|boolean',
            'walk_in_junior_price' => [Rule::requiredIf($is_walk_in_pricing && $is_walk_in_age_sensitive), 'numeric'],
            'walk_in_adult_price' => [Rule::requiredIf($is_walk_in_pricing  && $is_walk_in_age_sensitive), 'numeric'],
            'walk_in_senior_price' => [Rule::requiredIf($is_walk_in_pricing  && $is_walk_in_age_sensitive), 'numeric'],

            'is_special_pricing' => 'nullable|boolean',
            'multi_attendee_price' => [Rule::requiredIf($is_special_pricing), 'numeric'],
            'all_space_price' => [Rule::requiredIf($is_special_pricing), 'numeric'],
        ];
    }
}
