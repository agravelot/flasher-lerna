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

    protected $casts = [
        'value' => 'string', // Dummy cast to force to call getCastType
    ];

    protected $enumCasts = [
        'type' => SettingType::class,
    ];

    protected function getCastType($key)
    {
        if ($key == 'value') {
            if (empty($this->type)) {
                throw new \LogicException('Setting cannot be empty');
            }

            if ($this->type === 'numeric') {
                return 'integer';
            }

            if ($this->type === 'textarea') {
                return 'string';
            }

            return $this->type;
        }

        return parent::getCastType($key);
    }
}
