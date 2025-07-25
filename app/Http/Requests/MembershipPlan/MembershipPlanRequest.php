<?php

namespace App\Http\Requests\MembershipPlan;

use App\Enums\MembershipPlanType;
use App\Enums\MembershipType;
use App\Enums\PlanStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MembershipPlanRequest extends FormRequest
{
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
            's' => 'nullable|string',
            'type' => ['nullable', 'string', Rule::in(MembershipType::cases())],
            'plan_type' => ['nullable', 'string', Rule::in(MembershipPlanType::cases())],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'status' => ['nullable', 'integer', Rule::in(PlanStatus::cases())],
        ];
    }
}
