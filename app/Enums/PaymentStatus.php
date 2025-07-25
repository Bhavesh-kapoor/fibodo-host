<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case PENDING = 0;
    case PAID = 1;
    case REFUNDED = 2;

    /**
     * Get the label for the payment status.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::REFUNDED => 'Refunded',
        };
    }
}
