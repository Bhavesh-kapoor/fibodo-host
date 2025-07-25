<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GeneratesUniqueCode
{
    /**
     * Generate a unique  code
     * Format: 3 uppercase letters + 5 numbers
     *
     * @return string
     */
    public static function generateUniqueCode(): string
    {
        do {
            $letters = Str::upper(Str::random(3));
            $numbers = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
            $code = $letters . $numbers;
        } while (self::where('code', $code)->exists());

        return $code;
    }
}
