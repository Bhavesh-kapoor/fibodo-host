<?php

namespace App\Enums;

enum TargetAudience: int
{
    case ALL_ATTENDEES = 1;
    case NEW_CLIENTS = 2;
    case LEAD_BROKER = 3;

    /**
     * Get the label for the target audience
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::ALL_ATTENDEES => 'All Attendees',
            self::NEW_CLIENTS => 'New Clients',
            self::LEAD_BROKER => 'Lead Broker',
        };
    }

    /**
     * Get the custom audience types
     *
     * @return array
     */
    public static function customTypes(): array
    {
        return [
            self::ALL_ATTENDEES => 'All Attendees',
            self::LEAD_BROKER => 'Lead Broker',
            self::NEW_CLIENTS => 'New Clients',
        ];
    }

    /**
     * Get all target audience types with their labels
     *
     * @return array
     */
    public static function all(): array
    {
        return collect(self::cases())->mapWithKeys(function ($case) {
            return [$case->value => $case->label()];
        })->toArray();
    }
}
