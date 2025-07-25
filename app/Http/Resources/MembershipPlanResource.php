<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembershipPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'host_id' => $this->host_id,
            'title' => $this->title,
            'type' => $this->type,
            'plan_type' => $this->plan_type,
            'description' => $this->description,

            // Family plan specific fields
            'junior_count' => $this->junior_count,
            'adult_count' => $this->adult_count,
            'senior_count' => $this->senior_count,
            'unlimited_junior' => $this->unlimited_junior,

            // Individual plan specific fields
            'individual_plan_type' => $this->individual_plan_type,

            // Cost & Billing
            'joining_fee' => $this->joining_fee,
            'billing_period' => $this->billing_period,
            'amount' => $this->amount,
            'payment_types' => $this->payment_types,

            // Terms & Conditions
            'renewal_day' => $this->renewal_day,
            'grace_period_days' => $this->grace_period_days,
            'cancellation_period_days' => $this->cancellation_period_days,
            'is_transferable' => $this->is_transferable,
            'can_pause' => $this->can_pause,

            // Benefits
            'benefits' => $this->whenLoaded('benefits', function () {
                return $this->benefits->map(function ($benefit) {
                    return [
                        'id' => $benefit->id,
                        'name' => $benefit->name,
                        'type' => $benefit->type,
                        'description' => $benefit->description,
                        'is_unlimited' => $benefit->pivot->is_unlimited,
                        'pass_count' => $benefit->pivot->pass_count,
                        'discount_percentage' => $benefit->pivot->discount_percentage,
                        'advance_booking_days' => $benefit->pivot->advance_booking_days,
                    ];
                });
            }),

            // Status
            'status' => $this->status,
            'published_at' => $this->published_at,
            'archived_at' => $this->archived_at,
            'created_at' => $this->created_at,
        ];
    }
}
