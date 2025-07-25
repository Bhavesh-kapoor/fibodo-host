<?php

namespace App\Http\Requests\Product;

use App\Rules\CategoryBelongsToCategory;
use App\Rules\HourOrHalfPast;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $priceSettings = request('price_settings', []);
        $is_age_sensitive = request('price_settings.is_age_sensitive');
        $is_walk_in_pricing = request('price_settings.is_walk_in_pricing');
        $is_walk_in_age_sensitive = request('price_settings.is_walk_in_age_sensitive');
        $is_special_pricing = request('price_settings.is_special_pricing');

        return [

            'title' => 'string|max:255',
            'product_type_id' => 'exists:product_types,id',

            'sub_title' => 'string|max:255',
            'description' => 'string|max:1000',
            'kcal_burn' => 'numeric',
            'category_id' => 'exists:categories,id',
            'sub_category_id' => ['exists:categories,id', new CategoryBelongsToCategory(request('category_id'))],
            'activity_type_id' => 'exists:activity_types,id',

            // session_duration 
            'session_duration' => ['required', 'integer', new HourOrHalfPast],

            // attendee setting 
            'attendee_settings' => 'array',
            'attendee_settings.ability_level' => 'string|in:beginner,intermediate,advanced',
            'attendee_settings.has_age_restriction' => 'boolean',
            'attendee_settings.age_below' => 'requiredIf:attendee_settings.has_age_restriction,true|numeric|between:0,100',
            'attendee_settings.age_above' => 'requiredIf:attendee_settings.has_age_restriction,true|numeric|between:0,100',
            'attendee_settings.gender_restrictions' => 'nullable|string|in:men,women',
            'attendee_settings.is_family_friendly' => 'boolean',

            // price setting
            'price_settings' => 'array',
            'price_settings.no_of_slots' => 'nullable|numeric|min:1',
            'price_settings.price' => 'nullable|requiredIf:price_settings.is_age_sensitive,false,price_settings,array|numeric',
            'price_settings.is_age_sensitive' => 'nullable|boolean',
            'price_settings.junior_price' => [Rule::requiredIf($is_age_sensitive && $priceSettings), 'numeric'],
            'price_settings.adult_price' => [Rule::requiredIf($is_age_sensitive && $priceSettings), 'numeric'],
            'price_settings.senior_price' => [Rule::requiredIf($is_age_sensitive && $priceSettings), 'numeric'],

            'price_settings.is_walk_in_pricing' => 'nullable|boolean',
            'price_settings.walk_in_price' => [Rule::requiredIf($is_walk_in_pricing && !$is_walk_in_age_sensitive && $priceSettings), 'numeric'],

            'price_settings.is_walk_in_age_sensitive' => 'nullable|boolean',
            'price_settings.walk_in_junior_price' => [Rule::requiredIf($is_walk_in_pricing && $is_walk_in_age_sensitive && $priceSettings), 'numeric'],
            'price_settings.walk_in_adult_price' => [Rule::requiredIf($is_walk_in_pricing  && $is_walk_in_age_sensitive && $priceSettings), 'numeric'],
            'price_settings.walk_in_senior_price' => [Rule::requiredIf($is_walk_in_pricing  && $is_walk_in_age_sensitive && $priceSettings), 'numeric'],

            'price_settings.is_special_pricing' => 'nullable|boolean',
            'price_settings.multi_attendee_price' => [Rule::requiredIf($is_special_pricing && $priceSettings), 'numeric'],
            'price_settings.all_space_price' => [Rule::requiredIf($is_special_pricing && $priceSettings), 'numeric'],

            // location settings 
            'location_settings' => 'array',
            'location_settings.address' => 'string',
            'location_settings.note' => 'string|max:1000',
            'location_settings.lat' => 'string',
            'location_settings.long' => 'string',

            // form & policies attachments
            'forms' => 'nullable|array',
            'forms.*' => 'required|exists:forms,id',
            'price_settings.policies' => 'nullable|array',
            'price_settings.policies.*' => 'required|exists:policies,id',

            // also take status 
            'status' => 'numeric|min:0|max:7'
        ];
    }
}
