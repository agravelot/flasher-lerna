<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Entities;

use Modules\Core\Enums\SettingType;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting.
 */
class Setting extends Model
{
    use CastsEnums;

    public $incrementing = false;

    protected $primaryKey = 'name';

    protected $fillable = ['name', 'value'];

    protected $enumCasts = [
        'type' => SettingType::class,
    ];
}
