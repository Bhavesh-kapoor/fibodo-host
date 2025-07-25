<?php

namespace App\Enums;

enum TransactionType: int
{
    case REFUND = 0;
    case PAYMENT = 1;

    /**
     * Get the label for the transaction type
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::PAYMENT => 'Payment',
            self::REFUND => 'Refund',
        };
    }
}
