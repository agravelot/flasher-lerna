<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Abilities;

use Illuminate\Support\Str;
use ReflectionClass;

trait HasParentModelTrait
{
    public function getTable()
    {
        if (! isset($this->table)) {
            return str_replace('\\', '', Str::snake(Str::plural(class_basename($this->getParentClass()))));
        }

        return $this->table;
    }

    protected function getParentClass()
    {
        return (new ReflectionClass($this))->getParentClass()->getName();
    }

    public function getForeignKey()
    {
        return Str::snake(class_basename($this->getParentClass())) . '_' . $this->primaryKey;
    }

    public function joiningTable($related, $instance = null)
    {
        $models = [
            Str::snake(class_basename($related)),
            Str::snake(class_basename($this->getParentClass())),
        ];
        sort($models);

        return mb_strtolower(implode('_', $models));
    }
}
