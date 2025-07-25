<?php

namespace App\Enums;

enum BookingStatus: int
{
    case PENDING = 0;
    case CONFIRMED = 1;
    case CANCELLED = 2;

    /**
     * Get the label for the booking status.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::CONFIRMED => 'Confirmed',
            self::CANCELLED => 'Cancelled',
        };
    }
}
