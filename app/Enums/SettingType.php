<?php

namespace App\Enums;

enum SettingType: string
{
    case TEXT = 'text';
    case NUMBER = 'number';
    case BOOLEAN = 'boolean';
    case JSON = 'json';
    case ARRAY = 'array';
    case SELECT = 'select';
    case MULTI_SELECT = 'multi_select';
    case DATE = 'date';
    case TIME = 'time';
    case DATETIME = 'datetime';
    case COLOR = 'color';
    case FILE = 'file';
    case IMAGE = 'image';
    case PASSWORD = 'password';
    case EMAIL = 'email';
    case URL = 'url';
    case PHONE = 'phone';

    /**
     * Get all setting types
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get setting type label
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::TEXT => 'Text',
            self::NUMBER => 'Number',
            self::BOOLEAN => 'Boolean',
            self::JSON => 'JSON',
            self::ARRAY => 'Array',
            self::SELECT => 'Select',
            self::MULTI_SELECT => 'Multi Select',
            self::DATE => 'Date',
            self::TIME => 'Time',
            self::DATETIME => 'Date Time',
            self::COLOR => 'Color',
            self::FILE => 'File',
            self::IMAGE => 'Image',
            self::PASSWORD => 'Password',
            self::EMAIL => 'Email',
            self::URL => 'URL',
            self::PHONE => 'Phone',
        };
    }
}
