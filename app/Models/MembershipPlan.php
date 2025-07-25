<?php

namespace App\Models;

use App\Enums\MembershipType;
use App\Enums\MembershipPlanType;
use App\Enums\MembershipBillingPeriod;
use App\Enums\PaymentType;
use App\Enums\PlanStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MembershipPlan extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'host_id',
        'title',
        'type',
        'plan_type',
        'description',
        'junior_count',
        'adult_count',
        'senior_count',
        'unlimited_junior',
        'individual_plan_type',
        'joining_fee',
        'billing_period',
        'amount',
        'payment_types',
        'renewal_day',
        'grace_period_days',
        'cancellation_period_days',
        'is_transferable',
        'can_pause',
        'status',
        'published_at',
        'archived_at',
    ];

    protected $casts = [
        'type' => MembershipType::class,
        'plan_type' => MembershipPlanType::class,
        'billing_period' => MembershipBillingPeriod::class,
        'payment_types' => 'array',
        'unlimited_junior' => 'boolean',
        'is_transferable' => 'boolean',
        'can_pause' => 'boolean',
        'joining_fee' => 'decimal:2',
        'amount' => 'decimal:2',
        'renewal_day' => 'integer',
        'grace_period_days' => 'integer',
        'cancellation_period_days' => 'integer',
        'status' => PlanStatus::class,
        'published_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function benefits(): BelongsToMany
    {
        return $this->belongsToMany(MembershipBenefit::class, 'membership_plan_benefits')
            ->withPivot(['is_unlimited', 'pass_count', 'discount_percentage', 'advance_booking_days'])
            ->withTimestamps();
    }

    public function isArchived(): bool
    {
        return $this->status === PlanStatus::ARCHIVED->value;
    }

    public function isPublished(): bool
    {
        return $this->status === PlanStatus::PUBLISHED->value;
    }

    public function isDraft(): bool
    {
        return $this->status === PlanStatus::DRAFT->value;
    }

    public function markAsDraft(): void
    {
        if ($this->isDraft()) {
            return;
        }

        $this->update([
            'status' => PlanStatus::DRAFT->value,
            'published_at' => null,
            'archived_at' => null,
        ]);
    }

    public function markAsPublished(): void
    {
        if ($this->isPublished()) {
            return;
        }

        $this->update([
            'status' => PlanStatus::PUBLISHED->value,
            'published_at' => now(),
        ]);
    }

    public function markAsArchived(): void
    {
        if ($this->isArchived()) {
            return;
        }

        $this->update([
            'status' => PlanStatus::ARCHIVED->value,
            'archived_at' => now(),
            'published_at' => null,
        ]);
    }
}
