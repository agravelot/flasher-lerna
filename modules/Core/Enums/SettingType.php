<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Enums;

use BenSampo\Enum\Enum;

final class SettingType extends Enum
{
    const String = 'string';
    const Numeric = 'numeric';
    const Boolean = 'bool';
    const TextArea = 'textarea';
//    const Json = 'json';
//    const Media = 'media';

    const ALIASES_TYPES = [
        self::String => 'string',
        self::Numeric => 'integer',
        self::Boolean => 'boolean',
        self::TextArea => 'string',
        // SettingType::Json => '??',
        // SettingType::Media => Media::class,
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
