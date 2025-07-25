<?php

namespace App\Enums;

enum MembershipPlanType: string
{
    case INDIVIDUAL = 'individual';
    case FAMILY = 'family';

    public function label(): string
    {
        return match ($this) {
            self::INDIVIDUAL => 'Individual',
            self::FAMILY => 'Family',
        };
    }
}
