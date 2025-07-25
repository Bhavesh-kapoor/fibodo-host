<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MembershipBenefit extends Model
{
    use HasUlids;

    protected $fillable = [
        'name',
        'type',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function membershipPlans(): BelongsToMany
    {
        return $this->belongsToMany(MembershipPlan::class, 'membership_plan_benefits')
            ->withPivot(['is_unlimited', 'pass_count', 'discount_percentage', 'advance_booking_days'])
            ->withTimestamps();
    }
}
