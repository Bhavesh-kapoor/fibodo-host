<?php

namespace App\Enums;

enum MembershipType: string
{
    case PEAK = 'peak';
    case OFF_PEAK = 'off_peak';

    public function label(): string
    {
        return match ($this) {
            self::PEAK => 'Peak',
            self::OFF_PEAK => 'Off-Peak',
        };
    }
}
