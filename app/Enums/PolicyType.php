<?php

namespace App\Enums;

enum PolicyType: int
{
    case REFUND = 1;
    case USER_AGREEMENT = 2;
    case PRIVACY_POLICY = 3;
    case TERMS_OF_SERVICE = 4;
    case CANCELLATION_POLICY = 5;

    public function label(): string
    {
        return match ($this) {
            self::REFUND => 'Refund',
            self::USER_AGREEMENT => 'User Agreement',
            self::PRIVACY_POLICY => 'Privacy Policy',
            self::TERMS_OF_SERVICE => 'Terms Of Service',
            self::CANCELLATION_POLICY => 'Cancellation Policy',
        };
    }

    public function slug(): string
    {
        return match ($this) {
            self::REFUND => 'refund',
            self::USER_AGREEMENT => 'user-agreement',
            self::PRIVACY_POLICY => 'privacy',
            self::TERMS_OF_SERVICE => 'terms',
            self::CANCELLATION_POLICY => 'cancellation',
        };
    }

    public static function fromSlug(string $slug): ?self
    {
        return match ($slug) {
            'refund' => self::REFUND,
            'user-agreement' => self::USER_AGREEMENT,
            'privacy' => self::PRIVACY_POLICY,
            'terms' => self::TERMS_OF_SERVICE,
            'cancellation' => self::CANCELLATION_POLICY,
            default => null,
        };
    }

    public static function getAllSlugs(): array
    {
        return array_map(fn($case) => $case->slug(), self::cases());
    }
}
