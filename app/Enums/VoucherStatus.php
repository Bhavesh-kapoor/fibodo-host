<?php

namespace App\Enums;

enum VoucherStatus: int
{
    case DRAFT = 0;
    case ACTIVE = 1;
    case PAUSED = 2;
    case EXPIRED = 3;

    /**
     * Get the label for the enum value.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::ACTIVE => 'Active',
            self::PAUSED => 'Paused',
            self::EXPIRED => 'Expired',
        };
    }

    /**
     * Get all enum values as an array.
     *
     * @return array<int, string>
     */
    public static function toArray(): array
    {
        return [
            self::DRAFT->value => self::DRAFT->label(),
            self::ACTIVE->value => self::ACTIVE->label(),
            self::PAUSED->value => self::PAUSED->label(),
            self::EXPIRED->value => self::EXPIRED->label(),
        ];
    }
}
