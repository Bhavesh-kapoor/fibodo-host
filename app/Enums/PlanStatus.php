<?php

namespace App\Enums;

enum PlanStatus: int
{
    case DRAFT = 0;
    case PUBLISHED = 1;
    case ARCHIVED = 2;

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
            self::ARCHIVED => 'Archived',
        };
    }
}
