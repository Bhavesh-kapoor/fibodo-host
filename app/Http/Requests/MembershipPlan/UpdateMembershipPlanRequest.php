<?php

namespace App\Http\Requests\MembershipPlan;

use App\Enums\MembershipType;
use App\Enums\MembershipPlanType;
use App\Enums\MembershipBillingPeriod;
use App\Enums\PaymentType;
use App\Enums\PlanStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMembershipPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', Rule::enum(MembershipType::class)],
            'plan_type' => ['sometimes', Rule::enum(MembershipPlanType::class)],
            'description' => ['nullable', 'string'],

            // Family plan specific fields
            'junior_count' => ['nullable', 'integer', 'min:0'],
            'adult_count' => ['nullable', 'integer', 'min:0'],
            'senior_count' => ['nullable', 'integer', 'min:0'],
            'unlimited_junior' => ['nullable', 'boolean'],

            // Individual plan specific fields
            'individual_plan_type' => ['nullable', 'string', Rule::in(['normal', 'pay_as_you_go'])],

            // Cost & Billing
            'joining_fee' => ['nullable', 'numeric', 'min:0'],
            'billing_period' => ['sometimes', Rule::enum(MembershipBillingPeriod::class)],
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'payment_types' => ['sometimes', 'array'],
            'payment_types.*' => ['required', Rule::enum(PaymentType::class)],

            // Terms & Conditions
            'renewal_day' => ['nullable', 'integer', 'min:1', 'max:31'],
            'grace_period_days' => ['nullable', 'integer', 'min:0'],
            'cancellation_period_days' => ['nullable', 'integer', 'min:0'],
            'is_transferable' => ['nullable', 'boolean'],
            'can_pause' => ['nullable', 'boolean'],

            // Benefits
            'benefits' => ['sometimes', 'array'],
            'benefits.*.id' => ['required', 'exists:membership_benefits,id'],
            'benefits.*.is_unlimited' => ['nullable', 'boolean'],
            'benefits.*.pass_count' => ['nullable', 'integer', 'min:0'],
            'benefits.*.discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'benefits.*.advance_booking_days' => ['nullable', 'integer', 'min:0'],

            // Status
            'status' => ['sometimes', Rule::enum(PlanStatus::class)],
        ];
    }
}
