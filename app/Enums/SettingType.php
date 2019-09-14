<?php

namespace App\Enums;

use App\Models\Media;
use BenSampo\Enum\Enum;

final class SettingType extends Enum
{
    public const String = 'string';
    public const Numeric = 'numeric';
    public const Boolean = 'bool';
    public const TextArea = 'textarea';
//    public const Json = 'json';
    public const Media = 'media';
    public const Email = 'email';

    public const ALIASES_TYPES = [
        self::String => 'string',
        self::Numeric => 'integer',
        self::Boolean => 'boolean',
        self::TextArea => 'string',
        // SettingType::Json => '??',
        self::Media => Media::class,
        self::Email => 'string',
    ];

    /**
     * Return the corresponding php type according to the setting type.
     */
    public static function getAliasType(string $key): string
    {
        return self::ALIASES_TYPES[$key];
    }
}
