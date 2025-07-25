<?php

namespace App\Enums;

enum TransactionStatus: int
{
    case PENDING = 0;
    case COMPLETED = 1;
    case FAILED = 2;

    /**
     * Get the label for the transaction status.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Failed',
        };
    }
}
