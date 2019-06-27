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
    const Json = 'json';
    const TextArea = 'textarea';
    const Media = 'media';
}
