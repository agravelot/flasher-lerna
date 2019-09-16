<?php

namespace App\Abilities;

use ReflectionClass;
use Illuminate\Support\Str;

trait HasParentModel
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
        return Str::snake(class_basename($this->getParentClass())).'_'.$this->primaryKey;
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
