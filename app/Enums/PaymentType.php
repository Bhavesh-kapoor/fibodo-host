<?php

namespace App\Enums;

enum PaymentType: string
{
    case DIRECT_DEBIT = 'direct_debit';
    case CREDIT_DEBIT_CARD = 'credit_debit_card';

    public function label(): string
    {
        return match ($this) {
            self::DIRECT_DEBIT => 'Direct Debit',
            self::CREDIT_DEBIT_CARD => 'Credit/Debit Card',
        };
    }
}
