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

    /**
     * Dynamic type casting for value from type.
     *
     * @param  string  $key
     *
     * @return mixed|string
     */
    protected function getCastType($key)
    {
        if ($key == 'value') {
            if (empty($this->type)) {
                throw new \LogicException('Setting type cannot be empty');
            }

            if ($this->type->value === 'numeric') {
                return 'integer';
            }

            if ($this->type->value === 'textarea') {
                return 'string';
            }

            return $this->type->value;
        }

        return parent::getCastType($key);
    }
}
