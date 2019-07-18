<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Enums;

use BenSampo\Enum\Enum;
use Spatie\MediaLibrary\Models\Media;

final class SettingType extends Enum
{
    public const String = 'string';
    public const Numeric = 'numeric';
    public const Boolean = 'bool';
    const TextArea = 'textarea';
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
     *
     * @param  string  $key
     *
     * @return string
     */
    public static function getAliasType(string $key): string
    {
        return self::ALIASES_TYPES[$key];
    }
}
